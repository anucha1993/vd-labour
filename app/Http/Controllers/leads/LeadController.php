<?php

namespace App\Http\Controllers\leads;

use App\Http\Controllers\Controller;
use App\Models\leads\LeadModel;
use App\Models\leads\LeadJobHistoryModel;
use App\Models\labours\labourModel;
use App\Models\positions\positionModel;
use App\Models\country\countryModel;
use App\Models\jobgroup\jobGroupModel;
use App\Models\staff\staffModel;
use App\Models\customers\customerModel;
use App\Models\examinations\examinationRoundModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class LeadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view lead')->only(['index', 'show']);
        $this->middleware('permission:create lead')->only(['create', 'store']);
        $this->middleware('permission:update lead')->only(['edit', 'update']);
        $this->middleware('permission:delete lead')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = LeadModel::with(['position', 'country', 'jobGroup', 'staff']);
        
        // Search filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('lead_firstname', 'like', "%{$search}%")
                  ->orWhere('lead_lastname', 'like', "%{$search}%")
                  ->orWhere('lead_phone', 'like', "%{$search}%")
                  ->orWhere('lead_passport_number', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('lead_status') && $request->lead_status !== 'all') {
            $query->where('lead_status', $request->lead_status);
        }
        
        if ($request->filled('position_id') && $request->position_id !== 'all') {
            $query->where(function($q) use ($request) {
                $q->where('position_id', $request->position_id)
                  ->orWhere('position_id_2', $request->position_id)
                  ->orWhere('position_id_3', $request->position_id);
            });
        }
        
        $leads = $query->latest()->paginate(20);
        
        $positions = positionModel::where('position_status', 'active')->get();
        
        return view('leads.index', compact('leads', 'positions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $positions = positionModel::where('position_status', 'active')->get();
        $countries = countryModel::where('country_status', 'active')->get();
        $jobGroups = jobGroupModel::where('job_group_status', 'active')->get();
        $staffs = staffModel::where('staff_status', 'active')->get();
        $examinationRounds = examinationRoundModel::where('examination_round_status', 'active')->get();
        $staffSubs = \App\Models\staff\staffSubModel::all();
        
        return view('leads.create', compact('positions', 'countries', 'jobGroups', 'staffs', 'examinationRounds', 'staffSubs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'lead_firstname' => 'required|string|max:255',
            'lead_lastname' => 'required|string|max:255',
            'lead_phone' => 'nullable|string|max:20',
            'lead_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'lead_photo.image' => 'ไฟล์ที่อัปโหลดต้องเป็นรูปภาพเท่านั้น',
            'lead_photo.mimes' => 'รูปภาพต้องเป็นไฟล์ประเภท: jpeg, png, jpg, gif',
            'lead_photo.max' => 'ขนาดรูปภาพต้องไม่เกิน 2MB',
        ]);
        
        $data = $request->except('lead_photo', 'job_history');
        
        // Calculate BMI if height and weight provided
        if ($request->filled('lead_height') && $request->filled('lead_weight')) {
            $heightM = $request->lead_height / 100;
            $data['lead_bmi'] = round($request->lead_weight / ($heightM * $heightM), 2);
        }
        
        // Handle photo upload
        if ($request->hasFile('lead_photo')) {
            try {
                $photo = $request->file('lead_photo');
                $filename = time() . '_' . $photo->getClientOriginalName();
                $data['lead_photo'] = $photo->storeAs('leads', $filename, 'public');
            } catch (\Exception $e) {
                return back()->with('error', 'เกิดข้อผิดพลาดในการอัปโหลดรูปภาพ: ' . $e->getMessage())->withInput();
            }
        }
        
        DB::beginTransaction();
        try {
            $lead = LeadModel::create($data);
            
            // Save job history
            if ($request->has('job_history') && is_array($request->job_history)) {
                foreach ($request->job_history as $index => $history) {
                    if (!empty($history['company_type']) && !empty($history['position'])) {
                        LeadJobHistoryModel::create([
                            'lead_id' => $lead->lead_id,
                            'company_type' => $history['company_type'],
                            'company_name' => $history['company_name'] ?? null,
                            'position' => $history['position'],
                            'country' => $history['country'] ?? 'THAI',
                            'experience_years' => $history['experience_years'] ?? 0,
                            'display_order' => $index + 1,
                        ]);
                    }
                }
            }
            
            DB::commit();
            return redirect()->route('leads.index')->with('success', 'เพิ่มข้อมูล Lead สำเร็จ');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $lead = LeadModel::with(['position', 'position2', 'position3', 'country', 'jobGroup', 'staff', 'jobHistory', 'labour', 'recommenderStaff', 'examinationRound'])
                        ->findOrFail($id);
        return view('leads.show', compact('lead'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $lead = LeadModel::with('jobHistory', 'recommenderStaff')->findOrFail($id);
        $positions = positionModel::where('position_status', 'active')->get();
        $countries = countryModel::where('country_status', 'active')->get();
        $jobGroups = jobGroupModel::where('job_group_status', 'active')->get();
        $staffs = staffModel::where('staff_status', 'active')->get();
        $examinationRounds = examinationRoundModel::where('examination_round_status', 'active')->get();
        $staffSubs = \App\Models\staff\staffSubModel::all();
        
        return view('leads.edit', compact('lead', 'positions', 'countries', 'jobGroups', 'staffs', 'examinationRounds', 'staffSubs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $lead = LeadModel::findOrFail($id);
        
        $request->validate([
            'lead_firstname' => 'required|string|max:255',
            'lead_lastname' => 'required|string|max:255',
            'lead_phone' => 'nullable|string|max:20',
            'lead_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'lead_photo.image' => 'ไฟล์ที่อัปโหลดต้องเป็นรูปภาพเท่านั้น',
            'lead_photo.mimes' => 'รูปภาพต้องเป็นไฟล์ประเภท: jpeg, png, jpg, gif',
            'lead_photo.max' => 'ขนาดรูปภาพต้องไม่เกิน 2MB',
        ]);
        
        $data = $request->except('lead_photo', 'job_history');
        
        // Calculate BMI
        if ($request->filled('lead_height') && $request->filled('lead_weight')) {
            $heightM = $request->lead_height / 100;
            $data['lead_bmi'] = round($request->lead_weight / ($heightM * $heightM), 2);
        }
        
        // Handle photo upload
        if ($request->hasFile('lead_photo')) {
            try {
                // Delete old photo if exists
                if ($lead->lead_photo) {
                    Storage::disk('public')->delete($lead->lead_photo);
                }
                
                $photo = $request->file('lead_photo');
                $filename = time() . '_' . $photo->getClientOriginalName();
                $data['lead_photo'] = $photo->storeAs('leads', $filename, 'public');
            } catch (\Exception $e) {
                return back()->with('error', 'เกิดข้อผิดพลาดในการอัปโหลดรูปภาพ: ' . $e->getMessage())->withInput();
            }
        }
        
        DB::beginTransaction();
        try {
            $lead->update($data);
            
            // Update job history
            $lead->jobHistory()->delete();
            if ($request->has('job_history') && is_array($request->job_history)) {
                foreach ($request->job_history as $index => $history) {
                    if (!empty($history['company_type']) && !empty($history['position'])) {
                        LeadJobHistoryModel::create([
                            'lead_id' => $lead->lead_id,
                            'company_type' => $history['company_type'],
                            'company_name' => $history['company_name'] ?? null,
                            'position' => $history['position'],
                            'country' => $history['country'] ?? 'THAI',
                            'experience_years' => $history['experience_years'] ?? 0,
                            'display_order' => $index + 1,
                        ]);
                    }
                }
            }
            
            DB::commit();
            return redirect()->route('leads.index')->with('success', 'อัปเดตข้อมูล Lead สำเร็จ');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $lead = LeadModel::findOrFail($id);
        
        if ($lead->isConverted()) {
            return back()->with('error', 'ไม่สามารถลบ Lead ที่ถูก Convert แล้ว');
        }
        
        try {
            if ($lead->lead_photo) {
                Storage::disk('public')->delete($lead->lead_photo);
            }
            
            $lead->delete();
            return redirect()->route('leads.index')->with('success', 'ลบข้อมูล Lead สำเร็จ');
        } catch (\Exception $e) {
            return back()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
    }

    public function convertForm(string $id)
    {
        $lead = LeadModel::with(['position', 'country', 'jobGroup'])->findOrFail($id);
        
        if ($lead->isConverted()) {
            return back()->with('error', 'Lead นี้ถูก Convert แล้ว');
        }
        
        $customers = customerModel::where('customer_status', 'active')->get();
        
        return view('leads.convert', compact('lead', 'customers'));
    }

    public function convert(Request $request, string $id)
    {
        $lead = LeadModel::findOrFail($id);
        
        if ($lead->isConverted()) {
            return back()->with('error', 'Lead นี้ถูก Convert แล้ว');
        }
        
        $request->validate([
            'customer_id' => 'required|exists:customers,customer_id',
        ]);
        
        DB::beginTransaction();
        try {
            // Create Labour from Lead
            $labour = labourModel::create([
                'labour_prefix' => $lead->lead_prefix,
                'labour_firstname' => $lead->lead_firstname,
                'labour_lastname' => $lead->lead_lastname,
                'labour_gender' => $lead->lead_gender,
                'labour_birthday' => $lead->lead_birthday,
                'labour_phone' => $lead->lead_phone,
                'labour_email' => $lead->lead_email,
                'labour_address' => $lead->lead_address,
                'labour_passport_number' => $lead->lead_passport_number,
                'labour_height' => $lead->lead_height,
                'labour_weight' => $lead->lead_weight,
                'position_id' => $lead->position_id,
                'country_id' => $lead->country_id,
                'job_group_id' => $lead->job_group_id,
                'customer_id' => $request->customer_id,
                'staff_id' => $lead->staff_id,
                'labour_status' => 'wait',
                'labour_photo' => $lead->lead_photo,
            ]);
            
            // Update Lead
            $lead->update([
                'lead_status' => 'converted',
                'labour_id' => $labour->labour_id,
                'converted_at' => now(),
            ]);
            
            DB::commit();
            return redirect()->route('labour.edit', $labour->labour_id)
                           ->with('success', 'Convert Lead เป็น Labour สำเร็จ');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
    }
}
