<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'transaction_id', 'payment_method', 'gateway',
        'amount', 'status', 'gateway_response', 'paid_at', 'expired_at'
    ];

    protected $casts = ['gateway_response' => 'array'];

    public function order() { return $this->belongsTo(Order::class); }
}