<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view role', ['only' => ['index']]);
        $this->middleware('permission:create role', ['only' => ['create', 'store']]);
        $this->middleware('permission:update role', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete role', ['only' => ['destroy']]);
    }
    

    public function index()
    {
        $roles = Role::with(['permissions', 'users'])->get();
        $permissions = Permission::all();
        return view('roles.index', compact('roles', 'permissions'));
    }

    public function create()
    {
        $permissions = Permission::all();
        return view('roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'role_name' => 'required|unique:roles,name',
        ]);

        $role = Role::create(['name' => $request->role_name]);
        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }
        return redirect()->route('roles.index')->with('success', 'Role created successfully!');
    }

    public function edit($id)
    {
        $role = Role::with(['permissions', 'users'])->findOrFail($id);
        $permissions = Permission::all();
        return view('roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $request->validate([
            'role_name' => 'required|unique:roles,name,' . $role->id,
        ]);

        $role->name = $request->role_name;
        $role->save();

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('roles.index')->with('success', 'แก้ไข Role เรียบร้อยแล้ว');
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        
        // ป้องกันการลบ admin role
        if ($role->name === 'admin') {
            return redirect()->route('roles.index')->with('error', 'ไม่สามารถลบ Admin Role ได้');
        }
        
        // ตรวจสอบว่ามีผู้ใช้ใน Role นี้หรือไม่
        if ($role->users()->count() > 0) {
            return redirect()->route('roles.index')->with('error', 'ไม่สามารถลบ Role ที่มีผู้ใช้งานอยู่ได้ กรุณาย้ายผู้ใช้ออกก่อน');
        }
        
        $role->delete();
        return redirect()->route('roles.index')->with('success', 'ลบ Role เรียบร้อยแล้ว');
    }
}
