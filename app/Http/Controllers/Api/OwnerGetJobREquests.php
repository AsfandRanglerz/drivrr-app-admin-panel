<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentRequest;
use App\Models\User;
use App\Models\Job;
use App\Models\Document;
use Illuminate\Support\Facades\Validator;


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
}
