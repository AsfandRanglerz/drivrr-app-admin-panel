<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use App\Models\BankAccount;


class BankAccountController extends Controller
{
    public function fetch($id)
    {
        try {
            $bankAccounts = BankAccount::where('user_id', $id)->get();


            if ($bankAccounts) {
                return response()->json([
                    'message' => 'Bank account details fetched successfully.',
                    'status' => 'Success',
                    'bank_accounts' => $bankAccounts,
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Bank account details fetched Unsuccessfully.',
                    'status' => 'Failed',

                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error fetching bank account details.',
                'status' => 'Error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function delete($accountId)
    {
        try {
            $deletedRows = BankAccount::where('id', $accountId)->delete();

            if ($deletedRows > 0) {
                return response()->json([
                    'message' => 'Bank account deleted successfully.',
                    'status' => 'Success',
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Bank account not found or already deleted.',
                    'status' => 'Failed',
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error deleting bank account.',
                'status' => 'Error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


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
        if ($request->status === '1') {
            BankAccount::where('user_id', $id)
                ->where('id', '!=', $account->id)
                ->update(['status' => '0']);
        }
        $all = BankAccount::all();
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

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }

        $account = BankAccount::find($id);

        if (!$account) {
            return $this->sendError('Account not found.');
        }

        $account->update([
            'bank_name' => $request->bank_name,
            'holder_name' => $request->holder_name,
            'account_number' => $request->account_number,
            'status' => $request->status,
        ]);

        if ($request->status === '1') {
            BankAccount::where('user_id', $account->user_id)
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
