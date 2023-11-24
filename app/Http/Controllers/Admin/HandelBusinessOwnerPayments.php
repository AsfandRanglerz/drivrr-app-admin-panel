<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HandelBusinessOwnerPayments extends Controller
{
     public function show_owner_payments()
     {
        return view('admin.payments.index');
     }
}
