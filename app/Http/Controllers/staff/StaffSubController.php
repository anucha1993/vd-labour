<?php

namespace App\Http\Controllers\staff;

use App\Http\Controllers\Controller;
use App\Models\staff\staffSubModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StaffSubController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view staff-sub')->only(['index', 'show']);
        $this->middleware('permission:create staff-sub')->only(['create', 'store']);
        $this->middleware('permission:update staff-sub')->only(['edit', 'update']);
        $this->middleware('permission:delete staff-sub')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = staffSubModel::query();
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('staff_sub_name', 'like', "%{$search}%")
                  ->orWhere('staff_sub_phone', 'like', "%{$search}%")
                  ->orWhere('staff_sub_staff', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('staff_sub_status', $request->status);
        }

        $staffSubs = $query->orderBy('staff_sub_name', 'asc')->paginate(15);
        
        return view('staff.staff_sub.index', compact('staffSubs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('staff.staff_sub.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'staff_sub_name' => 'required|string|max:255',
            'staff_sub_phone' => 'nullable|string|max:20',
            'staff_sub_staff' => 'nullable|string|max:255',
            'staff_sub_status' => 'required|in:active,inactive',
        ], [
            'staff_sub_name.required' => 'กรุณากรอกชื่อสายหางาน',
            'staff_sub_name.max' => 'ชื่อสายหางานต้องไม่เกิน 255 ตัวอักษร',
            'staff_sub_phone.max' => 'เบอร์โทรศัพท์ต้องไม่เกิน 20 ตัวอักษร',
            'staff_sub_staff.max' => 'ชื่อเจ้าหน้าที่ต้องไม่เกิน 255 ตัวอักษร',
            'staff_sub_status.required' => 'กรุณาเลือกสถานะ',
            'staff_sub_status.in' => 'สถานะไม่ถูกต้อง',
        ]);

        try {
            DB::beginTransaction();
            
            staffSubModel::create($request->all());
            
            DB::commit();
            return redirect()->route('staff-sub.index')->with('success', 'เพิ่มข้อมูลสายหางานสำเร็จ');
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
        $staffSub = staffSubModel::findOrFail($id);
        return view('staff.staff_sub.show', compact('staffSub'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $staffSub = staffSubModel::findOrFail($id);
        return view('staff.staff_sub.edit', compact('staffSub'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $staffSub = staffSubModel::findOrFail($id);
        
        $request->validate([
            'staff_sub_name' => 'required|string|max:255',
            'staff_sub_phone' => 'nullable|string|max:20',
            'staff_sub_status' => 'required|in:active,inactive',
        ], [
            'staff_sub_name.required' => 'กรุณากรอกชื่อสายหางาน',
            'staff_sub_name.max' => 'ชื่อสายหางานต้องไม่เกิน 255 ตัวอักษร',
            'staff_sub_phone.max' => 'เบอร์โทรศัพท์ต้องไม่เกิน 20 ตัวอักษร',
            'staff_sub_status.required' => 'กรุณาเลือกสถานะ',
            'staff_sub_status.in' => 'สถานะไม่ถูกต้อง',
        ]);

        try {
            DB::beginTransaction();
            
            $staffSub->update($request->all());
            
            DB::commit();
            return redirect()->route('staff-sub.index')->with('success', 'อัปเดตข้อมูลสายหางานสำเร็จ');
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
            $staffSub = staffSubModel::findOrFail($id);
            
            // Check if this staff_sub is being used by leads
            $usageCount = DB::table('leads')->where('lead_recommender_staff_sub_id', $id)->count();
            
            if ($usageCount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => "ไม่สามารถลบได้ เนื่องจากมี Lead จำนวน {$usageCount} รายการที่ใช้สายหางานนี้"
                ]);
            }
            
            $staffSub->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'ลบข้อมูลสายหางานสำเร็จ'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ]);
        }
    }
}