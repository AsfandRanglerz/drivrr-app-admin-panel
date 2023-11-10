<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\DriverWallet;


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
}
