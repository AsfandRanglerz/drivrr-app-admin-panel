<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Job;
use App\Models\Driver;
use App\Models\Review;
use App\Models\Document;
use App\Models\Question;
use App\Mail\driverBlock;
use App\Mail\driverUnBlock;
use Illuminate\Support\Str;
use App\Models\DriverWallet;
use Illuminate\Http\Request;
use App\Mail\VerifyDriverEmail;
use App\Mail\driverRegistration;
use App\Mail\SignupPasswordSend;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class DriverController  extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index()
    // {
    //     // $data = Driver::whereHas('roles', function ($q) {
    //     //     $q->where('title', 'driver');
    //     // })->orderBy('id', 'DESC')->get();

    //     $data = User::whereHas('roles', function ($q) {
    //         $q->where('name', 'driver');
    //     })->orderBy('id', 'DESC')->get()
    //         ->each(function ($driver) {
    //             $driver->documentCount = $driver->document->count();
    //             $driver->vehicleCount = $driver->driverVehicle->count();
    //         });
    //     // return $data;
    //     return view('admin.driver.index', compact('data'));
    // }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function create()
    // {
    //     return view('admin.driver.create');
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(Request $request)
    // {

    //     $request->validate([
    //         'fname' => 'required',
    //         'lname' => 'required',
    //         'phone' => 'required',
    //         'email' => 'required|unique:users,email|email',
    //     ]);

    //     if ($request->hasFile('image')) {
    //         $file = $request->file('image');
    //         $extension = $file->getClientOriginalExtension();
    //         $filename = time() . '.' . $extension;
    //         $file->move(public_path('admin/assets/images/users/'), $filename);
    //         $image = 'public/admin/assets/images/users/' . $filename;
    //     }
    //     else {
    //         $image = 'public/admin/assets/images/users/owner.jpg';
    //     }
    //     /**generate random password */
    //     $password = Str::random(10);
    //     $driver = User::create([
    //         'fname' => $request->fname,
    //         'lname' => $request->lname,
    //         'phone' => $request->phone,
    //         'email' => $request->email,
    //         'image' => $image,
    //         'role_id' => 3,
    //         // 'password' => Hash::make($password),
    //     ]);
    //     $wallet = DriverWallet::create([
    //         'driver_id'=> $driver->id,
    //         'total_earning'=> 0,
    //     ]);
    //     /** assign the role  */
    //     $driver->roles()->sync(3);

    //     $message['email'] = $request->email;
    //     $message['password'] = $password;
    //     $status = 'Driver';
    //     Mail::to($request->email)->send(new SignupPasswordSend($status));
    //     try {
    //         // Mail::to($request->email)->send(new UserLoginPassword($message));
    //         return redirect()->route('driver.index')->with(['status' => true, 'message' => 'Driver Created Successfully.']);
    //     } catch (\Throwable $th) {
    //         dd($th->getMessage());
    //         return back()
    //             ->with(['status' => false, 'message' => $th->getMessage()]);
    //     }
    // }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function show()
    // {
    //     // return "running";
    // }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function edit($id)
    // {
    //     $data = User::find($id);
    //     return view('admin.driver.edit', compact('data'));
    // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'fname' => 'required',
    //         'lname' => 'required',
    //         'phone' => 'required',
    //         'email' => 'required',
    //     ]);

    //     $driver = User::find($id);
    //     if ($request->hasfile('image')) {
    //         $destination = 'public/admin/assets/images/users' . $driver->image;
    //         if (File::exists($destination) || File::exists($driver->image)) {
    //             File::delete($destination);
    //             File::delete($driver->image);
    //         }
    //         $file = $request->file('image');
    //         $extension = $file->getClientOriginalExtension();
    //         $filename = time() . '.' . $extension;
    //         $file->move('public/admin/assets/images/users', $filename);
    //         $image = 'public/admin/assets/images/users/' . $filename;
    //     } else {
    //         $image = $driver->image;
    //     }


    //     $driver->update([
    //         'fname' => $request->fname,
    //         'lname' => $request->lname,
    //         'phone' => $request->phone,
    //         'email' => $request->email,
    //     ] + ['image' => $image]);
    //     return redirect()->route('driver.index')->with(['status' => true, 'message' => 'Driver Updated Successfully']);
    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function destroy($id)
    // {
    //     // Delete associated data
    //     // if(Document::where('user_id', $id) ||  Review::where('user_id', $id) || Question::where('user_id', $id))
    //     // {
    //     //     User::destroy($id);
    //     // }
    //     // Document::where('user_id', $id)->delete();
    //     // Review::where('user_id', $id)->delete();
    //     // Question::where('user_id', $id)->delete();
    //     // // Then delete the user
    //     User::destroy($id);
    //     return redirect()->back()->with(['status' => true, 'message' => 'Driver Deleted Successfully']);
    // }

    // public function status($id)
    // {
    //     $data = User::find($id);
    //     $data->update(['is_active' => $data->is_active == 0 ? '1' : '0']);
    //     $status = $data->is_active;
    //     Mail::to($data->email)->send(new VerifyUserEmail($status));
    //     return redirect()->back()->with(['status' => true, 'message' => 'Status Updated Successfully']);
    // }



    // public function show_documents()
    // {
    //     $data = User::whereHas('roles', function ($q) {
    //         $q->where('title', 'driver');
    //     })->orderBy('id', 'DESC')->get();
    //     return view('admin.driver.index', compact('data'));
    // }
    public function driversData()
    {
        $drivers = User::whereHas('roles', function ($q) {
            $q->where('name', 'Driver');
        })->latest()->get();
        $json_data["data"] = $drivers;
        return json_encode($json_data);
    }
    public function driversIndex()
    {
        $drivers = User::whereHas('roles', function ($q) {
            $q->where('name', 'Driver');
        })->latest()->get();
        return view('admin.driver.index', compact('drivers'));
    }
    public function driversCreate(Request $request)
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
            $driver = new User($request->only(['fname', 'lname', 'email', 'phone']));
            $driver->password = bcrypt($request->input('password'));
            $driver->role_id = 3;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('admin/assets/images/users'), $filename);
                $driver->image = 'public/admin/assets/images/users/' . $filename;
            }
            $driver->save();
            if ($driver) {
                $driver->roles()->sync(3);
                //#########Create Wallet###########
                $wallet = new DriverWallet();
                $wallet->driver_id = $driver->id;
                $wallet->total_earning = 0;
                $wallet->save();
                $data['username'] =  $driver->fname . ' ' .  $driver->lname;
                $data['useremail'] =  $driver->email;
                $data['password'] = $request->password;
                Mail::to($driver->email)->send(new driverRegistration($data));
                return response()->json(['alert' => 'success', 'message' => 'Driver Created Successfully!']);
            } else {
                return response()->json(['alert' => 'error', 'message' => 'Driver Not Created!']);
            }
            return response()->json(['alert' => 'success', 'message' => 'Driver Created Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'message' => 'An error occurred while Creating Driver!'], 500);
        }
    }

    public function showDrivers($id)
    {
        $driver = User::find($id);
        if (!$driver) {
            return response()->json(['alert' => 'error', 'message' => 'Driver Not Found'], 404);
        }
        return response()->json($driver);
    }
    public function updateDrivers(Request $request, $id)
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
            $driver = User::findOrFail($id);
            $driver->fill($request->only(['fname', 'lname', 'email', 'phone']));
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('admin/assets/images/users'), $filename);
                $driver->image = 'public/admin/assets/images/users/' . $filename;
            }
            $driver->save();
            return response()->json(['alert' => 'success', 'message' => 'User Updated Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'message' => 'An error occurred while updating User'], 500);
        }
    }
    public function deleteDrivers($id)
    {
        $driver = User::findOrFail($id);
        $driver->delete();
        return response()->json(['alert' => 'success', 'message' => 'Driver Deleted SuccessFully!']);
    }
    // ####### Update Blocking Status########
    public function updateBlockStatus($id)
    {
        try {
            $driver = User::findOrFail($id);

            if ($driver->is_active == '0') {
                $driver->is_active = '1';
                $message = 'Driver Activate Successfully';
                $data['drivername'] =  $driver->fname . ' ' .  $driver->lname;
                $data['driveremail'] =  $driver->email;
                Mail::to($driver->email)->send(new driverUnBlock($data));
            } else if ($driver->is_active == '1') {
                $driver->is_active = '0';
                $message = 'Driver Blocked Successfully';
                $data['drivername'] =  $driver->fname . ' ' .  $driver->lname;
                $data['driveremail'] =  $driver->email;
                Mail::to($driver->email)->send(new driverBlock($data));
            } else {
                return response()->json(['alert' => 'info', 'message' => 'Driver status is already updated or cannot be updated.']);
            }
            $driver->save();
            return response()->json(['alert' => 'success', 'message' => $message]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['alert' => 'error', 'message' => 'Driver not found.']);
        } catch (Exception $e) {
            return response()->json(['alert' => 'error', 'message' => 'An error occurred while updating Driver status: ' . $e->getMessage()], 500);
        }
    }
}
