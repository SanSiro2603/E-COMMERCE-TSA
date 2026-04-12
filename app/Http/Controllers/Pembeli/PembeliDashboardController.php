<?php
// app/Http/Controllers/Pembeli/PembeliDashboardController.php

namespace App\Http\Controllers\Pembeli;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class PembeliDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Statistik
        $totalOrders = $user->orders()->count();
        $pendingOrders = $user->orders()->where('status', 'pending')->count();
        $shippedOrders = $user->orders()->where('status', 'shipped')->count();
        $completedOrders = $user->orders()->where('status', 'completed')->count();

        // Pesanan terbaru
        $recentOrders = $user->orders()
            ->with(['items.product'])
            ->latest()
            ->take(3)
            ->get();

        // Produk terlaris (untuk rekomendasi)
        $topProducts = Product::withSum('orderItems as total_sold', 'quantity')
            ->where('is_active', true)
            ->orderByDesc('total_sold')
            ->take(4)
            ->get();

        // TAMBAHAN BARU: Kategori aktif
        $categories = Category::where('is_active', true)
            ->whereNotNull('image')
            ->whereHas('products', function ($query) {
                $query->where('is_active', true);
            })
            ->orderBy('name')
            ->get();

        // TAMBAHAN BARU: Semua produk (untuk section "Semua Hewan")
        $allProducts = Product::with('category')
            ->where('is_active', true)
            ->latest()
            ->get();

        return view('pembeli.dashboard', compact(
            'user', 
            'totalOrders', 
            'pendingOrders', 
            'shippedOrders', 
            'completedOrders',
            'recentOrders', 
            'topProducts',
            'categories',      
            'allProducts'      
        ));
    }
}