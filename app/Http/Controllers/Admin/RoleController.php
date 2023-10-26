<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        $permissions = Permission::all();
        return view('admin.roles-permission.index', compact('roles', 'permissions'));
    }
    // public function store(Request $request)
    // {

    //     $request->validate([
    //         'role' => 'required|unique:roles,name|max:255',
    //     ]);

    //     $role = Role::create(['name' => $request->input('role')]);

    //     return response()->json([
    //         'status' => 200,
    //         'messages' => 'Role Addedd Successfully',
    //     ]);
    // }

    // public function assignPermissions(Request $request, Role $role)
    // {
    //     $permissions = $request->input('permissions');

    //     $role->syncPermissions($permissions);

    //     return response()->json([
    //         'status' => 200,
    //         'messages' => 'Permission Assign Successfully',
    //     ]);
    // }

    // public function updatePermissions(Request $request, Role $role)
    // {
    //     $permissions = $request->input('permissions');

    //     $role->syncPermissions($permissions);

    //     return response()->json([
    //         'status' => 200,
    //         'messages' => 'Permission Updated Successfully',
    //     ]);
    // }
}
