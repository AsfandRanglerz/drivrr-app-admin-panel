<?php

namespace App\Http\Controllers\Api;

use App\Models\Job;
use App\Models\User;
use App\Models\Document;
use App\Models\BankAccount;
use App\Models\DriverWallet;
use Illuminate\Http\Request;
use App\Models\PaymentRequest;
use App\Mail\OwnerAcceptJobRequest;
use App\Mail\OwnerCancelJobRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;


class OwnerGetJobREquests extends Controller
{
    public function ownerPayDriver($id)
    {
        try {
            $ownerPay = PaymentRequest::findOrFail($id);
            if ($ownerPay->counter_offer === '0') {
                $ownerJob = $ownerPay->job->price;
                $ownerPay->update(['payment_amount' =>  $ownerJob]);
                $driverId = $ownerPay->driver_id;
                $driverWallet = DriverWallet::where('driver_id', $driverId)->firstOrFail();
                $driverWallet->update(['total_earning' => $driverWallet->total_earning + $ownerJob]);
                return response()->json([
                    'message' => 'Payment amount updated successfully',
                    'status' => 'success',
                    'totalAmount' =>  $driverWallet,
                ], 200);
            } else if ($ownerPay->counter_offer > 0) {
                $ownerCounterpay = $ownerPay->counter_offer;
                $ownerPay->update(['payment_amount' =>  $ownerCounterpay]);
                $driverId = $ownerPay->driver_id;
                $driverWallet = DriverWallet::where('driver_id', $driverId)->firstOrFail();
                $driverWallet->update(['total_earning' => $driverWallet->total_earning + $ownerCounterpay]);

                return response()->json([
                    'message' => 'Payment amount with CounterOffer updated successfully',
                    'status' => 'success',
                    'totalAmount' =>  $driverWallet,
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error: ' . $e->getMessage(),
                'status' => 'error',
            ], 500);
        }
    }

    public function owner_accept_job_request(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'card_number' => 'required',
            'expiry_date' => 'required',
            'card_holder' => 'required',
            'cvc' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }
        $owner_accept = PaymentRequest::find($id);
        if (!$owner_accept) {
            return response()->json([
                'message' => 'Request Rejected.',
                'status' => 'failed',
            ], 400);
        }
        $job = $owner_accept->job;
        $owner = $owner_accept->owner;
        if ($job) {
            $owner_accept->update([
                'card_number' => $request->card_number,
                'expiry_date' => $request->expiry_date,
                'card_holder' => $request->card_holder,
                'cvc' => $request->cvc,
                'status' => 'Accepted',
            ]);
            $job->update([
                'active_job' => '1',
            ]);
            $driver = $owner_accept->driver;
            if ($driver && $driver->email) {
                Mail::to($driver->email)->send(new OwnerAcceptJobRequest($owner));
            }
            return response()->json([
                'message' => 'Request Accepted Successfully.',
                'status' => 'success',
                'data' => $owner_accept,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Job not found for the given PaymentRequest.',
                'status' => 'failed',
            ], 400);
        }
    }

    public function owner_cancle_request($id)
    {
        $check = PaymentRequest::where('id', $id)->first();

        if ($check) {
            $job_request = PaymentRequest::find($id);
            $driver_email = User::where('id', $job_request->driver_id)->value('email');
            $owner = User::find($job_request->owner_id);
            if ($job_request->status !== 'CancelRide') {
                $job_request->update(['status' => 'CancelRide']);
                if ($job_request->job->is_active === '0') {
                    $job_request->job->update(['is_active' => 1]);
                }
                Mail::to($driver_email)->send(new OwnerCancelJobRequest($owner));
                return response()->json([
                    'message' => 'This request has been canceled, and the job is now active.',
                    'status' => 'success',
                ], 200);
            } else {
                return response()->json([
                    'message' => 'This request has already been canceled.',
                    'status' => 'failed',
                ], 400);
            }
        } else {
            return response()->json([
                'message' => 'Request is not found.',
                'status' => 'failed',
            ], 400);
        }
    }
}
