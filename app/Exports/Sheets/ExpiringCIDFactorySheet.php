<?php

namespace App\Exports\Sheets;

use App\Models\labours\labourModel;
use App\Exports\LabourExportHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class ExpiringCIDFactorySheet implements FromCollection, WithHeadings, WithTitle,WithColumnWidths
{
    public function collection()
    {
        return labourModel::ExpiringCIDFactory()
            ->with('customer') // ดึงข้อมูลลูกค้ามาด้วย
            ->get()
            
            ->map(function ($item) {
                return [
                    $item->labour_prefix,
                    $item->labour_firstname,
                    $item->labour_lastname,
                    $item->labour_phone,
                    $item->labour_passport_number,
                    $item->labour_passport_expiry ? date('d/m/Y',strtotime($item->labour_passport_expiry)) : '-',
                    optional($item->country)->country_name_th ?? '-', 
                    optional($item->jobGroup)->labour_job_name ?? '-',
                    optional($item->position)->position_name ?? '-',
                    optional($item->locationTest)->location_test_name ?? '-',
                    $item->labour_register_number,
                    optional($item->customer)->customer_name ?? 'ยังไม่มีนายจ้าง', 
                    $item->labour_disease_expriry ? date('d/m/Y',strtotime($item->labour_disease_expriry)) : '-',
                    $item->labour_cid_expriry ? date('d/m/Y',strtotime($item->labour_cid_expriry)) : '-',
                    optional($item->staff)->staff_name ?? 'ยังไม่มีผู้ดูแล', 
                    optional($item->staffSub)->staff_sub_name ?? '-', 
                    labourStatusBadge(optional($item)->labour_status ?? ''),
                    $item->labour_note,
                ];
            });
    }

    public function headings(): array
    {
        return LabourExportHeadings::headings();
    }

    public function title(): string { return 'CID โรงงาน'; }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 20,
            'C' => 20,
            'D' => 20,
            'E' => 20,
            'F' => 20,
            'G' => 25,
            'H' => 25,
            'I' => 25,
            'J' => 30,
            'K' => 30,
            'L' => 30,
            'M' => 20,
            'N' => 20,
            'O' => 30,
            'P' => 30,
            'Q' => 20,
            'R' => 30,
        ];
    }

}
