<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Shipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'courier', 'courier_service', 'tracking_number',
        'notes', 'status', 'shipped_at', 'delivered_at', 'history'
    ];

    protected $casts = [
        'shipped_at' => 'datetime', 'delivered_at' => 'datetime',
        'history' => 'array'
    ];

    public function order() { return $this->belongsTo(Order::class); }

    public function getStatusLabelAttribute()
    {
        return [
            'preparing' => 'Sedang Dipersiapkan',
            'shipped' => 'Sudah Dikirim',
            'in_transit' => 'Dalam Perjalanan',
            'delivered' => 'Sudah Diterima',
        ][$this->status] ?? ucfirst($this->status);
    }

    public function getStatusColorAttribute()
    {
        return [
            'preparing' => 'gray', 'shipped' => 'blue',
            'in_transit' => 'yellow', 'delivered' => 'green',
        ][$this->status] ?? 'gray';
    }
}