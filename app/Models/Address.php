<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'user_id', 'label', 'recipient_name', 'recipient_phone',
        'province_id', 'province_name', 'city_id', 'city_name', 'city_type',
        'postal_code', 'full_address', 'is_default'
    ];

    protected $casts = [
        'is_default' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Set alamat ini jadi default, yang lain jadi false
    public function setAsDefault()
    {
        $this->user->addresses()->where('is_default', true)->update(['is_default' => false]);
        $this->update(['is_default' => true]);
    }
}