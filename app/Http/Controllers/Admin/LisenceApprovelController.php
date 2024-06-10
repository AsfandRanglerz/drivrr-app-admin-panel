<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;

class LisenceApprovelController extends Controller
{
    public function lisenceApprovelData()
    {
        $lisenceApprovels = Document::with('user')->latest()->get();
        $json_data["data"] =  $lisenceApprovels;
        return json_encode($json_data);
    }
    public function lisenceApprovelIndex()
    {
        $lisenceApprovels = Document::with('user')->latest()->get();
        return view('admin.lisenceApprovel.index', compact('lisenceApprovels'));
    }
}
