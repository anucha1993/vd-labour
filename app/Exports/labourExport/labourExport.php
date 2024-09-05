<?php

namespace App\Exports\labourExport;

use App\Models\labours\labourModel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class labourExport implements FromCollection,WithHeadings,WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    private $num = 0;
    public function collection()
    {
        //
        $labour = labourModel::leftjoin('position','position.position_id','labours.labour_position')
        ->leftjoin('staff','staff.staff_id','labours.labour_staff')
        ->get();
        return $labour;
    }

    public function headings(): array
    {
        return [
            'No.',
            'First-Name',
            'Last-Name',
            'NAME',
            'Position',
            'รหัส',
            'Passport',
            'Date Issue', //วันเริ่มต้น Passport
            'Date Expiry',//วันหมด Passport
            'เบอร์ติดต่อ',
            'เจ้าหน้าที่ดูแล',
            'Docs Success',
            'Docs Wait',
            'Remark',
            'สถานะ',
        ];
    }


    public function map($labour): array
    {
        // Labour Status
        switch ($labour->labour_status) {
            case 'wait':
               $status = 'กำลังดำเนินการ';
              break;
            case 'success':
                $status = 'บินแล้ว';
              break;
            case 'cancel':
                $status = 'ยกเลิก';
              break;
            default:
              $status = '-';
          }

        return [
            ++$this->num,
            $labour->labour_firstname,
            $labour->labour_lastname,
            $labour->labour_prefix.'.'.$labour->labour_firstname.' '.$labour->lastname,
            $labour->position_name,
            $labour->labour_register_number,
            $labour->labour_passport_number,
            $labour->labour_passport_issue,
            $labour->labour_passport_expiry,
            $labour->labour_phone,
            $labour->staff_name,
            'CID',
            'DOC',
            $labour->labour_note,
            $status,
        ];
    }
}
