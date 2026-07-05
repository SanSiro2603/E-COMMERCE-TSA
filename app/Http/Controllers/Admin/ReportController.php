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
    protected array $validStatuses = ['paid', 'processing', 'shipped', 'completed'];

    public function index(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate   = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));
        $status    = $request->input('status');

        $baseQuery = Order::with(['user', 'items.product', 'address', 'shippingSnapshot'])
            ->whereBetween('created_at', [
                $startDate . ' 00:00:00',
                $endDate   . ' 23:59:59',
            ]);

        if ($status) $baseQuery->where('status', $status);

        $statsQuery  = clone $baseQuery;
        $statsOrders = $statsQuery->whereIn('status', $this->validStatuses)->get();

        $stats = [
            'total_revenue'    => $statsOrders->sum('grand_total'),
            'total_orders'     => $statsOrders->count(),
            'total_items_sold' => $statsOrders->sum(fn($o) => $o->items->sum('quantity')),
            'avg_order_value'  => $statsOrders->count() > 0
                ? round($statsOrders->avg('grand_total'), 0)
                : 0,
        ];

        $orders = (clone $baseQuery)->latest()->paginate(5)->withQueryString();

        $statusOptions = [
            'pending'    => 'Menunggu Pembayaran',
            'paid'       => 'Sudah Dibayar',
            'processing' => 'Diproses',
            'shipped'    => 'Dikirim',
            'completed'  => 'Selesai',
            'cancelled'  => 'Dibatalkan',
        ];

        $statsNote = $this->statsNote($status, $statusOptions);

        return view('admin.reports.index', compact(
            'orders', 'stats',
            'startDate', 'endDate', 'status',
            'statusOptions', 'statsNote'
        ));
    }

    public function exportPdf(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate   = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));
        $status    = $request->input('status');

        $query = Order::with(['user', 'items.product', 'address', 'shippingSnapshot'])
            ->whereBetween('created_at', [
                $startDate . ' 00:00:00',
                $endDate   . ' 23:59:59',
            ])
            ->orderBy('created_at', 'asc');

        if ($status) $query->where('status', $status);

        $orders = $query->get();

        $statsOrders = $orders->filter(fn($o) => in_array($o->status, $this->validStatuses));

        $stats = [
            'total_revenue'    => $statsOrders->sum('grand_total'),
            'total_orders'     => $statsOrders->count(),
            'total_items_sold' => $statsOrders->sum(fn($o) => $o->items->sum('quantity')),
            'avg_order_value'  => $statsOrders->count() > 0
                ? round($statsOrders->avg('grand_total'), 0)
                : 0,
        ];

        $statusOptions = [
            'pending'    => 'Menunggu Pembayaran',
            'paid'       => 'Sudah Dibayar',
            'processing' => 'Diproses',
            'shipped'    => 'Dikirim',
            'completed'  => 'Selesai',
            'cancelled'  => 'Dibatalkan',
        ];

        $statsNote = $this->statsNote($status, $statusOptions);

        $pdf = Pdf::loadView('admin.reports.pdf', compact(
            'orders', 'stats', 'startDate', 'endDate', 'statsNote'
        ))->setPaper('a4', 'landscape');

        return $pdf->download('laporan-penjualan-' . $startDate . '-sd-' . $endDate . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate   = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));
        $status    = $request->input('status');

        $fileName = 'laporan-penjualan-' . $startDate . '-sd-' . $endDate . '.xlsx';

        return Excel::download(
            new SalesReportExport($startDate, $endDate, $status),
            $fileName
        );
    }

    private function statsNote(?string $status, array $statusOptions): ?string
    {
        if (!$status || in_array($status, $this->validStatuses)) {
            return null;
        }

        return 'Pesanan dengan status "'
            . ($statusOptions[$status] ?? $status)
            . '" tetap ditampilkan di tabel untuk analisis, tetapi tidak dihitung pada kartu statistik penjualan karena belum menjadi transaksi valid.';
    }
}
