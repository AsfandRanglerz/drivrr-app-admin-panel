<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;

class UserPermssionController extends Controller
{
    public function index()
    {
        $users = User::all();
        $permissions = Permission::all();
        return view('admin.subadmin.index', compact('users,permsssions'));
    }
}
