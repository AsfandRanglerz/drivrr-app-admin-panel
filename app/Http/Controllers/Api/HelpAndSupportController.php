<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Question;
use Illuminate\Http\Request;
use App\Mail\SendResponseToUser;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class HelpAndSupportController extends Controller
{
    public function queryStore(Request $request, $id)
    {
        // $user = User::find($id);
        // return $request;
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'details' => 'required',
        ]);
        if (!$validator) {
            return $this->showError($validator->errors()->first());
        }
        $query = Question::create([
            'user_id' => $id,
            'title' => $request->title,
            'details' => $request->details,
        ]);
        return response()->json([
            'message' => 'Your query has been added.',
            'status' => 'Success.',
            'queryData' => $query,
        ], 200);
    }
    public function get_query($id, $user_id)
    {
        $query = Question::where('id', $id)->where('user_id', $user_id)->whereNotNull('answer')->get();
        return response()->json([
            'message' => 'Your query.',
            'status' => 'Success.',
            'yourData' => $query,
        ], 200);
    }

    public function get($driverId)
    {
        $driverQuery = Question::where('user_id', $driverId)->get();
        if ($driverQuery) {
            return response()->json([
                'message' => 'Your query has been Get.',
                'status' => 'Success.',
                'yourdata' =>  $driverQuery,
            ], 200);
        }
    }
}
