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
    public function index()
    {

        $data = User::with('driverWallet')->where('role_id', 3)->orderBy('id', 'DESC')->get();
        return view('admin.wallet.index', compact('data'));
    }
    public function show_withdrawal_requests()
    {
        $requestCount = WithdrawalRequest::where('status', '0')->where('seen', '0')->get();
        if ($requestCount->isNotEmpty()) {
            WithdrawalRequest::where('status', '0')->where('seen', '0')->update([
                'seen' => 1,
            ]);
        }
        $withdraw_requests = WithdrawalRequest::with('bankAccount', 'user')->orderBy('id', 'DESC')->get();
        return view('admin.withdrawal_requests.index', compact('withdraw_requests'));
    }
    public function send_money(Request $request, $id, $amount)
    {
        $request->validate([
            'image' => 'required',
        ]);
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move(public_path('admin/assets/images/users/'), $filename);
            $image = 'public/admin/assets/images/users/' . $filename;
        } else {
            $image = 'public/admin/assets/images/users/owner.jpg';
        }
        $driver_id = WithdrawalRequest::where('id', $id)->value('driver_id');
        $wallet_amount = DriverWallet::where('driver_id', $driver_id)->value('total_earning');
        $updated_amount = $wallet_amount - $amount;
        DriverWallet::where('driver_id', $driver_id)->update([
            'total_earning' => $updated_amount,
        ]);
        $approved_request = WithdrawalRequest::find($id);
        $approved_request->update([
            'image' => $image,
            'status' => 1,
        ]);
        $driver_email = User::where('id', $driver_id)->value('email');
        $data = [
            'driver_id' => $driver_id,
            'amount' => $amount,
            'image' => $image,
        ];

            Mail::to($driver_email)->send(new PaymentProved($data));
        return redirect()->back()->with(['status' => 'success', 'message' => 'Action is successfully taken.']);
    }
    public function delete_request($id)
    {
        $approved_request = WithdrawalRequest::find($id);
        WithdrawalRequest::destroy($id);
        return redirect()->back()->with(['status' => 'success', 'message' => 'Request deleted successfully.']);
    }

    public function show_receipts($id)
    {
        $driver = WithdrawalRequest::where('driver_id', $id)->where('status', 1)->get();
        return view('admin.wallet.withdrawals.index', compact('driver'));
        return $driver;
    }
}
