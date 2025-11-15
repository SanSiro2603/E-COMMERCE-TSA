<?php
// app/Exports/SalesReportExport.php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SalesReportExport implements FromCollection, WithHeadings, WithMapping
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        $orders = Order::whereBetween('created_at', [$this->startDate, $this->endDate])
            ->where('status', 'completed')
            ->with(['user', 'address', 'items'])
            ->orderBy('created_at', 'asc')
            ->get();

        // Tambahkan jumlah transaksi per pengguna
        $orders->each(function($order) {
            $order->transactions_count = Order::where('user_id', $order->user_id)
                ->where('status', 'completed')
                ->whereBetween('created_at', [$this->startDate, $this->endDate])
                ->count();
        });

        return $orders;
    }

    public function headings(): array
    {
        return [
            'No.',
            'No. Pesanan',
            'Tanggal',
            'Nama Pembeli',
            'Provinsi',
            'Kota',
            'Alamat',
            'No. Telp',
            // 'Jumlah Transaksi',
            'Total (Rp)',
        ];
    }

    public function map($order): array
    {
        static $no = 1; // untuk nomor urut

        return [
            $no++,
            $order->order_number,
            $order->created_at->format('d/m/Y'),
            $order->recipient_name ?? $order->user->name,
            $order->province ?? '-',
            $order->city ?? '-',
            $order->address?->full_address ?? $order->shipping_address ?? '-',
            $order->recipient_phone ?? '-',
            // $order->transactions_count,
            number_format($order->grand_total),
        ];
    }

}
