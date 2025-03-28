<?php

namespace App\Http\Controllers\formExport;

use Illuminate\Http\Request;
use App\Models\staff\staffModel;
use App\Models\labours\labourModel;
use App\Http\Controllers\Controller;
use App\Models\country\countryModel;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\jobgroup\jobGroupModel;
use App\Models\customers\customerModel;
use App\Exports\labourExport\labourExport;
use App\Models\examinations\examinationRoundModel;

class labourFormExportController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $examinationRound = examinationRoundModel::where('examination_round_status', 'active')->latest()->get();
        $jobGroup = jobGroupModel::where('job_group_status', 'active')->latest()->get();
        $customers = customerModel::latest()->get();
        $staffs = staffModel::where('staff_status', 'active')->get();
        $country = countryModel::where('country_status', 'active')->get();

        $labour_disease_date_start = $request->labour_disease_date_start;
        $labour_disease_date_end = $request->labour_disease_date_end;
        $labour_cid_start = $request->labour_cid_start;
        $labour_cid_end = $request->labour_cid_end;
        $labour_country = $request->labour_country;
        $labour_job_group = $request->labour_job_group;
        $labour_staff = $request->labour_staff;
        $labour_status = $request->labour_status;
        $labour_customer = $request->labour_customer;
        $labour_examination = $request->labour_examination;
        $labour_examination = $request->labour_examination;
        $labour_cid_deposit_status = $request->labour_cid_deposit_status;

        //dd($labour_customer);
        $query = labourModel::with('customer');
        // ค้นหานายจ้าง
        if ($labour_customer) {
            $query->where('labour_customer', $labour_customer);
        }
        // ค้นหาโรงงาน
        if ($labour_customer === 'null') {
            $query->whereNull('labour_customer');
        }
        //ค้นหาผลโรคหมดอายุ
        if (!empty($labour_disease_date_start) && !empty($labour_disease_date_end)) {
            $query->whereBetween('labour_disease_expriry', [$labour_disease_date_start, $labour_disease_date_end]);
        }

        // ค้นหาผล CID หมดอายุ
        if (!empty($labour_cid_start) && !empty($labour_cid_end)) {
            $query->whereBetween('labour_cid_expriry', [$labour_cid_start, $labour_cid_end]);
        }

        // ค้นหาประเทศ
        if ($labour_country && $labour_country != 'all') {
            $query->where('labour_country', $labour_country);
        }

        // ค้นหาประเภทงาน
        if ($labour_job_group && $labour_job_group != 'all') {
            $query->where('labour_job_group', $labour_job_group);
        }

        // ค้นหาชื่อสรรหา
        if ($labour_staff && $labour_staff != 'all') {
            $query->where('labour_staff', $labour_staff);
        }

        // // ค้นหาสถานะ
        if ($labour_status && $labour_status != 'all') {
            $query->where('labour_status', $labour_status);
        }

        //labour_examination

        if (is_array($labour_examination)) {
            $query->whereIn('labour_examination', $labour_examination);
        }
        // //labour_examination

        if ($labour_cid_deposit_status && $labour_cid_deposit_status != 'all') {
            $query->where('labour_cid_deposit_status', $labour_cid_deposit_status);
        }
        if($request->all()) { // ตรวจสอบว่า request มีข้อมูลใดๆ
            $labours = $query->latest()->get();
        } else {
            $labours = $query->latest()->limit(1)->get();
        }
       

        return view('exports/form-export-labour', compact('jobGroup', 'customers', 'staffs', 'country', 'examinationRound', 'labours','request'));
    }

    public function export(Request $request)
    {
        //dd($request);
        $labour_disease_date_start = $request->labour_disease_date_start;
        $labour_disease_date_end = $request->labour_disease_date_end;
        $labour_cid_start = $request->labour_cid_start;
        $labour_cid_end = $request->labour_cid_end;
        $labour_country = $request->labour_country;
        $labour_job_group = $request->labour_job_group;
        $labour_staff = $request->labour_staff;
        $labour_status = $request->labour_status;
        $labour_customer = $request->labour_customer;
        $labour_examination = $request->labour_examination;
        $labour_examination = $request->labour_examination;
        $labour_cid_deposit_status = $request->labour_cid_deposit_status;

        $labours = labourModel::get();

        //dd($labour_cid_deposit_status);

        // return Excel::download(new labourExport(
        //     $labour_disease_date_start,
        //     $labour_disease_date_end,
        //     $labour_cid_start,
        //     $labour_cid_end,
        //     $labour_country,
        //     $labour_job_group,
        //     $labour_staff,
        //     $labour_status,
        //     $labour_customer,
        //     $labour_examination,
        //     $labour_cid_deposit_status
        // ),'labour_'.date('d-m-Y').'.xlsx');
    }
}
