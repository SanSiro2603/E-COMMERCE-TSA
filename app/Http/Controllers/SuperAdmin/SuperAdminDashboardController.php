<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
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
        $categoryName  = $categoryId ? Category::query()->whereKey($categoryId)->value('name') : null;

        // =============================================
        // STATUS YANG DIHITUNG SEBAGAI TRANSAKSI VALID
        // pending  = belum bayar → TIDAK dihitung
        // cancelled = dibatalkan → TIDAK dihitung
        // =============================================
        $validStatuses = ['paid', 'processing', 'shipped', 'completed'];

        // =============================================
        // CLOSURE FILTER — dipakai di semua query Eloquent
        // =============================================
        $applyBase = function ($q) use ($dateFrom, $dateTo, $province, $categoryId, $categoryName, $paymentMethod, $paymentStatus) {

            $q->leftJoin('order_shipping_snapshots', 'orders.id', '=', 'order_shipping_snapshots.order_id');
            $q->leftJoin('addresses', 'orders.address_id', '=', 'addresses.id'); // fallback untuk order lama tanpa snapshot

            $q->whereBetween('orders.created_at', [
                $dateFrom . ' 00:00:00',
                $dateTo   . ' 23:59:59',
            ]);

            if ($province) {
                $q->where(function ($sub) use ($province) {
                    $sub->where('order_shipping_snapshots.province_name', $province)
                        ->orWhere('addresses.province_name', $province);
                });
            }
            if ($paymentStatus) $q->where('orders.status', $paymentStatus);

            if ($paymentMethod) {
                // Cek di payments.payment_type DAN orders.payment_method
                $q->where(function ($sub) use ($paymentMethod) {
                    $sub->whereHas('payment', fn($p) => $p->where('payment_type', $paymentMethod))
                        ->orWhere('orders.payment_method', $paymentMethod);
                });
            }

            if ($categoryId) {
                $q->whereHas('items', function (Builder $items) use ($categoryId, $categoryName) {
                    $items->whereHas('product', fn($p) => $p->where('category_id', $categoryId));

                    if ($categoryName) {
                        $items->orWhere('product_category_name', $categoryName);
                    }
                });
            }
        };

        // =============================================
        // CACHE dinonaktifkan sementara — real-time untuk demo seminar
        // =============================================
        $cached = (function () use (
            $applyBase, $validStatuses, $dateFrom, $dateTo,
            $province, $categoryId, $categoryName, $paymentMethod, $paymentStatus
        ) {
        // =============================================
        // SCORE CARDS
        // ✅ DIPERBAIKI: tambah prefix orders. di semua kolom score cards
        // agar tidak ambigu setelah leftJoin addresses
        // =============================================
        $baseQ = Order::query()->tap($applyBase);

        // Total Pendapatan: hanya status valid (paid, processing, shipped, completed)
        $totalRevenue = (clone $baseQ)
            ->whereIn('orders.status', $validStatuses)
            ->sum('orders.grand_total');

        // Total Transaksi: hanya status valid — TIDAK termasuk pending & cancelled
        $totalTransactions = (clone $baseQ)
            ->whereIn('orders.status', $validStatuses)
            ->count('orders.id');

        // Produk Terjual: hanya dari order berstatus valid
        $totalProductsSold = (clone $baseQ)
            ->whereIn('orders.status', $validStatuses)
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->sum('order_items.quantity');

        // Rata-rata nilai transaksi: dihitung dari transaksi valid saja
        $avgTransactionValue = $totalTransactions > 0
            ? round($totalRevenue / $totalTransactions, 0)
            : 0;

        // Pelanggan Aktif: distinct user yang punya transaksi valid
        $totalBuyers = (clone $baseQ)
            ->whereIn('orders.status', $validStatuses)
            ->distinct('orders.user_id')
            ->count('orders.user_id');

        // =============================================
        // GRAFIK PENDAPATAN HARIAN
        // =============================================
        $revenueByDate = Order::query()
            ->tap($applyBase)
            ->whereIn('orders.status', $validStatuses)
            ->selectRaw('DATE(orders.created_at) as date, SUM(orders.grand_total) as total')
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
            ->leftJoin('products', 'order_items.product_id', '=', 'products.id')
            ->leftJoin('order_shipping_snapshots', 'orders.id', '=', 'order_shipping_snapshots.order_id')
            ->leftJoin('addresses', 'orders.address_id', '=', 'addresses.id')

            ->whereIn('orders.status', $validStatuses)
            ->tap(fn($q) => $this->applyDashboardDbFilters($q, $dateFrom, $dateTo, $province, $categoryId, $categoryName, $paymentMethod, $paymentStatus))

            ->select(
                DB::raw('MIN(products.id) as id'),
                DB::raw("COALESCE(order_items.product_name, products.name, 'Produk dihapus') as name"),
                DB::raw('COALESCE(order_items.product_image, products.image) as image'),
                DB::raw('SUM(order_items.quantity) as total_sold')
            )

            ->groupByRaw("COALESCE(order_items.product_name, products.name, 'Produk dihapus'), COALESCE(order_items.product_image, products.image)")
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();


        // =============================================
        // TOP 5 KATEGORI — Pie Chart
        // =============================================
        $topCategories = DB::table('order_items')
            ->join('orders',     'order_items.order_id',   '=', 'orders.id')
            ->leftJoin('products',   'order_items.product_id', '=', 'products.id')
            ->leftJoin('categories', 'products.category_id',   '=', 'categories.id')
            ->leftJoin('order_shipping_snapshots', 'orders.id', '=', 'order_shipping_snapshots.order_id')
            ->leftJoin('addresses', 'orders.address_id', '=', 'addresses.id')
            ->whereIn('orders.status', $validStatuses)
            ->tap(fn($q) => $this->applyDashboardDbFilters($q, $dateFrom, $dateTo, $province, $categoryId, $categoryName, $paymentMethod, $paymentStatus))
            ->select(
                DB::raw('MIN(categories.id) as id'),
                DB::raw("COALESCE(order_items.product_category_name, categories.name, 'Uncategorized') as name"),
                DB::raw('SUM(order_items.quantity) as total_sold')
            )
            ->groupByRaw("COALESCE(order_items.product_category_name, categories.name, 'Uncategorized')")
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        // =============================================
        // TOP 5 PROVINSI — Bar Chart
        // =============================================
        $topProvinces = Order::query()
            ->tap($applyBase)
            ->whereIn('orders.status', $validStatuses)
            ->where(function ($q) {
                $q->whereNotNull('order_shipping_snapshots.province_name')
                    ->where('order_shipping_snapshots.province_name', '!=', '')
                    ->orWhere(function ($sub) {
                        $sub->whereNotNull('addresses.province_name')
                            ->where('addresses.province_name', '!=', '');
                    });
            })
            ->select(
                DB::raw('COALESCE(order_shipping_snapshots.province_name, addresses.province_name) as province'),
                DB::raw('COUNT(*) as total_orders'),
                DB::raw('SUM(orders.grand_total) as total_revenue')
            )
            ->groupBy('province')
            ->orderByDesc('total_orders')
            ->limit(5)
            ->get();

        // =============================================
        // METODE PEMBAYARAN — Pie Chart
        // =============================================
        $paymentMethods = DB::table('payments')
            ->join('orders', 'payments.order_id', '=', 'orders.id')
            ->leftJoin('order_shipping_snapshots', 'orders.id', '=', 'order_shipping_snapshots.order_id')
            ->leftJoin('addresses', 'orders.address_id', '=', 'addresses.id') // LEFT JOIN agar tidak kehilangan data
            ->whereBetween('orders.created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->whereNull('orders.deleted_at')
            ->whereNotNull('payments.payment_type')
            ->whereIn('orders.status', $validStatuses)
            ->when($province,      fn($q) => $q->where(function ($sub) use ($province) {
                $sub->where('order_shipping_snapshots.province_name', $province)
                    ->orWhere('addresses.province_name', $province);
            }))
            ->when($paymentStatus, fn($q) => $q->where('orders.status', $paymentStatus))
            ->when($paymentMethod, fn($q) => $q->where('payments.payment_type', $paymentMethod))
            ->when($categoryId,    fn($q) => $q->whereIn('orders.id',
                DB::table('order_items')
                    ->leftJoin('products', 'order_items.product_id', '=', 'products.id')
                    ->select('order_id')
                    ->where(function ($sub) use ($categoryId, $categoryName) {
                        $sub->where('products.category_id', $categoryId);

                        if ($categoryName) {
                            $sub->orWhere('order_items.product_category_name', $categoryName);
                        }
                    })
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

        // Tambahkan data dari orders.payment_method untuk order yang belum punya payments.payment_type
        $paymentMethodsFromOrders = DB::table('orders')
            ->leftJoin('order_shipping_snapshots', 'orders.id', '=', 'order_shipping_snapshots.order_id')
            ->leftJoin('addresses', 'orders.address_id', '=', 'addresses.id')
            ->whereBetween('orders.created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->whereNull('orders.deleted_at')
            ->whereNotNull('orders.payment_method')
            ->where('orders.payment_method', '!=', '')
            ->whereIn('orders.status', $validStatuses)
            ->whereNotIn('orders.id', DB::table('payments')->whereNotNull('payment_type')->select('order_id'))
            ->when($province,      fn($q) => $q->where(function ($sub) use ($province) {
                $sub->where('order_shipping_snapshots.province_name', $province)
                    ->orWhere('addresses.province_name', $province);
            }))
            ->when($paymentStatus, fn($q) => $q->where('orders.status', $paymentStatus))
            ->when($paymentMethod, fn($q) => $q->where('orders.payment_method', $paymentMethod))
            ->when($categoryId,    fn($q) => $q->whereIn('orders.id',
                DB::table('order_items')
                    ->leftJoin('products', 'order_items.product_id', '=', 'products.id')
                    ->select('order_id')
                    ->where(function ($sub) use ($categoryId, $categoryName) {
                        $sub->where('products.category_id', $categoryId);

                        if ($categoryName) {
                            $sub->orWhere('order_items.product_category_name', $categoryName);
                        }
                    })
            ))
            ->select('orders.payment_method as payment_type', DB::raw('COUNT(*) as total'))
            ->groupBy('orders.payment_method')
            ->get()
            ->map(fn($item) => [
                'label' => $this->labelPaymentMethod($item->payment_type),
                'value' => (int) $item->total,
                'key'   => $item->payment_type,
            ]);

        // Merge kedua sumber dan gabungkan yang sama
        $paymentMethods = $paymentMethods->concat($paymentMethodsFromOrders)
            ->groupBy('key')
            ->map(fn($group) => [
                'label' => $group->first()['label'],
                'value' => $group->sum('value'),
                'key'   => $group->first()['key'],
            ])
            ->values();

        // =============================================
        // STATUS PESANAN — Donut Chart
        // Sengaja TIDAK difilter $validStatuses agar semua status tampil di chart
        // supaya admin bisa melihat distribusi lengkap termasuk pending & cancelled
        // =============================================
        $paymentStatuses = Order::query()
            ->tap($applyBase)
            ->select('orders.status', DB::raw('COUNT(*) as total'))
            ->groupBy('orders.status')
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
                ->leftJoin('products',   'order_items.product_id', '=', 'products.id')
                ->leftJoin('categories', 'products.category_id',   '=', 'categories.id')
                ->leftJoin('order_shipping_snapshots', 'orders.id', '=', 'order_shipping_snapshots.order_id')
                ->leftJoin('addresses', 'orders.address_id', '=', 'addresses.id')
                ->whereIn('orders.status', $validStatuses)
                ->tap(fn($q) => $this->applyDashboardDbFilters($q, $dateFrom, $dateTo, null, $categoryId, $categoryName, $paymentMethod, $paymentStatus))
                ->whereIn(DB::raw('COALESCE(order_shipping_snapshots.province_name, addresses.province_name)'), $top5ProvinceNames)
                ->select(
                    DB::raw('COALESCE(order_shipping_snapshots.province_name, addresses.province_name) as province_name'),
                    DB::raw("COALESCE(order_items.product_category_name, categories.name, 'Uncategorized') as category_name"),
                    DB::raw('SUM(order_items.quantity) as total_sold')
                )
                ->groupByRaw("COALESCE(order_shipping_snapshots.province_name, addresses.province_name), COALESCE(order_items.product_category_name, categories.name, 'Uncategorized')")
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
                $row = $cpRaw->first(fn($r) => $r->province_name === $prov && $r->category_name === $catName);
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
        // Hanya dari transaksi valid
        // =============================================
        $hourBucketSql = DB::connection()->getDriverName() === 'sqlite'
            ? "CAST(strftime('%H', orders.created_at) AS INTEGER) as hour, COUNT(*) as total"
            : 'HOUR(orders.created_at) as hour, COUNT(*) as total';

        $hourRaw = Order::query()
            ->tap($applyBase)
            ->whereIn('orders.status', $validStatuses)
            ->selectRaw($hourBucketSql)
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
        // Hanya dari transaksi valid
        // =============================================
        $allBuyerIds = Order::query()
            ->tap($applyBase)
            ->whereIn('orders.status', $validStatuses)
            ->whereNotNull('orders.user_id')
            ->distinct()
            ->pluck('orders.user_id');

        $repeatCustomers = 0;
        $newCustomers    = 0;

        if ($allBuyerIds->isNotEmpty()) {
            // Cek apakah user pernah order (valid) sebelum dateFrom
            $repeatIds = Order::whereIn('user_id', $allBuyerIds)
                ->whereIn('status', $validStatuses)
                ->where('created_at', '<', $dateFrom . ' 00:00:00')
                ->whereNull('deleted_at')
                ->distinct()
                ->pluck('user_id');

            $repeatCustomers = $repeatIds->count();
            $newCustomers    = $allBuyerIds->count() - $repeatCustomers;
        }

        // =============================================
        // DROPDOWN OPTIONS
        // =============================================
        $provinceOptions = collect()
            ->merge(DB::table('order_shipping_snapshots')
                ->join('orders', 'order_shipping_snapshots.order_id', '=', 'orders.id')
                ->whereNotNull('order_shipping_snapshots.province_name')
                ->where('order_shipping_snapshots.province_name', '!=', '')
                ->whereNull('orders.deleted_at')
                ->pluck('order_shipping_snapshots.province_name'))
            ->merge(DB::table('orders')
                ->leftJoin('addresses', 'orders.address_id', '=', 'addresses.id')
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

        return compact(
            'totalRevenue', 'totalTransactions', 'totalProductsSold',
            'avgTransactionValue', 'totalBuyers',
            'chartDates', 'chartRevenues',
            'topProducts', 'topCategories', 'topProvinces',
            'paymentMethods', 'paymentStatuses',
            'cpProvince', 'cpDatasets',
            'hourLabels', 'hourData',
            'repeatCustomers', 'newCustomers',
            'provinceOptions', 'categoryOptions'
        );
        })(); // immediately invoked

        // Ekstrak semua variabel dari cache
        extract($cached);

        // Sales table tidak dicache — bergantung pada halaman aktif (pagination)
        $salesTable = Order::with(['items.product.category', 'payment', 'address', 'shippingSnapshot'])
            ->tap($applyBase)
            ->addSelect([
                'orders.*',
                DB::raw('COALESCE(order_shipping_snapshots.province_name, addresses.province_name) as province'),
            ])
            ->latest('orders.created_at')
            ->paginate(5)
            ->withQueryString();

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

    private function applyDashboardDbFilters(QueryBuilder $query, string $dateFrom, string $dateTo, ?string $province, int|string|null $categoryId, ?string $categoryName, ?string $paymentMethod, ?string $paymentStatus): void
    {
        $query->whereBetween('orders.created_at', [
                $dateFrom . ' 00:00:00',
                $dateTo . ' 23:59:59',
            ])
            ->whereNull('orders.deleted_at');

        if ($province) {
            $query->where(function ($sub) use ($province) {
                $sub->where('order_shipping_snapshots.province_name', $province)
                    ->orWhere('addresses.province_name', $province);
            });
        }

        if ($paymentStatus) {
            $query->where('orders.status', $paymentStatus);
        }

        if ($categoryId) {
            $query->where(function ($sub) use ($categoryId, $categoryName) {
                $sub->where('products.category_id', $categoryId);

                if ($categoryName) {
                    $sub->orWhere('order_items.product_category_name', $categoryName);
                }
            });
        }

        if ($paymentMethod) {
            $query->where(function ($sub) use ($paymentMethod) {
                $sub->where('orders.payment_method', $paymentMethod)
                    ->orWhereIn('orders.id', DB::table('payments')
                        ->select('order_id')
                        ->where('payment_type', $paymentMethod));
            });
        }
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
