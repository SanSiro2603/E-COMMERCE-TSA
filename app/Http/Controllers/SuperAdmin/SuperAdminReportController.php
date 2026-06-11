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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

// Controller laporan penjualan SuperAdmin — tampil, export PDF, export Excel
// Perbedaan dengan Admin: ada filter tambahan (provinsi, kategori, metode pembayaran)
// View   : resources/views/superadmin/reports/index.blade.php
// Export : app/Exports/SuperAdminReportExport.php
class SuperAdminReportController extends Controller
{
    // Status yang dihitung di statistik (pending & cancelled TIDAK dihitung)
    // [+] Sesuaikan jika ada perubahan aturan bisnis status valid
    protected array $validStatuses = ['paid', 'processing', 'shipped', 'completed'];

    public function index(Request $request)
    {
        // Default: periode bulan berjalan
        $startDate     = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate       = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));
        $province      = $request->input('province');
        $categoryId    = $request->input('category_id');
        $paymentMethod = $request->input('payment_method');
        $status        = $request->input('status');

        // Query dasar: filter rentang tanggal + eager load relasi
        // [+] Tambah relasi ke with([]) jika perlu tampilkan data tambahan di tabel
        $baseQuery = Order::with(['items.product.category', 'payment', 'address'])
            ->whereBetween('created_at', [
                $startDate . ' 00:00:00',
                $endDate   . ' 23:59:59',
            ]);

        // Filter opsional — masing-masing hanya aktif jika parameter dikirim
        // [+] Tambah blok if baru di sini jika perlu filter tambahan
        if ($province)      $this->applyProvinceFilter($baseQuery, $province);
        if ($status)        $baseQuery->where('status', $status);
        if ($paymentMethod) $baseQuery->where(function ($q) use ($paymentMethod) {
            // Cek di tabel payments (payment_type) ATAU di kolom orders (payment_method)
            $q->whereHas('payment', fn($p) => $p->where('payment_type', $paymentMethod))
              ->orWhere('payment_method', $paymentMethod);
        });
        if ($categoryId)    $this->applyCategoryFilter($baseQuery, $categoryId);

        // STATISTIK — selalu hanya dari $validStatuses, tidak peduli filter status user
        $statsQuery = clone $baseQuery;
        $allOrders  = $statsQuery->whereIn('status', $this->validStatuses)->get();

        // ✅ DIPERBAIKI: gunakan grand_total agar konsisten dengan Admin ReportController
        // dan tidak ada perbedaan angka jika ada diskon/voucher di masa depan
        $stats = [
            'total_revenue'    => $allOrders->sum('grand_total'),
            'total_orders'     => $allOrders->count(),
            'total_items_sold' => $allOrders->sum(fn($o) => $o->items->sum('quantity')),
            'avg_order_value'  => $allOrders->count() > 0
                ? round($allOrders->avg('grand_total'), 0)
                : 0,
        ];

        // TABEL — tampilkan semua status agar admin bisa lihat semua pesanan
        // [+] Ganti angka 5 untuk ubah jumlah baris per halaman
        $orders = (clone $baseQuery)->latest()->paginate(5)->withQueryString();

        // Dropdown opsi provinsi — diambil dari tabel addresses yang terhubung ke orders aktif
        $provinceOptions = collect()
            ->merge(DB::table('orders')
                ->whereNotNull('shipping_province_name')
                ->where('shipping_province_name', '!=', '')
                ->whereNull('deleted_at')
                ->pluck('shipping_province_name'))
            ->merge(DB::table('addresses')
                ->join('orders', 'orders.address_id', '=', 'addresses.id')
                ->whereNotNull('addresses.province_name')
                ->where('addresses.province_name', '!=', '')
                ->whereNull('orders.deleted_at')
                ->pluck('addresses.province_name'))
            ->filter()
            ->unique()
            ->sort()
            ->values();

        // Dropdown opsi kategori — hanya kategori aktif
        $categoryOptions = Category::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        // [+] Tambah metode pembayaran baru di sini jika ada integrasi payment gateway baru
        $paymentMethodOptions = [
            'bank_transfer' => 'Transfer Bank (VA)',
            'echannel'      => 'Mandiri E-Channel',
            'cstore'        => 'Minimarket',
            'gopay'         => 'GoPay',
            'qris'          => 'QRIS',
            'shopeepay'     => 'ShopeePay',
            'credit_card'   => 'Kartu Kredit',
        ];

        // [+] Tambah status baru di sini jika ada perubahan enum
        $statusOptions = [
            'pending'    => 'Menunggu Pembayaran',
            'paid'       => 'Sudah Dibayar',
            'processing' => 'Diproses',
            'shipped'    => 'Dikirim',
            'completed'  => 'Selesai',
            'cancelled'  => 'Dibatalkan',
        ];

        $statsNote = $this->statsNote($status, $statusOptions);

        return view('superadmin.reports.index', compact(
            'orders', 'stats',
            'startDate', 'endDate',
            'province', 'categoryId', 'paymentMethod', 'status',
            'provinceOptions', 'categoryOptions', 'statsNote',
            'paymentMethodOptions', 'statusOptions'
        ));
    }

    // Export laporan ke PDF — menggunakan DomPDF
    // View PDF: resources/views/superadmin/reports/pdf.blade.php
    public function exportPdf(Request $request)
    {
        $startDate     = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate       = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));
        $province      = $request->input('province');
        $categoryId    = $request->input('category_id');
        $paymentMethod = $request->input('payment_method');
        $status        = $request->input('status');

        // [+] Tambah relasi ke with([]) jika perlu kolom baru di PDF
        $query = Order::with(['items.product.category', 'payment', 'address'])
            ->whereBetween('created_at', [
                $startDate . ' 00:00:00',
                $endDate   . ' 23:59:59',
            ])
            ->orderBy('created_at', 'asc');

        if ($province)      $this->applyProvinceFilter($query, $province);
        if ($status)        $query->where('status', $status);
        if ($paymentMethod) $query->where(function ($q) use ($paymentMethod) {
            $q->whereHas('payment', fn($p) => $p->where('payment_type', $paymentMethod))
              ->orWhere('payment_method', $paymentMethod);
        });
        if ($categoryId)    $this->applyCategoryFilter($query, $categoryId);

        $orders = $query->get();

        // Statistik PDF: sama dengan aturan di index(), hanya dari validStatuses
        $statsOrders = $orders->filter(fn($o) => in_array($o->status, $this->validStatuses));

        // ✅ DIPERBAIKI: gunakan grand_total agar konsisten
        $stats = [
            'total_revenue'    => $statsOrders->sum('grand_total'),
            'total_orders'     => $statsOrders->count(),
            'total_items_sold' => $statsOrders->sum(fn($o) => $o->items->sum('quantity')),
            'avg_order_value'  => $statsOrders->count() > 0
                ? round($statsOrders->avg('grand_total'), 0)
                : 0,
        ];

        // Filter aktif dikirim ke PDF untuk ditampilkan di bawah judul
        // [+] Tambah entri baru jika ada filter baru yang perlu ditampilkan di PDF
        $activeFilters = array_filter([
            'Provinsi'          => $province,
            'Kategori'          => $categoryId ? Category::find($categoryId)?->name : null,
            'Metode Pembayaran' => $paymentMethod,
            'Status'            => $status,
        ]);

        // ✅ DIPERBAIKI: tambah $statsNote agar PDF tidak membingungkan
        // ketika superadmin filter status di luar validStatuses (misal: cancelled)
        $statusOptions = [
            'pending'    => 'Menunggu Pembayaran',
            'paid'       => 'Sudah Dibayar',
            'processing' => 'Diproses',
            'shipped'    => 'Dikirim',
            'completed'  => 'Selesai',
            'cancelled'  => 'Dibatalkan',
        ];

        $statsNote = $this->statsNote($status, $statusOptions);

        // [+] Ganti 'landscape' ke 'portrait' jika perlu orientasi berbeda
        $pdf = Pdf::loadView('superadmin.reports.pdf', compact(
            'orders', 'stats', 'startDate', 'endDate', 'activeFilters', 'statsNote'
        ))->setPaper('a4', 'landscape');

        return $pdf->download('laporan-penjualan-' . $startDate . '-sd-' . $endDate . '.pdf');
    }

    // Export laporan ke Excel — menggunakan SuperAdminReportExport
    // Logika styling & isi kolom ada di: app/Exports/SuperAdminReportExport.php
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

    private function applyProvinceFilter(Builder $query, string $province): void
    {
        $query->where(function (Builder $q) use ($province) {
            $q->where('shipping_province_name', $province)
                ->orWhereHas('address', fn($address) => $address->where('province_name', $province));
        });
    }

    private function applyCategoryFilter(Builder $query, $categoryId): void
    {
        $categoryName = Category::query()->whereKey($categoryId)->value('name');

        $query->whereHas('items', function (Builder $items) use ($categoryId, $categoryName) {
            $items->whereHas('product', fn($product) => $product->where('category_id', $categoryId));

            if ($categoryName) {
                $items->orWhere('product_category_name', $categoryName);
            }
        });
    }

    private function statsNote(?string $status, array $statusOptions): ?string
    {
        if (!$status || in_array($status, $this->validStatuses)) {
            return null;
        }

        return 'Pesanan dengan status "'
            . ($statusOptions[$status] ?? $status)
            . '" tetap ditampilkan di tabel untuk analisis, tetapi tidak dihitung pada kartu statistik penjualan karena belum menjadi transaksi valid.';
    }
}
