<?php
// app/Http/Controllers/Admin/ReportController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\SalesReportExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate   = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));
        $status    = $request->input('status');

        // =====================
        // QUERY DASAR
        // =====================
        $baseQuery = Order::with(['user', 'items.product'])
            ->whereBetween('created_at', [
                $startDate . ' 00:00:00',
                $endDate   . ' 23:59:59',
            ]);

        if ($status) $baseQuery->where('status', $status);

        // =====================
        // STATISTIK — dari SEMUA data (bukan dari paginate)
        // =====================
        $allOrders = (clone $baseQuery)->get();

        $stats = [
            'total_revenue'    => $allOrders->sum('grand_total'),
            'total_orders'     => $allOrders->count(),
            'total_items_sold' => $allOrders->sum(fn($o) => $o->items->sum('quantity')),
            'avg_order_value'  => $allOrders->count() > 0
                ? round($allOrders->avg('grand_total'), 0)
                : 0,
        ];

        // =====================
        // TABEL dengan pagination 5
        // =====================
        $orders = (clone $baseQuery)->latest()->paginate(5)->withQueryString();

        // =====================
        // DROPDOWN STATUS
        // =====================
        $statusOptions = [
            'pending'    => 'Menunggu Pembayaran',
            'paid'       => 'Sudah Dibayar',
            'processing' => 'Diproses',
            'shipped'    => 'Dikirim',
            'completed'  => 'Selesai',
            'cancelled'  => 'Dibatalkan',
        ];

        return view('admin.reports.index', compact(
            'orders', 'stats',
            'startDate', 'endDate', 'status',
            'statusOptions'
        ));
    }

    public function exportPdf(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate   = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));
        $status    = $request->input('status');

        $query = Order::with(['user', 'items.product'])
            ->whereBetween('created_at', [
                $startDate . ' 00:00:00',
                $endDate   . ' 23:59:59',
            ])
            ->orderBy('created_at', 'asc');

        if ($status) $query->where('status', $status);

        $orders = $query->get();

        $stats = [
            'total_revenue'    => $orders->sum('grand_total'),
            'total_orders'     => $orders->count(),
            'total_items_sold' => $orders->sum(fn($o) => $o->items->sum('quantity')),
            'avg_order_value'  => $orders->count() > 0
                ? round($orders->avg('grand_total'), 0)
                : 0,
        ];

        $pdf = Pdf::loadView('admin.reports.pdf', compact(
            'orders', 'stats', 'startDate', 'endDate'
        ))->setPaper('a4', 'landscape');

        return $pdf->download('laporan-penjualan-' . $startDate . '-sd-' . $endDate . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate   = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));
        $status    = $request->input('status');

        $fileName = 'laporan-penjualan-' . $startDate . '-sd-' . $endDate . '.xlsx';

        return Excel::download(
            new SalesReportExport($startDate, $endDate, $status),
            $fileName
        );
    }
}