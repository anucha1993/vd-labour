<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $permissions = Permission::all();
        return view('permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('permissions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'permission_name' => 'required|unique:permissions,name',
        ]);

        Permission::create(['name' => $request->permission_name]);

        return redirect()->route('permissions.index')->with('success', 'เพิ่ม Permission เรียบร้อยแล้ว');
    }

    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);
        
        // ตรวจสอบว่ามี Role ใช้งาน Permission นี้หรือไม่
        if ($permission->roles()->count() > 0) {
            return redirect()->route('permissions.index')
                ->with('error', 'ไม่สามารถลบ Permission ที่มี Role ใช้งานอยู่ได้');
        }
        
        $permission->delete();
        return redirect()->route('permissions.index')->with('success', 'ลบ Permission เรียบร้อยแล้ว');
    }
}
