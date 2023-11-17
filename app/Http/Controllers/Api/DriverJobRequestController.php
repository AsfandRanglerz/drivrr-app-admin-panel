<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentRequest;
use App\Models\User;
use App\Models\Job;
use App\Models\Document;
use Illuminate\Support\Facades\Validator;

class DriverJobRequestController extends Controller
{
    public function add_job_request_without_counter(Request $request, $owner_id, $driver_id, $job_id)
    {
        // return "running";
        $owner = User::find($owner_id);
        // return $owner;

        $driver = User::find($driver_id);
        // return $driver;

        $job = Job::find($job_id);
        // return $job;

        $location = User::where('id',$driver_id)->value('location');
        // return $location;

        $approve_document = Document::where('user_id',$driver_id)->value('is_active');
        // return  $approve_document;
        if($approve_document == 1)
        {
            $driver_job_request = PaymentRequest::create([
                'owner_id'=>$owner_id,
                'driver_id'=>$driver_id,
                'job_id'=>$job_id,
                'counter_offer'=>0,
                'location'=>$location,
            ]);
            return response()->json([
                'message'=>'Your request is send successfully.',
                'status'=>'Success.',
                'owner'=>$owner,
                'job'=>$job,
                'driver'=>$driver,
            ],200);
        }
        else
        {
            return response()->json([
                'message'=>'Your document is not approved therefore your are not eligible to apply any job.',
                'status'=>'failed',
            ],400);
        }

    }
    public function add_job_request_counter(Request $request, $owner_id, $driver_id, $job_id)
    {
                 // return "running";
        $owner = User::find($owner_id);
        // return $owner;

        $driver = User::find($driver_id);
        // return $driver;

        $job = Job::find($job_id);
        // return $job;

        $location = User::where('id',$driver_id)->value('location');
        // return $location;

        $approve_document = Document::where('user_id',$driver_id)->value('is_active');
        // return  $approve_document;

        $validator = Validator::make($request->all(),[
            'counter_offer'=>'required',
        ]);
        if(!$validator)
        {
            return $this->sendError($validator->errors()->first());
        }
        if($approve_document == 1)
        {
            $driver_job_request = PaymentRequest::create([
                'owner_id'=>$owner_id,
                'driver_id'=>$driver_id,
                'job_id'=>$job_id,
                'counter_offer'=>$request->counter_offer,
                'location'=>$location,
            ]);
            return response()->json([
                'message'=>'Your request is send successfully.',
                'status'=>'Success.',
                'owner'=>$owner,
                'job'=>$job,
                'driver'=>$driver,
            ],200);
        }
        else
        {
            return response()->json([
                'message'=>'Your document is not approved therefore your are not eligible to apply any job.',
                'status'=>'failed',
            ],400);
        }
    }
}
