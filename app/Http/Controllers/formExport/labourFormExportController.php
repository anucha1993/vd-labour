<?php

namespace App\Http\Controllers\formExport;

use App\Exports\labourExport\labourExport;
use Illuminate\Http\Request;
use App\Models\staff\staffModel;
use App\Http\Controllers\Controller;
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

        return view('exports/form-export-labour',compact('jobGroup','customers','staffs'));
    }

    public function export(Request $request)
    {
        return Excel::download(new labourExport,'labour_'.date('d-m-Y').'.xlsx');
    }
    
}
