<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'payment_type',
        'transaction_id',
        'transaction_status',
        'gross_amount',
        'payment_code',
        'pdf_url',
        'transaction_time',
        'expiry_time',
        'snap_token',
        'snap_url',
        'metadata',
    ];

    protected $casts = [
        'gross_amount' => 'decimal:2',
        'transaction_time' => 'datetime',
        'expiry_time' => 'datetime',
        'metadata' => 'array',
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Helpers
    public function isPending()
    {
        return $this->transaction_status === 'pending';
    }

    public function isSuccess()
    {
        return in_array($this->transaction_status, ['capture', 'settlement']);
    }

    public function isFailed()
    {
        return in_array($this->transaction_status, ['deny', 'cancel', 'expire']);
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => 'Menunggu Pembayaran',
            'capture' => 'Berhasil',
            'settlement' => 'Berhasil',
            'deny' => 'Ditolak',
            'cancel' => 'Dibatalkan',
            'expire' => 'Kadaluarsa',
        ];

        return $labels[$this->transaction_status] ?? $this->transaction_status;
    }
}