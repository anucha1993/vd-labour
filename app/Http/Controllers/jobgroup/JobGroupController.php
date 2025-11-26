<?php

namespace App\Http\Controllers\jobgroup;

use App\Http\Controllers\Controller;
use App\Models\jobgroup\jobGroupModel;
use Illuminate\Http\Request;

class JobGroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view job-group')->only(['index', 'show']);
        $this->middleware('permission:create job-group')->only(['create', 'store']);
        $this->middleware('permission:update job-group')->only(['edit', 'update']);
        $this->middleware('permission:delete job-group')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jobGroups = jobGroupModel::all();
        return view('jobgroups.index', compact('jobGroups'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('jobgroups.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'job_group_name' => 'required|string|max:255',
            'job_group_name_th' => 'required|string|max:255',
            'job_group_status' => 'required|in:active,disable',
        ]);

        jobGroupModel::create($request->all());

        return redirect()->route('jobgroups.index')
            ->with('success', 'เพิ่มกลุ่มงานเรียบร้อยแล้ว');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $jobGroup = jobGroupModel::findOrFail($id);
        return view('jobgroups.show', compact('jobGroup'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $jobGroup = jobGroupModel::findOrFail($id);
        return view('jobgroups.edit', compact('jobGroup'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'job_group_name' => 'required|string|max:255',
            'job_group_name_th' => 'required|string|max:255',
            'job_group_status' => 'required|in:active,disable',
        ]);

        $jobGroup = jobGroupModel::findOrFail($id);
        $jobGroup->update($request->all());

        return redirect()->route('jobgroups.index')
            ->with('success', 'แก้ไขกลุ่มงานเรียบร้อยแล้ว');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $jobGroup = jobGroupModel::findOrFail($id);
        $jobGroup->delete();

        return redirect()->route('jobgroups.index')
            ->with('success', 'ลบกลุ่มงานเรียบร้อยแล้ว');
    }
}
