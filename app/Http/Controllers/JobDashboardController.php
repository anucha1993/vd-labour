<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\jobs\JobModel;
use App\Models\jobs\JobLeadModel;
use App\Models\country\countryModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class JobDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware('permission:job-dashboard');
    }

    /**
     * Display job application dashboard
     */
    public function index()
    {
        // Overall Statistics
        $totalJobs = JobModel::count();
        $activeJobs = JobModel::where('job_status', 'เปิดรับสมัคร')->count();
        $closedJobs = JobModel::where('job_status', 'ปิดรับสมัคร')->count();
        $expiredJobs = JobModel::whereNotNull('job_end_date')
                             ->where('job_end_date', '<', now())
                             ->count();

        try {
            $totalApplications = JobLeadModel::count();
            $lockedLeads = JobLeadModel::where('is_locked', true)->count();
        } catch (\Exception $e) {
            $totalApplications = 0;
            $lockedLeads = 0;
        }
        
        // Applications by Status
        try {
            $applicationsByStatus = JobLeadModel::select('job_lead_status', DB::raw('COUNT(*) as count'))
                                              ->groupBy('job_lead_status')
                                              ->pluck('count', 'job_lead_status')
                                              ->toArray();
        } catch (\Exception $e) {
            // ถ้า table job_leads ยังไม่มี
            $applicationsByStatus = [];
        }

        // Success Rate
        $acceptedApplications = $applicationsByStatus['ตอบรับ'] ?? 0;
        $successRate = $totalApplications > 0 ? round(($acceptedApplications / $totalApplications) * 100, 1) : 0;

        // Top Countries
        $topCountries = JobModel::select('country_id', DB::raw('COUNT(*) as job_count'))
                               ->with('country')
                               ->groupBy('country_id')
                               ->orderBy('job_count', 'desc')
                               ->limit(5)
                               ->get()
                               ->map(function ($item) {
                                   return [
                                       'country_name' => $item->country->country_name_th ?? 'Unknown',
                                       'job_count' => $item->job_count,
                                       'applications' => JobLeadModel::whereHas('job', function($q) use ($item) {
                                           $q->where('country_id', $item->country_id);
                                       })->count(),
                                       'accepted' => JobLeadModel::whereHas('job', function($q) use ($item) {
                                           $q->where('country_id', $item->country_id);
                                       })->where('job_lead_status', 'ตอบรับ')->count()
                                   ];
                               });

        // Recent Jobs
        $recentJobs = JobModel::with(['country', 'demand'])
                             ->withCount(['jobLeads', 'jobLeads as accepted_count' => function($q) {
                                 $q->where('job_lead_status', 'ตอบรับ');
                             }])
                             ->orderBy('created_at', 'desc')
                             ->limit(10)
                             ->get();

        // Alerts & Notifications
        $alerts = [];
        
        // Jobs close to expiry
        try {
            $soonToExpire = JobModel::whereNotNull('job_end_date')
                                   ->where('job_end_date', '>', now())
                                   ->where('job_end_date', '<=', now()->addDays(7))
                                   ->where('job_status', 'เปิดรับสมัคร')
                                   ->count();
            
            if ($soonToExpire > 0) {
                $alerts[] = [
                    'type' => 'warning',
                    'message' => "มีงาน {$soonToExpire} รายการ ที่จะหมดอายุภายใน 7 วัน",
                    'count' => $soonToExpire
                ];
            }

            // Jobs with long pending applications
            $longPendingCount = JobLeadModel::where('job_lead_status', 'ส่งแล้ว')
                                           ->where('created_at', '<', now()->subDays(7))
                                           ->count();
            
            if ($longPendingCount > 0) {
                $alerts[] = [
                    'type' => 'info',
                    'message' => "มีใบสมัคร {$longPendingCount} รายการ รอนายจ้างตอบเกิน 7 วัน",
                    'count' => $longPendingCount
                ];
            }
        } catch (\Exception $e) {
            // ถ้าเกิด error ในการดึงข้อมูล alerts
        }

        // Monthly Statistics for Chart
        try {
            $monthlyStats = JobLeadModel::select(
                                          DB::raw('YEAR(created_at) as year'),
                                          DB::raw('MONTH(created_at) as month'),
                                          DB::raw('COUNT(*) as total'),
                                          DB::raw('SUM(CASE WHEN job_lead_status = "ตอบรับ" THEN 1 ELSE 0 END) as accepted')
                                      )
                                      ->where('created_at', '>=', now()->subMonths(6))
                                      ->groupBy('year', 'month')
                                      ->orderBy('year', 'asc')
                                      ->orderBy('month', 'asc')
                                      ->get()
                                      ->map(function ($item) {
                                          return [
                                              'month' => Carbon::create($item->year, $item->month, 1)->format('M Y'),
                                              'total' => $item->total,
                                              'accepted' => $item->accepted,
                                              'rate' => $item->total > 0 ? round(($item->accepted / $item->total) * 100, 1) : 0
                                          ];
                                      });
        } catch (\Exception $e) {
            $monthlyStats = collect();
        }

        return view('jobs.dashboard', compact(
            'totalJobs', 'activeJobs', 'closedJobs', 'expiredJobs',
            'totalApplications', 'lockedLeads', 'applicationsByStatus',
            'successRate', 'topCountries', 'recentJobs', 'alerts', 'monthlyStats'
        ));
    }

    /**
     * Get dashboard data as JSON for AJAX
     */
    public function getData()
    {
        $data = [
            'jobs' => [
                'total' => JobModel::count(),
                'active' => JobModel::where('job_status', 'เปิดรับสมัคร')->count(),
                'closed' => JobModel::where('job_status', 'ปิดรับสมัคร')->count(),
                'expired' => JobModel::whereNotNull('job_end_date')
                                   ->where('job_end_date', '<', now())
                                   ->count(),
            ],
            'applications' => [
                'total' => JobLeadModel::count(),
                'locked' => JobLeadModel::where('is_locked', true)->count(),
                'by_status' => JobLeadModel::select('job_lead_status', DB::raw('COUNT(*) as count'))
                                         ->groupBy('job_lead_status')
                                         ->pluck('count', 'job_lead_status')
                                         ->toArray(),
            ],
            'success_rate' => (function() {
                $total = JobLeadModel::count();
                $accepted = JobLeadModel::where('job_lead_status', 'ตอบรับ')->count();
                return $total > 0 ? round(($accepted / $total) * 100, 1) : 0;
            })(),
            'updated_at' => now()->format('d/m/Y H:i:s')
        ];

        return response()->json($data);
    }

    /**
     * Export dashboard statistics
     */
    public function export(Request $request)
    {
        $format = $request->get('format', 'excel');
        
        // Implementation for export functionality
        // This would integrate with Excel/PDF export libraries
        
        return redirect()->back()->with('info', 'ฟีเจอร์ Export กำลังพัฒนา');
    }
}
