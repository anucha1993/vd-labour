<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\jobs\JobModel;
use App\Models\jobs\JobLeadModel;
use App\Models\country\countryModel;
use App\Models\customers\customerModel;
use App\Models\demands\DemandModel;
use App\Models\jobgroup\jobGroupModel;
use App\Models\positions\positionModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class JobController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware('permission:job-list|job-create|job-edit|job-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:job-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:job-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:job-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of jobs
     */
    public function index(Request $request)
    {
        $query = JobModel::with(['country', 'demand', 'createdBy', 'updatedBy'])
                        ->withCount([
                            'jobLeads', 
                            'jobLeads as accepted_count' => function($q) {
                                $q->where('job_lead_status', 'ตอบรับ');
                            },
                            'jobLeads as locked_leads_count' => function($q) {
                                $q->where('is_locked', 1);
                            }
                        ]);
        
        // Search filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('job_name', 'like', "%{$search}%")
                  ->orWhere('job_number', 'like', "%{$search}%")
                  ->orWhereHas('country', function($countryQuery) use ($search) {
                      $countryQuery->where('country_name_th', 'like', "%{$search}%")
                                  ->orWhere('country_name_en', 'like', "%{$search}%");
                  });
            });
        }
        
        if ($request->filled('country_id')) {
            $query->where('country_id', $request->country_id);
        }
        
        if ($request->filled('job_status')) {
            $query->where('job_status', $request->job_status);
        }
        
        $jobs = $query->orderBy('created_at', 'desc')->paginate(10);
        $countries = countryModel::where('country_status', 'active')->get();
        
        return view('jobs.index', compact('jobs', 'countries'));
    }

    /**
     * Show the form for creating a new job
     */
    public function create()
    {
        $countries = countryModel::where('country_status', 'active')->get();
        $demands = DemandModel::with(['country', 'industryType'])
                             ->orderBy('created_at', 'desc')
                             ->get();
        $jobGroups = jobGroupModel::where('job_group_status', 'active')->get();
        $positions = positionModel::where('position_status', 'active')->get();
        $customer = customerModel::where('customer_status', 'active')->get();
        
        return view('jobs.create', compact('countries', 'demands', 'jobGroups', 'positions','customer'));
    }

    /**
     * Store a newly created job
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'job_name' => 'required|string|max:255',
            'country_id' => 'required|exists:country,country_id',
            'customer_id' => 'required|exists:customers,customer_id',
            'dm_id' => 'required|exists:demands,dm_id',
            'job_group_id' => 'nullable|exists:job_group,job_group_id',
            'position_ids' => 'nullable|array',
            'position_ids.*' => 'exists:position,position_id',
            'job_total' => 'required|integer|min:1',
            'job_start_date' => 'required|date|after_or_equal:today',
            'job_end_date' => 'nullable|date|after:job_start_date',
            'job_status' => 'required|in:เปิดรับสมัคร,ปิดรับสมัคร',
        ], [
            'job_name.required' => 'กรุณาระบุชื่องาน',
            'country_id.required' => 'กรุณาเลือกประเทศ',
            'dm_id.required' => 'กรุณาเลือก Demand',
            'job_total.required' => 'กรุณาระบุจำนวนที่เปิดรับ',
            'job_start_date.required' => 'กรุณาระบุวันเริ่มเปิดรับสมัคร',
            'job_start_date.after_or_equal' => 'วันเริ่มรับสมัครต้องไม่เป็นวันที่ผ่านมาแล้ว',
            'job_end_date.after' => 'วันปิดรับสมัครต้องหลังวันเริ่มรับสมัคร',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();
            
            $jobData = $request->only([
                'job_name','country_id','customer_id','dm_id','job_group_id',
                'job_total','job_start_date','job_end_date','job_status'
            ]);
            $jobData['position_ids'] = $request->input('position_ids', []);
            // Keep first position as position_id for backward compatibility
            $jobData['position_id'] = !empty($jobData['position_ids']) ? $jobData['position_ids'][0] : null;
            $job = JobModel::create($jobData);
            
            DB::commit();
            
            return redirect()->route('jobs.index')
                           ->with('success', 'สร้างงานใหม่สำเร็จ หมายเลขงาน: ' . $job->job_number);
                           
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified job
     */
    public function show($id)
    {
        $job = JobModel::with([
                          'country', 
                          'demand.industryType',
                          'jobGroup',
                          'position', 
                          'customer',
                          'createdBy', 
                          'updatedBy', 
                          'jobLeads.createdBy',
                          'jobLeads.lead.staff',
                          'jobLeads.lead.recommenderStaff',
                          'jobLeads.lead.position',
                          'jobLeads.lead.country'
                      ])
                      ->findOrFail($id);
        
        // Statistics
        $stats = [
            'total_applications' => $job->jobLeads()->count(),
            'by_status' => $job->jobLeads()
                             ->selectRaw('job_lead_status, COUNT(*) as count')
                             ->groupBy('job_lead_status')
                             ->pluck('count', 'job_lead_status')
                             ->toArray(),
            'locked_leads' => $job->jobLeads()->where('is_locked', true)->count(),
            'remaining_positions' => $job->remaining_positions
        ];
        
        return view('jobs.show', compact('job', 'stats'));
    }

    /**
     * Show the form for editing the job
     */
    public function edit($id)
    {
        $job = JobModel::findOrFail($id);
        $countries = countryModel::where('country_status', 'active')->get();
        $demands = DemandModel::with(['country', 'industryType'])
                             ->orderBy('created_at', 'desc')
                             ->get();
        $jobGroups = jobGroupModel::where('job_group_status', 'active')->get();
        $positions = positionModel::where('position_status', 'active')->get();
        $customer = customerModel::where('customer_status', 'active')->get();
        return view('jobs.edit', compact('job', 'countries', 'demands', 'jobGroups', 'positions', 'customer'));
    }

    /**
     * Update the specified job
     */
    public function update(Request $request, $id)
    {
        $job = JobModel::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'job_name' => 'required|string|max:255',
            'country_id' => 'required|exists:country,country_id',
            'dm_id' => 'required|exists:demands,dm_id',
            'job_total' => 'required|integer|min:1',
            'job_start_date' => 'required|date',
            'job_end_date' => 'nullable|date|after:job_start_date',
            'job_status' => 'required|in:เปิดรับสมัคร,ปิดรับสมัคร',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();
            
            $jobData = $request->only([
                'job_name','country_id','dm_id','job_group_id',
                'job_total','job_start_date','job_end_date','job_status','customer_id'
            ]);
            $jobData['position_ids'] = $request->input('position_ids', []);
            // Keep first position as position_id for backward compatibility
            $jobData['position_id'] = !empty($jobData['position_ids']) ? $jobData['position_ids'][0] : null;

            $job->update($jobData);
            
            DB::commit();
            
            return redirect()->route('jobs.index')
                           ->with('success', 'อัปเดตข้อมูลงานสำเร็จ');
                           
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified job
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            
            $job = JobModel::findOrFail($id);
            
            // ตรวจสอบว่ามีใบสมัครที่ล็อคอยู่หรือไม่
            $lockedLeads = $job->jobLeads()->where('is_locked', true)->count();
            $totalLeads = $job->jobLeads()->count();
            
            $message = 'ลบงานสำเร็จ';
            
            // ถ้ามี locked leads ให้ปลดล็อคอัตโนมัติก่อนลบ
            if ($lockedLeads > 0) {
                // ปลดล็อคคนงานทั้งหมดก่อนลบ
                $job->jobLeads()->where('is_locked', true)->update([
                    'is_locked' => false,
                    'unlocked_at' => now(),
                    'updated_by' => auth()->id(),
                    'remarks' => DB::raw("CONCAT(COALESCE(remarks, ''), '\n[ปลดล็อคอัตโนมัติ: งานถูกลบโดย " . auth()->user()->name . " เมื่อ " . now()->format('d/m/Y H:i') . "]')")
                ]);
                
                $message = "ลบงานสำเร็จ และปลดล็อคคนงาน {$lockedLeads} รายการอัตโนมัติ";
            }
            
            // ลบใบสมัครทั้งหมดก่อน
            $job->jobLeads()->delete();
            
            // ลบงาน
            $job->delete();
            
            DB::commit();
            
            return redirect()->route('jobs.index')->with('success', $message);
                           
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาดในการลบงาน: ' . $e->getMessage());
        }
    }

    /**
     * Toggle job status
     */
    public function toggleStatus($id)
    {
        try {
            $job = JobModel::findOrFail($id);
            
            $newStatus = $job->job_status === 'เปิดรับสมัคร' ? 'ปิดรับสมัคร' : 'เปิดรับสมัคร';
            
            $job->update(['job_status' => $newStatus]);
            
            return redirect()->back()->with('success', 'เปลี่ยนสถานะงานเป็น ' . $newStatus . ' แล้ว');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
    }

    /**
     * Get job statistics for dashboard
     */
    public function statistics()
    {
        $stats = [
            'total_jobs' => JobModel::count(),
            'active_jobs' => JobModel::where('job_status', 'เปิดรับสมัคร')->count(),
            'closed_jobs' => JobModel::where('job_status', 'ปิดรับสมัคร')->count(),
            'expired_jobs' => JobModel::whereNotNull('job_end_date')
                                    ->where('job_end_date', '<', now())
                                    ->count(),
            'total_applications' => JobLeadModel::count(),
            'applications_by_status' => JobLeadModel::selectRaw('job_lead_status, COUNT(*) as count')
                                                   ->groupBy('job_lead_status')
                                                   ->pluck('count', 'job_lead_status')
                                                   ->toArray(),
            'locked_leads' => JobLeadModel::where('is_locked', true)->count(),
        ];
        
        return response()->json($stats);
    }
}
