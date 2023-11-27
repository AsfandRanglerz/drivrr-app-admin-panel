<?php

namespace App\Models;

use App\Models\DriverVehicle;
use Faker\Provider\ar_EG\Payment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Job extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }
    public function DriverVehicle()
    {
        return $this->belongsTo(DriverVehicle::class, 'vehicles_id', 'vehicles_id');
    }
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
