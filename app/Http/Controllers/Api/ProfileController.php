<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{

    public function show($id)
    {
        // Fetch user data by ID
        $user_data = User::with('roles')->find($id);
        if ($user_data) {
            $role_id = $user_data->roles->first()->pivot->role_id;
            $user_data['role_id'] = $role_id;

            return response()->json([
                'status' => 'success',
                'data' => $user_data,
            ], 200);
        } else {
            return response()->json([
                'message' => 'User not found.',
                'status' => 'Failed',
            ], 404);
        }
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'fname' => 'required',
            'lname' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }

        $user = User::find($id);

        if (!$user) {
            return $this->sendError('User not found', 404);
        }

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move(public_path('admin/assets/images/users/'), $filename);
            $image = 'public/admin/assets/images/users/' . $filename;
        } else {
            $image = 'public/admin/assets/images/users/owner.png';
        }

        // Update user data
        $user->update([
            'fname' => $request->fname,
            'lname' => $request->lname,
            'phone' => $request->phone,
            'email' => $request->email,
            'image' => $image,
        ]);

        // Fetch the updated user data
        // $updatedUser = User::find($id);
        $updatedUser = User::with('roles')->find($id);
        if ($updatedUser) {
            $role_id =   $updatedUser->roles->first()->pivot->role_id;
            $updatedUser['role_id'] = $role_id;

            return response()->json([
                'status' => 'success',
                'data' =>   $updatedUser,
            ], 200);
        } else {
            return response()->json([
                'message' => 'User not found.',
                'status' => 'Failed',
            ], 404);
        }
    }
}
