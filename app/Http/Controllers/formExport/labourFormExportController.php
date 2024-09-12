<?php

namespace App\Http\Controllers\formExport;

use App\Exports\labourExport\labourExport;
use Illuminate\Http\Request;
use App\Models\staff\staffModel;
use App\Http\Controllers\Controller;
use App\Models\country\countryModel;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\jobgroup\jobGroupModel;
use App\Models\customers\customerModel;

class labourFormExportController extends Controller
{
    //

    public function index()
    {
        $jobGroup = jobGroupModel::where('job_group_status', 'active')->latest()->get();
        $customers = customerModel::latest()->get();
        $staffs = staffModel::where('staff_status', 'active')->get();
        $country = countryModel::where('country_status', 'active')->get();
        return view('exports/form-export-labour',compact('jobGroup','customers','staffs','country'));
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

        return Excel::download(new labourExport(
            $labour_disease_date_start,
            $labour_disease_date_end,
            $labour_cid_start,
            $labour_cid_end,
            $labour_country,
            $labour_job_group,
            $labour_staff,
            $labour_status,
            $labour_customer
        ),'labour_'.date('d-m-Y').'.xlsx');
    }

    
}
