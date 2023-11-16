<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\DriverWallet;
use App\Models\WithdrawalRequest;
use App\Models\BankAccount;


class WalletController extends Controller
{
    public function index()
    {
        // return "running";
        // $data = User::whereHas('roles', function ($q) {
        //     $q->where('name', 'driver');
        // })->has('driverWallet')->with('driverWallet')->orderBy('id', 'DESC')->get();
        $data = User::with('driverWallet')->where('role_id',3)->get();
        // return $data;
        // return   $data;
        // $driver_id = $data->id;
        // $wallet = User::with('driverWallet')->find($driver_id);
        return view('admin.wallet.index', compact('data'));
    }
    public function show_withdrawal_requests()
    {
        $withdraw_requests = WithdrawalRequest::with('bankAccount','user')->get();
        return view('admin.withdrawal_requests.index',compact('withdraw_requests'));
    }
    public function send_money(Request $request , $id ,$amount)
    {
        $request->validate([
             'image'=>'required',
        ]);
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move(public_path('admin/assets/images/users/'), $filename);
            $image = 'public/admin/assets/images/users/' . $filename;
        }
        else {
            $image = 'public/admin/assets/images/users/owner.jpg';
        }

        $driver_id = WithdrawalRequest::where('id', $id)->value('driver_id');
        // return $driver_id;
        $wallet_amount = DriverWallet::where('driver_id', $driver_id)->value('total_earning');
        // return  [$wallet_amount-$amount];
        $updated_amount = $wallet_amount - $amount;
        // return  $updated_amount;
        
        DriverWallet::where('driver_id', $driver_id)->update([
            'total_earning'=>$updated_amount,
        ]);
        $approved_request = WithdrawalRequest::find($id);
        $approved_request->update([
            'image'=>$image,
            'status'=>1,
        ]);
        // return  [$approved_request,$image];
        return redirect()->back()->with(['status'=>'success','message'=>'Action is successfully taken.']);

    }
    public function delete_request($id)
    {
        $approved_request = WithdrawalRequest::find($id);
        WithdrawalRequest::destroy($id);
        return redirect()->back()->with(['status'=>'success','message'=>'Request deleted successfully.']);
    }

    public function show_receipts($id)
    {
        $driver = WithdrawalRequest::where('driver_id',$id)->where('status',1)->get();
        return view('admin.wallet.withdrawals.index',compact('driver'));
        return $driver;
    }

}
