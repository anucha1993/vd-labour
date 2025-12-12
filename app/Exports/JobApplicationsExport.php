<?php

namespace App\Exports;

use App\Models\jobs\JobLeadModel;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\Exportable;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class JobApplicationsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    use Exportable;

    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = JobLeadModel::with(['lead.position', 'lead.country', 'lead.staff', 'lead.recommenderStaff', 'job'])
                    ->orderBy('created_at', 'desc');

        // Filter by selected job_lead_ids
        if (!empty($this->filters['job_lead_ids'])) {
            $query->whereIn('job_lead_id', $this->filters['job_lead_ids']);
        }

        // Filter by statuses
        if (!empty($this->filters['statuses'])) {
            $query->whereIn('job_lead_status', $this->filters['statuses']);
        }

        // Filter by staff (สรรหา)
        if (!empty($this->filters['staff_ids'])) {
            $query->whereHas('lead', function($q) {
                $q->whereIn('staff_id', $this->filters['staff_ids']);
            });
        }

        // Filter by recommender staff (สายหาคน)
        if (!empty($this->filters['recommender_staff_ids'])) {
            $query->whereHas('lead', function($q) {
                $q->whereIn('lead_recommender_staff_sub_id', $this->filters['recommender_staff_ids']);
            });
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            '#',
            'เลขที่ใบสมัคร',
            'เลขที่ผู้สมัคร',
            'ชื่อ-นามสกุล',
            'Passport',
            'เบอร์โทร',
            'ตำแหน่ง',
            'ประเทศ',
            'งาน',
            'เลขงาน',
            'สถานะใบสมัคร',
            'สรรหาโดย (Staff)',
            'สายแนะนำ (Staff Sub)',
            'วันที่สมัคร',
            'วันที่อัพเดท',
        ];
    }

    /**
     * @var JobLeadModel $jobLead
     */
    public function map($jobLead): array
    {
        static $rowNumber = 0;
        $rowNumber++;

        $lead = $jobLead->lead;

        return [
            $rowNumber,
            $jobLead->job_lead_number ?? '-',
             $lead->lead_number ?? '-',
            ($lead->lead_prefix ?? '') . ' ' . ($lead->lead_firstname ?? '') . ' ' . ($lead->lead_lastname ?? ''),
            $lead->lead_passport_number ?? '-',
            $lead->lead_phone ?? '-',
            $lead->position->position_name ?? '-',
            $jobLead->job->country->country_name_th ?? '-',
            $jobLead->job->job_name ?? '-',
            $jobLead->job->job_number ?? '-',
            $jobLead->job_lead_status ?? '-',
            $lead->staff->staff_name ?? '-',
            $lead->recommenderStaff->staff_sub_name ?? '-',
            $jobLead->created_at ? $jobLead->created_at->format('d/m/Y H:i') : '-',
            $jobLead->updated_at ? $jobLead->updated_at->format('d/m/Y H:i') : '-',
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4']
                ],
                'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true],
            ],
        ];
    }
}
