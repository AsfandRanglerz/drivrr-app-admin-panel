<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasFactory,Notifiable;

    protected $guard = 'admin';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'image',
        'role_id',
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
