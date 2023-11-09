<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\BankAccount;

class BankAccountController extends Controller
{
    public function store_account(Request $request ,$id)
    {
        $validator = Validator::make($request->all(),[
            'bank_name'=>'required',
            'holder_name'=>'required',
            'account_number'=>'required',
        ]);
        if(!$validator)
        {
            return $this->sendError($validator->errors()->first());
        }
        $driver = User::find($id);
        $account = BankAccount::create([
            'user_id'=>$id,
            'bank_name'=>$request->bank_name,
            'holder_name'=>$request->holder_name,
            'account_number'=>$request->account_number,
        ]);
        return response()->json([
            'message'=>'Account information added successfully.',
            'status'=>'Success.',
            'account_data'=> $account,
            'driver_data'=> $driver,
        ]);
    }
}
