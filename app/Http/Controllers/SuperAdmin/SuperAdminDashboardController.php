<?php
// app/Http/Controllers/SuperAdmin/SuperAdminDashboardController.php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SuperAdminDashboardController extends Controller
{
    public function index()
    {
        // === STATISTIK UTAMA ===
        $totalRevenue = Order::where('status', 'completed')->sum('grand_total');
        $totalOrders = Order::count();
        $totalProducts = Product::count();
        $totalCustomers = User::where('role', 'pembeli')->count();
        $totalAdmins = User::where('role', 'admin')->count();

        // Revenue hari ini
        $todayRevenue = Order::where('status', 'completed')
            ->whereDate('paid_at', today())
            ->sum('grand_total');

        // Revenue bulan ini
        $monthlyRevenue = Order::where('status', 'completed')
            ->whereMonth('paid_at', now()->month)
            ->whereYear('paid_at', now()->year)
            ->sum('grand_total');

        // Pertumbuhan revenue (vs bulan lalu)
        $lastMonthRevenue = Order::where('status', 'completed')
            ->whereMonth('paid_at', now()->subMonth()->month)
            ->whereYear('paid_at', now()->subMonth()->year)
            ->sum('grand_total');

        $revenueGrowth = $lastMonthRevenue > 0 
            ? (($monthlyRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100 
            : 0;

        // === GRAFIK PENDAPATAN 30 HARI TERAKHIR ===
        $revenueLast30Days = Order::where('status', 'completed')
            ->whereBetween('paid_at', [now()->subDays(29), now()])
            ->selectRaw('DATE(paid_at) as date, SUM(grand_total) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

        $dates = collect();
        $revenues = collect();
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dates->push(now()->subDays($i)->format('d M'));
            $revenues->push($revenueLast30Days->get($date, 0));
        }

        // === TOP 5 PRODUK TERLARIS ===
        $topProducts = Product::withSum('orderItems as total_sold', 'quantity')
            ->orderByDesc('total_sold')
            ->where('is_active', true)
            ->limit(5)
            ->get();

        // === STATISTIK PESANAN ===
        $orderStats = [
            'pending' => Order::where('status', 'pending')->count(),
            'paid' => Order::where('status', 'paid')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
            'completed' => Order::where('status', 'completed')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        // === PESANAN TERBARU ===
        $recentOrders = Order::with('user')
            ->latest()
            ->limit(10)
            ->get();

        // === PERFORMA ADMIN ===
        $adminPerformance = User::where('role', 'admin')
            ->withCount(['orders as orders_handled' => function($q) {
                $q->whereIn('status', ['processing', 'shipped', 'completed']);
            }])
            ->orderByDesc('orders_handled')
            ->limit(5)
            ->get();

        return view('superadmin.dashboard', compact(
            'totalRevenue',
            'totalOrders',
            'totalProducts',
            'totalCustomers',
            'totalAdmins',
            'todayRevenue',
            'monthlyRevenue',
            'revenueGrowth',
            'dates',
            'revenues',
            'topProducts',
            'orderStats',
            'recentOrders',
            'adminPerformance'
        ));
    }
}