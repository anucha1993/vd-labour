<?php

namespace App\Http\Controllers\categorys;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\examinations\examinationRoundModel;

class ExaminationRounController extends Controller
{
    //

    public function index()
    {
        $ExaminationRoun = examinationRoundModel::where('examination_round_status','active')->orderBy('created_at','DESC')->get();
       return view('categorys.ExaminationRoun.index',compact('ExaminationRoun'));
    }

    public function store(Request $request)
    {
        examinationRoundModel::create($request->all());
        return redirect()->back();
    }

    public function cancel(examinationRoundModel $examinationRoundModel)
    {
        $examinationRoundModel->update(['examination_round_status','cancel']);
        return redirect()->back();
    }

}
