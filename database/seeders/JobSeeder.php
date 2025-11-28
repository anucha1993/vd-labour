<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\jobs\JobModel;
use App\Models\jobs\JobLeadModel;
use App\Models\country\countryModel;
use App\Models\demands\DemandModel;
use App\Models\User;
use Carbon\Carbon;

class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // หาข้อมูลที่จำเป็น
        $countries = countryModel::all();
        $demands = DemandModel::all();
        $users = User::all();
        
        if ($countries->isEmpty() || $demands->isEmpty() || $users->isEmpty()) {
            $this->command->warn('ไม่พบข้อมูลประเทศ, Demands หรือผู้ใช้งาน กรุณาเพิ่มข้อมูลเหล่านี้ก่อน');
            return;
        }
        
        $user = $users->first();
        
        // สร้างงานตัวอย่าง
        $jobs = [
            [
                'job_name' => 'คนงานทั่วไป โรงงานผลิตอาหาร',
                'country_id' => $countries->first()->country_id,
                'dm_id' => $demands->first()->dm_id,
                'job_total' => 50,
                'job_start_date' => Carbon::now(),
                'job_end_date' => Carbon::now()->addMonths(3),
                'job_status' => 'เปิดรับสมัคร'
            ],
            [
                'job_name' => 'พ่อครัว ร้านอาหารไทย',
                'country_id' => $countries->skip(1)->first()->country_id ?? $countries->first()->country_id,
                'dm_id' => $demands->skip(1)->first()->dm_id ?? $demands->first()->dm_id,
                'job_total' => 5,
                'job_start_date' => Carbon::now()->subDays(10),
                'job_end_date' => Carbon::now()->addMonth(),
                'job_status' => 'เปิดรับสมัคร'
            ],
            [
                'job_name' => 'คนขับรถบรรทุก',
                'country_id' => $countries->first()->country_id,
                'dm_id' => $demands->first()->dm_id,
                'job_total' => 10,
                'job_start_date' => Carbon::now()->subMonths(2),
                'job_end_date' => Carbon::now()->subDays(5),
                'job_status' => 'ปิดรับสมัคร'
            ],
            [
                'job_name' => 'แม่บ้าน โรงแรม 5 ดาว',
                'country_id' => $countries->first()->country_id,
                'dm_id' => $demands->first()->dm_id,
                'job_total' => 20,
                'job_start_date' => Carbon::now(),
                'job_end_date' => null, // รับต่อเนื่อง
                'job_status' => 'เปิดรับสมัคร'
            ],
        ];
        
        foreach ($jobs as $jobData) {
            $jobData['created_by'] = $user->id;
            $jobData['updated_by'] = $user->id;
            
            $job = JobModel::create($jobData);
            
            $this->command->info("สร้างงาน: {$job->job_number} - {$job->job_name}");
            
            // สร้างใบสมัครตัวอย่าง
            $this->createSampleJobLeads($job, $user);
        }
    }
    
    private function createSampleJobLeads($job, $user)
    {
        // Mock lead IDs - ในความเป็นจริงจะมาจากตารางคนงาน/leads
        $sampleLeads = [
            ['id' => 1001, 'status' => 'ร่าง'],
            ['id' => 1002, 'status' => 'ส่งแล้ว'],
            ['id' => 1003, 'status' => 'กำลังพิจารณา'],
            ['id' => 1004, 'status' => 'นัดสัมภาษณ์'],
            ['id' => 1005, 'status' => 'เสนองาน'],
            ['id' => 1006, 'status' => 'ตอบรับ'],
            ['id' => 1007, 'status' => 'ปฏิเสธ'],
            ['id' => 1008, 'status' => 'ถอน'],
        ];
        
        // สร้างใบสมัครตัวอย่าง (สุ่ม 3-7 ใบต่องาน)
        $numberOfLeads = rand(3, min(7, $job->job_total));
        
        for ($i = 0; $i < $numberOfLeads; $i++) {
            $leadData = $sampleLeads[$i % count($sampleLeads)];
            
            JobLeadModel::create([
                'job_id' => $job->job_id,
                'lead_id' => $leadData['id'] + ($i * 100), // สร้าง ID ไม่ซ้ำ
                'job_lead_status' => $leadData['status'],
                'remarks' => "ใบสมัครตัวอย่าง - สถานะ: {$leadData['status']}",
                'is_locked' => in_array($leadData['status'], JobLeadModel::LOCKED_STATUSES),
                'locked_at' => in_array($leadData['status'], JobLeadModel::LOCKED_STATUSES) ? Carbon::now() : null,
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]);
        }
        
        $this->command->info("  └─ สร้างใบสมัคร {$numberOfLeads} รายการ");
    }
}
