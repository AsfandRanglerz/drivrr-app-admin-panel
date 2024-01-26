<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntertainerEventPhotos extends Model
{
    use HasFactory;
    protected $guarded=[];
    public function entertainerDetails(){
        return $this->belongsTo('App\Models\EntertainerDetail');
     }

    public function getEventPhotosAttribute($path)
    {
        if ($path){
            return asset($path);
        }else{
            return null;
        }
    }
}
