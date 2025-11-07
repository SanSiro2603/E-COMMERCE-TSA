<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'order_id_midtrans',
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
        'bank',
        'va_number',
        'bill_key',
        'biller_code',
        'store',
        'gopay_url',
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

    // === STATUS HELPER ===
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

    public function isExpired()
    {
        return $this->expiry_time && now()->greaterThan($this->expiry_time);
    }

    // === LABEL & DISPLAY ===
    public function getStatusLabelAttribute()
    {
        return match($this->transaction_status) {
            'pending' => 'Menunggu Pembayaran',
            'capture', 'settlement' => 'Berhasil',
            'deny' => 'Ditolak',
            'cancel' => 'Dibatalkan',
            'expire' => 'Kadaluarsa',
            default => ucfirst($this->transaction_status ?? 'Unknown')
        };
    }

    public function getPaymentTypeLabelAttribute()
    {
        return match($this->payment_type) {
            'bank_transfer' => 'Transfer Bank',
            'echannel' => 'Mandiri E-Channel',
            'cstore' => 'Minimarket',
            'gopay' => 'GoPay',
            'qris' => 'QRIS',
            'shopeepay' => 'ShopeePay',
            'credit_card' => 'Kartu Kredit',
            default => ucfirst(str_replace('_', ' ', $this->payment_type ?? 'unknown'))
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->transaction_status) {
            'pending' => 'yellow',
            'capture', 'settlement' => 'green',
            'deny', 'cancel', 'expire' => 'red',
            default => 'gray'
        };
    }

    // === FORMATTER ===
    public function getFormattedVaNumberAttribute()
    {
        return $this->va_number ? chunk_split($this->va_number, 4, ' ') : null;
    }

    public function getExpiryCountdownAttribute()
    {
        if (!$this->expiry_time || $this->isExpired()) return null;
        return Carbon::now()->diffForHumans($this->expiry_time, ['parts' => 2, 'join' => ' ']);
    }

    // === URL HELPER ===
    public function getPaymentUrlAttribute()
    {
        if ($this->gopay_url) return $this->gopay_url;
        if ($this->pdf_url) return $this->pdf_url;
        return null;
    }
}