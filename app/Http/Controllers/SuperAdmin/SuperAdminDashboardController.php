<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SuperAdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        // =============================================
        // FILTER PARAMETERS
        // =============================================
        $dateFrom      = $request->input('date_from', now()->startOfMonth()->format('Y-m-d'));
        $dateTo        = $request->input('date_to', now()->format('Y-m-d'));
        $province      = $request->input('province');
        $categoryId    = $request->input('category_id');
        $paymentMethod = $request->input('payment_method'); // Midtrans payment_type
        $paymentStatus = $request->input('payment_status'); // orders.status

        // =============================================
        // CLOSURE FILTER — dipakai di semua query Eloquent
        // =============================================
        $applyBase = function ($q) use ($dateFrom, $dateTo, $province, $categoryId, $paymentMethod, $paymentStatus) {
            $q->whereBetween('orders.created_at', [
                $dateFrom . ' 00:00:00',
                $dateTo   . ' 23:59:59',
            ]);
            if ($province)      $q->where('orders.province', $province);
            if ($paymentStatus) $q->where('orders.status', $paymentStatus);
            // FIX: payment_method ada di tabel payments (Midtrans menyimpan sebagai payment_type)
            if ($paymentMethod) {
                $q->whereHas('payment', fn($p) => $p->where('payment_type', $paymentMethod));
            }
            if ($categoryId) {
                $q->whereHas('items.product', fn($p) => $p->where('category_id', $categoryId));
            }
        };

        // =============================================
        // SCORE CARDS
        // =============================================
        $baseQ = Order::query()->tap($applyBase);

        $totalRevenue = (clone $baseQ)
            ->whereIn('status', ['paid', 'processing', 'shipped', 'completed'])
            ->sum('grand_total');

        $totalTransactions = (clone $baseQ)->count();

        $totalProductsSold = (clone $baseQ)
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->sum('order_items.quantity');

        $avgTransactionValue = $totalTransactions > 0
            ? round($totalRevenue / $totalTransactions, 0)
            : 0;

        $totalBuyers = (clone $baseQ)->distinct('user_id')->count('user_id');

        // =============================================
        // GRAFIK PENDAPATAN HARIAN
        // =============================================
        $revenueByDate = Order::query()
            ->tap($applyBase)
            ->whereIn('status', ['paid', 'processing', 'shipped', 'completed'])
            ->selectRaw('DATE(orders.created_at) as date, SUM(grand_total) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

        $start = \Carbon\Carbon::parse($dateFrom);
        $end   = \Carbon\Carbon::parse($dateTo);
        $diff  = $start->diffInDays($end);

        $chartDates    = collect();
        $chartRevenues = collect();
        for ($i = 0; $i <= $diff; $i++) {
            $d = $start->copy()->addDays($i);
            $chartDates->push($d->format('d M'));
            $chartRevenues->push((float) $revenueByDate->get($d->format('Y-m-d'), 0));
        }

        // =============================================
        // TOP 5 PRODUK TERLARIS — Bar Chart
        // =============================================
        $topProducts = DB::table('order_items')
            ->join('orders',   'order_items.order_id',   '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->whereBetween('orders.created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->whereIn('orders.status', ['paid', 'processing', 'shipped', 'completed'])
            ->whereNull('orders.deleted_at')
            ->when($province,      fn($q) => $q->where('orders.province', $province))
            ->when($paymentStatus, fn($q) => $q->where('orders.status', $paymentStatus))
            ->when($categoryId,    fn($q) => $q->where('products.category_id', $categoryId))
            ->when($paymentMethod, fn($q) => $q->whereIn('orders.id',
                DB::table('payments')->select('order_id')->where('payment_type', $paymentMethod)
            ))
            ->select(
                'products.id',
                'products.name',
                'products.image',
                DB::raw('SUM(order_items.quantity) as total_sold')
            )
            ->groupBy('products.id', 'products.name', 'products.image')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        // =============================================
        // TOP 5 KATEGORI — Pie Chart + Persentase
        // FIX: join langsung ke tabel categories (bukan via relasi Eloquent)
        // =============================================
        $topCategories = DB::table('order_items')
            ->join('orders',     'order_items.order_id',   '=', 'orders.id')
            ->join('products',   'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id',   '=', 'categories.id')
            ->whereBetween('orders.created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->whereIn('orders.status', ['paid', 'processing', 'shipped', 'completed'])
            ->whereNull('orders.deleted_at')
            ->when($province,      fn($q) => $q->where('orders.province', $province))
            ->when($paymentStatus, fn($q) => $q->where('orders.status', $paymentStatus))
            ->when($categoryId,    fn($q) => $q->where('products.category_id', $categoryId))
            ->when($paymentMethod, fn($q) => $q->whereIn('orders.id',
                DB::table('payments')->select('order_id')->where('payment_type', $paymentMethod)
            ))
            ->select(
                'categories.id',
                'categories.name',
                DB::raw('SUM(order_items.quantity) as total_sold')
            )
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        // =============================================
        // TOP 5 PROVINSI — Bar Chart
        // =============================================
        $topProvinces = Order::query()
            ->tap($applyBase)
            ->whereNotNull('province')
            ->where('province', '!=', '')
            ->select('province', DB::raw('COUNT(*) as total_orders'), DB::raw('SUM(grand_total) as total_revenue'))
            ->groupBy('province')
            ->orderByDesc('total_orders')
            ->limit(5)
            ->get();

        // =============================================
        // METODE PEMBAYARAN — Bar Horizontal
        // FIX: baca dari tabel payments.payment_type (Midtrans)
        // =============================================
        $paymentMethods = DB::table('payments')
            ->join('orders', 'payments.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->whereNull('orders.deleted_at')
            ->whereNotNull('payments.payment_type')
            ->when($province,      fn($q) => $q->where('orders.province', $province))
            ->when($paymentStatus, fn($q) => $q->where('orders.status', $paymentStatus))
            ->when($categoryId,    fn($q) => $q->whereIn('orders.id',
                DB::table('order_items')
                    ->join('products', 'order_items.product_id', '=', 'products.id')
                    ->select('order_id')
                    ->where('products.category_id', $categoryId)
            ))
            ->select('payments.payment_type', DB::raw('COUNT(*) as total'))
            ->groupBy('payments.payment_type')
            ->orderByDesc('total')
            ->get()
            ->map(fn($item) => [
                'label' => $this->labelPaymentMethod($item->payment_type),
                'value' => (int) $item->total,
                'key'   => $item->payment_type,
            ]);

        // =============================================
        // STATUS PEMBAYARAN — Donut Chart + Persentase
        // =============================================
        $paymentStatuses = Order::query()
            ->tap($applyBase)
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->get()
            ->map(fn($item) => [
                'label' => $this->labelStatus($item->status),
                'value' => (int) $item->total,
                'key'   => $item->status,
            ]);

        // =============================================
        // TABEL PENJUALAN — Paginasi 15
        // =============================================
        $salesTable = Order::with(['items.product.category', 'payment'])
            ->tap($applyBase)
            ->latest('orders.created_at')
            ->paginate(15)
            ->withQueryString();

        // =============================================
        // DROPDOWN OPTIONS
        // =============================================
        $provinceOptions = Order::whereNotNull('province')
            ->where('province', '!=', '')
            ->whereNull('deleted_at')
            ->distinct()
            ->orderBy('province')
            ->pluck('province');

        $categoryOptions = Category::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        // Sesuai Midtrans payment_type
        $paymentMethodOptions = [
            'bank_transfer' => 'Transfer Bank (VA)',
            'echannel'      => 'Mandiri E-Channel',
            'cstore'        => 'Minimarket',
            'gopay'         => 'GoPay',
            'qris'          => 'QRIS',
            'shopeepay'     => 'ShopeePay',
            'credit_card'   => 'Kartu Kredit',
        ];

        $paymentStatusOptions = [
            'pending'    => 'Menunggu Pembayaran',
            'paid'       => 'Sudah Dibayar',
            'processing' => 'Diproses',
            'shipped'    => 'Dikirim',
            'completed'  => 'Selesai',
            'cancelled'  => 'Dibatalkan',
        ];

        return view('superadmin.dashboard', compact(
            'dateFrom', 'dateTo', 'province', 'categoryId',
            'paymentMethod', 'paymentStatus',
            'totalRevenue', 'totalTransactions', 'totalProductsSold',
            'avgTransactionValue', 'totalBuyers',
            'chartDates', 'chartRevenues',
            'topProducts', 'topCategories', 'topProvinces',
            'paymentMethods', 'paymentStatuses',
            'salesTable',
            'provinceOptions', 'categoryOptions',
            'paymentMethodOptions', 'paymentStatusOptions'
        ));
    }

    private function labelPaymentMethod(?string $method): string
    {
        return match ($method) {
            'bank_transfer' => 'Transfer Bank',
            'echannel'      => 'Mandiri E-Channel',
            'cstore'        => 'Minimarket',
            'gopay'         => 'GoPay',
            'qris'          => 'QRIS',
            'shopeepay'     => 'ShopeePay',
            'credit_card'   => 'Kartu Kredit',
            default         => ucfirst(str_replace('_', ' ', $method ?? 'Lainnya')),
        };
    }

    private function labelStatus(?string $status): string
    {
        return match ($status) {
            'pending'    => 'Menunggu',
            'paid'       => 'Dibayar',
            'processing' => 'Diproses',
            'shipped'    => 'Dikirim',
            'completed'  => 'Selesai',
            'cancelled'  => 'Dibatalkan',
            default      => ucfirst($status ?? 'Unknown'),
        };
    }
}