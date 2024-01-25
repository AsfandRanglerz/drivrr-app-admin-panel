<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class ContactUsController extends Controller
{
    public function contactIndex()
    {
        return view('admin.contactus.index');
    }
    public function submitForm(Request $request)
    {
        $adminEmail = "ranabilal19999@gmail.com";

        $request->validate([
            'email' => 'required|email',
            'description' => 'required',
        ]);
        $email = $request->input('email');
        $description = $request->input('description');

        // Compose and send email
        Mail::to($adminEmail)->send(new \App\Mail\AdminContactUs($email, $description));

        if (count(Mail::failures()) > 0) {
            return redirect()->back()->with(['status' => false, 'message' => 'Failed to send email. Please try again.']);
        }

        return redirect()->back()->with(['status' => true, 'message' => 'Your Query Sent successfully To Admin']);
    }
}
