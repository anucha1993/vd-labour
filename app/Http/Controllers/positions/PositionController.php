<?php

namespace App\Http\Controllers\positions;

use App\Http\Controllers\Controller;
use App\Models\positions\positionModel;
use App\Models\jobgroup\jobGroupModel;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view position')->only(['index', 'show']);
        $this->middleware('permission:create position')->only(['create', 'store']);
        $this->middleware('permission:update position')->only(['edit', 'update']);
        $this->middleware('permission:delete position')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // ดึงข้อมูล Job Groups พร้อมกับจำนวน positions
        $jobGroups = jobGroupModel::where('job_group_status', 1)
            ->withCount(['positions' => function($query) {
                $query->where('position_status', 'active');
            }])
            ->having('positions_count', '>', 0)
            ->orderBy('job_group_name')
            ->get();
            
        return view('positions.index', compact('jobGroups'));
    }
    
    /**
     * Display positions for specific job group
     */
    public function showByJobGroup($jobGroupId)
    {
        $jobGroup = jobGroupModel::findOrFail($jobGroupId);
        $positions = positionModel::with('jobGroup')
            ->where('job_group_id', $jobGroupId)
            ->orderBy('position_name')
            ->get();
            
        return view('positions.by-job-group', compact('jobGroup', 'positions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jobGroups = jobGroupModel::where('job_group_status', 1)->get();
        return view('positions.create', compact('jobGroups'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'position_name' => 'required|string|max:255',
            'position_name_th' => 'required|string|max:255',
            'job_group_id' => 'required|exists:job_group,job_group_id',
            'position_status' => 'required|in:active,disable',
        ]);

        positionModel::create($request->all());

        return redirect()->route('positions.index')
            ->with('success', 'เพิ่มตำแหน่งงานเรียบร้อยแล้ว');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $position = positionModel::with('jobGroup')->findOrFail($id);
        return view('positions.show', compact('position'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $position = positionModel::findOrFail($id);
        $jobGroups = jobGroupModel::where('job_group_status', 1)->get();
        return view('positions.edit', compact('position', 'jobGroups'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'position_name' => 'required|string|max:255',
            'position_name_th' => 'required|string|max:255',
            'job_group_id' => 'required|exists:job_group,job_group_id',
            'position_status' => 'required|in:active,disable',
        ]);

        $position = positionModel::findOrFail($id);
        $position->update($request->all());

        return redirect()->route('positions.index')
            ->with('success', 'แก้ไขตำแหน่งงานเรียบร้อยแล้ว');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $position = positionModel::findOrFail($id);
        $position->delete();

        return redirect()->route('positions.index')
            ->with('success', 'ลบตำแหน่งงานเรียบร้อยแล้ว');
    }
}
