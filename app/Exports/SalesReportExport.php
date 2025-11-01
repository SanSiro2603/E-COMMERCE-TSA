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
        return Order::whereBetween('created_at', [$this->startDate, $this->endDate])
            ->where('status', 'completed')
            ->with('user')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No. Pesanan',
            'Pembeli',
            'Tanggal',
            'Total (Rp)',
            'Status'
        ];
    }

    public function map($order): array
    {
        return [
            $order->order_number,
            $order->user->name,
            $order->created_at->format('d/m/Y H:i'),
            number_format($order->grand_total),
            ucfirst($order->status)
        ];
    }
}