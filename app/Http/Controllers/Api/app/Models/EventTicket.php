<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventTicket extends Model
{
    use HasFactory;
    protected $guarded=[];
    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function event()
    {
        return $this->belongsTo(Event::class,'event_id','id');
    }
    public function getPhotoAttribute($path)
    {
        if ($path){
            return asset($path);
        }else{
            return null;
        }
    }
}
