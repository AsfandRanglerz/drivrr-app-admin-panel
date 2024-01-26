<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Job;
use App\Models\PaymentRequest;

class HandelBusinessOwnerPayments extends Controller
{
    public function show_owner_payments()
    {
        $paymentRequests = PaymentRequest::with('owner', 'driver', 'job.vehicle')->get();
        // return $paymentRequests;
        return view('admin.payments.index', compact('paymentRequests'));
    }
    public function completeJobs()
    {
        $paymentRequests = PaymentRequest::with('owner', 'driver', 'job.vehicle')->latest()->get();
        return view('admin.completedjobs.index', compact('paymentRequests'));
    }
}
