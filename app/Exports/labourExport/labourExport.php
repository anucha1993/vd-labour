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
    private $labour_examination;

    public function __construct(
        $labour_disease_date_start,
        $labour_disease_date_end,
        $labour_cid_start,
        $labour_cid_end,
        $labour_country,
        $labour_job_group,
        $labour_staff,
        $labour_status,
        $labour_customer,
        $labour_examination
    ) {
        $this->labour_disease_date_start = $labour_disease_date_start;
        $this->labour_disease_date_end = $labour_disease_date_end;
        $this->labour_cid_start = $labour_cid_start;
        $this->labour_cid_end = $labour_cid_end;
        $this->labour_country = $labour_country;
        $this->labour_job_group = $labour_job_group;
        $this->labour_staff = $labour_staff;
        $this->labour_status = $labour_status;
        $this->labour_customer = $labour_customer;
        $this->labour_examination = $labour_examination;
    }


    public function collection()
    {
        //dd($this->labour_staff);
        // เริ่มการ Query ข้อมูลด้วยการ Join ตารางที่ต้องการ
        $query = labourModel::leftJoin('position', 'position.position_id', '=', 'labours.labour_position')
            ->leftJoin('staff', 'staff.staff_id', '=', 'labours.labour_staff')
            ->leftJoin('customers', 'customers.customer_id', '=', 'labours.labour_customer');
        
        // ค้นหาผลโรคหมดอายุ
        if (!empty($this->labour_disease_date_start) && !empty($this->labour_disease_date_end)) {
            $query->whereBetween('labours.labour_disease_expriry', [$this->labour_disease_date_start, $this->labour_disease_date_end]);
        }
        
        // ค้นหาผล CID หมดอายุ
        if (!empty($this->labour_cid_start) && !empty($this->labour_cid_end)) {
            $query->whereBetween('labours.labour_cid_expriry', [$this->labour_cid_start, $this->labour_cid_end]);
        }
        
        // ค้นหาประเทศ
        if ($this->labour_country && $this->labour_country != 'all') {
            $query->where('labours.labour_country', $this->labour_country);
        }
        
        // ค้นหาประเภทงาน
        if ($this->labour_job_group && $this->labour_job_group != 'all') {
            $query->where('labours.labour_job_group', $this->labour_job_group);
        }
        
        // ค้นหาชื่อสรรหา
        if ($this->labour_staff && $this->labour_staff != 'all') {
            $query->where('labours.labour_staff', $this->labour_staff);
        }
        
        // ค้นหาสถานะ
        if ($this->labour_status && $this->labour_status != 'all') {
            $query->where('labours.labour_status', $this->labour_status);
        }
        
        // ค้นหาโรงงาน
if ($this->labour_customer === 'null') {
    $query->whereNull('labours.labour_customer');
} elseif ($this->labour_customer && $this->labour_customer != 'all') {
    $query->where('labours.labour_customer', $this->labour_customer);
}

        //labour_examination

        if ($this->labour_examination && $this->labour_examination != 'all') {
            $query->whereIn('labours.labour_examination', $this->labour_examination);
        }
       
        
        // เรียงลำดับข้อมูล
        $query->orderBy('labours.labour_id');
    
        // ดึงข้อมูลทั้งหมด
        $this->labour = $query->get();
    
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

    // ตรวจสอบว่ามีการตั้งค่า $filePathSuccessHeaders หรือไม่ เพื่อให้ loop ทำงานแค่ครั้งเดียว
    static $filePathSuccessHeaders = null; 
    if ($filePathSuccessHeaders === null) {
        // ถ้ายังไม่มีการตั้งค่าให้สร้างขึ้นมาใหม่
        $filePathSuccessHeaders = [];
        foreach ($sampleLabourFiles as $file) {
            $filePathSuccessHeaders[] = $file->labour_file_name;
        }
        // กำจัดค่าซ้ำออกจาก array
        $filePathSuccessHeaders = array_unique($filePathSuccessHeaders);
    }

    // รวม headers ทั้งหมด
    return array_merge(
        [
            'No.',
            'First-Name',
            'Last-Name',
            'NAME',
            'Company Name',
            'Position',
            'รหัส',
            'Passport',
            'รอบสอบ',
            'Date Issue', // วันเริ่มต้น Passport
            'Date Expiry', // วันหมด Passport
            'เบอร์ติดต่อ',
            'เจ้าหน้าที่ดูแล',
        ],
        ['สถานะ', 'Remark'],
        $filePathSuccessHeaders
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
            [++$this->num, $labour->labour_firstname, 
            $labour->labour_lastname, $labour->labour_prefix . '.' 
            . $labour->labour_firstname . ' ' 
            . $labour->labour_lastname, 
            $labour->customer_name ? $labour->customer_name : 'ยังไม่ระบุ' ,
            $labour->position_name, 
            $labour->labour_register_number,
             $labour->labour_passport_number, 
            date('d-m-Y',strtotime($labour->labour_examination)), 
            $labour->labour_passport_issue, 
            $labour->labour_passport_expiry, $labour->labour_phone, $labour->staff_name],
            [$status, $labour->labour_note],
            $filePathSuccess, // รวมเครื่องหมาย / หรือ X สำหรับ success// รวมเครื่องหมาย / หรือ X สำหรับ wait
           
        );
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 20,
            'C' => 20,
            'D' => 35,
            'E' => 25,
            'F' => 15,
            'G' => 15,
            'H' => 15,
            'I' => 15,
            'J' => 20,
            'K' => 30,
            'L' => 30,
            'M' => 15,
            'N' => 15,
            'O' => 45,
            'P' => 5,
            'Q' => 5,
            'R' => 5,
            'S' => 5,
            'T' => 5,
            'U' => 5,
            'V' => 5,
            'W' => 5,
            'X' => 5,
            'Y' => 5,
            'Z' => 5,
            'AA'=> 5,
            'AB'=> 5,
            'AC'=> 5,
            'AF'=> 5,
            'AD'=> 5,
            'AE'=> 5,
            'AG'=> 5,
        ];
    }
}
