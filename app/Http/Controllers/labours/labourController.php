<?php

namespace App\Http\Controllers\labours;

use Illuminate\Http\Request;
use App\Models\staff\staffModel;
use App\Models\files\listFileModel;
use App\Models\labours\labourModel;
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

        //dd($request->all());
        $labourModel->update($request->all());
        labourFileModel::where('labour_id', $labourModel->labour_id)->update(['labour_passport_number' => $labourModel->labour_passport_number]);
        $files = $request->file('files');
        $fullPath = 'LABOURS/' . $labourModel->labour_path;
        if ($files) {
            foreach ($files as $key => $file) {
                // สร้างชื่อไฟล์ที่ไม่ซ้ำกัน
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME); // ชื่อไฟล์เดิมโดยไม่มีนามสกุล
                $extension = $file->getClientOriginalExtension(); // นามสกุลไฟล์
                $uniqueName = $request->labour_file_name[$key] . '_' . $labourModel->labour_firstname . '_' . $labourModel->labour_lastname . '.' . $extension; // แก้ไขให้ใช้ $labourModel->id แทน $labourModel

                 //สร้าง Forlder
                 if (!Storage::disk('public')->exists($fullPath)) {
                    Storage::disk('public')->makeDirectory($fullPath);
                }

                // อัปโหลดไฟล์ไปยัง disk ที่กำหนด
                $path = $file->storeAs($fullPath, $uniqueName, 'public');

                // ตรวจสอบผลลัพธ์ของการอัปโหลด
                if ($path) {
                    labourFileModel::where('labour_file_id', $request->labour_file_id[$key])->update(['labour_file_path' => $uniqueName]);
                } else {
                    // อัปโหลดไม่สำเร็จ
                }
            }
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
                ]);
            }
        }
      


        
        

        $counFile = labourFileModel::where('labour_id', $labourModel->labour_id)->count('labour_file_id');
        $counFileNotNull = labourFileModel::where('labour_id', $labourModel->labour_id)
            ->whereNotNull('labour_file_path')
            ->count('labour_file_id');
        $labourModel->update(['labour_file_count' => $counFile, 'labour_file_list' => $counFileNotNull]);


      
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
        $labour->update(['labour_cid_results_file' => null]);
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
        $labour->update(['labour_visa_file' => null]);
    }

    return redirect()->back()->with('success', 'ลบไฟล์ VISA เรียบร้อยแล้ว');
}


    public function store(Request $request)
    {
        $checkLabour = null;

        $checkLabour = labourModel::orWhere(function($query) use ($request) {
            $query->where('labour_firstname', $request->labour_firstname)
                  ->where('labour_lastname', $request->labour_lastname);
        })
        ->first();

       

        if (empty($checkLabour)) {
            $request->merge(['created_by' => Auth::user()->name]);
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

            $labourModel->update(['labour_path' => $folderPath, 'labour_file_count' => $filecount, 'labour_file_list' => 0]);

            if ($labourModel) {
                foreach ($listfiles as $list) {
                    labourFileModel::create([
                        'labour_file_name' => $list->list_file_name,
                        'labour_file_note' => $list->list_file_note,
                        'labour_file_path' => null,
                        'list_file_id' => $list->list_file_id,
                        'labour_id' => $labourModel->labour_id,
                        'labour_passport_number' => $labourModel->labour_passport_number,
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
                        ]);
                    }
                }
            }
        } else {
            dd('ข้อมูลคนงานซ้ำในระบบกรุณาตรวสอบข้อมูล : ' . $request->labour_firstname . ' ' . $request->labour_lastname);
        }
        return redirect()->back();
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


  

}
