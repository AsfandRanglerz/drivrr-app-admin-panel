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
    public function send_money(Request $request , $id)
    {
        $withdraw = WithdrawalRequest::find($id);
        $drivrr_id = $withdraw->driver_id;
        $driver_wallet = DriverWallet::find($drivrr_id);
        return $driver_wallet;
        $request->validate([
             'image'=>'required',
        ]);
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move(public_path('admin/assets/images/approve/'), $filename);
            $image = 'public/admin/assets/images/approve/' . $filename;
        }
        else {
            $image = 'public/admin/assets/images/approve/owner.jpg';
        }
        // $user = DriverWallet::
        // $approve = DriverWallet::create([

        // ]);
    }
}
