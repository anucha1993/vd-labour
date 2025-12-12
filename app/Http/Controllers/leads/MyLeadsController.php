<?php

namespace App\Http\Controllers\leads;

use App\Http\Controllers\Controller;
use App\Models\leads\LeadModel;
use App\Models\positions\positionModel;
use Illuminate\Http\Request;

class MyLeadsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display leads where staff is linked to the logged-in user
     */
    public function index(Request $request)
    {
        // Get staff_id that is linked to current user
        $userStaff = \App\Models\staff\staffModel::where('user_id', auth()->id())->first();
        
        if (!$userStaff) {
            // If user is not linked to any staff, show empty result
            $leads = LeadModel::where('staff_id', null)->paginate(20);
            $positions = positionModel::where('position_status', 'active')->get();
            return view('my-leads.index', compact('leads', 'positions'));
        }
        
        $query = LeadModel::with(['position', 'country', 'jobGroup', 'staff', 'recommenderStaff', 'jobLeads'])
            ->where('staff_id', $userStaff->staff_id);
        
        // Search filters // 
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('lead_number', 'like', "%{$search}%")
                  ->orWhere('lead_firstname', 'like', "%{$search}%")
                  ->orWhere('lead_lastname', 'like', "%{$search}%")
                  ->orWhere('lead_phone', 'like', "%{$search}%")
                  ->orWhere('lead_passport_number', 'like', "%{$search}%")
                  ->orWhereHas('staff', function($q) use ($search) {
                      $q->where('staff_name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('recommenderStaff', function($q) use ($search) {
                      $q->where('staff_sub_name', 'like', "%{$search}%");
                  });
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
        
        // Calculate statistics for user's leads
        $totalLeads = LeadModel::where('staff_id', $userStaff->staff_id)->count();
        
        // Job Application Status Statistics
        $allJobLeads = \App\Models\jobs\JobLeadModel::whereHas('lead', function($q) use ($userStaff) {
            $q->where('staff_id', $userStaff->staff_id);
        });
        
        $jobLeadStats = [
            'draft' => (clone $allJobLeads)->where('job_lead_status', 'ร่าง')->count(),
            'sent' => (clone $allJobLeads)->where('job_lead_status', 'ส่งแล้ว')->count(),
            'considering' => (clone $allJobLeads)->where('job_lead_status', 'กำลังพิจารณา')->count(),
            'interview' => (clone $allJobLeads)->where('job_lead_status', 'นัดสัมภาษณ์')->count(),
            'offer' => (clone $allJobLeads)->where('job_lead_status', 'เสนองาน')->count(),
            'accepted' => (clone $allJobLeads)->where('job_lead_status', 'ตอบรับ')->count(),
            'rejected' => (clone $allJobLeads)->where('job_lead_status', 'ปฏิเสธ')->count(),
            'withdrawn' => (clone $allJobLeads)->where('job_lead_status', 'ถอน')->count(),
        ];
        
        // Convert Statistics
        $convertedLeads = LeadModel::where('staff_id', $userStaff->staff_id)
            ->where('lead_status', 'converted')
            ->whereNotNull('labour_id')
            ->count();
        
        // Labour Statistics (from converted leads)
        $labourStats = [
            'flying' => \App\Models\labours\labourModel::whereHas('leadModel', function($q) use ($userStaff) {
                $q->where('staff_id', $userStaff->staff_id);
            })->where('labour_status', 'success')->count(),
            
            'processing' => \App\Models\labours\labourModel::whereHas('leadModel', function($q) use ($userStaff) {
                $q->where('staff_id', $userStaff->staff_id);
            })->where('labour_status', 'wait')->count(),
            
            'cancelled' => \App\Models\labours\labourModel::whereHas('leadModel', function($q) use ($userStaff) {
                $q->where('staff_id', $userStaff->staff_id);
            })->where('labour_status', 'cancel')->count(),
        ];
        
        return view('my-leads.index', compact('leads', 'positions', 'totalLeads', 'jobLeadStats', 'convertedLeads', 'labourStats'));
    }

    /**
     * Show lead timeline for user's own leads (via staff)
     */
    public function timeline($id)
    {
        try {
            // Get staff_id that is linked to current user
            $userStaff = \App\Models\staff\staffModel::where('user_id', auth()->id())->first();
            
            if (!$userStaff) {
                return response()->json([
                    'success' => false,
                    'error' => 'คุณไม่มีสิทธิ์เข้าถึงข้อมูลนี้'
                ], 403);
            }
            
            $lead = LeadModel::where('staff_id', $userStaff->staff_id)
                ->findOrFail($id);
            
            // Get all activities for this lead (รวมถึงที่ job_lead ถูกยกเลิก)
            $activities = \App\Models\jobs\JobLeadActivityModel::with(['user'])
                ->where('lead_id', $lead->lead_id)
                ->orderBy('created_at', 'desc')
                ->get();
            
            // Load job info for each activity
            foreach ($activities as $activity) {
                if ($activity->job_lead_id) {
                    // ยังมี job_lead อยู่
                    $jobLead = \App\Models\jobs\JobLeadModel::with(['job.country', 'job.demand'])
                        ->find($activity->job_lead_id);
                    
                    if ($jobLead) {
                        $activity->job_info = [
                            'number' => $jobLead->job_lead_number,
                            'name' => $jobLead->job->job_name ?? '',
                            'country' => $jobLead->job->country->country_name_th ?? ''
                        ];
                    }
                } else {
                    // job_lead ถูกยกเลิกแล้ว - ใช้ข้อมูลที่เก็บไว้
                    $activity->job_info = [
                        'number' => $activity->job_lead_number ?? 'ไม่ระบุ',
                        'name' => 'ถูกยกเลิกแล้ว',
                        'country' => ''
                    ];
                }
            }
            
            // Render view
            $html = view('leads.partials.timeline', [
                'activities' => $activities,
                'lead' => $lead
            ])->render();
            
            return response()->json([
                'success' => true,
                'html' => $html,
                'count' => $activities->count()
            ]);
                           
        } catch (\Exception $e) {
            \Log::error('My Leads Timeline Error: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'error' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show export form
     */
    public function exportForm()
    {
        return view('my-leads.export-form');
    }

    /**
     * Export job applications to Excel with filters
     */
    public function exportJobApplications(Request $request)
    {
        try {
            // Get staff_id that is linked to current user
            $userStaff = \App\Models\staff\staffModel::where('user_id', auth()->id())->first();
            
            if (!$userStaff) {
                return redirect()->back()->with('error', 'ไม่พบข้อมูลเจ้าหน้าที่ที่เชื่อมกับบัญชีของคุณ');
            }

            // Prepare filters
            $filters = [];

            // Get job_lead_ids from request (selected checkboxes)
            if ($request->filled('job_lead_ids')) {
                $filters['job_lead_ids'] = is_array($request->job_lead_ids) 
                    ? $request->job_lead_ids 
                    : explode(',', $request->job_lead_ids);
            }

            // Get statuses filter (multiple selection)
            if ($request->filled('statuses')) {
                $filters['statuses'] = is_array($request->statuses) 
                    ? $request->statuses 
                    : [$request->statuses];
            }

            // Get staff_ids filter (multiple selection)
            if ($request->filled('staff_ids')) {
                $filters['staff_ids'] = is_array($request->staff_ids) 
                    ? $request->staff_ids 
                    : [$request->staff_ids];
            }

            // Get recommender staff_ids filter (multiple selection)
            if ($request->filled('recommender_staff_ids')) {
                $filters['recommender_staff_ids'] = is_array($request->recommender_staff_ids) 
                    ? $request->recommender_staff_ids 
                    : [$request->recommender_staff_ids];
            }

            // If no specific filters, export only user's leads
            if (empty($filters)) {
                $filters['staff_ids'] = [$userStaff->staff_id];
            }

            $filename = 'รายงานใบสมัครงาน_' . now()->format('Ymd_His') . '.xlsx';

            return \Excel::download(new \App\Exports\JobApplicationsExport($filters), $filename);

        } catch (\Exception $e) {
            \Log::error('Export Job Applications Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาดในการ Export: ' . $e->getMessage());
        }
    }

    /**
     * Get job leads list for export selection
     */
    public function getJobLeadsList()
    {
        try {
            // Get staff_id that is linked to current user
            $userStaff = \App\Models\staff\staffModel::where('user_id', auth()->id())->first();
            
            if (!$userStaff) {
                return response()->json([
                    'success' => false,
                    'message' => 'ไม่พบข้อมูลเจ้าหน้าที่'
                ]);
            }

            // Get all job leads for user's leads
            $jobLeads = \App\Models\jobs\JobLeadModel::with(['lead', 'job'])
                ->whereHas('lead', function($q) use ($userStaff) {
                    $q->where('staff_id', $userStaff->staff_id);
                })
                ->orderBy('created_at', 'desc')
                ->limit(100) // Limit to latest 100 for performance
                ->get()
                ->map(function($jobLead) {
                    return [
                        'job_lead_id' => $jobLead->job_lead_id,
                        'job_lead_number' => $jobLead->job_lead_number ?? 'N/A',
                        'lead_name' => ($jobLead->lead->lead_firstname ?? '') . ' ' . ($jobLead->lead->lead_lastname ?? ''),
                        'job_name' => $jobLead->job->job_name ?? 'N/A',
                        'status' => $jobLead->job_lead_status ?? 'N/A'
                    ];
                });

            return response()->json([
                'success' => true,
                'jobLeads' => $jobLeads
            ]);

        } catch (\Exception $e) {
            \Log::error('Get Job Leads List Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ], 500);
        }
    }
}
