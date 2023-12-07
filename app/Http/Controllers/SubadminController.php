<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Subadmin;
use App\Mail\verifysubAdmin;
use Illuminate\Http\Request;
use App\Mail\VerifyUserEmail;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class SubadminController extends Controller
{

    public function index()
    {
        $data = User::whereHas('roles', function ($q) {
            $q->where('name', 'subadmin');
        })->orderBy('id', 'DESC')->get();

        $users = User::orderBy('id', 'DESC')->get();
        $permissions = Permission::all();

        return view('admin.subadmin.index', compact('data', 'permissions'));
    }
    public function create()
    {
        return view('admin.subadmin.create');
    }
    public function store(Request $request)
    {

        $request->validate([
            'fname' => 'required',
            'lname' => 'required',
            'phone' => 'required',
            'email' => 'required|unique:users,email|email',
            'password' => 'required',
        ]);
        $password = $request->password;
        $owner = User::create([
            'fname' => $request->fname,
            'lname' => $request->lname,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($password),
        ]);


        /** assign the role  */
        $owner->roles()->sync(1);

        $message['email'] = $request->email;
        $message['password'] = $password;

        try {
            // Mail::to($request->email)->send(new UserLoginPassword($message));
            return redirect()->route('subadmin.index')->with(['status' => true, 'message' => 'Subadmin Created successfully.']);
        } catch (\Throwable $th) {
            dd($th->getMessage());
            return back()
                ->with(['status' => false, 'message' => $th->getMessage()]);
        }
    }
    public function edit($id)
    {
        $data = User::find($id);
        return view('admin.subadmin.edit', compact('data'));
    }
    public function update(Request $request, $id)
    {

        $request->validate([
            'fname' => 'required',
            'lname' => 'required',
            'phone' => 'required',
            'email' => 'required',
        ]);
        $owner = User::find($id);
        $owner->update([
            'fname' => $request->fname,
            'lname' => $request->lname,
            'phone' => $request->phone,
            'email' => $request->email,
        ]);

        return redirect()->route('subadmin.index')->with(['status' => true, 'message' => 'Subadmin Updated successfully.']);
    }
    public function destroy($id)
    {
        User::destroy($id);
        return redirect()->back()->with(['status' => true, 'message' => 'Subadmin Deleted successfully.']);
    }

    public function status($id)
    {
        $data = User::find($id);
        $user['fname'] = $data->fname;
        $user['email'] = $data->email;
        // return $user;
        $data->update(['is_active' => $data->is_active == 0 ? '1' : '0']);
        $status = $data->is_active;
        Mail::to($data->email)->send(new verifysubAdmin($status));
        return redirect()->back()->with(['status' => true, 'message' => 'Status Updated successfully.']);
    }
    // Permissions

    public function storePermissions(Request $request, $userId)
    {
        $permissions = $request->input('permissions');

        $success = true;

        DB::beginTransaction();

        try {
            foreach ($permissions as $permissionId) {
                // Insert each selected permission for the user
                $insertResult = DB::table('component_permission')->insert([
                    'user_id' => $userId,
                    'permission_id' => $permissionId,
                ]);

                if (!$insertResult) {
                    $success = false;
                    break;
                }
            }

            if ($success) {
                DB::commit();
                return redirect()->back()->with('message', 'Permissions updated successfully');
            } else {
                DB::rollBack();
                return redirect()->back()->with('error', 'An error occurred while updating permissions');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'An error occurred while updating permissions');
        }
    }
    public function updatePermissions(Request $request, $userId)
    {

        $user = User::find($userId);
        if (!$user) {
            return redirect()->back()->with('message', 'User Not Found');
        }
        $permissions = $request->input('permissions');
        $user->syncPermissions($permissions);
        return redirect()->back()->with('message', 'Permissions updated successfully');
    }
}
