<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Users extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'username', 'password', 'person_id', 'role_id', 'is_active', 'last_login'
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'is_active' => 'boolean',
        'last_login' => 'datetime',
    ];

    public function person()
    {
        return $this->belongsTo(Person::class 'person_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function loyaltyPoints()
    {
        return $this->hasOne(LoyaltyPoint::class);
    }
}
