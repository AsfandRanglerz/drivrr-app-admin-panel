<?php

namespace App\Http\Controllers\Admin;

use App\Models\Document;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\driverLisenceApprovel;
use App\Mail\driverLisenceRejection;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

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
    public function showPaymentRequest($id)
    {
        $lisenceApprovel = Document::with('user')->find($id);
        if (!$lisenceApprovel) {
            return response()->json(['alert' => 'error', 'message' => 'Payment Id Not Found'], 500);
        }
        return response()->json($lisenceApprovel);
    }
    public function getStatus($id)
    {
        try {
            $lisenceApprovel = Document::findOrFail($id);
            return response()->json(['status' => $lisenceApprovel->is_active]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed To Get' . $e->getMessage()], 500);
        }
    }
    public function updateStatus(Request $request, $id)
    {
        try {
            $lisenceApprovel = Document::with('user')->findOrFail($id);
            $lisenceApprovel->is_active = $request->is_active;
            if ($request->is_active == 2) {
                $lisenceApprovel->rejection_reason = $request->rejection_reason;
                $data['drivername'] =  $lisenceApprovel->user->fname . ' ' .  $lisenceApprovel->user->lname;
                $data['driveremail'] =  $lisenceApprovel->user->email;
                $data['rejection_reason'] =  $lisenceApprovel->rejection_reason;
                Mail::to($lisenceApprovel->user->email)->send(new driverLisenceRejection($data));
            } else {
                $data['drivername'] =  $lisenceApprovel->user->fname . ' ' .  $lisenceApprovel->user->lname;
                $data['driveremail'] =  $lisenceApprovel->user->email;
                Mail::to($lisenceApprovel->user->email)->send(new driverLisenceApprovel($data));
            }
            $lisenceApprovel->save();
            return response()->json(['alert' => 'success', 'message' => 'Status updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update status: ' . $e->getMessage()], 500);
        }
    }
    public function getlisenceApprovelCount()
    {
        try {
            $lisenceApprovel = Document::where('is_active', 0)->count();
            return response()->json(['lisenceApprovel' => $lisenceApprovel]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
