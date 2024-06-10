<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Notification;
use App\Models\Job;
use App\Models\User;
use App\Models\Admin;
use App\Models\Review;
use App\Models\Question;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\VerifyUserEmail;
use App\Mail\SignupPasswordSend;
use App\Http\Controllers\Controller;
use App\Mail\ownerBlock;
use App\Mail\ownerRegistration;
use App\Mail\ownerUnBlock;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Notifications\TestingNotification;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class BusinessOwnerController extends Controller
// {
//     /**
//      * Display a listing of the resource.
//      *
//      * @return \Illuminate\Http\Response
//      */
//     public function index()
//     {
//         $data = User::whereHas('roles', function ($q) {
//             $q->where('name', 'Owner');
//         })->orderBy('id', 'DESC')->get()
//             ->each(function ($owner) {
//             $owner->jobsCount = $owner->job->count();
//         });;
//         // return $data;
//         return view('admin.owner.index', compact('data'));
//     }

//     /**
//      * Show the form for creating a new resource.
//      *
//      * @return \Illuminate\Http\Response
//      */
//     public function create()
//     {
//         return view('admin.owner.create');
//     }

//     /**
//      * Store a newly created resource in storage.
//      *
//      * @param  \Illuminate\Http\Request  $request
//      * @return \Illuminate\Http\Response
//      */
//     public function store(Request $request)
//     {
//         $request->validate([
//             'fname' => 'required',
//             'lname' => 'required',
//             'phone' => 'required',
//             'email' => 'required|unique:users,email|email',
//             'company_name' => 'required',
//             'company_info' => 'required',

//             // 'password' => 'required|confirmed',
//         ]);

//         if ($request->hasFile('image')) {
//             $file = $request->file('image');
//             $extension = $file->getClientOriginalExtension();
//             $filename = time() . '.' . $extension;
//             $file->move(public_path('admin/assets/images/users/'), $filename);
//             $image = 'public/admin/assets/images/users/' . $filename;
//         }
//         else {
//             $image = 'public/admin/assets/images/users/owner.jpg';
//         }

//         /**generate random password */
//         $password = Str::random(10);
//         $owner = User::create([
//             'fname' => $request->fname,
//             'lname' => $request->lname,
//             'phone' => $request->phone,
//             'email' => $request->email,
//             'company_name' => $request->company_name,
//             'company_info' => $request->company_info,
//             'image' => $image,
//             'role_id' => 2,
//             // 'password' => Hash::make($password),
//             // 'email' => $request->email,
//             // 'role_id'=> 1,
//         ]);
//         /* assign the role  */
//         $owner->roles()->sync(2);
//         $message['email'] = $request->email;
//         $message['password'] = $password;
//         $status = 'Owner';
//         Mail::to($request->email)->send(new SignupPasswordSend($status));


//          $admin = Admin::where('email', 'admin@gmail.com')->first();
//         //  $testNotification = User::first();
//          $admin->notify(new TestingNotification($owner));
//         //  dd($admin->notifications);


//         // $admin->notify(new NewUser($owner));

//         try {
//             return redirect()->route('businessOwner.index')->with(['status' => true, 'message' => 'Business Owner Created Successfully.']);
//         } catch (\Throwable $th) {
//             dd($th->getMessage());
//             return back()
//                 ->with(['status' => false, 'message' => $th->getMessage()]);
//         }
//     }

//     /**
//      * Display the specified resource.
//      *
//      * @param  int  $id
//      * @return \Illuminate\Http\Response
//      */
//     public function show($id)
//     {
//         //
//     }

//     /**
//      * Show the form for editing the specified resource.
//      *
//      * @param  int  $id
//      * @return \Illuminate\Http\Response
//      */
//     public function edit($id)
//     {
//         $data = User::find($id);
//         return view('admin.owner.edit', compact('data'));
//     }

//     /**
//      * Update the specified resource in storage.
//      *
//      * @param  \Illuminate\Http\Request  $request
//      * @param  int  $id
//      * @return \Illuminate\Http\Response
//      */
//     public function update(Request $request, $id)
//     {
//         // return $request;
//         $request->validate([
//             'fname' => 'required',
//             'lname' => 'required',
//             'phone' => 'required',
//             'email' => 'required',
//             'company_name' => 'required',
//             'company_info' => 'required',

//         ]);

//         $owner = User::find($id);
//         if ($request->hasfile('image')) {
//             $destination = 'public/admin/assets/images/users' . $owner->image;
//             if (File::exists($destination) || File::exists($owner->image)) {
//                 File::delete($destination);
//                 File::delete($owner->image);
//             }
//             $file = $request->file('image');
//             $extension = $file->getClientOriginalExtension();
//             $filename = time() . '.' . $extension;
//             $file->move('public/admin/assets/images/users', $filename);
//             $image = 'public/admin/assets/images/users/' . $filename;
//         } else {
//             $image = $owner->image;
//         }

//         $owner->update([
//             'fname' => $request->fname,
//             'lname' => $request->lname,
//             'phone' => $request->phone,
//             'email' => $request->email,
//             'company_name' => $request->company_name,
//             'company_info' => $request->company_info,
//             // 'last_name' => $request->last_name,
//             // 'designation' => $request->designation,
//         ] + ['image' => $image]);

//         return redirect()->route('businessOwner.index')->with(['status' => true, 'message' => 'Business Owner Updated Successfully.']);
//     }

//     /**
//      * Remove the specified resource from storage.
//      *
//      * @param  int  $id
//      * @return \Illuminate\Http\Response
//      */
//     public function destroy($id)
//     {
//         // if(Review::where('user_id', $id)->delete() ||  Review::where('user_id', $id) || Question::where('user_id', $id))
//         // {
//         //     User::destroy($id);
//         // }
//         // Job::where('user_id', $id)->delete();
//         // Review::where('user_id', $id)->delete();
//         // Question::where('user_id', $id)->delete();
//         User::destroy($id);
//         return redirect()->back()->with(['status' => true, 'message' => 'Business Owner Deleted Successfully.']);
//     }

//     public function status($id)
//     {
//         $data = User::find($id);
//         $user['fname'] = $data->fname;
//         $user['email'] = $data->email;
//         // return $user;
//         $data->update(['is_active' => $data->is_active == 0 ? '1' : '0']);
//         $status = $data->is_active;
//         Mail::to($data->email)->send(new VerifyUserEmail($status));
//         return redirect()->back()->with(['status' => true, 'message' => 'Status Updated Successfully.']);
//     }
// }
{
    public function busniessOwnerData()
    {
        $busniessOwners = User::whereHas('roles', function ($q) {
            $q->where('name', 'Owner');
        })->latest()->get();
        $json_data["data"] = $busniessOwners;
        return json_encode($json_data);
    }
    public function busniessOwnerIndex()
    {
        $busniessOwners = User::whereHas('roles', function ($q) {
            $q->where('name', 'Owner');
        })->latest()->get();
        return view('admin.owner.index', compact('busniessOwners'));
    }
    public function busniessOwnerCreate(Request $request)
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
            $busniessOwner = new User($request->only(['fname', 'lname', 'email', 'phone', 'company_info', 'company_name']));
            $busniessOwner->password = bcrypt($request->input('password'));
            $busniessOwner->role_id = 2;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('admin/assets/images/users'), $filename);
                $busniessOwner->image = 'public/admin/assets/images/users/' . $filename;
            }
            $busniessOwner->save();
            if ($busniessOwner) {
                $busniessOwner->roles()->sync(2);
                $data['username'] =  $busniessOwner->fname . ' ' .  $busniessOwner->lname;
                $data['useremail'] =  $busniessOwner->email;
                $data['password'] = $request->password;
                Mail::to($busniessOwner->email)->send(new ownerRegistration($data));
                return response()->json(['alert' => 'success', 'message' => 'Busniess Owner Created Successfully!']);
            } else {
                return response()->json(['alert' => 'error', 'message' => 'Busniess Owner Not Created!']);
            }
            return response()->json(['alert' => 'success', 'message' => 'Busniess Owner Created Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'message' => 'An error occurred while Creating Busniess Owner!'], 500);
        }
    }

    public function showBusniessOwner($id)
    {
        $busniessOwner = User::find($id);
        if (!$busniessOwner) {
            return response()->json(['alert' => 'error', 'message' => 'Busniess Owner Not Found'], 404);
        }
        return response()->json($busniessOwner);
    }
    public function updateBusniessOwner(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'phone' => 'unique:users,phone,' . $id,
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        try {
            $busniessOwner = User::findOrFail($id);
            $busniessOwner->fill($request->only(['fname', 'lname', 'email', 'phone', 'company_info', 'company_name']));

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('admin/assets/images/users'), $filename);
                $busniessOwner->image = 'public/admin/assets/images/users/' . $filename;
            }
            $busniessOwner->save();
            return response()->json(['alert' => 'success', 'message' => 'Busniess Owner Updated Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'message' => 'An error occurred while updating User'], 500);
        }
    }
    public function deleteBusniessOwner($id)
    {
        $busniessOwner = User::findOrFail($id);
        $busniessOwner->delete();
        return response()->json(['alert' => 'success', 'message' => 'Busniess Owner Deleted SuccessFully!']);
    }
    // ####### Update Blocking Status########
    public function updateBlockStatus($id)
    {
        try {
            $busniessOwner = User::findOrFail($id);

            if ($busniessOwner->is_active == '0') {
                $busniessOwner->is_active = '1';
                $message = 'Busniess Owner Activate Successfully';
                $data['ownername'] =  $busniessOwner->fname . ' ' .  $busniessOwner->lname;
                $data['owneremail'] =  $busniessOwner->email;
                Mail::to($busniessOwner->email)->send(new ownerUnBlock($data));
            } else if ($busniessOwner->is_active == '1') {
                $busniessOwner->is_active = '0';
                $message = 'Busniess Owner Blocked Successfully';
                $data['ownername'] =  $busniessOwner->fname . ' ' .  $busniessOwner->lname;
                $data['owneremail'] =  $busniessOwner->email;
                Mail::to($busniessOwner->email)->send(new ownerBlock($data));
            } else {
                return response()->json(['alert' => 'info', 'message' => 'Busniess Owner status is already updated or cannot be updated.']);
            }
            $busniessOwner->save();
            return response()->json(['alert' => 'success', 'message' => $message]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['alert' => 'error', 'message' => 'Busniess Owner not found.']);
        } catch (Exception $e) {
            return response()->json(['alert' => 'error', 'message' => 'An error occurred while updating Busniess Owner status: ' . $e->getMessage()], 500);
        }
    }
}
