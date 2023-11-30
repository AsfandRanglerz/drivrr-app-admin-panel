<?php

namespace App\Http\Controllers\Api;

use App\Models\Job;
use App\Models\User;
use App\Models\Document;
use Illuminate\Http\Request;
use App\Models\PaymentRequest;
use App\Mail\OwnerCancelJobRequest;
use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;


class OwnerGetJobREquests extends Controller
{

    public function owner_accept_job_request(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'payment_amount' => 'required',
            'card_number' => 'required',
            'expiry_date' => 'required',
            'card_holder' => 'required',
            'cvc' => 'required',
        ]);
        if (!$validator) {
            return $this->sendError($validator->errors()->first());
        }
        $owner_accept = PaymentRequest::find($id);
        if ($owner_accept) {
            $owner_accept->update([
                'payment_amount' => $request->payment_amount,
                'card_number' => $request->card_number,
                'expiry_date' => $request->expiry_date,
                'card_holder' => $request->card_holder,
                'cvc' => $request->cvc,
                'status' => 'Accepted',
            ]);
            return response()->json([
                'message' => 'Request Accepted Successfully.',
                'status' => 'success.',
                'data' => $owner_accept,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Request Rejected.',
                'status' => 'failed.',
            ], 400);
        }
    }
    public function owner_cancle_request($id)
    {
        $job_request = PaymentRequest::find($id);
        $driver_email = User::where('id', $job_request->driver_id)->value('email');
        $owner = User::find($job_request->owner_id);
        $job_request->delete();
        Mail::to($driver_email)->send(new OwnerCancelJobRequest($owner));
        if ($job_request) {
            return response()->json([
                'message' => 'this request has been canceled.',
                'status' => 'success',
            ], 200);
        } else {
            return response()->json([
                'message' => 'Request is not found.',
                'status' => 'failed',
            ], 400);
        }
    }
}
