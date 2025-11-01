{{-- resources/views/admin/reports/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Laporan Penjualan')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-charcoal">Laporan Penjualan</h1>
        <div class="flex gap-2">
            <a href="{{ route('admin.reports.exportPdf', request()->only('start_date', 'end_date')) }}"
               class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700">
                PDF
            </a>
            <a href="{{ route('admin.reports.exportExcel', request()->only('start_date', 'end_date')) }}"
               class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700">
                Excel
            </a>
        </div>
    </div>

    <!-- Filter Tanggal -->
    <form method="GET" class="bg-white p-4 rounded-xl shadow-sm">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <input type="date" name="start_date" value="{{ $startDate }}" required class="px-4 py-2 border rounded-lg">
            <input type="date" name="end_date" value="{{ $endDate }}" required class="px-4 py-2 border rounded-lg">
            <button type="submit" class="px-4 py-2 bg-soft-green text-white rounded-lg font-medium">
                Tampilkan
            </button>
        </div>
    </form>

    <!-- Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-xl shadow-sm">
            <p class="text-sm text-charcoal/70">Total Pendapatan</p>
            <p class="text-2xl font-bold text-soft-green">Rp {{ number_format($totalRevenue) }}</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm">
            <p class="text-sm text-charcoal/70">Jumlah Pesanan</p>
            <p class="text-2xl font-bold text-charcoal">{{ $totalOrders }}</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm">
            <p class="text-sm text-charcoal/70">Rata-rata Pesanan</p>
            <p class="text-2xl font-bold text-warm-yellow">Rp {{ number_format($averageOrderValue) }}</p>
        </div>
    </div>

    <!-- Grafik -->
    <div class="bg-white p-6 rounded-xl shadow-sm">
        <h3 class="text-lg font-semibold mb-4">Pendapatan Harian</h3>
        <canvas id="revenueChart" height="100"></canvas>
    </div>

    <!-- Tabel Detail -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No. Pesanan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pembeli</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($orders as $order)
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-charcoal">{{ $order->order_number }}</td>
                        <td class="px-6 py-4 text-sm">{{ $order->user->name }}</td>
                        <td class="px-6 py-4 text-sm">{{ $order->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-soft-green">Rp {{ number_format($order->grand_total) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center py-8 text-gray-500">Tidak ada data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div>{{ $orders->appends(request()->query())->links() }}</div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    new Chart(document.getElementById('revenueChart'), {
        type: 'bar',
        data: {
            labels: @json($dates),
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: @json($revenues),
                backgroundColor: '#7BB661',
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });
</script>
@endpush
@endsection