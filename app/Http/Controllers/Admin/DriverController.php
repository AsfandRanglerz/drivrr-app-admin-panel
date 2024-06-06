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
            return response()->json(['alert' => 'success', 'message' => 'Driver Updated Successfully!']);
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
