<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model
{
    use HasFactory;
    protected $table = 'role_user';
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // Define the relationship with the Role model
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
