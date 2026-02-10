<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'profile_photo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relasi ke Order
    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id');
    }

    // Relasi ke Cart
    public function carts()
    {
        return $this->hasMany(Cart::class, 'user_id');
    }

    // Accessor: $user->cart_count
    public function getCartCountAttribute()
    {
        return $this->carts()->count();
    }
    public function addresses()
{
    return $this->hasMany(\App\Models\Address::class, 'user_id');
}

}