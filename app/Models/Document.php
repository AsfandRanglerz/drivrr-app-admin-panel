<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;
    protected $fillable= ['image','user_id','is_active'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // public function getImageAttribute($path)
    // {
    //     if ($path){
    //         return asset($path);
    //     }else{
    //         return null;
    //     }
    // }
}
