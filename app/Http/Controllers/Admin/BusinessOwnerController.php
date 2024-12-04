<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Notification;
use App\Models\Job;
use App\Models\User;
use App\Models\Admin;
use App\Models\Review;
use App\Mail\ownerBlock;
use App\Models\Question;
use App\Mail\ownerUnBlock;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\VerifyUserEmail;
use App\Mail\ownerRegistration;
use App\Mail\SignupPasswordSend;
use App\Models\PushNotification;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Helpers\FcmNotificationHelper;
use Illuminate\Support\Facades\Validator;
use App\Notifications\TestingNotification;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class BusinessOwnerController extends Controller

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
                'image' => 'nullable|image|mimes:jpeg,jpg,png|max:1048'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            $busniessOwner = new User($request->only(['fname', 'lname', 'email', 'phone', 'company_info', 'company_name']));
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
                $data['ownername'] =  $busniessOwner->fname . ' ' .  $busniessOwner->lname;
                $data['owneremail'] =  $busniessOwner->email;
                Mail::to($busniessOwner->email)->send(new ownerRegistration($data));
            }
            return response()->json(['alert' => 'success', 'message' => 'Business Owner Created Successfully!'], 201);
        } catch (\Exception $e) {
            // Return error response with exception details
            return response()->json(['alert' => 'error', 'message' => 'An error occurred while creating the Business Owner: ' . $e->getMessage()], 500);
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
            return response()->json(['alert' => 'error', 'message' => 'An error occurred while updating User' . $e->getMessage()], 500);
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
                $blockReason = request('block_reason', 'No reason provided');
                $data['block_reason'] = $blockReason;
                Mail::to($busniessOwner->email)->send(new ownerBlock($data));
                $title = 'Admin';
                $description = 'You Are Blocked!';
                $notificationData = [
                    'job_idd' =>  $busniessOwner->role_id,
                ];
                if (!is_null($busniessOwner->fcm_token)) {
                    FcmNotificationHelper::sendFcmNotification($busniessOwner->fcm_token, $title, $description, $notificationData);
                    PushNotification::create([
                        'title' => $title,
                        'description' => $description,
                        'user_name' =>  $busniessOwner->id,
                        'user_id' => $busniessOwner->id,

                    ]);
                }
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
