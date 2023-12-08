<?php

namespace App\Models;

use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DriverVehicle extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'vehicle_id', 'vehicle_brand', 'model', 'year', 'license_plate', 'color', 'is_active'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class,);
    }
    public function job()
    {
        return $this->belongsTo(Job::class, 'vehicle_id', 'vehicle_id');
    }

}
