<?php
// app/Http/Controllers/Admin/SearchController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $q = trim($request->get('q', ''));

        if (strlen($q) < 2) {
            return response()->json([]);
        }

        // ── Pesanan ───────────────────────────────────────────────
        $orders = Order::where('order_number', 'like', "%{$q}%")
            ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$q}%"))
            ->latest()
            ->limit(5)
            ->get()
            ->map(fn($o) => [
                'title'    => $o->order_number ?? 'ORD-' . $o->id,
                'subtitle' => ($o->user->name ?? 'Pelanggan') . ' · Rp ' . number_format($o->grand_total, 0, ',', '.'),
                'badge'    => ucfirst($o->status),
                'url'      => route('admin.orders.show', $o->id),
            ]);

        // ── Produk ────────────────────────────────────────────────
        $products = Product::where('name', 'like', "%{$q}%")
            ->where('is_active', true)
            ->limit(5)
            ->get()
            ->map(fn($p) => [
                'title'    => $p->name,
                'subtitle' => ($p->category->name ?? 'Uncategorized') . ' · Stok: ' . $p->stock,
                'badge'    => 'Rp ' . number_format($p->price, 0, ',', '.'),
                'url'      => route('admin.products.edit', $p->id),
            ]);

        // ── Pelanggan ─────────────────────────────────────────────
        $customers = User::where('role', 'pembeli')
            ->where(fn($u) =>
                $u->where('name', 'like', "%{$q}%")
                  ->orWhere('email', 'like', "%{$q}%")
            )
            ->limit(5)
            ->get()
            ->map(fn($u) => [
                'title'    => $u->name,
                'subtitle' => $u->email,
                'badge'    => null,
                'url'      => route('admin.orders.index') . '?user=' . $u->id,
            ]);

        // ── Kategori ──────────────────────────────────────────────
        $categories = Category::where('name', 'like', "%{$q}%")
            ->limit(4)
            ->get()
            ->map(fn($c) => [
                'title'    => $c->name,
                'subtitle' => ($c->products_count ?? $c->products()->count()) . ' produk',
                'badge'    => null,
                'url'      => route('admin.categories.edit', $c->id),
            ]);

        return response()->json([
            'Pesanan'   => $orders,
            'Produk'    => $products,
            'Pelanggan' => $customers,
            'Kategori'  => $categories,
        ]);
    }
}