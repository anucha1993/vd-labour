<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\jobs\JobModel;
use App\Models\jobs\JobLeadModel;
use App\Models\leads\LeadModel;
use App\Models\labours\labourModel;
use App\Models\files\fileManageModel;
use App\Models\files\listFileModel;
use App\Models\files\labourFileModel;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class JobLeadConversionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware('permission:job-lead-convert', ['only' => ['index', 'show', 'convert', 'storeConversion']]);
    }

    /**
     * Display list of job leads ready for conversion (status = ตอบรับ, convert_status = pending)
     */
    public function index(Request $request)
    {
        $query = JobLeadModel::with(['job.country', 'job.demand', 'lead', 'createdBy'])
                            ->where('job_lead_status', 'ตอบรับ')
                            ->where('convert_status', 'pending')
                            ->orderBy('created_at', 'desc');

        // Search filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('job_lead_number', 'like', "%{$search}%")
                  ->orWhereHas('lead', function($leadQuery) use ($search) {
                      $leadQuery->whereRaw("CONCAT(lead_prefix, ' ', lead_firstname, ' ', lead_lastname) LIKE ?", ["%{$search}%"])
                                ->orWhere('lead_passport_number', 'like', "%{$search}%")
                                ->orWhere('lead_phone', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('job_id')) {
            $query->where('job_id', $request->job_id);
        }

        $jobLeads = $query->paginate(15);
        $jobs = JobModel::where('job_status', 'เปิดรับสมัคร')->get();

        return view('job-leads.conversion-index', compact('jobLeads', 'jobs'));
    }

    /**
     * Show conversion details and form for single job lead
     */
    public function show(JobLeadModel $jobLead)
    {
        $jobLead->load(['job.country', 'job.demand', 'lead', 'createdBy','job.jobgroup','job.position']);

        // ตรวจสอบเงื่อนไขการ Convert
        $validationErrors = $this->validateConversionRules($jobLead);

        return view('job-leads.conversion-show', compact('jobLead', 'validationErrors'));
    }

    /**
     * Perform conversion: JobLead → Labour
     */
    public function convert(JobLeadModel $jobLead)
    {
        // ตรวจสอบเงื่อนไขอีกครั้ง
        $validationErrors = $this->validateConversionRules($jobLead);
        if (!empty($validationErrors)) {
            return response()->json([
                'success' => false,
                'errors' => $validationErrors
            ], 400);
        }

        try {
            DB::beginTransaction();

            $lead = $jobLead->lead;
            $job = $jobLead->job;

            // สร้าง Labour record
            $labour = $this->createLabourFromLead($lead, $job, $jobLead);

            // Update JobLead status
            $jobLead->update([
                'convert_status' => 'completed',
                'converted_at' => now(),
                'labour_id' => $labour->labour_id,
                'is_locked' => true,
                'locked_at' => now(),
                'updated_by' => auth()->id(),
                'remarks' => ($jobLead->remarks ?? '') . "\n[Convert → Labour #" . $labour->labour_id . " เมื่อ " . now()->format('d/m/Y H:i') . " โดย " . auth()->user()->name . "]"
            ]);

            // Update Lead status
            $lead->update([
                'lead_status' => 'converted',
                'labour_id' => $labour->labour_id,
                'converted_at' => now()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Convert สำเร็จ! Labour ID: ' . $labour->labour_id,
                'labour_id' => $labour->labour_id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Validate conversion rules
     */
    private function validateConversionRules($jobLead)
    {
        $errors = [];
        $lead = $jobLead->lead;
        $job = $jobLead->job;

        // 1. Check JobLead status = ตอบรับ
        if ($jobLead->job_lead_status !== 'ตอบรับ') {
            $errors[] = 'สถานะใบสมัครต้องเป็น "ตอบรับ" เท่านั้น';
        }

        // 2. Check Lead exists
        if (!$lead) {
            $errors[] = 'ไม่พบข้อมูลคนงาน (Lead)';
        } else {
            // 3. Check Passport expiry >= 3 years from now
            if (!$lead->lead_passport_expiry_date) {
                $errors[] = 'ไม่พบวันหมดอายุ Passport';
            } else {
                $expiryDate = Carbon::parse($lead->lead_passport_expiry_date);
                $threeYearsFromNow = Carbon::now()->addYears(3);
                if ($expiryDate->lessThan($threeYearsFromNow)) {
                    $errors[] = 'วันหมดอายุ Passport ต้องมากกว่า 3 ปี (วันที่: ' . $expiryDate->format('d/m/Y') . ')';
                }
            }

            // 4. Check Passport number duplicate in Labour
            $duplicatePassport = labourModel::where('labour_passport_number', $lead->lead_passport_number)
                                           ->whereNotNull('labour_passport_number')
                                           ->first();
            if ($duplicatePassport) {
                $errors[] = 'เลข Passport นี้มีอยู่แล้วใน Labour ID: ' . $duplicatePassport->labour_id;
            }
        }

        // 5. Check Job exists
        if (!$job) {
            $errors[] = 'ไม่พบข้อมูลงาน (Job)';
        }

        return $errors;
    }

    /**
     * Create Labour from Lead and Job data
     */
    private function createLabourFromLead($lead, $job, $jobLead)
    {
        // สร้าง folder path (LABOURS/YYYY/MM/firstname_lastname)
        $folderYear = date('Y');
        $folderMonth = date('m');
        $folderPath = 'LABOURS/' . $folderYear . '/' . $folderMonth . '/' . $lead->lead_firstname . '_' . $lead->lead_lastname;

        // Create folder if not exists
        if (!Storage::disk('public')->exists($folderPath)) {
            Storage::disk('public')->makeDirectory($folderPath);
        }

        // Prepare Labour data (field mapping)
        $labourData = [
            'labour_prefix' => $lead->lead_prefix ?? 'นาย',
            'labour_firstname' => $lead->lead_firstname,
            'labour_lastname' => $lead->lead_lastname,
            'labour_phone' => $lead->lead_phone,
            'labour_passport_number' => $lead->lead_passport_number,
            'labour_passport_issue' => $lead->lead_passport_issue_date,
            'labour_passport_expiry' => $lead->lead_passport_expiry_date,
            'labour_country' => $lead->country_id,
            'labour_staff_sub' => $lead->lead_recommender_staff_sub_id,
            'labour_staff' => $lead->staff_id,
            'labour_examination' => $lead->examination_round_id,
            'labour_customer' => $job->customer_id,
            'labour_job_group' => $job->job_group_id, // Priority: job's group
            'labour_position' => $job->position_id, // Priority: job's position
            'labour_birthday' => $lead->lead_birthday,
            'labour_status' => 'wait', // Default status
            'labour_path' => $folderPath,
            'labour_folder_year' => $folderYear,
            'lead_id' => $lead->lead_id, // Store lead_id reference
            'created_by' => Auth::user()->name,
            'updated_by' => Auth::user()->name,
        ];

        // Create Labour record
        $labour = labourModel::create($labourData);

        // สร้าง Labour Files จาก file_manage (location_doc from job/lead)
        $fileManageId = $job->demand->location ?? null; // หรือจาก lead ถ้า job ไม่มี
        if ($fileManageId) {
            $listfiles = listFileModel::where('file_manage_id', $fileManageId)->get();
            foreach ($listfiles as $list) {
                labourFileModel::create([
                    'labour_file_name' => $list->list_file_name,
                    'labour_file_note' => $list->list_file_note,
                    'labour_file_path' => null,
                    'list_file_id' => $list->list_file_id,
                    'labour_id' => $labour->labour_id,
                    'labour_passport_number' => $labour->labour_passport_number,
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id(),
                ]);
            }
        }

        // Update Labour file count
        $filecount = labourFileModel::where('labour_id', $labour->labour_id)->count();
        $labour->update(['labour_file_count' => $filecount, 'labour_file_list' => 0]);

        return $labour;
    }
}
