<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserRoleController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->get();
        $roles = Role::all();
        return view('users.index', compact('users', 'roles'));
    }

    public function assignRole(Request $request)
    {
        $user = User::find($request->user_id);
        $user->syncRoles($request->roles);
        return redirect()->route('users.index')->with('success', 'Role assigned to user successfully!');
    }
}
