<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\WithdrawalRequest;
class DriverWalletController extends Controller
{
    public function add_withdrawal_request(Request $request , $id)
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
        $add_withdrawal_request = WithdrawalRequest::create([
            'driver_id' => $id,
            'withdrawal_amount' => $request->withdrawal_amount,
        ]);
        return response()->json([
            'message' => 'Request Send successfully.',
            'status' => 'Success.',
            'this is a job you created' => $add_withdrawal_request,
        ], 200);

    }
}
