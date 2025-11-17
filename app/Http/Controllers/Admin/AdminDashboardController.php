<?php
// app/Http/Controllers/Admin/AdminDashboardController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Statistik Utama
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $processingOrders = Order::where('status', 'processing')->count();
        $completedOrders = Order::where('status', 'completed')->count();

        $totalRevenue = Order::where('status', 'completed')->sum('grand_total');
        $todayRevenue = Order::where('status', 'completed')
            ->whereDate('paid_at', today())
            ->sum('grand_total');

        $totalProducts = Product::count();
        $lowStockProducts = Product::where('stock', '<=', 5)->where('is_active', true)->count();

        $totalCustomers = User::where('role', 'pembeli')->count();

        // Grafik: Pendapatan 7 Hari Terakhir
        $revenueLast7Days = Order::where('status', 'completed')
            ->whereBetween('paid_at', [now()->subDays(6), now()])
            ->selectRaw('DATE(paid_at) as date, SUM(grand_total) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

        $dates = collect();
        $revenues = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dates->push($date);
            $revenues->push($revenueLast7Days->get($date, 0));
        }

        // Produk Terlaris
        $topProducts = Product::withSum('orderItems as total_sold', 'quantity')
            ->orderByDesc('total_sold')
            ->where('is_active', true)
            ->limit(5)
            ->get();

        $recentOrders = Order::with('user')  
        ->latest()                        
        ->limit(5)
        ->get();
            
        return view('admin.dashboard', compact(
            'totalOrders', 'pendingOrders', 'processingOrders', 'completedOrders',
            'totalRevenue', 'todayRevenue', 'totalProducts', 'lowStockProducts',
            'totalCustomers', 'dates', 'revenues', 'topProducts', 'recentOrders'
        ));
    }
}