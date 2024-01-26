<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function chatfavourite(){

        return $this->belongsTo('App\Models\ChatFavourite','chat_favourites_id');
    }
}
