<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Mail\paymentProof;
use Illuminate\Http\Request;
use App\Models\WithdrawalRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class WithDrawalController extends Controller
{
    public function paymentRequestData()
    {
        $paymentRequests = WithdrawalRequest::with('user.driverWallet')->latest()->get();
        $json_data["data"] =  $paymentRequests;
        return json_encode($json_data);
    }

    public function paymentRequestIndex()
    {
        $paymentRequests = WithdrawalRequest::with('user.driverWallet')->latest()->get();
        return view('admin.withdrawal_requests.index', compact('paymentRequests'));
    }

    public function getAccountDetails(Request $request, $userId)
    {
        try {
            $user = User::findOrFail($userId);
            $bankInfos = $user->bankAccounts()
                ->whereIn('status', ['Active', 'Inactive'])
                ->orderByRaw("FIELD(status, 'Active') DESC")
                ->get();
            return view('admin.withdrawal_requests.useraccountdetails', compact('bankInfos'));
        } catch (\Exception $e) {
            return response()->json([
                'alert' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function showPaymentRequest($id)
    {
        $paymentRequest = WithdrawalRequest::with('user')->find($id);
        if (!$paymentRequest) {
            return response()->json(['alert' => 'error', 'message' => 'Payment Id Not Found'], 500);
        }
        return response()->json($paymentRequest);
    }
    public function updatePaymentRequest(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'image' => 'required|image|mimes:jpg,jpeg,png|max:1024', // Max size in KB (2MB)
            ]);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            $paymentRequest = WithdrawalRequest::with('user')->findOrFail($id);
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                $file->move(public_path('admin/assets/images/users/'), $filename);
                $imagePath = 'public/admin/assets/images/users/' . $filename;
                $paymentRequest->image = $imagePath;
            } else {
                return response()->json([
                    'alert' => 'error',
                    'message' => 'Error in uploading image!'
                ], 400);
            }
            $paymentRequest->status = 1;
            $paymentRequest->save();
            if ($paymentRequest) {
                $data['username'] =  $paymentRequest->user->fname . ' ' .  $paymentRequest->user->lname;
                $data['useremail'] =  $paymentRequest->user->email;
                $data['withdrawal_amount'] =  $paymentRequest->withdrawal_amount;
                $data['image'] =  $paymentRequest->image;
                if ($data) {
                    Mail::to($paymentRequest->user->email)->send(new paymentProof($data));
                    return response()->json([
                        'alert' => 'success',
                        'message' => 'Payment Proof Sent Successfully.',
                    ], 200);
                } else {
                    return response()->json([
                        'alert' => 'error',
                        'message' => 'Error In Sending Mail To User!',
                    ]);
                }
            } else {
                return response()->json([
                    'alert' => 'error',
                    'message' => 'Payment Proof Not Sent!.',
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function getPaymentRequestCount()
    {
        try {
            $paymentRequest = WithdrawalRequest::where('status', 0)->count();
            return response()->json(['paymentRequest' => $paymentRequest]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
