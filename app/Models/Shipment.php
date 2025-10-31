<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Shipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'courier', 'tracking_number', 'notes',
        'status', 'shipped_at', 'delivered_at'
    ];

    public function order() { return $this->belongsTo(Order::class); }
}