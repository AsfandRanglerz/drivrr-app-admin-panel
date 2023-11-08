<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;
    use HasApiTokens, Notifiable, HasRoles;
    // protected $guard = 'web';

    protected $fillable = ['name', 'fname', 'maiden_name', 'lname', 'email', 'image', 'password', 'designation', 'is_active', 'address', 'role_id', 'phone', 'document', 'company_name',
    'company_info'];

    public function usercompany()
    {
        return $this->belongsTo('App\Models\Company', 'company_id', 'id');
    }
    public function userdocument()
    {
        return $this->hasMany(UserDocument::class, 'user_id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
    public function document()
    {
        return $this->hasMany(Document::class, 'user_id');
    }
    // public function getImageAttribute($path)
    // {
    //     if ($path) {
    //         return asset($path);
    //     } else {
    //         return null;
    //     }
    // }
    public function driverVehicle()
    {
        return $this->hasMany(DriverVehicle::class, 'user_id');
    }
    public function job()
    {
        return $this->hasMany(Job::class, 'user_id');
    }
    public function question()
    {
        return $this->hasMany(Question::class, 'user_id');
    }
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'component_permission', 'user_id', 'permission_id');
    }
    public function review()
    {
        return $this->hasMany(Review::class,'owner_id','driver_id');
    }
    public function user_login_with_otps()
    {
        return $this->hasOne(UserLoginWithOtp::class,'user_id');
    }
}
