<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
class User extends Authenticatable
{
    use HasApiTokens,HasFactory,Notifiable;
    protected $guarded =[];
    public function entertainerDetail()
    {
        return $this->hasMany('App\Models\EntertainerDetail','user_id');
    }
    public function venues()
    {
        return $this->hasMany('App\Models\Venue','user_id');
    }
    public function events()
    {
        return $this->hasMany('App\Models\Event','user_id');
    }
    public function chatfavourites()
    {
        return $this->hasMany('App\Models\ChatFavourite','user_id');
    }
    public function eventTicket()
    {
        return $this->hasMany('App\Models\EventTicket','user_id');
    }

    // public function talentCategory(){
    //     return $this->belongsToMany('App\Models\TalentCategory','entertainer_details','user_id','category_id');
    //  }
    public function getImageAttribute($path)
    {
        if ($path){
            return asset($path);
        }else{
            return null;
        }
    }



}
