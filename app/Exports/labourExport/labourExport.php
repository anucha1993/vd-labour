<?php

namespace App\Exports\labourExport;

use App\Models\labours\labourModel;
use App\Models\files\labourFileModel;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class labourExport implements FromCollection, WithHeadings, WithMapping, WithColumnWidths
{
    /**
     * @return \Illuminate\Support\Collection
     */
    private $num = 0;
    private $labour;

    private $labour_disease_date_start;
    private $labour_disease_date_end;
    private $labour_cid_start;
    private $labour_cid_end;
    private $labour_country;
    private $labour_job_group;
    private $labour_staff;
    private $labour_status;
    private $labour_customer;

    public function collection()
    {
        // ดึงข้อมูลจากฐานข้อมูลแล้วเก็บไว้ในตัวแปร $this->labour
        $this->labour = labourModel::leftjoin('position', 'position.position_id', 'labours.labour_position')
            ->leftjoin('staff', 'staff.staff_id', 'labours.labour_staff')

            //ค้นหาผลโรคหมดอายุ
            ->when($this->labour_disease_date_start !== null && $this->labour_disease_date_end !== null, function ($query) {
                return $query->whereDate('labours.labour_disease_expriry', '>=', $this->labour_disease_date_start)->whereDate('labours.labour_disease_expriry', '<=', $this->labour_disease_date_end);
            })
            //ค้นหาผล CID หมดอายุ
            ->when($this->labour_cid_start !== null && $this->labour_cid_end !== null, function ($query) {
                return $query->whereDate('labours.labour_cid_expriry', '>=', $this->labour_cid_start)->whereDate('labours.labour_cid_expriry', '<=', $this->labour_cid_end);
            })

            //ค้นหา ประเทศ
            ->when($this->labour_country != 'all' , function ($query) {
                return $query->where('labours.labour_country', '=', $this->labour_country);
            })
             //ค้นหา ประเภทงาน
             ->when($this->labour_job_group != 'all' , function ($query) {
                return $query->where('labours.labour_job_group', '=', $this->labour_job_group);
            })
             //ค้นหา ชื่อสรรหา
             ->when($this->labour_staff != 'all' , function ($query) {
                return $query->where('labours.labour_staff', '=', $this->labour_staff);
            })
             //ค้นหา สถานะ
             ->when($this->labour_status != 'all' , function ($query) {
                return $query->where('labours.labour_status', '=', $this->labour_status);
            })
              //ค้นหา โรงงาน
              ->when($this->labour_customer != 'all' , function ($query) {
                return $query->where('labours.labour_customer', '=', $this->labour_customer);
            })
            ->orderBy('labours.labour_id')
            ->get();

        return $this->labour;
    }
    public function headings(): array
    {
        // ตรวจสอบว่ามีข้อมูลใน $this->labour หรือไม่
        if (!$this->labour) {
            $this->collection(); // เรียกฟังก์ชัน collection() เพื่อดึงข้อมูล
        }

        // ดึงไฟล์ที่เกี่ยวข้องสำหรับ labour ทั้งหมด
        $sampleLabourFiles = labourFileModel::whereIn('labour_id', $this->labour->pluck('labour_id'))->get();

        // สร้าง headers สำหรับ Success และ Wait
        $filePathSuccessHeaders = [];

        foreach ($sampleLabourFiles as $file) {
            $filePathSuccessHeaders[] = $file->labour_file_name;
        }

        // รวม headers ทั้งหมด
        return array_merge(
            [
                'No.',
                'First-Name',
                'Last-Name',
                'NAME',
                'Position',
                'รหัส',
                'Passport',
                'Date Issue', // วันเริ่มต้น Passport
                'Date Expiry', // วันหมด Passport
                'เบอร์ติดต่อ',
                'เจ้าหน้าที่ดูแล',
            ],
            $filePathSuccessHeaders,
            ['สถานะ', 'Remark'],
        );
    }

    public function map($labour): array
    {
        // ดึงข้อมูลไฟล์ที่เกี่ยวข้องกับ labour นี้
        $files = labourFileModel::where('labour_id', $labour->labour_id)->get();

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

        // สร้าง arrays สำหรับ success และ wait
        $filePathSuccess = [];

        foreach ($files as $file) {
            if (!empty($file->labour_file_path)) {
                $filePathSuccess[] = '/';
            } else {
                $filePathSuccess[] = 'X';
            }
        }

        // Return ข้อมูลโดยรวม arrays ที่เราทำ loop
        return array_merge(
            [++$this->num, $labour->labour_firstname, $labour->labour_lastname, $labour->labour_prefix . '.' . $labour->labour_firstname . ' ' . $labour->labour_lastname, $labour->position_name, $labour->labour_register_number, $labour->labour_passport_number, $labour->labour_passport_issue, $labour->labour_passport_expiry, $labour->labour_phone, $labour->staff_name],
            $filePathSuccess, // รวมเครื่องหมาย / หรือ X สำหรับ success// รวมเครื่องหมาย / หรือ X สำหรับ wait
            [$status, $labour->labour_note],
        );
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 20,
            'C' => 20,
            'D' => 35,
            'E' => 20,
            'F' => 15,
            'G' => 15,
            'H' => 15,
            'I' => 15,
            'J' => 20,
            'K' => 20,
            'L' => 10,
            'M' => 10,
            'N' => 10,
            'O' => 10,
            'P' => 10,
            'Q' => 10,
            'R' => 10,
            'S' => 10,
            'T' => 10,
            'U' => 10,
            'V' => 10,
            'W' => 10,
            'X' => 10,
            'Y' => 10,
            'Z' => 15,
            'AA' => 45,
        ];
    }
}
