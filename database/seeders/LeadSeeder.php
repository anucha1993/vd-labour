<?php

namespace Database\Seeders;

use App\Models\leads\LeadModel;
use App\Models\leads\LeadJobHistoryModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class LeadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ลบข้อมูลเก่า (ถ้ามี) โดยเรียงลำดับให้ถูกต้อง
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        LeadJobHistoryModel::truncate();
        LeadModel::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // ข้อมูลตัวอย่าง Lead 5 คน
        $leads = [
            [
                'lead_prefix' => 'Mr.',
                'lead_firstname' => 'สมชาย',
                'lead_lastname' => 'ใจดี',
                'lead_gender' => 'male',
                'lead_marital_status' => 'single',
                'lead_birthday' => '1990-03-15',
                'lead_age' => 35,
                'lead_height' => 170.5,
                'lead_weight' => 65.0,
                'lead_bmi' => '22.39',
                'lead_phone' => '0812345001',
                'lead_phone_2' => '0923456001',
                'lead_address' => '123 Moo 1, Soi Ladprao 15, Road Ladprao, Sub-district Wang Thonglang, District Wang Thonglang, Bangkok Province 10310',
                'lead_passport_number' => '123456',
                'lead_passport_issue_date' => '2024-01-15',
                'lead_passport_expiry_date' => '2034-01-14',
                'lead_shirt_size' => 'M',
                'lead_pant_size' => '32',
                'lead_shoes_size' => '42',
                'lead_education' => 'high_school',
                'lead_chinese_speaking' => 'beginner',
                'lead_english_speaking' => 'intermediate',
                'lead_other_language' => 'Japanese (Basic)',
                'lead_work_israel' => 'no',
                'lead_criminal_history' => 'no',
                'lead_eyesight' => 'normal',
                'lead_color_blindness' => 'no',
                'lead_additional_details' => '- Healthy, no chronic disease
- Have experience in construction work for 5 years
- Completed safety training certificate
- Highly responsible and punctual
- Can work well in team environment',
                'lead_emergency_name' => 'นาง สมใส ใจดี',
                'lead_emergency_phone' => '0812345002',
                'lead_emergency_status' => 'Mother',
                'lead_driving_license' => 'yes',
                'lead_car_type' => 'Private Car Driving License',
                'lead_license_valid_until' => '2028-03-15',
                'position_id' => 1,
                'position_id_2' => 2,
                'country_id' => 1,
                'job_group_id' => 1,
                'lead_status' => 'new',
                'lead_note' => 'มีประสบการณ์งานก่อสร้าง เรียนรู้เร็ว',
                'staff_id' => 1,
                'lead_recommender_staff_sub_id' => 1,
                'documents' => [
                    'รูปถ่ายขนาด_2_นิ้ว',
                    'Passport',
                    'บัตรประชาชน_สำเนา',
                    'ผลโรค',
                    'ใบผ่านงาน'
                ],
                'examination_round_id' => null,
            ],
            [
                'lead_prefix' => 'Mrs.',
                'lead_firstname' => 'สมหญิง',
                'lead_lastname' => 'เก่งงาม',
                'lead_gender' => 'female',
                'lead_marital_status' => 'married',
                'lead_birthday' => '1988-07-22',
                'lead_age' => 37,
                'lead_height' => 160.0,
                'lead_weight' => 55.0,
                'lead_bmi' => '21.48',
                'lead_phone' => '0823456002',
                'lead_phone_2' => '0934567002',
                'lead_address' => '456 Moo 2, Soi Sukhumvit 39, Road Sukhumvit, Sub-district Khlong Toei Nuea, District Watthana, Bangkok Province 10110',
                'lead_passport_number' => '234567',
                'lead_passport_issue_date' => '2024-02-10',
                'lead_passport_expiry_date' => '2034-02-09',
                'lead_shirt_size' => 'S',
                'lead_pant_size' => '28',
                'lead_shoes_size' => '37',
                'lead_education' => 'voc_cert',
                'lead_chinese_speaking' => 'no',
                'lead_english_speaking' => 'beginner',
                'lead_other_language' => null,
                'lead_work_israel' => 'no',
                'lead_criminal_history' => 'no',
                'lead_eyesight' => 'normal',
                'lead_color_blindness' => 'no',
                'lead_additional_details' => '- Good health condition
- Experience in housekeeping and cleaning work
- Detail-oriented and organized
- Friendly personality, good with children
- Previous training in hotel housekeeping standards',
                'lead_emergency_name' => 'นาย สมศักดิ์ เก่งงาม',
                'lead_emergency_phone' => '0823456003',
                'lead_emergency_status' => 'Husband',
                'lead_driving_license' => 'yes',
                'lead_car_type' => 'Motorcycle Driving License',
                'lead_license_valid_until' => '2027-07-22',
                'position_id' => 3,
                'country_id' => 1,
                'job_group_id' => 2,
                'lead_status' => 'contacted',
                'lead_note' => 'เหมาะสำหรับงานดูแลบ้าน มีประสบการณ์',
                'staff_id' => 1,
                'lead_recommender_staff_sub_id' => 2,
                'documents' => [
                    'รูปถ่ายขนาด_2_นิ้ว',
                    'Passport',
                    'บัตรประชาชน_สำเนา',
                    'ผลโรค',
                    'หนังสือสมรส_กรณีสมรส'
                ],
                'examination_round_id' => null,
            ],
            [
                'lead_prefix' => 'Mr.',
                'lead_firstname' => 'วิชาญ',
                'lead_lastname' => 'ช่างฝีมือ',
                'lead_gender' => 'male',
                'lead_marital_status' => 'single',
                'lead_birthday' => '1985-11-10',
                'lead_age' => 40,
                'lead_height' => 175.0,
                'lead_weight' => 70.0,
                'lead_bmi' => '22.86',
                'lead_phone' => '0834567003',
                'lead_phone_2' => null,
                'lead_address' => '789 Moo 3, Soi Ratchada 15, Road Ratchadaphisek, Sub-district Din Daeng, District Din Daeng, Bangkok Province 10400',
                'lead_passport_number' => '345678',
                'lead_passport_issue_date' => '2023-12-05',
                'lead_passport_expiry_date' => '2033-12-04',
                'lead_shirt_size' => 'L',
                'lead_pant_size' => '34',
                'lead_shoes_size' => '43',
                'lead_education' => 'high_voc_cert',
                'lead_chinese_speaking' => 'no',
                'lead_english_speaking' => 'advance',
                'lead_other_language' => 'Korean (Intermediate)',
                'lead_work_israel' => 'yes',
                'lead_criminal_history' => 'no',
                'lead_eyesight' => 'normal',
                'lead_color_blindness' => 'no',
                'lead_additional_details' => '- Excellent health, very strong physically
- 8 years experience in welding and steel construction
- Certified welder with multiple certificates
- Previously worked in Israel for 2 years (2020-2022)
- Leadership skills, can supervise small teams
- Fluent in English communication',
                'lead_emergency_name' => 'นาง วันดี ช่างฝีมือ',
                'lead_emergency_phone' => '0834567004',
                'lead_emergency_status' => 'Sister',
                'lead_driving_license' => 'yes',
                'lead_car_type' => 'Category 2 (บ.2)',
                'lead_license_valid_until' => '2026-11-10',
                'position_id' => 4,
                'position_id_2' => 1,
                'country_id' => 1,
                'job_group_id' => 1,
                'lead_status' => 'qualified',
                'lead_note' => 'ผู้สมัครที่มีคุณภาพสูง มีประสบการณ์ในอิสราเอล',
                'staff_id' => 1,
                'lead_recommender_staff_sub_id' => 3,
                'documents' => [
                    'รูปถ่ายขนาด_2_นิ้ว',
                    'Passport',
                    'บัตรประชาชน_สำเนา',
                    'ผลโรค',
                    'ใบผ่านงาน',
                    'ใบ_สผท',
                    'CV'
                ],
                'examination_round_id' => null,
            ],
            [
                'lead_prefix' => 'Mr.',
                'lead_firstname' => 'ประเสริฐ',
                'lead_lastname' => 'ขยันทำ',
                'lead_gender' => 'male',
                'lead_marital_status' => 'divorced',
                'lead_birthday' => '1992-05-18',
                'lead_age' => 33,
                'lead_height' => 168.0,
                'lead_weight' => 62.0,
                'lead_bmi' => '21.97',
                'lead_phone' => '0845678004',
                'lead_phone_2' => '0956789004',
                'lead_address' => '321 Moo 4, Soi Petchkasem 15, Road Petchkasem, Sub-district Bang Khae, District Bang Khae, Bangkok Province 10160',
                'lead_passport_number' => '456789',
                'lead_passport_issue_date' => '2024-03-20',
                'lead_passport_expiry_date' => '2034-03-19',
                'lead_shirt_size' => 'M',
                'lead_pant_size' => '30',
                'lead_shoes_size' => '41',
                'lead_education' => 'bachelor',
                'lead_chinese_speaking' => 'intermediate',
                'lead_english_speaking' => 'intermediate',
                'lead_other_language' => null,
                'lead_work_israel' => 'no',
                'lead_criminal_history' => 'no',
                'lead_eyesight' => 'normal',
                'lead_color_blindness' => 'no',
                'lead_additional_details' => '- Bachelor degree in Engineering
- Good health, regular exercise
- Experience in project management and supervision
- Intermediate level in Chinese and English
- Quick learner, adaptable to new environments
- Good communication and problem-solving skills',
                'lead_emergency_name' => 'นาย สมบูรณ์ ขยันทำ',
                'lead_emergency_phone' => '0845678005',
                'lead_emergency_status' => 'Father',
                'lead_driving_license' => 'yes',
                'lead_car_type' => 'Private Car Driving License',
                'lead_license_valid_until' => '2029-05-18',
                'position_id' => 5,
                'position_id_2' => 6,
                'country_id' => 1,
                'job_group_id' => 3,
                'lead_status' => 'interview',
                'lead_note' => 'วุฒิการศึกษาสูง เหมาะกับตำแหน่งหัวหน้างาน',
                'staff_id' => 1,
                'lead_recommender_staff_sub_id' => null,
                'documents' => [
                    'รูปถ่ายขนาด_2_นิ้ว',
                    'Passport',
                    'บัตรประชาชน_สำเนา',
                    'ผลโรค',
                    'ใบผ่านงาน',
                    'CV',
                    'ใบ_สผท'
                ],
                'examination_round_id' => null,
            ],
            [
                'lead_prefix' => 'Ms.',
                'lead_firstname' => 'นิภา',
                'lead_lastname' => 'สุขใส',
                'lead_gender' => 'female',
                'lead_marital_status' => 'single',
                'lead_birthday' => '1995-09-08',
                'lead_age' => 30,
                'lead_height' => 155.0,
                'lead_weight' => 48.0,
                'lead_bmi' => '19.98',
                'lead_phone' => '0856789005',
                'lead_phone_2' => null,
                'lead_address' => '654 Moo 5, Soi Kaset-Nawamin 25, Road Kaset-Nawamin, Sub-district Lat Phrao, District Lat Phrao, Bangkok Province 10230',
                'lead_passport_number' => '567890',
                'lead_passport_issue_date' => '2024-04-12',
                'lead_passport_expiry_date' => '2034-04-11',
                'lead_shirt_size' => 'S',
                'lead_pant_size' => '26',
                'lead_shoes_size' => '36',
                'lead_education' => 'high_school',
                'lead_chinese_speaking' => 'beginner',
                'lead_english_speaking' => 'beginner',
                'lead_other_language' => null,
                'lead_work_israel' => 'no',
                'lead_criminal_history' => 'no',
                'lead_eyesight' => 'normal',
                'lead_color_blindness' => 'no',
                'lead_additional_details' => '- Young and energetic, good health
- Experience in elderly care and childcare
- Patient and gentle personality
- Basic cooking skills, traditional Thai cuisine
- Willing to learn new skills and languages
- No previous overseas work experience but very motivated',
                'lead_emergency_name' => 'นาง สมศรี สุขใส',
                'lead_emergency_phone' => '0856789006',
                'lead_emergency_status' => 'Mother',
                'lead_driving_license' => 'no',
                'lead_car_type' => 'None',
                'lead_license_valid_until' => null,
                'position_id' => 7,
                'country_id' => 1,
                'job_group_id' => 2,
                'lead_status' => 'new',
                'lead_note' => 'เหมาะสำหรับงานดูแลผู้สูงอายุ อายุยังน้อย',
                'staff_id' => 1,
                'lead_recommender_staff_sub_id' => 1,
                'documents' => [
                    'รูปถ่ายขนาด_2_นิ้ว',
                    'Passport',
                    'บัตรประชาชน_สำเนา',
                    'ผลโรค'
                ],
                'examination_round_id' => null,
            ]
        ];

        // สร้าง Lead records
        foreach ($leads as $leadData) {
            $lead = LeadModel::create($leadData);
            
            // สร้าง Job History สำหรับบาง Lead
            $this->createJobHistoryForLead($lead);
        }

        $this->command->info('Created 5 sample Lead records with job histories successfully!');
    }

    /**
     * สร้าง Job History สำหรับ Lead
     */
    private function createJobHistoryForLead(LeadModel $lead)
    {
        $jobHistories = [];

        switch ($lead->lead_firstname) {
            case 'สมชาย':
                $jobHistories = [
                    [
                        'lead_id' => $lead->lead_id,
                        'start_date' => '2019-01',
                        'end_date' => '2022-12',
                        'position' => 'Construction Worker',
                        'company_type' => 'General Construction',
                        'company_name' => 'ABC Construction Co., Ltd.',
                        'country' => 'THAI',
                        'experience_years' => 4.0,
                        'description' => '- Responsible for concrete pouring and finishing
- Assisted senior workers in structural work
- Maintained construction site safety standards',
                        'display_order' => 1,
                    ],
                    [
                        'lead_id' => $lead->lead_id,
                        'start_date' => '2023-01',
                        'end_date' => null,
                        'position' => 'Plasterer',
                        'company_type' => 'Interior Contractor',
                        'company_name' => 'XYZ Interior Ltd.',
                        'country' => 'THAI',
                        'experience_years' => 2.0,
                        'description' => '- Plastering walls and ceilings
- Interior finishing work
- Quality control and surface preparation',
                        'display_order' => 2,
                    ],
                ];
                break;

            case 'สมหญิง':
                $jobHistories = [
                    [
                        'lead_id' => $lead->lead_id,
                        'start_date' => '2020-03',
                        'end_date' => '2024-10',
                        'position' => 'Housekeeper',
                        'company_type' => 'Hotel Service',
                        'company_name' => 'Grand Hotel Bangkok',
                        'country' => 'THAI',
                        'experience_years' => 4.5,
                        'description' => '- Daily housekeeping in hotel rooms
- Maintained cleanliness standards
- Inventory management of cleaning supplies
- Customer service excellence',
                        'display_order' => 1,
                    ],
                ];
                break;

            case 'วิชาญ':
                $jobHistories = [
                    [
                        'lead_id' => $lead->lead_id,
                        'start_date' => '2018-01',
                        'end_date' => '2020-02',
                        'position' => 'Welder',
                        'company_type' => 'Steel Construction',
                        'company_name' => 'Steel Pro Thailand',
                        'country' => 'THAI',
                        'experience_years' => 2.0,
                        'description' => '- Arc and MIG welding
- Steel structure assembly
- Quality inspection of welding work',
                        'display_order' => 1,
                    ],
                    [
                        'lead_id' => $lead->lead_id,
                        'start_date' => '2020-03',
                        'end_date' => '2022-08',
                        'position' => 'Senior Welder',
                        'company_type' => 'Construction Contractor',
                        'company_name' => 'Israel Construction Ltd.',
                        'country' => 'ISRAEL',
                        'experience_years' => 2.5,
                        'description' => '- Advanced welding techniques
- Team supervision and training
- Project coordination with engineers
- Safety protocol implementation',
                        'display_order' => 2,
                    ],
                    [
                        'lead_id' => $lead->lead_id,
                        'start_date' => '2022-09',
                        'end_date' => null,
                        'position' => 'Welding Supervisor',
                        'company_type' => 'Industrial Construction',
                        'company_name' => 'Industrial Steel Co.',
                        'country' => 'THAI',
                        'experience_years' => 2.5,
                        'description' => '- Supervise welding team of 8 workers
- Quality assurance and control
- Training new welders
- Project planning and execution',
                        'display_order' => 3,
                    ],
                ];
                break;

            case 'ประเสริฐ':
                $jobHistories = [
                    [
                        'lead_id' => $lead->lead_id,
                        'start_date' => '2018-06',
                        'end_date' => '2023-12',
                        'position' => 'Project Engineer',
                        'company_type' => 'Engineering Consultant',
                        'company_name' => 'Engineering Solutions Ltd.',
                        'country' => 'THAI',
                        'experience_years' => 5.5,
                        'description' => '- Project planning and management
- Site supervision and coordination
- Technical documentation and reporting
- Client communication and liaison',
                        'display_order' => 1,
                    ],
                ];
                break;

            case 'นิภา':
                $jobHistories = [
                    [
                        'lead_id' => $lead->lead_id,
                        'start_date' => '2022-01',
                        'end_date' => '2024-08',
                        'position' => 'Caregiver',
                        'company_type' => 'Elderly Care Service',
                        'company_name' => 'Happy Care Center',
                        'country' => 'THAI',
                        'experience_years' => 2.5,
                        'description' => '- Daily care for elderly patients
- Medication assistance and monitoring
- Physical therapy support
- Emotional support and companionship',
                        'display_order' => 1,
                    ],
                ];
                break;
        }

        foreach ($jobHistories as $jobHistory) {
            LeadJobHistoryModel::create($jobHistory);
        }
    }
}
