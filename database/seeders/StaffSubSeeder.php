<?php

namespace Database\Seeders;

use App\Models\staff\staffSubModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StaffSubSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ลบข้อมูลเก่า (ถ้ามี)
        staffSubModel::truncate();

        // ข้อมูลตัวอย่าง Staff Sub (ผู้แนะนำ)
        $staffSubs = [
            [
                'staff_sub_name' => 'บริษัท ABC จำกัด',
                'staff_sub_phone' => '02-123-4567',
                'staff_sub_staff' => null,
            ],
            [
                'staff_sub_name' => 'นายสมชาย เพื่อนดี',
                'staff_sub_phone' => '081-234-5678',
                'staff_sub_staff' => null,
            ],
            [
                'staff_sub_name' => 'นางสาวจิรภา ช่วยงาน',
                'staff_sub_phone' => '089-345-6789',
                'staff_sub_staff' => null,
            ],
            [
                'staff_sub_name' => 'บริษัท XYZ แนะนำคน',
                'staff_sub_phone' => '02-987-6543',
                'staff_sub_staff' => null,
            ],
            [
                'staff_sub_name' => 'สำนักงานแรงงาน กทม.',
                'staff_sub_phone' => '02-555-1234',
                'staff_sub_staff' => null,
            ],
        ];

        foreach ($staffSubs as $staffSubData) {
            staffSubModel::create($staffSubData);
        }

        $this->command->info('Created ' . count($staffSubs) . ' Staff Sub records successfully!');
    }
}
