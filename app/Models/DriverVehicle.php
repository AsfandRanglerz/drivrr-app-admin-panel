<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverVehicle extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','vehicle_id','vehicle_brand','model','year','license_plate','color','is_active'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function vehicle()
    {
        return $this->belongsTo(vehicle::class);
    }
}
