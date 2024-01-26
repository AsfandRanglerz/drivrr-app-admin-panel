<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VenueCategory extends Model
{
    use HasFactory;
    protected $guarded =[];
    public function Venue(){
        return $this->hasMany('App\Models\Venue','category_id','id');
    }
}
