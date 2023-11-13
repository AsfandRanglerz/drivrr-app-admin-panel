<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use App\Models\BankAccount;


class BankAccountController extends Controller
{
    public function store_account(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'bank_name' => 'required',
            'holder_name' => 'required',
            'account_number' => 'required',
            'status' => 'required',
        ]);
        if (!$validator) {
            return $this->sendError($validator->errors()->first());
        }
        $driver = User::find($id);
        $account = BankAccount::create([
            'user_id' => $id,
            'bank_name' => $request->bank_name,
            'holder_name' => $request->holder_name,
            'account_number' => $request->account_number,
            'status' => $request->status,
        ]);
        $all = BankAccount::all();
        if ($request->status === '1') {
            BankAccount::where('user_id', $id)
                ->where('id', '!=', $account->id)
                ->update(['status' => '0']);
        }
        return response()->json([
            'message' => 'Account information added successfully.',
            'status' => 'Success.',
            'account_data' => $account,
            'all accounts' => $all,
            'driver_data' => $driver,
        ]);
    }

    public function update_account(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'bank_name' => 'required',
            'holder_name' => 'required',
            'account_number' => 'required',
            'status' => 'required',
        ]);
        if (!$validator) {
            return $this->sendError($validator->errors()->first());
        }
        $account = BankAccount::find($id);
        $account->update([
            'bank_name' => $request->bank_name,
            'holder_name' => $request->holder_name,
            'account_number' => $request->account_number,
            'status' => $request->status,
        ]);
        if ($request->status === '1') {
            // Deactivate all other accounts for the same user
            BankAccount::where('user_id', $id)
                ->where('id', '!=', $account->id)
                ->update(['status' => '0']);
        }
        return response()->json([
            'message' => 'Account information updated successfully.',
            'status' => 'Success.',
            'account_data' => $account,
        ]);
    }
}
