<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VenuesPhoto extends Model
{
    use HasFactory;
    protected $guarded=[];
    public function Venue(){
        return $this->belongsTo('App\Models\Venue');
    }
    public function getPhotosAttribute($path)
    {
        if ($path){
            return asset($path);
        }else{
            return null;
        }
    }
}
