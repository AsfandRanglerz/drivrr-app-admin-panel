<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;
    Protected $fillable = ['name'];
    public function job()
    {
        return $this->hasOne(DriverVehicle::class,'vehicle_id');
    }
}
