<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Kolom yang boleh diisi (mass assignment)
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'profile_photo',
    ];

    /**
     * Kolom yang disembunyikan saat serialisasi (misal: JSON)
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Konversi tipe data kolom tertentu
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Mutator untuk otomatis enkripsi password
     */
    public function setPasswordAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['password'] = bcrypt($value);
        }
    }

    /**
     * Cek apakah user memiliki role tertentu
     */
    public function hasRole($role)
    {
        return $this->role === $role;
    }
}
