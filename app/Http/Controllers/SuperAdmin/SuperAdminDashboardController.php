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
        $paymentMethod = $request->input('payment_method');
        $paymentStatus = $request->input('payment_status');

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
        // TOP 5 KATEGORI — Pie Chart
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
        // METODE PEMBAYARAN — Pie Chart
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
        // STATUS PESANAN — Donut Chart
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
        // KATEGORI PER PROVINSI — Grouped Bar Horizontal
        // Top 5 provinsi × top 5 kategori
        // =============================================
        $top5ProvinceNames = $topProvinces->pluck('province');

        $cpRaw = collect();
        if ($top5ProvinceNames->isNotEmpty()) {
            $cpRaw = DB::table('order_items')
                ->join('orders',     'order_items.order_id',   '=', 'orders.id')
                ->join('products',   'order_items.product_id', '=', 'products.id')
                ->join('categories', 'products.category_id',   '=', 'categories.id')
                ->whereBetween('orders.created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
                ->whereIn('orders.status', ['paid', 'processing', 'shipped', 'completed'])
                ->whereNull('orders.deleted_at')
                ->whereIn('orders.province', $top5ProvinceNames)
                ->when($paymentMethod, fn($q) => $q->whereIn('orders.id',
                    DB::table('payments')->select('order_id')->where('payment_type', $paymentMethod)
                ))
                ->when($categoryId, fn($q) => $q->where('products.category_id', $categoryId))
                ->select(
                    'orders.province',
                    'categories.name as category_name',
                    DB::raw('SUM(order_items.quantity) as total_sold')
                )
                ->groupBy('orders.province', 'categories.name')
                ->orderByDesc('total_sold')
                ->get();
        }

        // Top 5 kategori dari data cross-province
        $top5CatNames = $cpRaw->groupBy('category_name')
            ->map(fn($rows) => $rows->sum('total_sold'))
            ->sortDesc()
            ->take(5)
            ->keys();

        // $cpProvince: list provinsi untuk sumbu Y chart
        $cpProvince = $top5ProvinceNames;

        // $cpDatasets: array dataset per kategori untuk Chart.js
        $palette = ['#6366f1', '#06b6d4', '#10b981', '#f59e0b', '#ef4444'];
        $cpDatasets = $top5CatNames->values()->map(function ($catName, $i) use ($cpRaw, $top5ProvinceNames, $palette) {
            $data = $top5ProvinceNames->map(function ($prov) use ($cpRaw, $catName) {
                $row = $cpRaw->first(fn($r) => $r->province === $prov && $r->category_name === $catName);
                return $row ? (int) $row->total_sold : 0;
            })->values();

            return [
                'label'           => $catName,
                'data'            => $data,
                'backgroundColor' => $palette[$i] ?? '#9ca3af',
                'borderRadius'    => 4,
                'borderSkipped'   => false,
            ];
        });

        // =============================================
        // JAM TERSIBUK — Bar Chart (24 jam)
        // =============================================
        $hourRaw = Order::query()
            ->tap($applyBase)
            ->selectRaw('HOUR(orders.created_at) as hour, COUNT(*) as total')
            ->groupBy('hour')
            ->pluck('total', 'hour');

        // Buat array lengkap 0–23 jam
        $hourLabels = collect();
        $hourData   = collect();
        for ($h = 0; $h < 24; $h++) {
            $hourLabels->push(str_pad($h, 2, '0', STR_PAD_LEFT) . ':00');
            $hourData->push((int) $hourRaw->get($h, 0));
        }

        // =============================================
        // REPEAT vs NEW CUSTOMER
        // =============================================
        // User yang pernah order SEBELUM periode ini = repeat customer
        $allBuyerIds = Order::query()
            ->tap($applyBase)
            ->whereNotNull('user_id')
            ->distinct()
            ->pluck('user_id');

        $repeatCustomers = 0;
        $newCustomers    = 0;

        if ($allBuyerIds->isNotEmpty()) {
            // Cek apakah user pernah order sebelum dateFrom
            $repeatIds = Order::whereIn('user_id', $allBuyerIds)
                ->where('created_at', '<', $dateFrom . ' 00:00:00')
                ->whereNull('deleted_at')
                ->distinct()
                ->pluck('user_id');

            $repeatCustomers = $repeatIds->count();
            $newCustomers    = $allBuyerIds->count() - $repeatCustomers;
        }

        // =============================================
        // TABEL PENJUALAN — Paginasi 15
        // =============================================
        $salesTable = Order::with(['items.product.category', 'payment'])
            ->tap($applyBase)
            ->latest('orders.created_at')
            ->paginate(5)
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
            'cpProvince', 'cpDatasets',
            'hourLabels', 'hourData',
            'repeatCustomers', 'newCustomers',
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