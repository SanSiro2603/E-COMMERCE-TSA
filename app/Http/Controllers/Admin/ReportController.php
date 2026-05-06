<?php
// app/Http/Controllers/Admin/ReportController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\SalesReportExport;
use Maatwebsite\Excel\Facades\Excel;

// Controller laporan penjualan — tampil, export PDF, export Excel
// View   : resources/views/admin/reports/index.blade.php
// Export : app/Exports/SalesReportExport.php
class ReportController extends Controller
{
    // Status yang dihitung di statistik (pending & cancelled TIDAK dihitung)
    // [+] Tambah atau hapus status di sini jika ada perubahan aturan bisnis
    protected array $validStatuses = ['paid', 'processing', 'shipped', 'completed'];

    public function index(Request $request)
    {
        // Default: periode bulan berjalan
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate   = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));
        $status    = $request->input('status');

        // Query dasar: filter rentang tanggal + eager load relasi
        // [+] Tambah relasi ke with([]) jika perlu tampilkan data tambahan di tabel
        $baseQuery = Order::with(['user', 'items.product'])
            ->whereBetween('created_at', [
                $startDate . ' 00:00:00',
                $endDate   . ' 23:59:59',
            ]);

        if ($status) $baseQuery->where('status', $status);

        // STATISTIK — selalu hanya dari $validStatuses, tidak peduli filter status user
        $statsQuery  = clone $baseQuery;
        $statsOrders = $statsQuery->whereIn('status', $this->validStatuses)->get();

        // [+] Tambah metrik baru di $stats jika dosen minta tambah kartu statistik
        $stats = [
            'total_revenue'    => $statsOrders->sum('grand_total'),
            'total_orders'     => $statsOrders->count(),
            'total_items_sold' => $statsOrders->sum(fn($o) => $o->items->sum('quantity')),
            'avg_order_value'  => $statsOrders->count() > 0
                ? round($statsOrders->avg('grand_total'), 0)
                : 0,
        ];

        // TABEL — tampilkan semua status agar admin bisa lihat semua pesanan
        // [+] Ganti angka 5 untuk ubah jumlah baris per halaman
        $orders = (clone $baseQuery)->latest()->paginate(5)->withQueryString();

        // [+] Tambah status baru di sini jika ada perubahan enum
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

    // Export laporan ke PDF — menggunakan DomPDF
    // View PDF: resources/views/admin/reports/pdf.blade.php
    public function exportPdf(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate   = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));
        $status    = $request->input('status');

        // [+] Tambah relasi ke with([]) jika perlu kolom baru di PDF
        $query = Order::with(['user', 'items.product'])
            ->whereBetween('created_at', [
                $startDate . ' 00:00:00',
                $endDate   . ' 23:59:59',
            ])
            ->orderBy('created_at', 'asc');

        if ($status) $query->where('status', $status);

        $orders = $query->get();

        // Statistik PDF: sama dengan aturan di index(), hanya dari validStatuses
        $statsOrders = $orders->filter(fn($o) => in_array($o->status, $this->validStatuses));

        $stats = [
            'total_revenue'    => $statsOrders->sum('grand_total'),
            'total_orders'     => $statsOrders->count(),
            'total_items_sold' => $statsOrders->sum(fn($o) => $o->items->sum('quantity')),
            'avg_order_value'  => $statsOrders->count() > 0
                ? round($statsOrders->avg('grand_total'), 0)
                : 0,
        ];

        // [+] Ganti 'landscape' ke 'portrait' jika perlu orientasi berbeda
        $pdf = Pdf::loadView('admin.reports.pdf', compact(
            'orders', 'stats', 'startDate', 'endDate'
        ))->setPaper('a4', 'landscape');

        return $pdf->download('laporan-penjualan-' . $startDate . '-sd-' . $endDate . '.pdf');
    }

    // Export laporan ke Excel — menggunakan SalesReportExport
    // Logika styling & isi kolom ada di: app/Exports/SalesReportExport.php
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