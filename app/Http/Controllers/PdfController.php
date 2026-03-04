<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\MpdfService;
use App\Models\leads\LeadModel;
use App\Models\positions\PositionModel;
use App\Models\demands\DemandModel;
use Illuminate\Http\Request;

class PdfController extends Controller
{
    protected $mpdfService;

    public function __construct(MpdfService $mpdfService)
    {
        $this->mpdfService = $mpdfService;
    }

    /**
     * Generate Lead PDF Report
     */
    public function generateLeadPdf($leadId)
    {
        $lead = LeadModel::with(['position', 'country', 'jobGroup', 'staff', 'jobHistory'])->findOrFail($leadId);
        
        $data = [
            'lead' => $lead,
            'title' => 'รายงานข้อมูล Lead: ' . $lead->getFullNameAttribute(),
            'generated_at' => now()->format('d/m/Y H:i:s')
        ];
        
        $filename = 'lead_report_' . $leadId . '.pdf';
        
        return $this->mpdfService->generateFromView('pdf.lead_report', $data, $filename, 'I'); // 'I' for inline display
    }

    /**
     * Generate Leads List PDF
     */
    public function generateLeadsListPdf(Request $request)
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
        
        return $this->mpdfService->generateFromView('pdf.leads_list', $data, $filename, 'I'); // 'I' for inline display
    }

    /**
     * Generate CV Form PDF
     */
    public function generateCvForm($leadId = null)
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
        
        // Set custom config for CV form
        $config = [
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 2,
            'margin_bottom' => 10,
            'orientation' => 'P'
        ];
        
        return $this->mpdfService->generateFromView('pdf.cv_form_complete', $data, $filename, 'I', $config);
    }

    /**
     * Test PDF Generation
     */
    public function testPdf()
    {
        $html = '
            <h1 style="text-align: center; color: #333;">ทดสอบ mPDF</h1>
            <p style="font-size: 16px;">นี่คือการทดสอบการสร้าง PDF ด้วย mPDF</p>
            <ul>
                <li>รองรับภาษาไทย ✓</li>
                <li>รองรับ Unicode ✓</li>
                <li>สร้าง PDF ได้ ✓</li>
            </ul>
            <p><strong>วันที่:</strong> ' . now()->format('d/m/Y H:i:s') . '</p>
        ';
        
        return $this->mpdfService->generateFromHtml($html, 'test.pdf', 'I'); // 'I' for inline display
    }

    /**
     * Generate Demand PDF Report
     */
    public function generateDemandPdf($demandId)
    {
        $demand = DemandModel::with(['industryType', 'positions.position'])->findOrFail($demandId);
        
        $data = [
            'demand' => $demand,
            'title' => 'รายงานความต้องการแรงงาน: ' . $demand->dm_com_name,
            'generated_at' => now()->format('d/m/Y H:i:s')
        ];

        $config = [
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 20,
            'margin_bottom' => 20,
            'margin_header' => 10,
            'margin_footer' => 10
        ];

        return $this->mpdfService->generateFromView('demands.pdf', $data, 'demand_report_' . $demandId . '.pdf', 'I', $config);
    }
}