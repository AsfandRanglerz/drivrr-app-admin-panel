<?php

namespace App\Http\Controllers\Admin;

use App\Models\Payment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    public function index(){
        $payments = Payment::with('user','entertainer','venue','event')->where('type','guest')->get();
      return view('admin.payment.payment',compact('payments'));
    }
    public function feature(){
        $payments = Payment::with('user','talent.talentCategory','entertainer','venue.venueCategory','event','venuePackage','eventPackage','entertainerFeaturePackage')->where('type','feature')->get();
        return view('admin.payment.feature',compact('payments'));
    }
    public function ticketPayment(){
        $payments = Payment::with('user','ticket','talent.talentCategory','entertainer','venue.venueCategory','event','venuePackage','eventPackage','entertainerFeaturePackage')->where('type','ticket')->get();
        return view('admin.payment.eventTicketPayment',compact('payments'));
    }
    public function status($id)
    {
        $data = Payment::find($id);
        $data->update(['status' => $data->status == 0 ? '1' : '0']);
        return redirect()->back()->with(['status' => true, 'message' => 'Updated Successfully']);
    }
}
