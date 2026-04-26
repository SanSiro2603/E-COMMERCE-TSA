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
    // Status yang dianggap valid untuk dihitung di statistik
    // pending  = belum bayar  → TIDAK dihitung
    // cancelled = dibatalkan  → TIDAK dihitung
    protected array $validStatuses = ['paid', 'processing', 'shipped', 'completed'];

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
        // STATISTIK
        // Selalu hanya hitung status valid, tidak peduli filter status apapun
        // pending & cancelled tidak pernah dihitung di statistik
        // =====================
        $statsQuery = clone $baseQuery;
        $statsQuery->whereIn('status', $this->validStatuses);
        $statsOrders = $statsQuery->get();

        $stats = [
            'total_revenue'    => $statsOrders->sum('grand_total'),
            'total_orders'     => $statsOrders->count(),
            'total_items_sold' => $statsOrders->sum(fn($o) => $o->items->sum('quantity')),
            'avg_order_value'  => $statsOrders->count() > 0
                ? round($statsOrders->avg('grand_total'), 0)
                : 0,
        ];

        // =====================
        // TABEL dengan pagination 5
        // Tetap tampilkan semua status agar admin bisa melihat semua pesanan
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

        // Tabel PDF: semua data sesuai filter
        $orders = $query->get();

        // Statistik PDF: selalu hanya status valid, tidak peduli filter status apapun
        $statsOrders = $orders->filter(fn($o) => in_array($o->status, $this->validStatuses));

        $stats = [
            'total_revenue'    => $statsOrders->sum('grand_total'),
            'total_orders'     => $statsOrders->count(),
            'total_items_sold' => $statsOrders->sum(fn($o) => $o->items->sum('quantity')),
            'avg_order_value'  => $statsOrders->count() > 0
                ? round($statsOrders->avg('grand_total'), 0)
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