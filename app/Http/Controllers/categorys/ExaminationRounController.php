<?php

namespace App\Http\Controllers\categorys;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\examinations\examinationRoundModel;

class ExaminationRounController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware('permission:view examination-round', ['only' => ['index']]);
        $this->middleware('permission:create examination-round', ['only' => ['store']]);
        $this->middleware('permission:update examination-round', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete examination-round', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of examination rounds
     */
    public function index()
    {
        $ExaminationRoun = examinationRoundModel::orderBy('created_at', 'DESC')->get();
        return view('categorys.ExaminationRoun.index', compact('ExaminationRoun'));
    }

    /**
     * Store a newly created examination round
     */
    public function store(Request $request)
    {
        $request->validate([
            'examination_round_name' => 'required|date',
            'examination_round_status' => 'required|in:active,disable',
        ]);

        examinationRoundModel::create([
            'examination_round_name' => $request->examination_round_name,
            'examination_round_note' => $request->examination_round_note,
            'examination_round_status' => $request->examination_round_status,
        ]);

        return redirect()->back()->with('success', 'เพิ่มรอบสอบสำเร็จ');
    }

    /**
     * Show the form for editing the examination round
     */
    public function edit(examinationRoundModel $examinationRoundModel)
    {
        $ExaminationRoun = examinationRoundModel::orderBy('created_at', 'DESC')->get();
        $editItem = $examinationRoundModel;
        return view('categorys.ExaminationRoun.index', compact('ExaminationRoun', 'editItem'));
    }

    /**
     * Update the specified examination round
     */
    public function update(Request $request, examinationRoundModel $examinationRoundModel)
    {
        $request->validate([
            'examination_round_name' => 'required|date',
            'examination_round_status' => 'required|in:active,disable',
        ]);

        $examinationRoundModel->update([
            'examination_round_name' => $request->examination_round_name,
            'examination_round_note' => $request->examination_round_note,
            'examination_round_status' => $request->examination_round_status,
        ]);

        return redirect()->route('category.examination')->with('success', 'แก้ไขรอบสอบสำเร็จ');
    }

    /**
     * Remove the specified examination round
     */
    public function destroy(examinationRoundModel $examinationRoundModel)
    {
        $examinationRoundModel->delete();
        return redirect()->back()->with('success', 'ลบรอบสอบสำเร็จ');
    }

    /**
     * Cancel the examination round (soft disable)
     */
    public function cancel(examinationRoundModel $examinationRoundModel)
    {
        $examinationRoundModel->update(['examination_round_status' => 'cancel']);
        return redirect()->back()->with('success', 'ยกเลิกรอบสอบสำเร็จ');
    }
}
