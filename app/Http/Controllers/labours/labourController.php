<?php

namespace App\Http\Controllers\labours;

use Illuminate\Http\Request;
use App\Models\staff\staffModel;
use App\Models\files\listFileModel;
use App\Models\labours\labourModel;
use App\Models\leads\LeadModel;
use App\Models\staff\staffSubModel;
use App\Http\Controllers\Controller;
use App\Models\country\countryModel;
use Illuminate\Support\Facades\Auth;
use App\Models\files\fileManageModel;
use App\Models\files\labourFileModel;
use App\Models\jobgroup\jobGroupModel;
use App\Models\customers\customerModel;
use App\Models\labours\CIDresultsModel;
use App\Models\positions\positionModel;
use Illuminate\Support\Facades\Storage;
use App\Models\locationTest\locationTestModel;
use App\Models\examinations\examinationRoundModel;

class labourController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view labour', ['only' => ['index', 'edit']]);
        $this->middleware('permission:create labour', ['only' => ['create', 'store']]);
        $this->middleware('permission:account update labour|update labour', ['only' => ['update']]);

        $this->middleware('permission:delete labour', ['only' => ['destroy']]);
    }

    

    public function index(Request $request)
    {
        $jobGroup = jobGroupModel::where('job_group_status', 'active')->latest()->get();
        $customers = customerModel::latest()->get();
        $staffs = staffModel::where('staff_status', 'active')->get();
        $staffSub = staffSubModel::where('staff_sub_status', 'active')->get();
    
        // ดึงค่าที่ค้นหามาจาก request
        $labour_firstname = $request->labour_firstname;
        $labour_lastname = $request->labour_lastname;
        $labour_phone = $request->labour_phone;
        $labour_passport_number = $request->labour_passport_number;
        $labour_disease_date_start = $request->labour_disease_date_start;
        $labour_disease_date_end = $request->labour_disease_date_end;
        $labour_cid_start = $request->labour_cid_start;
        $labour_cid_end = $request->labour_cid_end;
        $labour_country = $request->labour_country;
        $labour_job_group = $request->labour_job_group;
        $labour_staff = $request->labour_staff;
        $labour_status = $request->labour_status;
    
        // Query ข้อมูลจาก labourModel
        $labours = labourModel::leftjoin('staff', 'staff.staff_id', 'labours.labour_staff')->latest('labours.updated_at');
    
        // ค้นหาด้วยชื่อ
        if (!empty($labour_firstname)) {
            $labours = $labours->where('labours.labour_firstname', 'LIKE', "%$labour_firstname%");
        }
    
        // ค้นหาด้วยนามสกุล
        if (!empty($labour_lastname)) {
            $labours = $labours->where('labours.labour_lastname', 'LIKE', "%$labour_lastname%");
        }
    
        // ค้นหาด้วยเบอร์โทรศัพท์
        if (!empty($labour_phone)) {
            $labours = $labours->where('labours.labour_phone', 'LIKE', "%$labour_phone%");
        }
    
        // ค้นหาด้วยหมายเลขหนังสือเดินทาง
        if (!empty($labour_passport_number)) {
            $labours = $labours->where('labours.labour_passport_number', 'LIKE', "%$labour_passport_number%");
        }
    
        // ค้นหาด้วยวันที่ผลโรค (เริ่มต้น - สิ้นสุด)
        if (!empty($labour_disease_date_start) && !empty($labour_disease_date_end)) {
            $labours = $labours->whereBetween('labours.labour_disease_expiry', [$labour_disease_date_start, $labour_disease_date_end]);
        }
    
        // ค้นหาด้วยวันที่หมดอายุ CID (เริ่มต้น - สิ้นสุด)
        if (!empty($labour_cid_start) && !empty($labour_cid_end)) {
            $labours = $labours->whereBetween('labours.labour_cid_expiry', [$labour_cid_start, $labour_cid_end]);
        }
    
        // ค้นหาด้วยประเทศ
        if (!empty($labour_country) && $labour_country !== 'all') {
            $labours = $labours->where('labours.labour_country', $labour_country);
        }
    
        // ค้นหาด้วยกลุ่มงาน
        if (!empty($labour_job_group) && $labour_job_group !== 'all') {
            $labours = $labours->where('labours.labour_job_group', $labour_job_group);
        }
    
        // ค้นหาด้วยพนักงาน
        if (!empty($labour_staff) && $labour_staff !== 'all') {
            $labours = $labours->where('labours.labour_staff', $labour_staff);
        }
    
        // ค้นหาด้วยสถานะ
        if (!empty($labour_status) && $labour_status !== 'all') {
            $labours = $labours->where('labours.labour_status', $labour_status);
        }
    
        // Paginate ผลลัพธ์
        $labours = $labours->paginate(10);
    
        // ส่งผลลัพธ์ไปยัง view
        return view('labours.index', compact('labours', 'customers', 'jobGroup', 'staffs', 'staffSub'));
    }
    
    public function edit(labourModel $labourModel)
    {
        $country = countryModel::where('country_status', 'active')->latest()->get();
        $jobGroup = jobGroupModel::where('job_group_status', 'active')->latest()->get();
        $locationtest = locationTestModel::where('location_test_status', 'active')->get();
        $staffs = staffModel::where('staff_status', 'active')->get();
        $fileManage = fileManageModel::where('file_manage_status', 'active')->get();
        $examinationRound = examinationRoundModel::where('examination_round_status', 'active')->latest()->get();
        $position = positionModel::where('position_id', $labourModel->labour_position)->first();
        $positions = positionModel::where('position_status', 'active')->get(); // เพิ่มบรรทัดนี้
        $labourfiles = labourFileModel::where('labour_id', $labourModel->labour_id)->get();
        $customers = customerModel::where('customer_status', 'active')->get();
        $CidResults = CIDresultsModel::get();



        $fileID = $labourfiles->pluck('list_file_id')->toArray();
        $listFiles = listFileModel::where('file_manage_id',$labourModel->labour_location_doc)->whereNotIn('list_file_id',$fileID)->get();
        
        $staffSub = staffSubModel::where('staff_sub_status', 'active')->get();
        return view('labours.form-edit', compact('listFiles', 'customers', 'labourModel','CidResults','staffSub', 'country', 'jobGroup', 'locationtest', 'staffs', 'fileManage', 'examinationRound', 'position', 'positions', 'labourfiles'));
    }

    public function create()
    {
        $CidResults = CIDresultsModel::get();
        $country = countryModel::where('country_status', 'active')->latest()->get();
        $jobGroup = jobGroupModel::where('job_group_status', 'active')->latest()->get();
        $locationtest = locationTestModel::where('location_test_status', 'active')->get();
        $staffs = staffModel::where('staff_status', 'active')->get();
        $fileManage = fileManageModel::where('file_manage_status', 'active')->get();
        $customers = customerModel::where('customer_status', 'active')->get();
        $staffSub = staffSubModel::where('staff_sub_status', 'active')->get();
        $positions = positionModel::where('position_status', 'active')->get(); // เพิ่มบรรทัดนี้

        $examinationRound = examinationRoundModel::where('examination_round_status', 'active')->latest()->get();
        return view('labours.form-create', compact('country','CidResults', 'jobGroup', 'locationtest', 'staffs', 'fileManage', 'examinationRound', 'customers','staffSub', 'positions'));
    }

    public function update(labourModel $labourModel, Request $request)
    {
        
        // ตรวจสอบ Passport ซ้ำ
        if ($request->has('labour_passport_number') && !empty($request->labour_passport_number)) {
            $passportNumber = $request->labour_passport_number;
            
            // ตรวจสอบใน labours table (ยกเว้น id ของตัวเอง)
            $duplicateInLabour = labourModel::where('labour_passport_number', $passportNumber)
                ->where('labour_id', '!=', $labourModel->labour_id)
                ->first();
                //dd($duplicateInLabour);
            
            // ตรวจสอบใน leads table (ยกเว้น lead ที่เป็นต้นทางของ labour นี้)
            $duplicateInLead = LeadModel::where('lead_passport_number', $passportNumber)
                ->where('lead_id', '!=', $labourModel->lead_id) // ยกเว้น lead ที่ถูก convert มา
                 // ยกเว้น lead ที่ถูก convert มา
                ->first();
                  //  dd($request);
            
            if ($duplicateInLabour) {
                return back()->withErrors([
                    'labour_passport_number' => 'หมายเลข Passport นี้มีอยู่ในระบบแล้ว (แรงงาน: ' . $duplicateInLabour->labour_firstname . ' ' . $duplicateInLabour->labour_lastname . ')'
                ])->withInput();
            }
            
            if ($duplicateInLead) {
                return back()->withErrors([
                    'labour_passport_number' => 'หมายเลข Passport นี้มีอยู่ในระบบแล้ว (ผู้สมัคร: ' . $duplicateInLead->lead_firstname . ' ' . $duplicateInLead->lead_lastname . ')'
                ])->withInput();
            }
        }
       
        
        // ตรวจสอบชื่อ-นามสกุล ซ้ำ (ยกเว้นตัวเอง)
        if ($request->has('labour_firstname') && $request->has('labour_lastname')) {
            $firstname = $request->labour_firstname;
            $lastname = $request->labour_lastname;
            
            // ตรวจสอบใน labours table (ยกเว้น id ของตัวเอง)
            $duplicateNameInLabour = labourModel::where('labour_firstname', $firstname)
                ->where('labour_lastname', $lastname)
                ->where('labour_id', '!=', $labourModel->labour_id)
                ->first();
            
            // ตรวจสอบใน leads table (ยกเว้น lead ที่เป็นต้นทางของ labour นี้)
            $duplicateNameInLead = LeadModel::where('lead_firstname', $firstname)
                ->where('lead_lastname', $lastname)
                ->where('lead_id', '!=', $labourModel->lead_id)
                ->first();
            
            if ($duplicateNameInLabour) {
                return back()->withErrors([
                    'labour_firstname' => 'ชื่อ-นามสกุล นี้มีอยู่ในระบบแล้ว (แรงงาน: ' . $duplicateNameInLabour->labour_firstname . ' ' . $duplicateNameInLabour->labour_lastname . ')'
                ])->withInput();
            }
            
            if ($duplicateNameInLead) {
                return back()->withErrors([
                    'labour_firstname' => 'ชื่อ-นามสกุล นี้มีอยู่ในระบบแล้ว (ผู้สมัคร: ' . $duplicateNameInLead->lead_firstname . ' ' . $duplicateNameInLead->lead_lastname . ')'
                ])->withInput();
            }
        }

        
        
        $data = $request->all();
        $data['updated_by'] = auth()->id();
        
        // ตรวจสอบว่ามีการเปลี่ยน labour_location_doc หรือไม่
        $oldLocationDoc = $labourModel->labour_location_doc;
        $newLocationDoc = $request->labour_location_doc;
        
        $labourModel->update($data);
        
        // ตรวจสอบว่ามี labour_file หรือยัง
        $existingFileCount = labourFileModel::where('labour_id', $labourModel->labour_id)->count();
        
        // ถ้ายังไม่มี labour_file เลย และมี labour_location_doc ให้สร้างรายการเอกสาร
        if ($existingFileCount == 0 && !empty($newLocationDoc)) {
            // ดึงรายการเอกสารจาก file_manage
            $listfiles = listFileModel::where('file_manage_id', $newLocationDoc)->get();
            
            // สร้างรายการเอกสารใหม่
            foreach ($listfiles as $list) {
                labourFileModel::create([
                    'labour_file_name' => $list->list_file_name,
                    'labour_file_note' => $list->list_file_note,
                    'labour_file_path' => null,
                    'list_file_id' => $list->list_file_id,
                    'labour_id' => $labourModel->labour_id,
                    'labour_passport_number' => $labourModel->labour_passport_number,
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id(),
                ]);
            }
            
            // อัปเดตจำนวนไฟล์
            $filecount = labourFileModel::where('labour_id', $labourModel->labour_id)->count();
            $labourModel->update([
                'labour_file_count' => $filecount,
                'updated_by' => auth()->id()
            ]);
        }
     
        
        labourFileModel::where('labour_id', $labourModel->labour_id)->update([
            'labour_passport_number' => $labourModel->labour_passport_number,
            'updated_by' => auth()->id()
        ]);
        
        // Upload ไฟล์ที่ใช้ index แยกกัน (file_0, file_1, file_2, ...)
        $fullPath = 'LABOURS/' . $labourModel->labour_path;
        $debugInfo = ['loop_count' => 0, 'found_files' => [], 'new_files' => []];
        
        foreach ($request->all() as $key => $value) {
            // ตรวจสอบว่าเป็น file input หรือไม่ (file_0, file_1, file_2, ...)
            if (strpos($key, 'file_') === 0) {
                $debugInfo['found_files'][$key] = [
                    'hasFile' => $request->hasFile($key),
                    'strpos' => strpos($key, 'file_')
                ];
            }
            
            // Upload ไฟล์เอกสารที่มีอยู่แล้ว (file_0, file_1, ...)
            if (strpos($key, 'file_') === 0 && $request->hasFile($key) && strpos($key, 'file_new_') === false) {
                $debugInfo['loop_count']++;
                $index = str_replace('file_', '', $key); // ดึง index (0, 1, 2, ...)
                $file = $request->file($key);
                
                // ดึงข้อมูล labour_file_name และ labour_file_id จาก index นั้นๆ
                $fileNameKey = 'labour_file_name_' . $index;
                $fileIdKey = 'labour_file_id_' . $index;
                
                $debugInfo['processing'][$key] = [
                    'index' => $index,
                    'fileNameKey' => $fileNameKey,
                    'fileIdKey' => $fileIdKey,
                    'has_name' => $request->has($fileNameKey),
                    'has_id' => $request->has($fileIdKey)
                ];
                
                if ($request->has($fileNameKey) && $request->has($fileIdKey)) {
                    $labourFileName = $request->input($fileNameKey);
                    $labourFileId = $request->input($fileIdKey);
                    
                    // สร้างชื่อไฟล์ใหม่
                    $extension = $file->getClientOriginalExtension();
                    $uniqueName = $labourFileName . '_' . $labourModel->labour_firstname . '_' . $labourModel->labour_lastname . '.' . $extension;
                    
                    // สร้าง Folder ถ้ายังไม่มี
                    if (!Storage::disk('public')->exists($fullPath)) {
                        Storage::disk('public')->makeDirectory($fullPath);
                    }
                    
                    // อัปโหลดไฟล์
                    $path = $file->storeAs($fullPath, $uniqueName, 'public');
                    
                    $debugInfo['uploaded'][$key] = [
                        'path' => $path,
                        'uniqueName' => $uniqueName,
                        'labourFileId' => $labourFileId
                    ];
                    
                    // อัปเดต database
                    if ($path) {
                        $updated = labourFileModel::where('labour_file_id', $labourFileId)->update([
                            'labour_file_path' => $uniqueName,
                            'updated_by' => auth()->id()
                        ]);
                        $debugInfo['uploaded'][$key]['db_updated'] = $updated;
                    }
                }
            }
            
            // Upload ไฟล์เอกสารใหม่ที่เพิ่มเข้ามา (file_new_0, file_new_1, ...)
            if (strpos($key, 'file_new_') === 0 && $request->hasFile($key)) {
                $debugInfo['new_files']['count'] = isset($debugInfo['new_files']['count']) ? $debugInfo['new_files']['count'] + 1 : 1;
                $index = str_replace('file_new_', '', $key);
                $file = $request->file($key);
                
                // ดึงข้อมูลเอกสารใหม่
                $fileNameKey = 'labour_file_name_new_' . $index;
                $listFileIdKey = 'list_file_id_new_' . $index;
                
                if ($request->has($fileNameKey) && $request->has($listFileIdKey)) {
                    $labourFileName = $request->input($fileNameKey);
                    $listFileId = $request->input($listFileIdKey);
                    
                    // ดึงข้อมูล list_file_note จาก listFileModel
                    $listFile = listFileModel::find($listFileId);
                    
                    // สร้างชื่อไฟล์ใหม่
                    $extension = $file->getClientOriginalExtension();
                    $uniqueName = $labourFileName . '_' . $labourModel->labour_firstname . '_' . $labourModel->labour_lastname . '.' . $extension;
                    
                    // สร้าง Folder ถ้ายังไม่มี
                    if (!Storage::disk('public')->exists($fullPath)) {
                        Storage::disk('public')->makeDirectory($fullPath);
                    }
                    
                    // อัปโหลดไฟล์
                    $path = $file->storeAs($fullPath, $uniqueName, 'public');
                    
                    if ($path) {
                        // สร้างรายการเอกสารใหม่ใน labourFileModel
                        $newLabourFile = labourFileModel::create([
                            'labour_file_name' => $labourFileName,
                            'labour_file_note' => $listFile ? $listFile->list_file_note : '',
                            'labour_file_path' => $uniqueName,
                            'list_file_id' => $listFileId,
                            'labour_id' => $labourModel->labour_id,
                            'labour_passport_number' => $labourModel->labour_passport_number,
                            'created_by' => auth()->id(),
                            'updated_by' => auth()->id(),
                        ]);
                        
                        $debugInfo['new_files']['uploaded'][$key] = [
                            'path' => $path,
                            'uniqueName' => $uniqueName,
                            'newLabourFileId' => $newLabourFile->labour_file_id
                        ];
                    }
                }
            }
        }
        
        // แสดง debug ถ้ามีการ process ไฟล์
        if ($debugInfo['loop_count'] > 0 || !empty($debugInfo['found_files']) || !empty($debugInfo['new_files'])) {
            session()->flash('upload_debug', $debugInfo);
        }

        //CID Upload 
        if ($request->hasFile('cid_file')) {
            $file = $request->file('cid_file');
        
            // สร้างชื่อไฟล์ใหม่ เช่น cid_ชื่อ_นามสกุล.jpg
            $extension = $file->getClientOriginalExtension();
            $uniqueName = 'cid_' . $labourModel->labour_firstname . '_' . $labourModel->labour_lastname . '.' . $extension;
        
            // ตรวจสอบและสร้างโฟลเดอร์ หากยังไม่มี
            if (!Storage::disk('public')->exists($labourModel->labour_path)) {
                Storage::disk('public')->makeDirectory($labourModel->labour_path);
            }
        
            // อัปโหลดไฟล์ไปยังโฟลเดอร์ที่กำหนดใน disk 'public'
            $path = $file->storeAs($labourModel->labour_path, $uniqueName, 'public');
        
            // ถ้าอัปโหลดสำเร็จ อัปเดตฟิลด์ labour_cid_results_file
            if ($path) {
                labourModel::where('labour_id', $labourModel->labour_id)->update([
                    'labour_cid_results_file' => $uniqueName,
                    'updated_by' => auth()->id(),
                ]);
            }
        }

        //VISA File Upload 
        if ($request->hasFile('visa_file')) {
            $file = $request->file('visa_file');
        
            // สร้างชื่อไฟล์ใหม่ เช่น visa_ชื่อ_นามสกุล.jpg
            $extension = $file->getClientOriginalExtension();
            $uniqueName = 'visa_' . $labourModel->labour_firstname . '_' . $labourModel->labour_lastname . '.' . $extension;
        
            // ตรวจสอบและสร้างโฟลเดอร์ หากยังไม่มี
            $fullPath = 'LABOURS/' . $labourModel->labour_path;
            if (!Storage::disk('public')->exists($fullPath)) {
                Storage::disk('public')->makeDirectory($fullPath);
            }
        
            // อัปโหลดไฟล์ไปยังโฟลเดอร์ที่กำหนดใน disk 'public'
            $path = $file->storeAs($fullPath, $uniqueName, 'public');
        
            // ถ้าอัปโหลดสำเร็จ อัปเดตฟิลด์ labour_visa_file
            if ($path) {
                labourModel::where('labour_id', $labourModel->labour_id)->update([
                    'labour_visa_file' => $uniqueName,
                    'updated_by' => auth()->id(),
                ]);
            }
        }
      


        
        

        $counFile = labourFileModel::where('labour_id', $labourModel->labour_id)->count('labour_file_id');
        $counFileNotNull = labourFileModel::where('labour_id', $labourModel->labour_id)
            ->whereNotNull('labour_file_path')
            ->count('labour_file_id');
        $labourModel->update([
            'labour_file_count' => $counFile, 
            'labour_file_list' => $counFileNotNull,
            'updated_by' => auth()->id()
        ]);


      
        return redirect()->back()->with('success', 'Updated Labour Successfully.');
    }

    public function deleteCidFile($labourId)
{
    $labour = labourModel::findOrFail($labourId);

    if ($labour->labour_cid_results_file) {
        $filePath = $labour->labour_path . '/' . $labour->labour_cid_results_file;

        if (Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }

        // เคลียร์ชื่อไฟล์ออกจากฐานข้อมูล
        $labour->update([
            'labour_cid_results_file' => null,
            'updated_by' => auth()->id()
        ]);
    }

    return redirect()->back()->with('success', 'ลบไฟล์เรียบร้อยแล้ว');
}

public function deleteVisaFile($labourId)
{
    $labour = labourModel::findOrFail($labourId);

    if ($labour->labour_visa_file) {
        $filePath = 'LABOURS/' . $labour->labour_path . '/' . $labour->labour_visa_file;

        if (Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }

        // เคลียร์ชื่อไฟล์ออกจากฐานข้อมูล
        $labour->update([
            'labour_visa_file' => null,
            'updated_by' => auth()->id()
        ]);
    }

    return redirect()->back()->with('success', 'ลบไฟล์ VISA เรียบร้อยแล้ว');
}


    public function store(Request $request)
    {
        // ตรวจสอบ Passport ซ้ำใน Labour และ Lead
        if ($request->filled('labour_passport_number')) {
            $passportNumber = $request->labour_passport_number;
            
            // ตรวจสอบใน labours table
            $duplicateInLabour = labourModel::where('labour_passport_number', $passportNumber)->first();
            
            // ตรวจสอบใน leads table
            $duplicateInLead = LeadModel::where('lead_passport_number', $passportNumber)->first();
            
            if ($duplicateInLabour) {
                return back()->withErrors([
                    'labour_passport_number' => 'หมายเลข Passport นี้มีอยู่ในระบบแล้ว (แรงงาน: ' . $duplicateInLabour->labour_firstname . ' ' . $duplicateInLabour->labour_lastname . ')'
                ])->withInput();
            }
            
            if ($duplicateInLead) {
                return back()->withErrors([
                    'labour_passport_number' => 'หมายเลข Passport นี้มีอยู่ในระบบแล้ว (ผู้สมัคร: ' . $duplicateInLead->lead_firstname . ' ' . $duplicateInLead->lead_lastname . ')'
                ])->withInput();
            }
        }
        
        // ตรวจสอบชื่อ-นามสกุล ซ้ำใน Labour และ Lead
        if ($request->filled('labour_firstname') && $request->filled('labour_lastname')) {
            $firstname = $request->labour_firstname;
            $lastname = $request->labour_lastname;
            
            // ตรวจสอบใน labours table
            $duplicateNameInLabour = labourModel::where('labour_firstname', $firstname)
                ->where('labour_lastname', $lastname)
                ->first();
            
            // ตรวจสอบใน leads table
            $duplicateNameInLead = LeadModel::where('lead_firstname', $firstname)
                ->where('lead_lastname', $lastname)
                ->first();
            
            if ($duplicateNameInLabour) {
                return back()->withErrors([
                    'labour_firstname' => 'ชื่อ-นามสกุล นี้มีอยู่ในระบบแล้ว (แรงงาน: ' . $duplicateNameInLabour->labour_firstname . ' ' . $duplicateNameInLabour->labour_lastname . ')'
                ])->withInput();
            }
            
            if ($duplicateNameInLead) {
                return back()->withErrors([
                    'labour_firstname' => 'ชื่อ-นามสกุล นี้มีอยู่ในระบบแล้ว (ผู้สมัคร: ' . $duplicateNameInLead->lead_firstname . ' ' . $duplicateNameInLead->lead_lastname . ')'
                ])->withInput();
            }
        }

        // เพิ่มข้อมูลผู้สร้างและปีโฟลเดอร์
        $request->merge(['created_by' => auth()->id()]);
        $request->merge(['updated_by' => auth()->id()]);
        $request->merge(['labour_folder_year' => date('Y')]);
        $labourModel = labourModel::create($request->all());
         
            $folderYear = $labourModel->labour_folder_year;
            $folderMonth = date('m');
            $folderPath = 'LABOURS/' . $folderYear . '/' . $folderMonth . '/' . $labourModel->labour_firstname . '_' . $labourModel->labour_lastname;
            //สร้าง Forlder
            if (!Storage::disk('public')->exists($folderPath)) {
                Storage::disk('public')->makeDirectory($folderPath);
            }

            //สร้างรายการเอกสาร
            $listfiles = listFileModel::where('file_manage_id', $labourModel->labour_location_doc)->get();
            $filecount = $listfiles->count();

            $labourModel->update([
                'labour_path' => $folderPath, 
                'labour_file_count' => $filecount, 
                'labour_file_list' => 0,
                'updated_by' => auth()->id()
            ]);

            if ($labourModel) {
                foreach ($listfiles as $list) {
                    labourFileModel::create([
                        'labour_file_name' => $list->list_file_name,
                        'labour_file_note' => $list->list_file_note,
                        'labour_file_path' => null,
                        'list_file_id' => $list->list_file_id,
                        'labour_id' => $labourModel->labour_id,
                        'labour_passport_number' => $labourModel->labour_passport_number,
                        'created_by' => auth()->id(),
                        'updated_by' => auth()->id(),
                    ]);
                }

                //CID Upload for new labour
                if ($request->hasFile('cid_file')) {
                    $file = $request->file('cid_file');
                
                    // สร้างชื่อไฟล์ใหม่ เช่น cid_ชื่อ_นามสกุล.jpg
                    $extension = $file->getClientOriginalExtension();
                    $uniqueName = 'cid_' . $labourModel->labour_firstname . '_' . $labourModel->labour_lastname . '.' . $extension;
                
                    // อัปโหลดไฟล์ไปยังโฟลเดอร์ที่กำหนดใน disk 'public'
                    $path = $file->storeAs($folderPath, $uniqueName, 'public');
                
                    // ถ้าอัปโหลดสำเร็จ อัปเดตฟิลด์ labour_cid_results_file
                    if ($path) {
                        $labourModel->update([
                            'labour_cid_results_file' => $uniqueName,
                            'updated_by' => auth()->id(),
                        ]);
                    }
                }

                //VISA File Upload for new labour
                if ($request->hasFile('visa_file')) {
                    $file = $request->file('visa_file');
                
                    // สร้างชื่อไฟล์ใหม่ เช่น visa_ชื่อ_นามสกุล.jpg
                    $extension = $file->getClientOriginalExtension();
                    $uniqueName = 'visa_' . $labourModel->labour_firstname . '_' . $labourModel->labour_lastname . '.' . $extension;
                
                    // อัปโหลดไฟล์ไปยังโฟลเดอร์ที่กำหนดใน disk 'public'
                    $path = $file->storeAs($folderPath, $uniqueName, 'public');
                
                    // ถ้าอัปโหลดสำเร็จ อัปเดตฟิลด์ labour_visa_file
                    if ($path) {
                        $labourModel->update([
                            'labour_visa_file' => $uniqueName,
                            'updated_by' => auth()->id(),
                        ]);
                    }
                }
            }

        return redirect()->route('labour.index')->with('success', 'เพิ่มข้อมูลแรงงานสำเร็จ');
    }

    public function CombinePDF(labourModel $labourModel, Request $request)
    {
        $labourfiles = labourFileModel::where('labour_id', $labourModel->labour_id)->get();
        return view('labours.modal-combinePDF', compact('labourfiles', 'labourModel'));
    }

    public function viewDocs(labourModel $labourModel)
    {
        $labourfiles = labourFileModel::where('labour_id', $labourModel->labour_id)->get();
        return view('labours.modal-view-doc', compact('labourfiles', 'labourModel'));
    }

    public function destroy(labourModel $labourModel)
    {
        try {
            // Delete related files if exists
            if ($labourModel->labour_photo && Storage::exists('public/' . $labourModel->labour_photo)) {
                Storage::delete('public/' . $labourModel->labour_photo);
            }

            // Delete labour files
            $labourFiles = labourFileModel::where('labour_id', $labourModel->labour_id)->get();
            foreach ($labourFiles as $file) {
                if ($file->labour_file_name && Storage::exists('public/' . $file->labour_file_name)) {
                    Storage::delete('public/' . $file->labour_file_name);
                }
                $file->delete();
            }

            // Delete CID results
            CIDresultsModel::where('labour_id', $labourModel->labour_id)->delete();

            // Delete the labour
            $labourModel->delete();

            return redirect()->route('labour.index')->with('success', 'ลบข้อมูลแรงงานเรียบร้อยแล้ว');
        } catch (\Exception $e) {
            return redirect()->route('labour.index')->with('error', 'เกิดข้อผิดพลาดในการลบข้อมูล: ' . $e->getMessage());
        }
    }

}
