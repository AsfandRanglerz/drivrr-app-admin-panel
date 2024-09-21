<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\WithdrawalRequest;
use App\Models\BankAccount;
use App\Models\DriverWallet;

class DriverWalletController extends Controller
{
    public function add_withdrawal_request(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'withdrawal_amount' => 'required',
            ]
        );
        if (!$validator) {
            return $this->sendError($validator->errors()->first());
        }
        $active_account = BankAccount::where('user_id', $id)->where('status', 'Active')->first();
        $current_amount = DriverWallet::where('driver_id', $id)->value('total_earning');
        // return [$current_amount >= $request->withdrawal_amount];
        if ($current_amount >= $request->withdrawal_amount) {
            $add_withdrawal_request = WithdrawalRequest::create([
                'driver_id' => $id,
                'withdrawal_amount' => $request->withdrawal_amount,
                'account_id' => $active_account->id,
            ]);
     
            return response()->json([
                'message' => 'Request Send successfully.',
                'status' => 'Success.',
                'your request' => $add_withdrawal_request,
            ], 200);
        } else {
            return response()->json([
                'message' => 'This amount is not present in your wallet.',
                'status' => 'Failed.',
            ], 200);
        }
    }
    public function getWalletDetails($walletId)
    {
        $driverWallet = DriverWallet::with('driver')
            ->where('driver_id', $walletId)
            ->first();
        if ($driverWallet) {
            return response()->json([
                'message' => 'Wallet Show successfully.',
                'status' => 'Success',
                'wallets' =>   $driverWallet,

            ], 200);
        } else {
            return response()->json([
                'message' => 'No Wallet found.',
                'status' => 'Failed',
            ], 404);
        }
    }
    public function showWithDrawalInfo($userId)
    {
        $driverRequest = WithdrawalRequest::where('driver_id', $userId)->select('id', 'driver_id', 'withdrawal_amount', 'status','created_at')->get();
        if ($driverRequest->isEmpty()) {
            return response()->json([
                'status' => 'failed',
                'message' => 'No Request found!',
            ], 404);
        } else {
            return response()->json([
                'status' => 'Success',
                'driverRequest' => $driverRequest,
            ], 200);
        }
    }
}
