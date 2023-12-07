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
            'your data' => $query,
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
    public function send(Request $request, $id)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'User not found.',
                ], 404);
            }

            $user_email = $user->email;
            $message = $request->message;

            if ($message == "") {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Your message is empty.',
                ], 400);
            } else {
                Mail::to($user_email)->send(new SendResponseToUser($message));

                return response()->json([
                    'status' => 'success',
                    'message' => $message,
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
