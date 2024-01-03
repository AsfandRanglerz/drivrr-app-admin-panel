<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PushNotificationController extends Controller
{
    public function notificationIndex()
    {
        return view('admin.notifications.index');
    }
    public function notificationCreate()
    {
        return view('admin.notifications.create');
    }
}
