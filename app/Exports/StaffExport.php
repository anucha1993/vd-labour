<?php

namespace App\Exports;

use App\Models\staff\staffModel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StaffExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return staffModel::with('user')->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            '#',
            'ชื่อเจ้าหน้าที่',
            'ชื่อเล่น',
            'User (ชื่อผู้ใช้)',
            'Email',
            'สถานะ',
            'จำนวน Lead',
            'วันที่สร้าง',
        ];
    }

    /**
     * @var staffModel $staff
     */
    public function map($staff): array
    {
        static $rowNumber = 0;
        $rowNumber++;

        $leadCount = \DB::table('leads')->where('staff_id', $staff->staff_id)->count();

        return [
            $rowNumber,
            $staff->staff_name,
            $staff->staff_nickname ?? '-',
            $staff->user ? $staff->user->name : '-',
            $staff->user ? $staff->user->email : '-',
            $staff->staff_status == 'active' ? 'ใช้งาน' : 'ไม่ใช้งาน',
            $leadCount,
            $staff->created_at ? $staff->created_at->format('d/m/Y H:i') : '-',
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

