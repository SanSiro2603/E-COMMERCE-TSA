<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderShippingSnapshot extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'label',
        'recipient_name',
        'recipient_phone',
        'province_id',
        'province_name',
        'city_id',
        'city_name',
        'city_type',
        'postal_code',
        'full_address',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
