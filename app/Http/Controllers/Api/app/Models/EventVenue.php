<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventVenue extends Model
{
    use HasFactory;
    protected $guarded=[];
    public function venue()
    {
        return $this->belongsTo('App\Models\Venue','venues_id','id');
    }
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

}
