<?php

namespace App\Http\Controllers\staff;

use App\Http\Controllers\Controller;
use App\Models\staff\staffModel;
use App\Exports\StaffExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class StaffController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view staff')->only(['index', 'show']);
        $this->middleware('permission:create staff')->only(['create', 'store']);
        $this->middleware('permission:update staff')->only(['edit', 'update']);
        $this->middleware('permission:delete staff')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = staffModel::with('user');
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('staff_name', 'like', "%{$search}%")
                  ->orWhere('staff_nickname', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('staff_status', $request->status);
        }

        $staffs = $query->orderBy('staff_name', 'asc')->paginate(15);
        
        return view('staff.staff.index', compact('staffs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = \App\Models\User::orderBy('name', 'asc')->get();
        return view('staff.staff.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'staff_name' => 'required|string|max:255',
            'staff_nickname' => 'nullable|string|max:100',
            'staff_status' => 'required|in:active,inactive',
        ], [
            'staff_name.required' => 'กรุณากรอกชื่อเจ้าหน้าที่',
            'staff_name.max' => 'ชื่อเจ้าหน้าที่ต้องไม่เกิน 255 ตัวอักษร',
            'staff_nickname.max' => 'ชื่อเล่นต้องไม่เกิน 100 ตัวอักษร',
            'staff_status.required' => 'กรุณาเลือกสถานะ',
            'staff_status.in' => 'สถานะไม่ถูกต้อง',
        ]);

        try {
            DB::beginTransaction();
            
            staffModel::create($request->all());
            
            DB::commit();
            return redirect()->route('staff.index')->with('success', 'เพิ่มข้อมูลเจ้าหน้าที่สำเร็จ');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $staff = staffModel::findOrFail($id);
        return view('staff.staff.show', compact('staff'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $staff = staffModel::findOrFail($id);
        $users = \App\Models\User::orderBy('name', 'asc')->get();
        return view('staff.staff.edit', compact('staff', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $staff = staffModel::findOrFail($id);
        
        $request->validate([
            'staff_name' => 'required|string|max:255',
            'staff_nickname' => 'nullable|string|max:100',
            'staff_status' => 'required|in:active,inactive',
        ], [
            'staff_name.required' => 'กรุณากรอกชื่อเจ้าหน้าที่',
            'staff_name.max' => 'ชื่อเจ้าหน้าที่ต้องไม่เกิน 255 ตัวอักษร',
            'staff_nickname.max' => 'ชื่อเล่นต้องไม่เกิน 100 ตัวอักษร',
            'staff_status.required' => 'กรุณาเลือกสถานะ',
            'staff_status.in' => 'สถานะไม่ถูกต้อง',
        ]);

        try {
            DB::beginTransaction();
            
            $staff->update($request->all());
            
            DB::commit();
            return redirect()->route('staff.index')->with('success', 'อัปเดตข้อมูลเจ้าหน้าที่สำเร็จ');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $staff = staffModel::findOrFail($id);
            
            // Check if this staff is being used by leads
            $usageCount = DB::table('leads')->where('staff_id', $id)->count();
            
            if ($usageCount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => "ไม่สามารถลบได้ เนื่องจากมี Lead จำนวน {$usageCount} รายการที่ใช้เจ้าหน้าที่นี้"
                ]);
            }
            
            $staff->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'ลบข้อมูลเจ้าหน้าที่สำเร็จ'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Export staff list to Excel
     */
    public function export()
    {
        return Excel::download(new StaffExport, 'staff-list-' . date('Y-m-d') . '.xlsx');
    }
}