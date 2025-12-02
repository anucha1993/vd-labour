<?php
namespace App\Exports;

use App\Models\jobs\JobLeadModel;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\Exportable;

class JobApplicantsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    use Exportable;

    protected $jobId;
    protected $filters;

    public function __construct($jobId, $filters = [])
    {
        $this->jobId = $jobId;
        $this->filters = $filters;
    }

    public function query()
    {
        $query = JobLeadModel::with(['lead.position', 'lead.country'])
                    ->where('job_id', $this->jobId)
                    ->orderBy('created_at', 'desc');

        if (!empty($this->filters['search'])) {
            $search = $this->filters['search'];
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

        if (isset($this->filters['job_lead_status']) && $this->filters['job_lead_status'] !== '') {
            $query->where('job_lead_status', $this->filters['job_lead_status']);
        }

        if (isset($this->filters['is_locked']) && $this->filters['is_locked'] !== '') {
            $query->where('is_locked', $this->filters['is_locked']);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'หมายเลขใบสมัคร',
            'ชื่อ-นามสกุล',
            'Passport',
            'เบอร์โทร',
            'ตำแหน่ง',
            'ประเทศ',
            'สถานะ',
            'ล็อค',
            'วันที่สมัคร',
            'หมายเหตุ'
        ];
    }

    public function map($jobLead): array
    {
        $lead = $jobLead->lead;

        return [
            $jobLead->job_lead_number,
            $lead ? $lead->getFullNameAttribute() : '',
            $lead ? ($lead->lead_passport_number ?? '') : '',
            $lead ? ($lead->lead_phone ?? '') : '',
            $lead && $lead->position ? $lead->position->position_name : '',
            $lead && $lead->country ? $lead->country->country_name_th : '',
            $jobLead->job_lead_status,
            $jobLead->is_locked ? 'ล็อค' : 'ไม่ล็อค',
            $jobLead->created_at ? $jobLead->created_at->format('Y-m-d H:i') : '',
            $jobLead->remarks ?? ''
        ];
    }
}
