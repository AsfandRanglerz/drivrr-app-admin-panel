<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Question;
use Illuminate\Http\Request;
use App\Mail\SendResponseToUser;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class HelpAndSupportController extends Controller
{
    public function index()
    {

        return view('admin.helpAndSupport.index');
    }

    public function getData($type)
    {
        $data = Question::whereHas('user.roles', function ($query) use ($type) {
            $query->where('name', $type);
        })
            ->latest()
            ->get();

        $data = $data->map(function ($question) {
            return [
                'id' => $question->id,
                'fname' => $question->user->fname,
                'lname' => $question->user->lname,
                'email' => $question->user->email,
                'title' => $question->title,
                'details' => $question->details
            ];
        });

        return response()->json(['data' => $data]);
    }

    public function send(Request $request, $id)
    {
        $request->validate([
            'answer' => 'required|string',
        ]);

        $query = Question::where('id', $id)->where('answer', NULL)->first();
        if ($query) {
            $query->update([
                'answer' => $request->answer,
            ]);
            return response()->json(['status' => true, 'message' => 'Feedback sent successfully.']);
        } else {
            return response()->json(['status' => false, 'message' => 'No matching question found.']);
        }
    }
}
