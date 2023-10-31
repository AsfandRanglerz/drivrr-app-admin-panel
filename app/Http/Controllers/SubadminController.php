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

        // if ($request->hasFile('image')) {
        //     $file = $request->file('image');
        //     $extension = $file->getClientOriginalExtension();
        //     $filename = time() . '.' . $extension;
        //     $file->move(public_path('admin/assets/images/users/'), $filename);
        //     $image = 'public/admin/assets/images/users/' . $filename;
        // } else {
        //     $image = 'public/admin/assets/images/users/owner.png';
        // }

        /**generate random password */
        // $password = random_int(10000000, 99999999);
        $password = $request->password;
        $owner = User::create([
            'fname' => $request->fname,
            'lname' => $request->lname,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($password),
        ]);
        // 'email' => $request->email,
        // 'role_id'=> 1,
        // ] + ['image' => $image]);

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
        // return $request;
        $request->validate([
            'fname' => 'required',
            'lname' => 'required',
            'phone' => 'required',
            'email' => 'required',
        ]);
        $owner = User::find($id);
        // if ($request->hasfile('image')) {
        //     $destination = 'public/admin/assets/images/users' . $owner->image;
        //     if (File::exists($destination) || File::exists($owner->image)) {
        //         File::delete($destination);
        //         File::delete($owner->image);
        //     }
        //     $file = $request->file('image');
        //     $extension = $file->getClientOriginalExtension();
        //     $filename = time() . '.' . $extension;
        //     $file->move('public/admin/assets/images/users', $filename);
        //     $image = 'public/admin/assets/images/users/' . $filename;
        // } else {
        //     $image = $owner->image;
        // }

        $owner->update([
            'fname' => $request->fname,
            'lname' => $request->lname,
            'phone' => $request->phone,
            'email' => $request->email,
        ]);
        // 'last_name' => $request->last_name,
        // 'designation' => $request->designation,
        //  + ['image' => $image]);

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
// return $permissions;
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
                    break; // Exit the loop if an error occurs
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
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }
        $permissions = $request->input('permissions');
        $user->syncPermissions($permissions);

        return response()->json(['success' => true, 'message' => 'Permissions updated successfully']);
    }
}
