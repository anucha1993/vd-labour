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
        $labours = labourModel::leftjoin('staff', 'staff.staff_id', 'labours.labour_staff')

            ->latest('labours.created_at')
            ->get();

        return view('labours.index', compact('labours'));
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
        $labourfiles = labourFileModel::where('labour_id', $labourModel->labour_id)->get();
        $customers = customerModel::where('customer_status', 'active')->get();

        $fileID = $labourfiles->pluck('list_file_id')->toArray();

        $listFiles = listFileModel::whereNotIn('list_file_id', $fileID)->get();
        


        return view('labours.form-edit', compact('listFiles','customers', 'labourModel', 'country', 'jobGroup', 'locationtest', 'staffs', 'fileManage', 'examinationRound', 'position', 'labourfiles'));
    }

    public function create()
    {
        $country = countryModel::where('country_status', 'active')->latest()->get();
        $jobGroup = jobGroupModel::where('job_group_status', 'active')->latest()->get();
        $locationtest = locationTestModel::where('location_test_status', 'active')->get();
        $staffs = staffModel::where('staff_status', 'active')->get();
        $fileManage = fileManageModel::where('file_manage_status', 'active')->get();
        $customers = customerModel::where('customer_status', 'active')->get();

        $examinationRound = examinationRoundModel::where('examination_round_status', 'active')->latest()->get();
        return view('labours.form-create', compact('country', 'jobGroup', 'locationtest', 'staffs', 'fileManage', 'examinationRound', 'customers'));
    }

    public function update(labourModel $labourModel, Request $request)
    {
        $labourModel->update($request->all());
        labourFileModel::where('labour_id',$labourModel->labour_id)->update(['labour_passport_number' => $labourModel->labour_passport_number ]);
        $files = $request->file('files');
        if ($files) {
            foreach ($files as $key => $file) {
                // สร้างชื่อไฟล์ที่ไม่ซ้ำกัน
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME); // ชื่อไฟล์เดิมโดยไม่มีนามสกุล
                $extension = $file->getClientOriginalExtension(); // นามสกุลไฟล์
                $uniqueName = $request->labour_file_name[$key] . '_' . $labourModel->labour_firstname . '_' . $labourModel->labour_lastname . '.' . $extension; // แก้ไขให้ใช้ $labourModel->id แทน $labourModel

                // อัปโหลดไฟล์ไปยัง disk ที่กำหนด
                $path = $file->storeAs($labourModel->labour_path, $uniqueName, 'public');

                // ตรวจสอบผลลัพธ์ของการอัปโหลด
                if ($path) {
                    labourFileModel::where('labour_file_id', $request->labour_file_id[$key])->update(['labour_file_path' => $uniqueName]);
                } else {
                    // อัปโหลดไม่สำเร็จ
                }
            }
        }

        if ($request->labour_customer_old === null && $labourModel->labour_customer !== null) {
            // ดึงข้อมูลที่จำเป็นจากฐานข้อมูล
            $country = countryModel::where('country_id', $labourModel->labour_country)->first();
            $jobGroup = jobGroupModel::where('job_group_id', $labourModel->labour_job_group)->first();
            $position = positionModel::where('position_id', $labourModel->labour_position)->first();
            $customer = customerModel::where('customer_id', $labourModel->labour_customer)->first();

            $folderYear = $labourModel->labour_folder_year;
            $countryName = $country->country_name_en;
            $jobGroupName = $jobGroup->job_group_name;
            $examinationRound = $labourModel->labour_examination;
            $folderNameLabour = $labourModel->labour_firstname . '_' . $labourModel->labour_lastname;
            $positionName = $position->position_name;
            $customerName = $customer->customer_name;

            // ใช้ DIRECTORY_SEPARATOR เพื่อความเข้ากันได้
            $folderPathNew = $folderYear . DIRECTORY_SEPARATOR . $countryName . DIRECTORY_SEPARATOR . $jobGroupName . DIRECTORY_SEPARATOR . $positionName . DIRECTORY_SEPARATOR . $examinationRound . DIRECTORY_SEPARATOR . $customerName . DIRECTORY_SEPARATOR . $folderNameLabour;
            $folderPathOld = $labourModel->labour_path;

            // ตรวจสอบว่าโฟลเดอร์ปลายทางมีอยู่หรือไม่
            if (!Storage::disk('public')->exists($folderPathNew)) {
                // สร้างโฟลเดอร์ปลายทาง
                Storage::disk('public')->makeDirectory($folderPathNew);
            }

            function copyDirectory($source, $destination)
            {
                if (!is_dir($source) || !is_dir($destination)) {
                    return false;
                }

                // ตรวจสอบว่าโฟลเดอร์ต้นทางมีไฟล์หรือไม่
                $files = scandir($source);
                if (count($files) <= 2) {
                    // โฟลเดอร์ว่างเปล่า (มีแค่ '.' และ '..')
                    return false;
                }

                if (!is_dir($destination)) {
                    if (!mkdir($destination, 0755, true)) {
                        echo 'Failed to create directory: ' . $destination;
                        return false;
                    }
                }

                foreach ($files as $file) {
                    if ($file !== '.' && $file !== '..') {
                        $sourceFile = $source . DIRECTORY_SEPARATOR . $file;
                        $destinationFile = $destination . DIRECTORY_SEPARATOR . $file;

                        if (is_dir($sourceFile)) {
                            copyDirectory($sourceFile, $destinationFile);
                        } else {
                            if (!copy($sourceFile, $destinationFile)) {
                                echo 'Failed to copy file: ' . $sourceFile;
                            }
                        }
                    }
                }

                return true;
            }

            $sourceDirectory = env('LOCATION_PATH') . DIRECTORY_SEPARATOR . $folderPathOld; // กำหนดตำแหน่งโฟลเดอร์ต้นทาง
            $destinationDirectory = env('LOCATION_PATH') . DIRECTORY_SEPARATOR . $folderPathNew; // กำหนดตำแหน่งโฟลเดอร์ปลายทาง
            $labourModel->update(['labour_path' => $folderPathNew]);

            // ตรวจสอบการคัดลอกและรายงานผลลัพธ์
            if (copyDirectory($sourceDirectory, $destinationDirectory)) {
              
                // ตรวจสอบว่าโฟลเดอร์ปลายทางมีอยู่หรือไม่
                if (Storage::disk('public')->exists($folderPathOld)) {
                    // สร้างโฟลเดอร์ปลายทาง
                    Storage::disk('public')->deleteDirectory($folderPathOld);
                }
            } else {
                echo 'No files to copy or failed to copy.';
            }
        }

        return redirect()->back()->with('success', 'Updated Labour Successfully.');
    }

    public function store(Request $request)
    {
         $checkLabour = NULL;

         if($request->labour_passport_number !== NULL){
            $checkLabour = labourModel::where('labour_passport_number', $request->labour_passport_number)->first();
         }

       
        if (empty($checkLabour)) {
            $request->merge(['created_by' => Auth::user()->name]);
            $request->merge(['labour_folder_year' => date('Y')]);

            $labourModel = labourModel::create($request->all());

            $country = countryModel::where('country_id', $labourModel->labour_country)->first();
            $jobGroup = jobGroupModel::where('job_group_id', $labourModel->labour_job_group)->first();
            $position = positionModel::where('position_id', $labourModel->labour_position)->first();
            $customer = customerModel::where('customer_id', $labourModel->labour_customer)->first();

            $folderYear = $labourModel->labour_folder_year;
            $countryName = $country->country_name_en;
            $jobGroupName = $jobGroup->job_group_name;
            $examinationRound = $labourModel->labour_examination;
            $folderNameLabour = $labourModel->labour_firstname . '_' . $labourModel->labour_lastname;
            $positionName = $position->position_name;
          

            if (empty($request->labour_customer)) {
                $folderPath = $folderYear . '\\BACKUP\\' . $labourModel->labour_firstname . '_' . $labourModel->labour_lastname;
            } else {
                $customerName = $customer->customer_name ;
                $folderPath = $folderYear . '\\' . $countryName . '\\' . $jobGroupName . '\\' . $positionName . '\\' . $examinationRound . '\\' . $customerName . '\\' . $folderNameLabour;
            }
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
            }
        } else {
            dd('ข้อมูลคนงานซ้ำในระบบกรุณาตรวสอบข้อมูล : ' . $request->labour_firstname . ' ' . $request->labour_lastname);
        }
        return redirect()->back();
    }


    public function CombinePDF(labourModel $labourModel, Request $request)
    {
        $labourfiles = labourFileModel::where('labour_id', $labourModel->labour_id)->get();
        return view('labours.modal-combinePDF',compact('labourfiles','labourModel'));
    }
}
