<?php

namespace App\Http\Controllers\labours;

use Illuminate\Http\Request;
use App\Models\staff\staffModel;
use App\Http\Controllers\Controller;
use App\Models\country\countryModel;
use App\Models\customers\customerModel;
use App\Models\examinations\examinationRoundModel;
use App\Models\files\fileManageModel;
use App\Models\files\labourFileModel;
use App\Models\files\listFileModel;
use App\Models\jobgroup\jobGroupModel;
use App\Models\labours\labourModel;
use Illuminate\Support\Facades\Storage;
use App\Models\locationTest\locationTestModel;
use App\Models\positions\positionModel;
use Illuminate\Support\Facades\Auth;

class labourController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $labours = labourModel::leftjoin('staff','staff.staff_id','labours.labour_staff')
        
        ->latest('labours.created_at')->get();
        
        return view('labours.index',compact('labours'));
    }

    public function edit(labourModel $labourModel)
    {
        $country = countryModel::where('country_status', 'active')->latest()->get();
        $jobGroup = jobGroupModel::where('job_group_status', 'active')->latest()->get();
        $locationtest = locationTestModel::where('location_test_status', 'active')->get();
        $staffs = staffModel::where('staff_status', 'active')->get();
        $fileManage = fileManageModel::where('file_manage_status', 'active')->get();
        $examinationRound = examinationRoundModel::where('examination_round_status','active')->latest()->get();
        $position = positionModel::where('position_id',$labourModel->labour_position)->first();
        $labourfiles = labourFileModel::where('labour_id',$labourModel->labour_id)->get();
        $customers = customerModel::where('customer_status', 'active')->get();
        return view('labours.form-edit',compact('customers','labourModel','country', 'jobGroup', 'locationtest', 'staffs', 'fileManage','examinationRound','position','labourfiles'));
    }

    public function create()
    {
        $country = countryModel::where('country_status', 'active')->latest()->get();
        $jobGroup = jobGroupModel::where('job_group_status', 'active')->latest()->get();
        $locationtest = locationTestModel::where('location_test_status', 'active')->get();
        $staffs = staffModel::where('staff_status', 'active')->get();
        $fileManage = fileManageModel::where('file_manage_status', 'active')->get();
        $customers = customerModel::where('customer_status', 'active')->get();

        $examinationRound = examinationRoundModel::where('examination_round_status','active')->latest()->get();
        return view('labours.form-create', compact('country', 'jobGroup', 'locationtest', 'staffs', 'fileManage','examinationRound','customers'));
    }

    public function update(labourModel $labourModel, Request $request)
    {
 
 
        $labourModel->update($request->all());
        $files = $request->file('files');
        if($files){
            foreach ($files  as $key => $file) {
                // สร้างชื่อไฟล์ที่ไม่ซ้ำกัน
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME); // ชื่อไฟล์เดิมโดยไม่มีนามสกุล
                $extension = $file->getClientOriginalExtension(); // นามสกุลไฟล์
                $uniqueName = $request->labour_file_name[$key] . '_' . $labourModel->labour_firstname .'_'.$labourModel->labour_lastname . '.' . $extension; // แก้ไขให้ใช้ $labourModel->id แทน $labourModel
        
                // อัปโหลดไฟล์ไปยัง disk ที่กำหนด
                $path = $file->storeAs($labourModel->labour_path, $uniqueName, 'public');
        
                // ตรวจสอบผลลัพธ์ของการอัปโหลด
                if ($path) {
                    labourFileModel::where('labour_file_id',$request->labour_file_id[$key])->update(['labour_file_path' => $uniqueName]);
                   
                } else {
                    // อัปโหลดไม่สำเร็จ
                }
            }
    
        }
        

        return redirect()->back()->with('success','Updated Labour Successfully.');
    }

    public function store(Request $request)
    {
        $checkLabour = labourModel::where('labour_passport_number',$request->labour_passport_number)->first();
        if(empty($checkLabour)) {
            $request->merge(['created_by' => Auth::user()->name]);
            $request->merge(['labour_folder_year' => date('Y')]);
    
            $labourModel = labourModel::create($request->all());
    
            $country = countryModel::where('country_id', $labourModel->labour_country)->first();
            $jobGroup = jobGroupModel::where('job_group_id', $labourModel->labour_job_group)->first();
            $position = positionModel::where('position_id',$labourModel->labour_position)->first();
    
            $folderYear = $labourModel->labour_folder_year;
            $countryName =  $country->country_name_en;
            $jobGroupName = $jobGroup->job_group_name;
            $examinationRound = $labourModel->labour_examination;
            $folderNameLabour = $labourModel->labour_firstname.'_'.$labourModel->labour_lastname;
            $positionName = $position->position_name;
            $folderPath = $folderYear.'\\BACKUP\\'.$labourModel->labour_firstname.'_'.$labourModel->labour_lastname;
    
           
            //สร้าง Forlder 
            if (!Storage::disk('public')->exists($folderPath)) {
                Storage::disk('public')->makeDirectory($folderPath);
            }
            

            //สร้างรายการเอกสาร
            $listfiles = listFileModel::where('file_manage_id',$labourModel->labour_location_doc)->get();
            $filecount = $listfiles->count();

            $labourModel->update(['labour_path'=>$folderPath,'labour_file_count' => $filecount, 'labour_file_list' => 0]);

            if($labourModel){
                foreach($listfiles as $list){
                    labourFileModel::create([
                     'labour_file_name' =>$list->list_file_name,
                     'labour_file_path' => NULL,
                     'list_file_id' => $list->list_file_id,
                     'labour_id' => $labourModel->labour_id ,
                     'labour_passport_number' => $labourModel->labour_passport_number,
                    ]);
                }
            }

        }else{
            dd('ข้อมูลคนงานซ้ำในระบบกรุณาตรวสอบข้อมูล : '.$request->labour_firstname.' '.$request->labour_lastname);
        }


        return redirect()->back();
    }

    // public function createFolder()
    // {
    //     $folderPath = 'testFolder';
    
    //     if (!Storage::disk('labour_documents')->exists($folderPath)) {
    //         Storage::disk('labour_documents')->makeDirectory($folderPath);
    //     }
    // }
    
}
