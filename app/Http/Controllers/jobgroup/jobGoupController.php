<?php

namespace App\Http\Controllers\jobgroup;

use App\Http\Controllers\Controller;
use App\Models\positions\positionModel;
use Illuminate\Http\Request;

class jobGoupController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function ajaxSelectPosition(Request $request)
    {
        $position = positionModel::where('job_group_id',$request->jobgroup)->get();

        return response()->json($position);
    }
}
