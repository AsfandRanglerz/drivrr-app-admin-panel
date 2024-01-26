<?php

namespace App\Http\Controllers\Api;

use Pusher\Pusher;
use App\Models\Admin;
use App\Models\ChatMessage;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\ChatFavourite;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    //    Chat Admin Favourite
    public function index()
    {
        $data['chatfavourites'] = ChatFavourite::where('user_deleted', 0)->where('user_id', Auth::id())->with('admin')->latest()->first();
        return $this->sendSuccess('Admin Chat', compact('data'));
    }

    // Get Admin Data
    public function getAdmin(){
      $data = Admin::select('id')->first();
      return $this->sendSuccess('Admin Data', compact('data'));
    }

    //Get All Messages
    public function get_ChatMessages(Request $request)
    {

        // ChatMessage::where('user_id', Auth::id())->update(['seen' => 1]);
        $data['chat_favourite'] = ChatFavourite::where('user_id', Auth::id())->with('admin')->first();
        $data['chat_messages'] = ChatMessage::where('chat_favourites_id', $data['chat_favourite']->id)->where('user_deleted', 0)->get();
        return $this->sendSuccess('User Messages', compact('data'));
    }

    // Message send (to store messages in database)
    public function store(Request $request)
    {
        $chat = ChatFavourite::where('admin_id', $request->admin_id)->where('user_id', Auth::id())->first();

        if(isset($chat)){
            $chat->admin_deleted=0;
            $chat->save();
        }
        $exists = ChatFavourite::where('admin_id', $request->admin_id)->where('user_id', Auth::id())->exists();
        if (!$exists) {
            $chatfavourite = ChatFavourite::create([
                'user_id' => Auth::id(),
                'admin_id' => $request->admin_id,
            ]);
            $data['chatfavourite'] = $chatfavourite;
            if ($request->hasFile('body')) {
                $filePath = $request->file('body')->store('uploads');

                $data['chatdata'] = ChatMessage::create([
                    'chat_favourites_id' => $chatfavourite->id,
                    'sender_type' => $request->sender_type,
                    'body' => $filePath,
                ]);
            } else {

                $data['chatdata'] = ChatMessage::create([
                    'chat_favourites_id' => $chatfavourite->id,
                    'sender_type' => $request->sender_type,
                    'body' => $request->body,
                ]);
            }
        } else {
            if ($request->hasFile('body')) {
                $filePath = $request->file('body')->store('uploads');

                $data['chatdata'] = ChatMessage::create([
                    'chat_favourites_id' => $request->chatfavourites_id,
                    'sender_type' => $request->sender_type,
                    'body' => $filePath,
                ]);
            } else {

                $data['chatdata'] = ChatMessage::create([
                    'chat_favourites_id' => $request->chat_favourites_id,
                    'sender_type' => $request->sender_type,
                    'body' => $request->body,
                ]);
            }
        }
        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            [
                'cluster' => env('PUSHER_APP_CLUSTER'),
                'encrypted' => true,
            ]
        );
        $pusher->trigger('chat', 'new-message', [
            'message' => $data,
        ]);
        return $this->sendSuccess('Message send successfully', compact('data'));
    }

    // single message delete
    public function messageDeleted(Request $request)
    {
        $admin = ChatMessage::find($request->id);
        if ($admin->admin_deleted == 0) {
            $admin->update(['user_deleted' => 1]);
        } else {
            $admin->delete();
        }
        return $this->sendSuccess('Message delete successfully');
    }

    // All Messages Delete
    public function allMessageDeleted(Request $request)
    {
        $admins = ChatMessage::where('chat_favourites_id', $request->id)->get();
        foreach ($admins as $admin) {
            if ($admin->admin_deleted == 0) {
                $admin->update(['user_deleted' => 1]);
            } else {
                $admin->delete();
            }
        }
        return $this->sendSuccess('Messages delete successfully');
    }
}
