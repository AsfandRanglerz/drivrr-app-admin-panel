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
        $data['owner'] = User::whereHas('roles', function ($q) {
            $q->where('name', 'Owner');
        })->has('question')->with('question')->orderBy('id', 'DESC')->get();

        $data['driver'] = User::whereHas('roles', function ($q) {
            $q->where('name', 'driver');
        })->has('question')->with('question')->orderBy('id', 'DESC')->get();
        //  return [$data['owner'],$data['driver']];

        return view('admin.helpAndSupport.index', compact('data'));
    }

    public function send(Request $request, $id)
    {
        $user = User::find($id);
        $user_email = $user->email;
        $message = $request->message;

        if ($message == "") {
            return redirect()->back()->with(['status' => true, 'message' => 'Your message is empty.']);
        } else {
            Mail::to($user_email)->send(new SendResponseToUser($message));
        }

        return redirect()->back()->with(['status' => true, 'message' => 'Email sent to that user successfully.']);
    }
}
