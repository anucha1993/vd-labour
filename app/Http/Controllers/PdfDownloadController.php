<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\MpdfService;
use App\Models\leads\LeadModel;
use App\Models\positions\PositionModel;
use Illuminate\Http\Request;

class PdfDownloadController extends Controller
{
    protected $mpdfService;

    public function __construct(MpdfService $mpdfService)
    {
        $this->mpdfService = $mpdfService;
    }

    /**
     * Download Lead PDF Report
     */
    public function downloadLeadPdf($leadId)
    {
        $lead = LeadModel::with(['position', 'country', 'jobGroup', 'staff', 'jobHistory'])->findOrFail($leadId);
        
        $data = [
            'lead' => $lead,
            'title' => 'รายงานข้อมูล Lead: ' . $lead->getFullNameAttribute(),
            'generated_at' => now()->format('d/m/Y H:i:s')
        ];
        
        $filename = 'lead_report_' . $leadId . '_' . now()->format('Ymd_His') . '.pdf';
        
        return $this->mpdfService->generateFromView('pdf.lead_report', $data, $filename, 'D'); // 'D' for download
    }

    /**
     * Download Leads List PDF
     */
    public function downloadLeadsListPdf(Request $request)
    {
        $query = LeadModel::with(['position', 'country', 'staff']);
        
        // Apply filters
        $filters = [];
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('lead_name_th', 'like', "%{$search}%")
                  ->orWhere('lead_name_en', 'like', "%{$search}%")
                  ->orWhere('lead_phone', 'like', "%{$search}%")
                  ->orWhere('lead_passport', 'like', "%{$search}%");
            });
            $filters['search'] = $search;
        }
        
        if ($request->filled('lead_status') && $request->lead_status != 'all') {
            $query->where('lead_status', $request->lead_status);
            $filters['lead_status'] = $request->lead_status;
        }
        
        if ($request->filled('position_id') && $request->position_id != 'all') {
            $query->where('position_id', $request->position_id);
            $position = PositionModel::find($request->position_id);
            $filters['position_name'] = $position ? ($position->position_name_th ?? $position->position_name) : '';
        }
        
        $leads = $query->orderBy('created_at', 'desc')->get();
        
        // Get status summary
        $statusSummary = LeadModel::selectRaw('lead_status, COUNT(*) as count')
            ->groupBy('lead_status')
            ->get();
        
        $data = [
            'leads' => $leads,
            'title' => 'รายงานข้อมูลผู้สนใจ (Leads)',
            'filters' => $filters,
            'hasFilters' => !empty($filters),
            'totalLeads' => LeadModel::count(),
            'statusSummary' => $statusSummary,
            'generated_at' => now()->format('d/m/Y H:i:s')
        ];
        
        $filename = 'leads_list_' . date('Y-m-d_H-i-s') . '.pdf';
        
        return $this->mpdfService->generateFromView('pdf.leads_list', $data, $filename, 'D'); // 'D' for download
    }

    /**
     * Download CV Form PDF
     */
    public function downloadCvForm($leadId = null)
    {
        $lead = null;
        if ($leadId) {
            $lead = LeadModel::with(['position', 'position2', 'position3', 'country', 'jobGroup', 'staff', 'jobHistory', 'recommenderStaff.staff'])->findOrFail($leadId);
        }
        
        $data = [
            'title' => 'Application Form - ใบสมัครงาน',
            'lead' => $lead,
            'generated_at' => now()->format('d/m/Y H:i:s')
        ];
        
        $filename = 'cv_form_' . ($leadId ? $leadId . '_' : '') . now()->format('Ymd_His') . '.pdf';
        
        return $this->mpdfService->generateFromView('pdf.cv_form_complete', $data, $filename, 'D'); // 'D' for download
    }
}