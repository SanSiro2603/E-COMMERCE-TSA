<?php

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

class SuperAdminReportController extends Controller
{
    protected array $validStatuses = ['paid', 'processing', 'shipped', 'completed'];

    public function index(Request $request)
    {
        $startDate     = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate       = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));
        $province      = $request->input('province');
        $categoryId    = $request->input('category_id');
        $paymentMethod = $request->input('payment_method');
        $status        = $request->input('status');

        $baseQuery = Order::with(['items.product.category', 'payment', 'address', 'shippingSnapshot'])
            ->whereBetween('created_at', [
                $startDate . ' 00:00:00',
                $endDate   . ' 23:59:59',
            ]);

        if ($province)      $this->applyProvinceFilter($baseQuery, $province);
        if ($status)        $baseQuery->where('status', $status);
        if ($paymentMethod) $baseQuery->where(function ($q) use ($paymentMethod) {
            $q->whereHas('payment', fn($p) => $p->where('payment_type', $paymentMethod))
              ->orWhere('payment_method', $paymentMethod);
        });
        if ($categoryId)    $this->applyCategoryFilter($baseQuery, $categoryId);

        $statsQuery = clone $baseQuery;
        $allOrders  = $statsQuery->whereIn('status', $this->validStatuses)->get();

        $stats = [
            'total_revenue'    => $allOrders->sum('grand_total'),
            'total_orders'     => $allOrders->count(),
            'total_items_sold' => $allOrders->sum(fn($o) => $o->items->sum('quantity')),
            'avg_order_value'  => $allOrders->count() > 0
                ? round($allOrders->avg('grand_total'), 0)
                : 0,
        ];

        $orders = (clone $baseQuery)->latest()->paginate(5)->withQueryString();

        $provinceOptions = collect()
            ->merge(DB::table('order_shipping_snapshots')
                ->join('orders', 'order_shipping_snapshots.order_id', '=', 'orders.id')
                ->whereNotNull('order_shipping_snapshots.province_name')
                ->where('order_shipping_snapshots.province_name', '!=', '')
                ->whereNull('orders.deleted_at')
                ->pluck('order_shipping_snapshots.province_name'))
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

        $statsNote = $this->statsNote($status, $statusOptions);

        return view('superadmin.reports.index', compact(
            'orders', 'stats',
            'startDate', 'endDate',
            'province', 'categoryId', 'paymentMethod', 'status',
            'provinceOptions', 'categoryOptions', 'statsNote',
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

        $query = Order::with(['items.product.category', 'payment', 'address', 'shippingSnapshot'])
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

        $statsOrders = $orders->filter(fn($o) => in_array($o->status, $this->validStatuses));

        $stats = [
            'total_revenue'    => $statsOrders->sum('grand_total'),
            'total_orders'     => $statsOrders->count(),
            'total_items_sold' => $statsOrders->sum(fn($o) => $o->items->sum('quantity')),
            'avg_order_value'  => $statsOrders->count() > 0
                ? round($statsOrders->avg('grand_total'), 0)
                : 0,
        ];

        $activeFilters = array_filter([
            'Provinsi'          => $province,
            'Kategori'          => $categoryId ? Category::find($categoryId)?->name : null,
            'Metode Pembayaran' => $paymentMethod,
            'Status'            => $status,
        ]);

        $statusOptions = [
            'pending'    => 'Menunggu Pembayaran',
            'paid'       => 'Sudah Dibayar',
            'processing' => 'Diproses',
            'shipped'    => 'Dikirim',
            'completed'  => 'Selesai',
            'cancelled'  => 'Dibatalkan',
        ];

        $statsNote = $this->statsNote($status, $statusOptions);

        $pdf = Pdf::loadView('superadmin.reports.pdf', compact(
            'orders', 'stats', 'startDate', 'endDate', 'activeFilters', 'statsNote'
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

    private function applyProvinceFilter(Builder $query, string $province): void
    {
        $query->where(function (Builder $q) use ($province) {
            $q->whereHas('shippingSnapshot', fn($snapshot) => $snapshot->where('province_name', $province))
                ->orWhereHas('address', fn($address) => $address->where('province_name', $province));
        });
    }

    private function applyCategoryFilter(Builder $query, int|string $categoryId): void
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
