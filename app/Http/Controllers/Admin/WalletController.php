<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Mail\PaymentProved;
use App\Models\BankAccount;
use App\Models\DriverWallet;
use Illuminate\Http\Request;
use App\Models\WithdrawalRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;


class WalletController extends Controller
{
    public function paymentHistoryData()
    {
        $users = User::with('driverWallet')->where('role_id', 3)->latest()->get();
        $json_data["data"] = $users;
        return json_encode($json_data);
    }

    public function paymentHistoryIndex()
    {
        $users = User::with('driverWallet')->where('role_id', 3)->latest()->get();
        return view('admin.wallet.index', compact('users'));
    }
    public function getPaymentHistory(Request $request, $id)
    {
        try {
            $paymentRequests = WithdrawalRequest::where('driver_id', $id)->where('status', 1)->get();
            return view('admin.wallet.withdrawals.index', compact('paymentRequests'));
        } catch (\Exception $e) {
            return response()->json([
                'alert' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
