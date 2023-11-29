<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentRequest;
use App\Models\User;
use App\Models\Job;
use App\Models\Document;
use Illuminate\Support\Facades\Validator;
use App\Mail\OwnerCancelJobRequest;
use Mail;


class OwnerGetJobREquests extends Controller
{
    public function show_job_requests($owner_id)
    {
        $job_id = PaymentRequest::where('owner_id',$owner_id)->value('job_id');
        $driver_id = PaymentRequest::where('owner_id',$owner_id)->value('driver_id');
        $driver_requests = PaymentRequest::where('owner_id',$owner_id)->get();
        return [$job_id,$driver_id,$driver_requests];
    }
    public function owner_accept_job_request(Request $request ,$id)
    {
        $validator = Validator::make($request->all(),[
            'payment_amount'=>'required',
            'card_number'=>'required',
            'expiry_date'=>'required',
            'card_holder'=>'required',
            'cvc'=>'required',
        ]);
        if(!$validator)
        {
            return $this->sendError($validator->errors()->first());
        }
        $owner_accept = PaymentRequest::find($id);
        // return  $owner_accept;
        if($owner_accept)
        {
        $owner_accept->update([
            'payment_amount'=>$request->payment_amount,
            'card_number'=>$request->card_number,
            'expiry_date'=>$request->expiry_date,
            'card_holder'=>$request->card_holder,
            'cvc'=>$request->cvc,
            'status'=>'Accepted',
        ]);
        return response()->json([
            'message'=>'Request Accepted Successfully.',
            'status'=>'success.',
            'data'=>$owner_accept,
        ],200);
        }
        else
        {
            return response()->json([
                'message'=>'Request Rejected.',
                'status'=>'failed.',
            ],400);
        }
    }
    public function owner_cancle_request($id)
    {
       $check = PaymentRequest::where('id',$id)->first();
       if($check)
       {
       $job_request = PaymentRequest::find($id);
       $driver_email = User::where('id',$job_request->driver_id)->value('email');
       $owner = User::find($job_request->owner_id);
       $job_request->delete();
            // return $owner_name->fname,$owner_name->lname;
       Mail::to($driver_email)->send(new OwnerCancelJobRequest($owner));
    if($job_request)
    {
        return response()->json([
            'message'=>'this request has been canceled.',
            'status'=>'success',
        ],200);
    }
    else
    {
        return response()->json([
            'message'=>'Request is not found.',
            'status'=>'failed',
        ],400);
    }
}
else
{
    return response()->json([
        'message'=>'Request is not exist.',
        'status'=>'failed',
    ],400);
}


    }
}
