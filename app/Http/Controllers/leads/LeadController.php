<?php

namespace App\Http\Controllers\leads;

use App\Http\Controllers\Controller;
use App\Models\leads\LeadModel;
use App\Models\leads\LeadJobHistoryModel;
use App\Models\labours\labourModel;
use App\Models\positions\positionModel;
use App\Models\country\countryModel;
use App\Models\jobgroup\jobGroupModel;
use App\Models\staff\staffModel;
use App\Models\customers\customerModel;
use App\Models\examinations\examinationRoundModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class LeadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view lead')->only(['index', 'show']);
        $this->middleware('permission:create lead')->only(['create', 'store']);
        $this->middleware('permission:update lead')->only(['edit', 'update']);
        $this->middleware('permission:delete lead')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = LeadModel::with(['position', 'country', 'jobGroup', 'staff', 'recommenderStaff', 'jobLeads']);
        
        // Search filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('lead_number', 'like', "%{$search}%")
                  ->orWhere('lead_firstname', 'like', "%{$search}%")
                  ->orWhere('lead_lastname', 'like', "%{$search}%")
                  ->orWhere('lead_phone', 'like', "%{$search}%")
                  ->orWhere('lead_passport_number', 'like', "%{$search}%")
                  ->orWhereHas('staff', function($q) use ($search) {
                      $q->where('staff_name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('recommenderStaff', function($q) use ($search) {
                      $q->where('staff_sub_name', 'like', "%{$search}%");
                  });
            });
        }
        
        if ($request->filled('lead_status') && $request->lead_status !== 'all') {
            $query->where('lead_status', $request->lead_status);
        }
        
        if ($request->filled('position_id') && $request->position_id !== 'all') {
            $query->where(function($q) use ($request) {
                $q->where('position_id', $request->position_id)
                  ->orWhere('position_id_2', $request->position_id)
                  ->orWhere('position_id_3', $request->position_id);
            });
        }
        
        $leads = $query->latest()->paginate(20);
        
        $positions = positionModel::where('position_status', 'active')->get();
        
        return view('leads.index', compact('leads', 'positions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $positions = positionModel::where('position_status', 'active')->get();
        $countries = countryModel::where('country_status', 'active')->get();
        $jobGroups = jobGroupModel::where('job_group_status', 'active')->get();
        $staffs = staffModel::where('staff_status', 'active')->get();
        $examinationRounds = examinationRoundModel::where('examination_round_status', 'active')->get();
        $staffSubs = \App\Models\staff\staffSubModel::all();
        
        return view('leads.create', compact('positions', 'countries', 'jobGroups', 'staffs', 'examinationRounds', 'staffSubs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'lead_firstname' => 'required|string|max:255',
            'lead_lastname' => 'required|string|max:255',
            'lead_phone' => 'nullable|string|max:20',
            'lead_passport_number' => 'nullable|string|max:50',
            'lead_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'lead_photo.image' => 'ไฟล์ที่อัปโหลดต้องเป็นรูปภาพเท่านั้น',
            'lead_photo.mimes' => 'รูปภาพต้องเป็นไฟล์ประเภท: jpeg, png, jpg, gif',
            'lead_photo.max' => 'ขนาดรูปภาพต้องไม่เกิน 2MB',
        ]);
          
        // ตรวจสอบ Passport ซ้ำใน Lead และ Labour
        if ($request->filled('lead_passport_number')) {
            $passportNumber = $request->lead_passport_number;
            
            // ตรวจสอบใน leads table
            $duplicateInLead = LeadModel::where('lead_passport_number', $passportNumber)->first();
            
            // ตรวจสอบใน labours table
            $duplicateInLabour = labourModel::where('labour_passport_number', $passportNumber)->first();
            
            if ($duplicateInLead) {
                return back()->withErrors([
                    'lead_passport_number' => 'หมายเลข Passport นี้มีอยู่ในระบบแล้ว (ผู้สมัคร: ' . $duplicateInLead->lead_firstname . ' ' . $duplicateInLead->lead_lastname . ')'
                ])->withInput();
            }
            
            if ($duplicateInLabour) {
                return back()->withErrors([
                    'lead_passport_number' => 'หมายเลข Passport นี้มีอยู่ในระบบแล้ว (แรงงาน: ' . $duplicateInLabour->labour_firstname . ' ' . $duplicateInLabour->labour_lastname . ')'
                ])->withInput();
            }
        }
        
        // ตรวจสอบชื่อ-นามสกุล ซ้ำใน Lead และ Labour
        if ($request->filled('lead_firstname') && $request->filled('lead_lastname')) {
            $firstname = $request->lead_firstname;
            $lastname = $request->lead_lastname;
            
            // ตรวจสอบใน leads table
            $duplicateNameInLead = LeadModel::where('lead_firstname', $firstname)
                ->where('lead_lastname', $lastname)
                ->first();
            
            // ตรวจสอบใน labours table
            $duplicateNameInLabour = labourModel::where('labour_firstname', $firstname)
                ->where('labour_lastname', $lastname)
                ->first();
            
            if ($duplicateNameInLead) {
                return back()->withErrors([
                    'lead_firstname' => 'ชื่อ-นามสกุล นี้มีอยู่ในระบบแล้ว (ผู้สมัคร: ' . $duplicateNameInLead->lead_firstname . ' ' . $duplicateNameInLead->lead_lastname . ')'
                ])->withInput();
            }
            
            if ($duplicateNameInLabour) {
                return back()->withErrors([
                    'lead_firstname' => 'ชื่อ-นามสกุล นี้มีอยู่ในระบบแล้ว (แรงงาน: ' . $duplicateNameInLabour->labour_firstname . ' ' . $duplicateNameInLabour->labour_lastname . ')'
                ])->withInput();
            }
        }
        
        $data = $request->except('lead_photo', 'job_history');
        
        // Add created_by
        $data['created_by'] = auth()->id();
        $data['updated_by'] = auth()->id();
        
        // Calculate BMI if height and weight provided
        if ($request->filled('lead_height') && $request->filled('lead_weight')) {
            $heightM = $request->lead_height / 100;
            $data['lead_bmi'] = round($request->lead_weight / ($heightM * $heightM), 2);
        }
        
        // Handle photo upload
        if ($request->hasFile('lead_photo')) {
            try {
                $photo = $request->file('lead_photo');
                $filename = time() . '_' . $photo->getClientOriginalName();
                $data['lead_photo'] = $photo->storeAs('leads', $filename, 'public');
            } catch (\Exception $e) {
                return back()->with('error', 'เกิดข้อผิดพลาดในการอัปโหลดรูปภาพ: ' . $e->getMessage())->withInput();
            }
        }
        
        DB::beginTransaction();
        try {
            $lead = LeadModel::create($data);
            
            // Save job history
            if ($request->has('job_history') && is_array($request->job_history)) {
                foreach ($request->job_history as $index => $history) {
                    if (!empty($history['company_type']) && !empty($history['position'])) {
                        LeadJobHistoryModel::create([
                            'lead_id' => $lead->lead_id,
                            'company_type' => $history['company_type'],
                            'company_name' => $history['company_name'] ?? null,
                            'position' => $history['position'],
                            'start_date' => $history['start_date'] ?? null,
                            'end_date' => $history['end_date'] ?? null,
                            'country' => $history['country'] ?? 'THAI',
                            'experience_years' => $history['experience_years'] ?? 0,
                            'description' => $history['description'] ?? null,
                            'company_about' => $history['company_about'] ?? null,
                            'display_order' => $index + 1,
                        ]);
                    }
                }
            }
            
            DB::commit();
            return redirect()->route('leads.index')->with('success', 'เพิ่มข้อมูล Lead สำเร็จ');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $lead = LeadModel::with(['position', 'position2', 'position3', 'country', 'jobGroup', 'staff', 'jobHistory', 'labour', 'recommenderStaff', 'examinationRound'])
                        ->findOrFail($id);
        return view('leads.show', compact('lead'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $lead = LeadModel::with('jobHistory', 'recommenderStaff')->findOrFail($id);
        $positions = positionModel::where('position_status', 'active')->get();
        $countries = countryModel::where('country_status', 'active')->get();
        $jobGroups = jobGroupModel::where('job_group_status', 'active')->get();
        $staffs = staffModel::where('staff_status', 'active')->get();
        $examinationRounds = examinationRoundModel::where('examination_round_status', 'active')->get();
        $staffSubs = \App\Models\staff\staffSubModel::all();
        
        return view('leads.edit', compact('lead', 'positions', 'countries', 'jobGroups', 'staffs', 'examinationRounds', 'staffSubs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $lead = LeadModel::findOrFail($id);
        
        // ตรวจสอบสิทธิ์สำหรับแก้ไข Lead ที่ Convert แล้ว
        if ($lead->lead_status === 'converted' && !auth()->user()->can('update converted lead')) {
            return back()->with('error', 'คุณไม่มีสิทธิ์แก้ไข Lead ที่ถูก Convert แล้ว');
        }
        
        $request->validate([
            'lead_firstname' => 'required|string|max:255',
            'lead_lastname' => 'required|string|max:255',
            'lead_phone' => 'nullable|string|max:20',
            'lead_passport_number' => 'nullable|string|max:50',
            'lead_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'lead_photo.image' => 'ไฟล์ที่อัปโหลดต้องเป็นรูปภาพเท่านั้น',
            'lead_photo.mimes' => 'รูปภาพต้องเป็นไฟล์ประเภท: jpeg, png, jpg, gif',
            'lead_photo.max' => 'ขนาดรูปภาพต้องไม่เกิน 2MB',
        ]);

        // ตรวจสอบ Passport ซ้ำใน Lead และ Labour (ยกเว้นตัวเอง)
        if ($request->filled('lead_passport_number')) {
            $passportNumber = $request->lead_passport_number;
            
            // ตรวจสอบใน leads table (ยกเว้น id ของตัวเอง)
            $duplicateInLead = LeadModel::where('lead_passport_number', $passportNumber)
                ->where('lead_id', '!=', $id)
                ->first();
            
            // ตรวจสอบใน labours table
            $duplicateInLabour = labourModel::where('labour_passport_number', $passportNumber)
                 ->where('lead_id', '!=', $id)
                ->first();
            
            if ($duplicateInLead) {
                return back()->withErrors([
                    'lead_passport_number' => 'หมายเลข Passport นี้มีอยู่ในระบบแล้ว (ผู้สมัคร: ' . $duplicateInLead->lead_firstname . ' ' . $duplicateInLead->lead_lastname . ')'
                ])->withInput();
            }
            
            if ($duplicateInLabour) {
                return back()->withErrors([
                    'lead_passport_number' => 'หมายเลข Passport นี้มีอยู่ในระบบแล้ว (แรงงาน: ' . $duplicateInLabour->labour_firstname . ' ' . $duplicateInLabour->labour_lastname . ')'
                ])->withInput();
            }
        }
        
        // ตรวจสอบชื่อ-นามสกุล ซ้ำใน Lead และ Labour (ยกเว้นตัวเอง)
        if ($request->filled('lead_firstname') && $request->filled('lead_lastname')) {
            $firstname = $request->lead_firstname;
            $lastname = $request->lead_lastname;
            
            // ตรวจสอบใน leads table (ยกเว้น id ของตัวเอง)
            $duplicateNameInLead = LeadModel::where('lead_firstname', $firstname)
                ->where('lead_lastname', $lastname)
                ->where('lead_id', '!=', $id)
                ->first();
            
            // ตรวจสอบใน labours table
            $duplicateNameInLabour = labourModel::where('labour_firstname', $firstname)
                ->where('labour_lastname', $lastname)
                ->where('lead_id', '!=', $id)
                ->first();
            
            if ($duplicateNameInLead) {
                return back()->withErrors([
                    'lead_firstname' => 'ชื่อ-นามสกุล นี้มีอยู่ในระบบแล้ว (ผู้สมัคร: ' . $duplicateNameInLead->lead_firstname . ' ' . $duplicateNameInLead->lead_lastname . ')'
                ])->withInput();
            }
            
            if ($duplicateNameInLabour) {
                return back()->withErrors([
                    'lead_firstname' => 'ชื่อ-นามสกุล นี้มีอยู่ในระบบแล้ว (แรงงาน: ' . $duplicateNameInLabour->labour_firstname . ' ' . $duplicateNameInLabour->labour_lastname . ')'
                ])->withInput();
            }
        }
        
        $data = $request->except('lead_photo', 'job_history');
        
        // Add updated_by
        $data['updated_by'] = auth()->id();
        
        // Lock lead_status if already converted - ห้ามเปลี่ยน status ถ้าถูก convert แล้ว
        if ($lead->lead_status === 'converted') {
            $data['lead_status'] = 'converted';
            $data['labour_id'] = $lead->labour_id;
            $data['converted_at'] = $lead->converted_at;
        }
        
        // Calculate BMI
        if ($request->filled('lead_height') && $request->filled('lead_weight')) {
            $heightM = $request->lead_height / 100;
            $data['lead_bmi'] = round($request->lead_weight / ($heightM * $heightM), 2);
        }
        
        // Handle photo upload
        if ($request->hasFile('lead_photo')) {
            try {
                // Delete old photo if exists
                if ($lead->lead_photo) {
                    Storage::disk('public')->delete($lead->lead_photo);
                }
                
                $photo = $request->file('lead_photo');
                $filename = time() . '_' . $photo->getClientOriginalName();
                $data['lead_photo'] = $photo->storeAs('leads', $filename, 'public');
            } catch (\Exception $e) {
                return back()->with('error', 'เกิดข้อผิดพลาดในการอัปโหลดรูปภาพ: ' . $e->getMessage())->withInput();
            }
        }
        
        DB::beginTransaction();
        try {
            $lead->update($data);
            
            // Job history is now managed via separate API endpoints
            // No need to update here since it's saved in real-time
            
            DB::commit();
            return redirect()->route('leads.index')->with('success', 'อัปเดตข้อมูล Lead สำเร็จ');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $lead = LeadModel::findOrFail($id);
        
        // ตรวจสอบว่า Lead ถูก Convert แล้วหรือไม่
        if ($lead->isConverted()) {
            // ถ้า Convert แล้ว ต้องมีสิทธิ์พิเศษถึงจะลบได้
            if (!auth()->user()->can('delete converted lead')) {
                return back()->with('error', 'ไม่สามารถลบ Lead ที่ถูก Convert แล้ว (ต้องมีสิทธิ์พิเศษ)');
            }
        }
        
        try {
            if ($lead->lead_photo) {
                Storage::disk('public')->delete($lead->lead_photo);
            }
            
            $lead->delete();
            return redirect()->route('leads.index')->with('success', 'ลบข้อมูล Lead สำเร็จ');
        } catch (\Exception $e) {
            return back()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
    }

    public function convertForm(string $id)
    {
        $lead = LeadModel::with(['position', 'country', 'jobGroup'])->findOrFail($id);
        
        if ($lead->isConverted()) {
            return back()->with('error', 'Lead นี้ถูก Convert แล้ว');
        }
        
        $customers = customerModel::where('customer_status', 'active')->get();
        
        return view('leads.convert', compact('lead', 'customers'));
    }

    public function convert(Request $request, string $id)
    {
        $lead = LeadModel::findOrFail($id);
        
        if ($lead->isConverted()) {
            return back()->with('error', 'Lead นี้ถูก Convert แล้ว');
        }
        
        $request->validate([
            'customer_id' => 'required|exists:customers,customer_id',
        ]);

        // ตรวจสอบ Passport ซ้ำใน Labour ก่อน Convert
        if ($lead->lead_passport_number) {
            $passportInLabour = labourModel::where('labour_passport_number', $lead->lead_passport_number)->exists();
            if ($passportInLabour) {
                return back()->with('error', 'เลขที่ Passport นี้มีในระบบ Labour แล้ว ไม่สามารถ Convert ได้');
            }
        }
        
        DB::beginTransaction();
        try {
            // Create Labour from Lead
            $labour = labourModel::create([
                'labour_prefix' => $lead->lead_prefix,
                'labour_firstname' => $lead->lead_firstname,
                'labour_lastname' => $lead->lead_lastname,
                'labour_gender' => $lead->lead_gender,
                'labour_birthday' => $lead->lead_birthday,
                'labour_phone' => $lead->lead_phone,
                'labour_email' => $lead->lead_email,
                'labour_address' => $lead->lead_address,
                'labour_passport_number' => $lead->lead_passport_number,
                'labour_height' => $lead->lead_height,
                'labour_weight' => $lead->lead_weight,
                'position_id' => $lead->position_id,
                'country_id' => $lead->country_id,
                'job_group_id' => $lead->job_group_id,
                'customer_id' => $request->customer_id,
                'staff_id' => $lead->staff_id,
                'labour_status' => 'wait',
                'labour_photo' => $lead->lead_photo,
            ]);
            
            // Update Lead
            $lead->update([
                'lead_status' => 'converted',
                'labour_id' => $labour->labour_id,
                'converted_at' => now(),
            ]);
            
            DB::commit();
            return redirect()->route('labour.edit', $labour->labour_id)
                           ->with('success', 'Convert Lead เป็น Labour สำเร็จ');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
    }

    /**
     * Get timeline of lead activities (from job applications)
     */
    public function timeline($lead)
    {
        try {
            // Support both ID and model binding
            if (!$lead instanceof LeadModel) {
                $lead = LeadModel::findOrFail($lead);
            }
            
            // Get all activities for this lead (รวมถึงที่ job_lead ถูกยกเลิก)
            $activities = \App\Models\jobs\JobLeadActivityModel::with(['user'])
                ->where('lead_id', $lead->lead_id)
                ->orderBy('created_at', 'desc')
                ->get();
            
            // Load job info for each activity
            foreach ($activities as $activity) {
                if ($activity->job_lead_id) {
                    // ยังมี job_lead อยู่
                    $jobLead = \App\Models\jobs\JobLeadModel::with(['job.country', 'job.demand'])
                        ->find($activity->job_lead_id);
                    
                    if ($jobLead) {
                        $activity->job_info = [
                            'number' => $jobLead->job_lead_number,
                            'name' => $jobLead->job->job_name ?? '',
                            'country' => $jobLead->job->country->country_name_th ?? ''
                        ];
                    }
                } else {
                    // job_lead ถูกยกเลิกแล้ว - ใช้ข้อมูลที่เก็บไว้
                    $activity->job_info = [
                        'number' => $activity->job_lead_number ?? 'ไม่ระบุ',
                        'name' => 'ถูกยกเลิกแล้ว',
                        'country' => ''
                    ];
                }
            }
            
            // Render view
            $html = view('leads.partials.timeline', [
                'activities' => $activities,
                'lead' => $lead
            ])->render();
            
            return response()->json([
                'success' => true,
                'html' => $html,
                'count' => $activities->count()
            ]);
                           
        } catch (\Exception $e) {
            \Log::error('Lead Timeline Error: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'error' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ], 500);
        }
    }

    public function cv(Request $request)
    {
        return view('leads.cv');
    }

    /**
     * Generate Resume/CV for a lead
     */
    public function resume($id)
    {
        try {
            $lead = LeadModel::with([
                'position', 
                'position2', 
                'position3',
                'country', 
                'jobGroup', 
                'staff',
                'recommenderStaff',
                'jobHistory',
                'examinationRound'
            ])->findOrFail($id);
            
            return view('leads.resume', compact('lead'));
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'ไม่พบข้อมูลผู้สมัคร');
        }
    }

    /**
     * Store job history for a lead
     */
    public function storeJobHistory(Request $request, $id)
    {
        $request->validate([
            'company_type' => 'required|string',
            'position' => 'required|string',
            'start_date' => 'nullable|string',
            'end_date' => 'nullable|string',
            'country' => 'nullable|string',
            'experience_years' => 'nullable|numeric',
            'company_name' => 'nullable|string',
            'description' => 'nullable|string',
            'company_about' => 'nullable|string',
        ]);

        try {
            $lead = LeadModel::findOrFail($id);
            
            // Get the max display_order
            $maxOrder = $lead->jobHistory()->max('display_order') ?? 0;
            
            $jobHistory = LeadJobHistoryModel::create([
                'lead_id' => $lead->lead_id,
                'company_type' => $request->company_type,
                'company_name' => $request->company_name,
                'position' => $request->position,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'country' => $request->country ?? 'THAI',
                'experience_years' => $request->experience_years ?? 0,
                'description' => $request->description,
                'company_about' => $request->company_about,
                'display_order' => $maxOrder + 1,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'บันทึกประวัติการทำงานสำเร็จ',
                'data' => $jobHistory
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update job history for a lead
     */
    public function updateJobHistory(Request $request, $leadId, $jobHistoryId)
    {
        $request->validate([
            'company_type' => 'required|string',
            'position' => 'required|string',
            'start_date' => 'nullable|string',
            'end_date' => 'nullable|string',
            'country' => 'nullable|string',
            'experience_years' => 'nullable|numeric',
            'company_name' => 'nullable|string',
            'description' => 'nullable|string',
            'company_about' => 'nullable|string',
        ]);

        try {
            $jobHistory = LeadJobHistoryModel::where('job_history_id', $jobHistoryId)
                ->where('lead_id', $leadId)
                ->firstOrFail();
            
            $jobHistory->update([
                'company_type' => $request->company_type,
                'company_name' => $request->company_name,
                'position' => $request->position,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'country' => $request->country ?? 'THAI',
                'experience_years' => $request->experience_years ?? 0,
                'description' => $request->description,
                'company_about' => $request->company_about,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'อัปเดตประวัติการทำงานสำเร็จ',
                'data' => $jobHistory
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete job history for a lead
     */
    public function deleteJobHistory($leadId, $jobHistoryId)
    {
        try {
            $jobHistory = LeadJobHistoryModel::where('job_history_id', $jobHistoryId)
                ->where('lead_id', $leadId)
                ->firstOrFail();
            
            $jobHistory->delete();

            return response()->json([
                'success' => true,
                'message' => 'ลบประวัติการทำงานสำเร็จ'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ], 500);
        }
    }
}

