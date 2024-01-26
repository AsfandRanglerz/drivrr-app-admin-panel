<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatFavourite extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function User()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
    public function admin()
    {
        return $this->belongsTo('App\Models\Admin', 'admin_id', 'id');
    }
    public function chatmessage()
    {
        return $this->hasMany('App\Models\ChatMessage', 'chat_favourites_id', 'id');
    }
}
