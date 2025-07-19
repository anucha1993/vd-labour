<?php

namespace App\Http\Controllers\labours;

use Illuminate\Http\Request;
use App\Models\labours\labourModel;
use App\Http\Controllers\Controller;

class labourPrintController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('auth');
    }
    public function print($id)
    {
        $labour = labourModel::with([
            'customer',
            'cid',
            'country',
            'jobGroup',
            'position',
            'locationTest',
            'staff',
            'staffSub',
            'labourFile'
        ])->findOrFail($id);
    
        return view('labours.print-profile', compact('labour'));
    }
}
