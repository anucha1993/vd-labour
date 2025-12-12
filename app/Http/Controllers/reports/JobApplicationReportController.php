<?php

namespace App\Http\Controllers\reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\JobApplicationsExport;

class JobApplicationReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show job applications export form
     */
    public function index()
    {
        return view('reports.job-applications-export');
    }

    /**
     * Get job leads list for export selection
     */
    public function getJobLeadsList()
    {
        try {
            // Get all job leads (ไม่จำกัดเฉพาะ user)
            $jobLeads = \App\Models\jobs\JobLeadModel::with(['lead', 'job'])
                ->orderBy('created_at', 'desc')
                ->limit(200) // Increase limit
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

    /**
     * Export job applications to Excel with filters
     */
    public function export(Request $request)
    {
        try {
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

            // ไม่จำกัด staff - export ทั้งหมดตาม filter ที่เลือก

            $filename = 'รายงานใบสมัครงาน_' . now()->format('Ymd_His') . '.xlsx';

            return Excel::download(new JobApplicationsExport($filters), $filename);

        } catch (\Exception $e) {
            \Log::error('Export Job Applications Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาดในการ Export: ' . $e->getMessage());
        }
    }
}
