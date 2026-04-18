<?php
// app/Http/Controllers/SuperAdmin/SuperAdminReportController.php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Category;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SuperAdminReportExport;

class SuperAdminReportController extends Controller
{
    // Status yang dianggap valid untuk dihitung di statistik
    // pending  = belum bayar  → TIDAK dihitung
    // cancelled = dibatalkan  → TIDAK dihitung
    protected array $validStatuses = ['paid', 'processing', 'shipped', 'completed'];

    public function index(Request $request)
    {
        $startDate     = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate       = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));
        $province      = $request->input('province');
        $categoryId    = $request->input('category_id');
        $paymentMethod = $request->input('payment_method');
        $status        = $request->input('status');

        // =====================
        // QUERY DASAR
        // =====================
        $baseQuery = Order::with(['items.product.category', 'payment'])
            ->whereBetween('created_at', [
                $startDate . ' 00:00:00',
                $endDate   . ' 23:59:59',
            ]);

        if ($province)      $baseQuery->where('province', $province);
        if ($status)        $baseQuery->where('status', $status);
        if ($paymentMethod) $baseQuery->whereHas('payment', fn($q) => $q->where('payment_type', $paymentMethod));
        if ($categoryId)    $baseQuery->whereHas('items.product', fn($q) => $q->where('category_id', $categoryId));

        // =====================
        // STATISTIK
        // Selalu hanya hitung status valid, tidak peduli filter status apapun
        // pending & cancelled tidak pernah dihitung di statistik
        // =====================
        $statsQuery = clone $baseQuery;
        $statsQuery->whereIn('status', $this->validStatuses);
        $allOrders = $statsQuery->get();

        $stats = [
            'total_revenue'    => $allOrders->sum(fn($o) => ($o->subtotal ?? 0) + ($o->shipping_cost ?? 0)),
            'total_orders'     => $allOrders->count(),
            'total_items_sold' => $allOrders->sum(fn($o) => $o->items->sum('quantity')),
            'avg_order_value'  => $allOrders->count() > 0
                ? round($allOrders->avg(fn($o) => ($o->subtotal ?? 0) + ($o->shipping_cost ?? 0)), 0)
                : 0,
        ];

        // =====================
        // TABEL (dengan pagination)
        // Tetap tampilkan semua status agar admin bisa melihat semua pesanan
        // =====================
        $orders = (clone $baseQuery)->latest()->paginate(5)->withQueryString();

        // =====================
        // DROPDOWN OPTIONS
        // =====================
        $provinceOptions = Order::whereNotNull('province')
            ->where('province', '!=', '')
            ->whereNull('deleted_at')
            ->distinct()
            ->orderBy('province')
            ->pluck('province');

        $categoryOptions = Category::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        $paymentMethodOptions = [
            'bank_transfer' => 'Transfer Bank (VA)',
            'echannel'      => 'Mandiri E-Channel',
            'cstore'        => 'Minimarket',
            'gopay'         => 'GoPay',
            'qris'          => 'QRIS',
            'shopeepay'     => 'ShopeePay',
            'credit_card'   => 'Kartu Kredit',
        ];

        $statusOptions = [
            'pending'    => 'Menunggu Pembayaran',
            'paid'       => 'Sudah Dibayar',
            'processing' => 'Diproses',
            'shipped'    => 'Dikirim',
            'completed'  => 'Selesai',
            'cancelled'  => 'Dibatalkan',
        ];

        return view('superadmin.reports.index', compact(
            'orders', 'stats',
            'startDate', 'endDate',
            'province', 'categoryId', 'paymentMethod', 'status',
            'provinceOptions', 'categoryOptions',
            'paymentMethodOptions', 'statusOptions'
        ));
    }

    public function exportPdf(Request $request)
    {
        $startDate     = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate       = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));
        $province      = $request->input('province');
        $categoryId    = $request->input('category_id');
        $paymentMethod = $request->input('payment_method');
        $status        = $request->input('status');

        $query = Order::with(['items.product.category', 'payment'])
            ->whereBetween('created_at', [
                $startDate . ' 00:00:00',
                $endDate   . ' 23:59:59',
            ])
            ->orderBy('created_at', 'asc');

        if ($province)      $query->where('province', $province);
        if ($status)        $query->where('status', $status);
        if ($paymentMethod) $query->whereHas('payment', fn($q) => $q->where('payment_type', $paymentMethod));
        if ($categoryId)    $query->whereHas('items.product', fn($q) => $q->where('category_id', $categoryId));

        // Tabel PDF: semua data sesuai filter
        $orders = $query->get();

        // Statistik PDF: selalu hanya status valid, tidak peduli filter status apapun
        $statsOrders = $orders->filter(fn($o) => in_array($o->status, $this->validStatuses));

        $stats = [
            'total_revenue'    => $statsOrders->sum(fn($o) => ($o->subtotal ?? 0) + ($o->shipping_cost ?? 0)),
            'total_orders'     => $statsOrders->count(),
            'total_items_sold' => $statsOrders->sum(fn($o) => $o->items->sum('quantity')),
            'avg_order_value'  => $statsOrders->count() > 0
                ? round($statsOrders->avg(fn($o) => ($o->subtotal ?? 0) + ($o->shipping_cost ?? 0)), 0)
                : 0,
        ];

        $activeFilters = array_filter([
            'Provinsi'          => $province,
            'Kategori'          => $categoryId ? Category::find($categoryId)?->name : null,
            'Metode Pembayaran' => $paymentMethod,
            'Status'            => $status,
        ]);

        $pdf = Pdf::loadView('superadmin.reports.pdf', compact(
            'orders', 'stats', 'startDate', 'endDate', 'activeFilters'
        ))->setPaper('a4', 'landscape');

        return $pdf->download('laporan-penjualan-' . $startDate . '-sd-' . $endDate . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $startDate     = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate       = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));
        $province      = $request->input('province');
        $categoryId    = $request->input('category_id');
        $paymentMethod = $request->input('payment_method');
        $status        = $request->input('status');

        $fileName = 'laporan-penjualan-' . $startDate . '-sd-' . $endDate . '.xlsx';

        return Excel::download(
            new SuperAdminReportExport($startDate, $endDate, $province, $categoryId, $paymentMethod, $status),
            $fileName
        );
    }
}