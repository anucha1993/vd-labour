<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\jobs\JobModel;
use App\Models\jobs\JobLeadModel;
use App\Models\leads\LeadModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\JobApplicantsExport;

class JobLeadController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware('permission:job-lead-list|job-lead-create|job-lead-edit|job-lead-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:job-lead-create', ['only' => ['create', 'store', 'searchAvailableLeads']]);
        $this->middleware('permission:job-lead-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:job-lead-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of job leads grouped by jobs
     */
    public function index(Request $request)
    {
        // ถ้าเลือกงานเฉพาะ จะแสดงผู้สมัครในงานนั้น
        if ($request->filled('job_id')) {
            return $this->showJobApplicants($request->job_id, $request);
        }
        
        // แสดงรายการงานที่มีใบสมัคร
        $jobsQuery = JobModel::with(['country', 'demand'])
                            ->withCount([
                                'jobLeads',
                                'jobLeads as draft_count' => function($q) {
                                    $q->where('job_lead_status', 'ร่าง');
                                },
                                'jobLeads as sent_count' => function($q) {
                                    $q->where('job_lead_status', 'ส่งแล้ว');
                                },
                                'jobLeads as accepted_count' => function($q) {
                                    $q->where('job_lead_status', 'ตอบรับ');
                                },
                                'jobLeads as locked_count' => function($q) {
                                    $q->where('is_locked', true);
                                }
                            ])
                            ->having('job_leads_count', '>', 0);
        
        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $jobsQuery->where(function($q) use ($search) {
                $q->where('job_name', 'like', "%{$search}%")
                  ->orWhere('job_number', 'like', "%{$search}%")
                  ->orWhereHas('country', function($countryQuery) use ($search) {
                      $countryQuery->where('country_name_th', 'like', "%{$search}%");
                  });
            });
        }
        
        $jobs = $jobsQuery->orderBy('created_at', 'desc')->get();
        
        return view('job-leads.index', compact('jobs'));
    }
    
    /**
     * Show applicants for specific job (Public method)
     */
    public function jobApplicants($jobId, Request $request)
    {
        return $this->showJobApplicants($jobId, $request);
    }
    
    /**
     * Show applicants for specific job (Private implementation)
     */
    private function showJobApplicants($jobId, Request $request)
    {
        $job = JobModel::with(['country', 'demand'])->findOrFail($jobId);
        
        $query = JobLeadModel::with(['lead', 'createdBy', 'updatedBy'])
                           ->where('job_id', $jobId);
        
        // Search within job applicants
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('job_lead_number', 'like', "%{$search}%")
                  ->orWhere('lead_id', 'like', "%{$search}%")
                  ->orWhereHas('lead', function($leadQuery) use ($search) {
                      $leadQuery->whereRaw("CONCAT(lead_prefix, ' ', lead_firstname, ' ', lead_lastname) LIKE ?", ["%{$search}%"])
                               ->orWhere('lead_passport_number', 'like', "%{$search}%")
                               ->orWhere('lead_phone', 'like', "%{$search}%");
                  });
            });
        }
        
        if ($request->filled('job_lead_status')) {
            $query->where('job_lead_status', $request->job_lead_status);
        }
        
        if ($request->filled('is_locked')) {
            $query->where('is_locked', $request->is_locked);
        }
        
        $jobLeads = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Statistics
        $stats = [
            'total' => $job->jobLeads()->count(),
            'by_status' => $job->jobLeads()
                             ->selectRaw('job_lead_status, COUNT(*) as count')
                             ->groupBy('job_lead_status')
                             ->pluck('count', 'job_lead_status')
                             ->toArray(),
            'locked' => $job->jobLeads()->where('is_locked', true)->count()
        ];
        
        return view('job-leads.job-applicants', compact('job', 'jobLeads', 'stats'));
    }

    /**
     * Export applicants for a job to Excel using current filters
     */
    public function export(Request $request, $jobId)
    {
        try {
            $job = JobModel::findOrFail($jobId);

            $filters = $request->only(['search', 'job_lead_status', 'is_locked']);

            $filename = 'ผู้สมัคร_' . ($job->job_number ?? $job->job_id) . '_' . now()->format('Ymd_His') . '.xlsx';

            return Excel::download(new JobApplicantsExport($jobId, $filters), $filename);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'ไม่สามารถสร้างไฟล์ Export ได้: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new job lead
     */
    public function create(Request $request)
    {
        $jobId = $request->job_id;
        $job = null;
        
        if ($jobId) {
            $job = JobModel::with(['country', 'demand'])->findOrFail($jobId);
            
            // ตรวจสอบว่าเต็มแล้วหรือไม่
            if ($job->remaining_positions <= 0) {
                return redirect()->route('jobs.show', $job->job_id)
                               ->with('warning', 'งานนี้เต็มแล้ว ไม่สามารถเพิ่มใบสมัครได้');
            }
        }
        
        $jobs = JobModel::where('job_status', 'เปิดรับสมัคร')
                       ->with(['country', 'demand'])
                       ->get();
        
        // หาคนงานที่ว่าง (ไม่ถูกล็อค)
        $availableLeads = $this->getAvailableLeads();
        
        return view('job-leads.create', compact('jobs', 'job', 'availableLeads'));
    }

    /**
     * Store a newly created job lead
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'job_id' => 'required|exists:jobs,job_id',
            'leads' => 'required|array|min:1',
            'leads.*' => 'required|numeric',
        ], [
            'job_id.required' => 'กรุณาเลือกงาน',
            'leads.required' => 'กรุณาเลือกคนงานอย่างน้อย 1 คน',
            'leads.min' => 'กรุณาเลือกคนงานอย่างน้อย 1 คน',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();
            
            $job = JobModel::findOrFail($request->job_id);
            $leads = $request->leads;
            
            // ตรวจสอบว่าเกินจำนวนที่เปิดรับหรือไม่
            if (count($leads) > $job->remaining_positions) {
                return redirect()->back()
                               ->with('error', 'จำนวนคนงานที่เลือก (' . count($leads) . ') เกินจำนวนที่เหลือ (' . $job->remaining_positions . ')')
                               ->withInput();
            }
            
            $createdCount = 0;
            $skippedCount = 0;
            $skippedLeads = [];
            
            foreach ($leads as $leadId) {
                // ตรวจสอบว่าคนงานสามารถส่งใบสมัครได้หรือไม่
                if (!JobLeadModel::isLeadAvailable($leadId)) {
                    $lead = \App\Models\leads\LeadModel::find($leadId);
                    $leadName = $lead ? $lead->getFullNameAttribute() : "Lead ID: $leadId";
                    
                    $skippedCount++;
                    $skippedLeads[] = $leadName . ' (มีใบสมัครอื่นอยู่แล้ว)';
                    continue;
                }
                
                // ตรวจสอบว่าเคยส่งใบสมัครงานนี้หรือไม่ (double check)
                $existingLead = JobLeadModel::where('job_id', $request->job_id)
                                          ->where('lead_id', $leadId)
                                          ->first();
                
                if ($existingLead) {
                    $lead = \App\Models\leads\LeadModel::find($leadId);
                    $leadName = $lead ? $lead->getFullNameAttribute() : "Lead ID: $leadId";
                    
                    $skippedCount++;
                    $skippedLeads[] = $leadName . ' (เคยส่งใบสมัครงานนี้แล้ว)';
                    continue;
                }
                
                JobLeadModel::create([
                    'job_id' => $request->job_id,
                    'lead_id' => $leadId,
                    'job_lead_status' => 'ร่าง',
                ]);
                
                $createdCount++;
            }
            
            DB::commit();
            
            $message = "สร้างใบสมัครสำเร็จ {$createdCount} รายการ";
            if ($skippedCount > 0) {
                $message .= " ข้าม {$skippedCount} รายการ (คนงานถูกล็อคหรือเคยส่งแล้ว)";
            }
            
            return redirect()->route('job-leads.index')
                           ->with('success', $message);
                           
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified job lead
     */
    public function show($id)
    {
        $jobLead = JobLeadModel::with(['job.country', 'job.demand', 'createdBy', 'updatedBy'])
                              ->findOrFail($id);
        
        return view('job-leads.show', compact('jobLead'));
    }

    /**
     * Show the form for editing the job lead
     */
    public function edit($id)
    {
        $jobLead = JobLeadModel::with(['job'])->findOrFail($id);
        
        return view('job-leads.edit', compact('jobLead'));
    }

    /**
     * Update the specified job lead
     */
    public function update(Request $request, $id)
    {
        $jobLead = JobLeadModel::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'job_lead_status' => 'required|in:ร่าง,ส่งแล้ว,กำลังพิจารณา,นัดสัมภาษณ์,เสนองาน,ตอบรับ,ปฏิเสธ,ถอน',
            'remarks' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();
            
            $oldStatus = $jobLead->job_lead_status;
            $newStatus = $request->job_lead_status;
            
            // อัปเดตข้อมูล
            $jobLead->update([
                'job_lead_status' => $newStatus,
                'remarks' => $request->remarks,
            ]);
            
            // บันทึกประวัติการเปลี่ยนสถานะ
            if ($oldStatus !== $newStatus) {
                $jobLead->update([
                    'remarks' => $jobLead->remarks . "\n[" . now()->format('d/m/Y H:i') . "] เปลี่ยนจาก '{$oldStatus}' เป็น '{$newStatus}' โดย " . auth()->user()->name
                ]);
            }
            
            DB::commit();
            
            return redirect()->route('job-leads.index')
                           ->with('success', 'อัปเดตสถานะใบสมัครสำเร็จ');
                           
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified job lead
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            
            $jobLead = JobLeadModel::findOrFail($id);
            
            // ปลดล็อคคนงานก่อนลบ
            if ($jobLead->is_locked) {
                $jobLead->unlock('ใบสมัครถูกลบ');
            }
            
            $jobLead->delete();
            
            DB::commit();
            
            return redirect()->route('job-leads.index')
                           ->with('success', 'ลบใบสมัครสำเร็จ');
                           
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
    }

    /**
     * Force unlock a job lead (Admin only)
     */
    public function forceUnlock(Request $request, $id)
    {
        if (!auth()->user()->hasPermissionTo('job-lead-admin') && !auth()->user()->hasRole('super-admin')) {
            return redirect()->back()->with('error', 'คุณไม่มีสิทธิ์ในการปลดล็อคบังคับ');
        }
        
        try {
            $jobLead = JobLeadModel::findOrFail($id);
            $reason = $request->reason ?? 'Admin บังคับปลดล็อค';
            
            $jobLead->forceUnlock($reason);
            
            return redirect()->back()->with('success', 'ปลดล็อคคนงานบังคับสำเร็จ');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
    }

    /**
     * Bulk update job lead status
     */
    public function bulkUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'job_lead_ids' => 'required|array|min:1',
            'job_lead_ids.*' => 'exists:job_leads,job_lead_id',
            'new_status' => 'required|in:ร่าง,ส่งแล้ว,กำลังพิจารณา,นัดสัมภาษณ์,เสนองาน,ตอบรับ,ปฏิเสธ,ถอน',
            'remarks' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        try {
            DB::beginTransaction();
            
            $updatedCount = 0;
            $newStatus = $request->new_status;
            $remarks = $request->remarks;
            
            foreach ($request->job_lead_ids as $jobLeadId) {
                $jobLead = JobLeadModel::find($jobLeadId);
                if ($jobLead) {
                    $oldStatus = $jobLead->job_lead_status;
                    
                    $jobLead->update([
                        'job_lead_status' => $newStatus,
                        'remarks' => $jobLead->remarks . "\n[" . now()->format('d/m/Y H:i') . "] Bulk update จาก '{$oldStatus}' เป็น '{$newStatus}' โดย " . auth()->user()->name . ($remarks ? " - {$remarks}" : "")
                    ]);
                    
                    $updatedCount++;
                }
            }
            
            DB::commit();
            
            return redirect()->back()->with('success', "อัปเดตสถานะสำเร็จ {$updatedCount} รายการ");
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
    }

    /**
     * Get available leads (not locked)
     */
    private function getAvailableLeads($limit = 20)
    {
        return LeadModel::with(['position', 'country'])
                       ->whereNotNull('lead_firstname')
                       ->whereNotNull('lead_lastname')
                       ->where('lead_status', '!=', 'converted') // ไม่เอาที่แปลงเป็น labour แล้ว
                       ->whereDoesntHave('jobLeads', function($query) {
                           $query->where('is_locked', true);
                       })
                       ->limit($limit)
                       ->get()
                       ->map(function($lead) {
                           return [
                               'id' => $lead->lead_id,
                               'name' => $lead->getFullNameAttribute(),
                               'passport' => $lead->lead_passport_number ?? 'ไม่มี',
                               'position' => $lead->position->position_name ?? 'ไม่ระบุ',
                               'country' => $lead->country->country_name_th ?? 'ไม่ระบุ',
                               'phone' => $lead->lead_phone,
                               'is_locked' => !JobLeadModel::isLeadAvailable($lead->lead_id),
                               'status' => $lead->lead_status
                           ];
                       })
                       ->toArray();
    }

    /**
     * Search available leads by AJAX
     */
    public function searchAvailableLeads(Request $request)
    {
        $search = $request->get('search', '');
        $jobId = $request->get('job_id');
        
        $query = LeadModel::with(['position', 'country'])
                          ->whereNotNull('lead_firstname')
                          ->whereNotNull('lead_lastname')
                          ->where('lead_status', '!=', 'converted'); // ไม่เอาที่แปลงเป็น labour แล้ว
        
        // Filter by search term
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->whereRaw("CONCAT(lead_prefix, ' ', lead_firstname, ' ', lead_lastname) LIKE ?", ["%{$search}%"])
                  ->orWhere('lead_passport_number', 'LIKE', "%{$search}%")
                  ->orWhere('lead_phone', 'LIKE', "%{$search}%")
                  ->orWhereHas('position', function($posQuery) use ($search) {
                      $posQuery->where('position_name', 'LIKE', "%{$search}%");
                  });
            });
        }
        
        $leads = $query->limit(50)->get()->map(function($lead) use ($jobId) {
            $isAvailable = JobLeadModel::isLeadAvailable($lead->lead_id, $jobId);
            $existingApp = !$isAvailable ? JobLeadModel::getExistingApplicationInfo($lead->lead_id, $jobId) : null;
            
            return [
                'id' => $lead->lead_id,
                'name' => $lead->getFullNameAttribute(),
                'passport' => $lead->lead_passport_number ?? 'ไม่มี',
                'position' => $lead->position->position_name ?? 'ไม่ระบุ',
                'country' => $lead->country->country_name_th ?? 'ไม่ระบุ',
                'phone' => $lead->lead_phone,
                'age' => $lead->lead_age,
                'education' => $lead->lead_education,
                'is_locked' => !$isAvailable,
                'already_applied' => $jobId ? $this->checkAlreadyApplied($jobId, $lead->lead_id) : false,
                'status' => $lead->lead_status,
                'existing_application' => $existingApp
            ];
        });
        
        return response()->json($leads->toArray());
    }

    /**
     * Check if lead already applied to specific job
     */
    private function checkAlreadyApplied($jobId, $leadId)
    {
        try {
            return JobLeadModel::where('job_id', $jobId)
                              ->where('lead_id', $leadId)
                              ->exists();
        } catch (\Exception $e) {
            // ถ้า table ยังไม่มี return false
            return false;
        }
    }

    /**
     * Cancel job application (specific route)
     */
    public function cancel($id)
    {
        try {
            DB::beginTransaction();
            
            $jobLead = JobLeadModel::findOrFail($id);
            
            // Check if user has permission
            if (!auth()->user()->can('job-lead-delete')) {
                return response()->json(['error' => 'ไม่มีสิทธิ์ในการยกเลิกใบสมัคร'], 403);
            }
            
            // Check if status allows cancellation
            if (!in_array($jobLead->job_lead_status, ['ร่าง', 'ส่งแล้ว'])) {
                return response()->json(['error' => 'ไม่สามารถยกเลิกใบสมัครในสถานะนี้ได้'], 400);
            }
            
            // Unlock lead if locked
            if ($jobLead->is_locked) {
                $jobLead->unlock('ยกเลิกใบสมัคร');
            }
            
            // Delete the job lead
            $jobLead->delete();
            
            DB::commit();
            
            return response()->json(['success' => 'ยกเลิกใบสมัครสำเร็จ']);
                           
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()], 500);
        }
    }
}
