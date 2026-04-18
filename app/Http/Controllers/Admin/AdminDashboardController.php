<?php
// app/Http/Controllers/Admin/AdminDashboardController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // ── KPI Utama ──────────────────────────────────────────────
        $totalOrders      = Order::count();
        $pendingOrders    = Order::where('status', 'pending')->count();
        $processingOrders = Order::where('status', 'processing')->count();
        $completedOrders  = Order::where('status', 'completed')->count();

        $totalRevenue = Order::where('status', 'completed')->sum('grand_total');
        $todayRevenue = Order::where('status', 'completed')
            ->whereDate('paid_at', today())
            ->sum('grand_total');

        $totalProducts    = Product::count();
        $lowStockProducts = Product::where('stock', '<=', 5)->where('is_active', true)->count();
        $totalCustomers   = User::where('role', 'pembeli')->count();

        // ── Trend % vs bulan lalu ──────────────────────────────────
        $thisStart = now()->startOfMonth();
        $lastStart = now()->subMonth()->startOfMonth();
        $lastEnd   = now()->subMonth()->endOfMonth();

        $ordersTrend = $this->calcTrend(
            Order::whereBetween('created_at', [$lastStart, $lastEnd])->count(),
            Order::where('created_at', '>=', $thisStart)->count()
        );

        $pendingTrend = $this->calcTrend(
            Order::where('status', 'pending')->whereBetween('created_at', [$lastStart, $lastEnd])->count(),
            Order::where('status', 'pending')->where('created_at', '>=', $thisStart)->count()
        );

        $revenueTrend = $this->calcTrend(
            Order::where('status', 'completed')->whereBetween('paid_at', [$lastStart, $lastEnd])->sum('grand_total'),
            Order::where('status', 'completed')->where('paid_at', '>=', $thisStart)->sum('grand_total')
        );

        $customersTrend = $this->calcTrend(
            User::where('role', 'pembeli')->whereBetween('created_at', [$lastStart, $lastEnd])->count(),
            User::where('role', 'pembeli')->where('created_at', '>=', $thisStart)->count()
        );

        // ── Grafik pendapatan 7 hari terakhir ─────────────────────
        [$dates, $revenues] = $this->getRevenueData(7);

        // ── Donut chart: penjualan per kategori bulan ini ─────────
        $categoryStats = $this->getCategoryStats();

        // ── Top 5 produk terlaris ─────────────────────────────────
        $topProducts = Product::withSum('orderItems as total_sold', 'quantity')
            ->with('category')
            ->orderByDesc('total_sold')
            ->where('is_active', true)
            ->limit(5)
            ->get();

        // ── 5 Pesanan terbaru ─────────────────────────────────────
        $recentOrders = Order::with('user')
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalOrders', 'pendingOrders', 'processingOrders', 'completedOrders',
            'totalRevenue', 'todayRevenue', 'totalProducts', 'lowStockProducts',
            'totalCustomers',
            'ordersTrend', 'pendingTrend', 'revenueTrend', 'customersTrend',
            'dates', 'revenues',
            'categoryStats',
            'topProducts', 'recentOrders'
        ));
    }

    /**
     * AJAX endpoint untuk filter periode grafik.
     * GET /admin/dashboard/chart?days=7|30|90
     */
    public function chartData(Request $request)
    {
        $days = (int) $request->get('days', 7);
        $days = in_array($days, [7, 30, 90]) ? $days : 7;

        [$dates, $revenues] = $this->getRevenueData($days);

        return response()->json([
            'dates'    => $dates,
            'revenues' => $revenues,
        ]);
    }

    // ── Helper: data pendapatan harian ────────────────────────────
    private function getRevenueData(int $days): array
    {
        $start = now()->subDays($days - 1)->startOfDay();

        $revenueMap = Order::where('status', 'completed')
            ->whereBetween('paid_at', [$start, now()->endOfDay()])
            ->selectRaw('DATE(paid_at) as date, SUM(grand_total) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        $dates    = collect();
        $revenues = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dates->push($date);
            $revenues[] = (float) ($revenueMap[$date] ?? 0);
        }

        return [$dates, $revenues];
    }

    // ── Helper: statistik penjualan per kategori bulan ini ────────
    private function getCategoryStats(): array
    {
        $data = DB::table('order_items')
            ->join('orders',     'orders.id',     '=', 'order_items.order_id')
            ->join('products',   'products.id',   '=', 'order_items.product_id')
            ->join('categories', 'categories.id', '=', 'products.category_id')
            ->where('orders.status', 'completed')
            ->where('orders.paid_at', '>=', now()->startOfMonth())
            ->selectRaw('categories.name, SUM(order_items.quantity) as total_sold')
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total_sold')
            ->limit(6)
            ->get();

        $grandTotal = $data->sum('total_sold');
        if ($grandTotal == 0) return [];

        return $data->map(fn($row) => [
            'name'       => $row->name,
            'total'      => $row->total_sold,
            'percentage' => round(($row->total_sold / $grandTotal) * 100, 1),
        ])->toArray();
    }

    // ── Helper: hitung % tren ─────────────────────────────────────
    private function calcTrend(int|float $last, int|float $current): float
    {
        if ($last == 0) return $current > 0 ? 100.0 : 0.0;
        return round((($current - $last) / $last) * 100, 1);
    }
}