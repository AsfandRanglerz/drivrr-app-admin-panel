<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentRequest extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function user()
    {
        return $this->belongsTo(User::class,'owner_id','driver_id');
    }
    public function job()
    {
        return $this->belongsTo(Job::class,'job_id');
    }
    public function driver()
    {
        return $this->belongsTo(User::class,'driver_id');
    }
    public function owner()
    {
        return $this->belongsTo(User::class,'owner_id');
    }
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class,);
    }
}
