<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Subadmin;
use App\Mail\verifysubAdmin;
use Illuminate\Http\Request;
use App\Mail\VerifyUserEmail;
use App\Mail\subAdminRegistration;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;

class SubadminController extends Controller
{

    // public function index()
    // {
    //     $data = User::whereHas('roles', function ($q) {
    //         $q->where('name', 'subadmin');
    //     })->orderBy('id', 'DESC')->get();

    //     $users = User::orderBy('id', 'DESC')->get();
    //     $permissions = Permission::all();

    //     return view('admin.subadmin.index', compact('data', 'permissions'));
    // }
    // public function create()
    // {
    //     return view('admin.subadmin.create');
    // }
    // public function store(Request $request)
    // {

    //     $request->validate([
    //         'fname' => 'required',
    //         'lname' => 'required',
    //         'phone' => 'required',
    //         'email' => 'required|unique:users,email|email',
    //         'password' => 'required',
    //     ]);
    //     $password = $request->password;
    //     $owner = User::create([
    //         'fname' => $request->fname,
    //         'lname' => $request->lname,
    //         'phone' => $request->phone,
    //         'email' => $request->email,
    //         'password' => Hash::make($password),
    //     ]);


    //     /** assign the role  */
    //     $owner->roles()->sync(1);

    //     $message['email'] = $request->email;
    //     $message['password'] = $password;

    //     try {
    //         // Mail::to($request->email)->send(new UserLoginPassword($message));
    //         return redirect()->route('subadmin.index')->with(['status' => true, 'message' => 'Subadmin Created successfully.']);
    //     } catch (\Throwable $th) {
    //         dd($th->getMessage());
    //         return back()
    //             ->with(['status' => false, 'message' => $th->getMessage()]);
    //     }
    // }
    // public function edit($id)
    // {
    //     $data = User::find($id);
    //     return view('admin.subadmin.edit', compact('data'));
    // }
    // public function update(Request $request, $id)
    // {

    //     $request->validate([
    //         'fname' => 'required',
    //         'lname' => 'required',
    //         'phone' => 'required',
    //         'email' => 'required',
    //     ]);
    //     $owner = User::find($id);
    //     $owner->update([
    //         'fname' => $request->fname,
    //         'lname' => $request->lname,
    //         'phone' => $request->phone,
    //         'email' => $request->email,
    //     ]);

    //     return redirect()->route('subadmin.index')->with(['status' => true, 'message' => 'Subadmin Updated successfully.']);
    // }
    // public function destroy($id)
    // {
    //     User::destroy($id);
    //     return redirect()->back()->with(['status' => true, 'message' => 'Subadmin Deleted successfully.']);
    // }

    // public function status($id)
    // {
    //     $data = User::find($id);
    //     $user['fname'] = $data->fname;
    //     $user['email'] = $data->email;
    //     // return $user;
    //     $data->update(['is_active' => $data->is_active == 0 ? '1' : '0']);
    //     $status = $data->is_active;
    //     Mail::to($data->email)->send(new verifysubAdmin($status));
    //     return redirect()->back()->with(['status' => true, 'message' => 'Status Updated successfully.']);
    // }
    // // Permissions

    // public function storePermissions(Request $request, $userId)
    // {
    //     $permissions = $request->input('permissions');

    //     $success = true;

    //     DB::beginTransaction();

    //     try {
    //         foreach ($permissions as $permissionId) {
    //             // Insert each selected permission for the user
    //             $insertResult = DB::table('component_permission')->insert([
    //                 'user_id' => $userId,
    //                 'permission_id' => $permissionId,
    //             ]);

    //             if (!$insertResult) {
    //                 $success = false;
    //                 break;
    //             }
    //         }

    //         if ($success) {
    //             DB::commit();
    //             return redirect()->back()->with('message', 'Permissions updated successfully');
    //         } else {
    //             DB::rollBack();
    //             return redirect()->back()->with('error', 'An error occurred while updating permissions');
    //         }
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return redirect()->back()->with('error', 'An error occurred while updating permissions');
    //     }
    // }
    // public function updatePermissions(Request $request, $userId)
    // {
    //     $user = User::find($userId);
    //     if (!$user) {
    //         return redirect()->back()->with('message', 'User Not Found');
    //     }
    //     $permissions = $request->input('permissions');
    //     $user->syncPermissions($permissions);
    //     return redirect()->back()->with('message', 'Permissions updated successfully');
    // }
    public function subadminData()
    {
        $subAdmins = User::whereHas('roles', function ($q) {
            $q->where('name', 'subadmin');
        })->latest()->get();
        $json_data["data"] = $subAdmins;
        return json_encode($json_data);
    }
    public function subadminIndex()
    {
        $subAdmins = User::whereHas('roles', function ($q) {
            $q->where('name', 'subadmin');
        })->latest()->get();
        $permissions = Permission::all();
        return view('admin.subadmin.index', compact('subAdmins', 'permissions'));
    }
    public function subadminCreate(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'fname' => 'required|string|max:255',
                'lname' => 'required|string|max:255',
                'email' => 'required|email|unique:users|max:255',
                'password' => 'required|string|min:8|max:255',
                'phone' => 'required|unique:users|min:11',
                'confirmpassword' => 'required|same:password',
                'image' => 'nullable|image|mimes:jpeg,jpg,png|max:1048'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            $subadmin = new User($request->only(['fname', 'lname', 'email', 'phone']));
            $subadmin->password = bcrypt($request->input('password'));
            $subadmin->role_id = 1;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('admin/assets/images/users'), $filename);
                $subadmin->image = 'public/admin/assets/images/users/' . $filename;
            }
            $subadmin->save();
            if ($subadmin) {
                $subadmin->roles()->sync(1);
                // $data['subadminname'] = $subadmin->fname . ' ' . $subadmin->lname;
                // $data['subadminemail'] = $subadmin->email;
                // $data['password'] = $request->password;
                // Mail::to($subadmin->email)->send(new subAdminRegistration($data));
                return response()->json(['alert' => 'success', 'message' => 'SubAdmin Created Successfully!']);
            } else {
                return response()->json(['alert' => 'error', 'message' => 'SubAdmin Not Created!']);
            }
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'message' => 'An error occurred while Creating SubAdmin!' . $e->getMessage()], 500);
        }
    }

    public function showSubAdmin($id)
    {
        $subadmin = User::find($id);
        if (!$subadmin) {
            return response()->json(['alert' => 'error', 'message' => 'Sub Admin Not Found'], 500);
        }
        return response()->json($subadmin);
    }
    public function updateAdmin(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'phone' => 'required',

        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        try {
            $subadmin = User::findOrFail($id);
            $subadmin->fill($request->only(['fname', 'lname', 'email', 'phone']));
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('admin/assets/images/users'), $filename);
                $subadmin->image = 'public/admin/assets/images/users/' . $filename;
            }
            $subadmin->save();
            return response()->json(['alert' => 'success', 'message' => 'SubAdmin Updated Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'message' => 'An error occurred while updating Sub Admin' . $e->getMessage()], 500);
        }
    }

    public function deleteSubadmin($id)
    {
        $subadmin = User::findOrFail($id);
        $subadmin->delete();
        return response()->json(['alert' => 'success', 'message' => 'SubAdmin Deleted SuccessFully!']);
    }
    // ######### Permissions Code ###########
    public function fetchUserPermissions(User $user)
    {
        $permissions = $user->permissions()->get();
        return response()->json(['permissions' => $permissions]);
    }
    public function updatePermissions(Request $request, User $user)
    {
        try {
            $permissions = $request->input('permissions', []);
            $permissions = array_map('intval', $permissions);
            $user->syncPermissions($permissions);
            return response()->json(['alert' => 'success', 'message' => 'Permissions updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'message' => 'An error occurred while updating permissions' . $e->getMessage()], 500);
        }
    }
}
