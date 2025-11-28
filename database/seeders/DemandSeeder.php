<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\demands\DemandModel;
use App\Models\demands\PositionDmModel;
use App\Models\inducstry\inducstryTypeModel;
use App\Models\positions\positionModel;
use Carbon\Carbon;

class DemandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some industry types and positions for foreign keys
        $industryTypes = inducstryTypeModel::take(3)->get();
        $positions = positionModel::take(10)->get();
        $countries = \App\Models\country\countryModel::where('country_status', 1)->take(3)->get();

        if ($industryTypes->isEmpty() || $positions->isEmpty()) {
            $this->command->warn('Please seed industry types and positions first');
            return;
        }

        // Get first user as creator
        $firstUser = \App\Models\User::first();
        $userId = $firstUser ? $firstUser->id : 1;
        
        // Get country IDs or use default
        $countryId1 = $countries->count() > 0 ? $countries[0]->country_id : 1;
        $countryId2 = $countries->count() > 1 ? $countries[1]->country_id : 1;
        $countryId3 = $countries->count() > 2 ? $countries[2]->country_id : 1;

        // Sample demand data
        $demands = [
            [
                'dm_issue_date' => Carbon::now()->subDays(10),
                'dm_let_no' => 'DM-001-2025',
                'dm_com_name' => 'บริษัท เทคโนโลยี จำกัด',
                'dm_com_addr' => '123 ถนนสุขุมวิท แขวงคลองเตย เขตคลองเตย กรุงเทพมหานคร 10110',
                'dm_reg_no' => 'REG-001-2025',
                'dm_indust_type' => $industryTypes[0]->inducstry_type_id,
                'country_id' => $countryId1,
                'dm_bmi' => '18.5-25',
                'dm_time_work' => '8:00-17:00 น.',
                'dm_sa' => 'เงินเดือน 25,000-35,000 บาท, โบนัสประจำปี, ประกันสังคม, ประกันสุขภาพกลุ่ม',
                'dm_job' => 'พัฒนาระบบซอฟต์แวร์, ดูแลระบบฐานข้อมูล, ทำงานร่วมกับทีมพัฒนา',
                'dm_exp' => ['accomm', 'food', 'med'],
                'location' => 'กรุงเทพมหานคร',
                'date' => '1 มกราคม 2025',
                'created_by' => $userId,
                'updated_by' => $userId
            ],
            [
                'dm_issue_date' => Carbon::now()->subDays(5),
                'dm_let_no' => 'DM-002-2025',
                'dm_com_name' => 'บริษัท การผลิต อุตสาหกรรม จำกัด',
                'dm_com_addr' => '456 ถนนรัชดาภิเษก แขวงลาดยาว เขตจตุจักร กรุงเทพมหานคร 10900',
                'dm_reg_no' => 'REG-002-2025',
                'dm_indust_type' => $industryTypes[1]->inducstry_type_id,
                'country_id' => $countryId2,
                'dm_bmi' => '18-28',
                'dm_time_work' => '6:00-14:00 น. (กะเช้า)',
                'dm_sa' => 'เงินเดือน 20,000-30,000 บาท, ค่าล่วงเวลา, ค่าอาหาร, ที่พักพนักงาน',
                'dm_job' => 'ควบคุมคุณภาพการผลิต, ตรวจสอบมาตรฐานสินค้า, บันทึกข้อมูลการผลิต',
                'dm_exp' => ['accomm', 'shuttle'],
                'location' => 'สมุทรปราการ',
                'date' => '15 มกราคม 2025',
                'created_by' => $userId,
                'updated_by' => $userId
            ],
            [
                'dm_issue_date' => Carbon::now()->subDays(2),
                'dm_let_no' => 'DM-003-2025',
                'dm_com_name' => 'บริษัท บริการ และ การค้า จำกัด',
                'dm_com_addr' => '789 ถนนพระราม 4 แขวงสุริยวงศ์ เขตบางรัก กรุงเทพมหานคร 10500',
                'dm_reg_no' => 'REG-003-2025',
                'dm_indust_type' => $industryTypes[2]->inducstry_type_id,
                'country_id' => $countryId3,
                'dm_bmi' => '18-26',
                'dm_time_work' => '9:00-18:00 น.',
                'dm_sa' => 'เงินเดือน 18,000-25,000 บาท, คอมมิชชั่น, ประกันสุขภาพ',
                'dm_job' => 'ให้บริการลูกค้า, ประสานงานขาย, จัดการเอกสาร, ตอบโทรศัพท์',
                'dm_exp' => ['food', 'med'],
                'location' => 'นนทบุรี',
                'date' => '25 มกราคม 2025',
                'created_by' => $userId,
                'updated_by' => $userId
            ]
        ];

        foreach ($demands as $index => $demandData) {
            // Create demand
            $demand = DemandModel::create($demandData);

            // Create positions for each demand
            $positionCount = rand(2, 4); // Random 2-4 positions per demand
            $usedPositions = [];
            
            for ($i = 0; $i < $positionCount; $i++) {
                // หลีกเลี่ยงการใช้ตำแหน่งซ้ำ
                $availablePositions = $positions->whereNotIn('position_id', $usedPositions);
                if ($availablePositions->count() === 0) {
                    break; // ไม่มีตำแหน่งเหลือ
                }
                
                $position = $availablePositions->random();
                $usedPositions[] = $position->position_id;
                
                PositionDmModel::create([
                    'dm_id' => $demand->dm_id,
                    'position_id' => $position->position_id,
                    'position_dm_amount' => rand(5, 20),
                    'position_dm_period' => $this->getRandomPeriod(),
                    'position_dm_age' => $this->getRandomAge()
                ]);
            }
        }

        $this->command->info('Demand seeder completed successfully!');
    }

    private function getRandomPeriod(): string
    {
        $periods = ['1 ปี', '2 ปี', '3 ปี', '1.5 ปี', '2.5 ปี'];
        return $periods[array_rand($periods)];
    }

    private function getRandomAge(): string
    {
        $ages = ['22-30 ปี', '25-35 ปี', '20-28 ปี', '23-32 ปี', '24-30 ปี'];
        return $ages[array_rand($ages)];
    }
}
