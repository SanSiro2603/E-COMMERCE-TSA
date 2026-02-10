<?php
// app/Http/Controllers/SuperAdmin/SuperAdminReportController.php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SuperAdminReportExport;

class SuperAdminReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));

        // Data pesanan
        $orders = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->with(['user', 'items.product'])
            ->latest()
            ->paginate(15);

        // === STATISTIK LENGKAP ===
        $stats = [
            'total_revenue' => Order::whereBetween('created_at', [$startDate, $endDate])
                ->where('status', 'completed')
                ->sum('grand_total'),
            
            'total_orders' => Order::whereBetween('created_at', [$startDate, $endDate])
                ->where('status', 'completed')
                ->count(),
            
            'avg_order_value' => Order::whereBetween('created_at', [$startDate, $endDate])
                ->where('status', 'completed')
                ->avg('grand_total'),
            
            'total_customers' => Order::whereBetween('created_at', [$startDate, $endDate])
                ->where('status', 'completed')
                ->distinct('user_id')
                ->count('user_id'),
            
            // ✅ FIX: Tambahkan prefix table untuk menghindari ambiguitas
            'total_items_sold' => Order::whereBetween('orders.created_at', [$startDate, $endDate])
                ->where('orders.status', 'completed')
                ->join('order_items', 'orders.id', '=', 'order_items.order_id')
                ->sum('order_items.quantity'),
        ];

        // Grafik pendapatan harian
        $dailyRevenue = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->selectRaw('DATE(created_at) as date, SUM(grand_total) as total, COUNT(*) as orders_count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $dates = [];
        $revenues = [];
        $orderCounts = [];
        
        $current = \Carbon\Carbon::parse($startDate);
        $end = \Carbon\Carbon::parse($endDate);

        while ($current <= $end) {
            $date = $current->format('Y-m-d');
            $dayData = $dailyRevenue->firstWhere('date', $date);
            
            $dates[] = $current->format('d M');
            $revenues[] = $dayData->total ?? 0;
            $orderCounts[] = $dayData->orders_count ?? 0;
            
            $current->addDay();
        }

        // Top 10 produk terlaris
        $topProducts = Product::whereHas('orderItems.order', function($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate])
                  ->where('status', 'completed');
            })
            ->withSum(['orderItems as total_sold' => function($q) use ($startDate, $endDate) {
                $q->whereHas('order', function($q) use ($startDate, $endDate) {
                    $q->whereBetween('created_at', [$startDate, $endDate])
                      ->where('status', 'completed');
                });
            }], 'quantity')
            ->withSum(['orderItems as total_revenue' => function($q) use ($startDate, $endDate) {
                $q->whereHas('order', function($q) use ($startDate, $endDate) {
                    $q->whereBetween('created_at', [$startDate, $endDate])
                      ->where('status', 'completed');
                });
            }], 'subtotal')
            ->orderByDesc('total_sold')
            ->limit(10)
            ->get();

        // Top 10 pelanggan
        $topCustomers = User::where('role', 'pembeli')
            ->whereHas('orders', function($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate])
                  ->where('status', 'completed');
            })
            ->withCount(['orders as orders_count' => function($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate])
                  ->where('status', 'completed');
            }])
            ->withSum(['orders as total_spent' => function($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate])
                  ->where('status', 'completed');
            }], 'grand_total')
            ->orderByDesc('total_spent')
            ->limit(10)
            ->get();

        return view('superadmin.reports.index', compact(
            'orders',
            'stats',
            'startDate',
            'endDate',
            'dates',
            'revenues',
            'orderCounts',
            'topProducts',
            'topCustomers'
        ));
    }

    public function exportPdf(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $orders = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->with(['user', 'items.product'])
            ->orderBy('created_at', 'asc')
            ->get();

        $stats = [
            'total_revenue' => $orders->sum('grand_total'),
            'total_orders' => $orders->count(),
            'avg_order_value' => $orders->avg('grand_total'),
            'total_items_sold' => $orders->sum(function($order) {
                return $order->items->sum('quantity');
            }),
        ];

        $pdf = Pdf::loadView('superadmin.reports.pdf', compact('orders', 'stats', 'startDate', 'endDate'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('laporan-super-admin-' . $startDate . '-sd-' . $endDate . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));

        $fileName = 'laporan-super-admin-' . $startDate . '-sd-' . $endDate . '.xlsx';

        return Excel::download(new SuperAdminReportExport($startDate, $endDate), $fileName);
    }
}