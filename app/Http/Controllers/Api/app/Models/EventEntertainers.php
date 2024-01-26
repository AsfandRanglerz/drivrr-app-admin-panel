<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventEntertainers extends Model
{
    use HasFactory;
    protected $guarded=[];
    public function entertainer()
    {
        return $this->belongsTo('App\Models\EntertainerDetail','entertainer_details_id','id');
    }
}
