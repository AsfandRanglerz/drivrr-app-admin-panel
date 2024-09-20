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
            'email' => 'required|email|max:255|unique:users,email,' . $id,
        ]);

        if ($validator->fails()) {
            $firstError = $validator->errors()->first();
            return response()->json(['status'=>'error','message' => $firstError],422);
        }


        $user = User::find($id);

        if (!$user) {
            return $this->sendError('User not found', 404);
        }

        // Update user data
        $user->update([
            'fname' => $request->fname,
            'lname' => $request->lname,
            'phone' => $request->phone,
            'email' => $request->email,
            'company_name' => $request->company_name,
            'company_info' => $request->company_info,
        ]);
        if ($request->hasFile('image')) {
            $oldImagePath = $user->image;
            if ($user->image &&  File::exists($oldImagePath)) {
                File::delete($oldImagePath);
            }
            $image = $request->file('image');
            $thumbnail_name = time() . '.' . $image->getClientOriginalExtension();
            $thumbnail_path = 'public/admin/assets/images/users/' . $thumbnail_name;
            $image->move(public_path('admin/assets/images/users'), $thumbnail_name);
            $user->image = $thumbnail_path;
            $user->save();
        }
        // Fetch the updated user data
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
    public function getImage($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'message' => 'User not found.',
                'status' => 'Failed',
            ], 404);
        }

        // Check if the user has an image
        if (!$user->image) {
            return response()->json([
                'message' => 'User has no image.',
                'status' => 'Failed',
            ], 404);
        }

        // Construct the full URL to the user's image
        $imageUrl = asset($user->image);

        return response()->json([
            'status' => 'success',
            'data' => ['image' => $imageUrl],
        ], 200);
    }

    public function updateImage(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'message' => 'User not found.',
                'status' => 'Failed',
            ], 404);
        }

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move(public_path('admin/assets/images/users/'), $filename);
            $image = 'public/admin/assets/images/users/' . $filename;
        } else {
            // Handle the case where no image is provided
            return response()->json([
                'message' => 'No image provided.',
                'status' => 'Failed',
            ], 400);
        }

        // Update the user's image
        $user->update([
            'image' => $image,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Image updated successfully',
            'image' => $image,
        ], 200);
    }
}
