<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\SalesReportExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));

        $orders = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->with('user')
            ->latest()
            ->paginate(10);

        // Statistik
        $totalRevenue = $orders->sum('grand_total');
        $totalOrders = $orders->count();
        $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        // Grafik: Pendapatan per Hari
        $dailyRevenue = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->selectRaw('DATE(created_at) as date, SUM(grand_total) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

        $dates = [];
        $revenues = [];
        $current = \Carbon\Carbon::parse($startDate);
        $end = \Carbon\Carbon::parse($endDate);

        while ($current <= $end) {
            $date = $current->format('Y-m-d');
            $dates[] = $current->format('d M');
            $revenues[] = $dailyRevenue->get($date, 0);
            $current->addDay();
        }

        return view('admin.reports.index', compact(
            'orders', 'totalRevenue', 'totalOrders', 'averageOrderValue',
            'startDate', 'endDate', 'dates', 'revenues'
        ));
    }

    public function exportPdf(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $orders = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->with('user', 'items.product')
            ->get();

        $totalRevenue = $orders->sum('grand_total');

        $pdf = Pdf::loadView('admin.reports.pdf', compact('orders', 'totalRevenue', 'startDate', 'endDate'));
        return $pdf->download('laporan-penjualan-' . $startDate . '-sd-' . $endDate . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(
            new SalesReportExport($request->start_date, $request->end_date),
            'laporan-penjualan-' . $request->start_date . '-sd-' . $request->end_date . '.xlsx'
        );
    }
}