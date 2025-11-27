<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\staff\staffSubModel;

class StaffSubSampleSeeder extends Seeder
{
    public function run()
    {
        $staffSubs = [
            [
                'staff_sub_name' => 'นายสมชาย อินทร์แสง',
                'staff_sub_phone' => '081-234-5678',
                'staff_sub_staff' => 'นางสาวจิรา วิทยากุล',
                'staff_sub_status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'staff_sub_name' => 'นางสุดา บุญมี',
                'staff_sub_phone' => '089-876-5432',
                'staff_sub_staff' => 'นายประเสริฐ จันทร์โชติ',
                'staff_sub_status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'staff_sub_name' => 'บริษัท แมนพาวเวอร์ จำกัด',
                'staff_sub_phone' => '02-123-4567',
                'staff_sub_staff' => 'นางแสงจันทร์ พันธ์ทอง',
                'staff_sub_status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'staff_sub_name' => 'นายชาคริต วิสุทธิ์',
                'staff_sub_phone' => '085-555-9999',
                'staff_sub_staff' => 'นายธนากร อัศวกุล',
                'staff_sub_status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'staff_sub_name' => 'หจก. รับสมัครแรงงาน',
                'staff_sub_phone' => '076-333-222',
                'staff_sub_staff' => 'นางสาววิไล รุ่งเรือง',
                'staff_sub_status' => 'inactive',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        foreach ($staffSubs as $staffSub) {
            staffSubModel::firstOrCreate(
                ['staff_sub_name' => $staffSub['staff_sub_name']], 
                $staffSub
            );
        }

        echo "Sample staff sub data created successfully!\n";
    }
}
