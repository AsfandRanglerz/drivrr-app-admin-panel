<?php

namespace App\Http\Controllers\Api;

use App\Models\Question;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
            'your data' => $query,
        ], 200);
    }
}
