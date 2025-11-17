{{-- resources/views/admin/reports/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Laporan Penjualan E-Commerce TSA')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-white">Laporan Penjualan</h1>
        <p class="text-zinc-400 text-sm">Pilih rentang tanggal untuk melihat laporan</p>
    </div>

    {{-- Filter Tanggal + Tombol Preview --}}
    <form method="GET" action="{{ route('admin.reports.preview') }}"
          class="bg-zinc-800 p-4 rounded-xl shadow-sm grid grid-cols-1 md:grid-cols-3 gap-4">

        <input type="date" name="start_date" value="{{ $startDate }}" required
               class="px-4 py-2 border rounded-lg bg-zinc-700 text-white border-zinc-600">

        <input type="date" name="end_date" value="{{ $endDate }}" required
               class="px-4 py-2 border rounded-lg bg-zinc-700 text-white border-zinc-600">

        <button type="submit" class="px-4 py-2 bg-soft-green text-white rounded-lg font-medium hover:shadow-lg">
            Tampilkan Laporan
        </button>
    </form>

    {{-- Statistik --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-zinc-800 p-6 rounded-xl shadow-sm card-hover">
            <p class="text-sm text-zinc-400">Total Pendapatan</p>
            <p class="text-2xl font-bold text-soft-green">Rp {{ number_format($totalRevenue) }}</p>
        </div>

        <div class="bg-zinc-800 p-6 rounded-xl shadow-sm card-hover">
            <p class="text-sm text-zinc-400">Jumlah Pesanan</p>
            <p class="text-2xl font-bold text-white">{{ $totalOrders }}</p>
        </div>

        <div class="bg-zinc-800 p-6 rounded-xl shadow-sm card-hover">
            <p class="text-sm text-zinc-400">Rata-rata Pesanan</p>
            <p class="text-2xl font-bold text-warm-yellow">Rp {{ number_format($averageOrderValue) }}</p>
        </div>
    </div>

    {{-- Grafik Pendapatan --}}
    <div class="bg-zinc-800 p-6 rounded-xl shadow-sm card-hover">
        <h3 class="text-lg font-semibold mb-4 text-white">Pendapatan Harian</h3>
        <canvas id="revenueChart" height="100"></canvas>
    </div>

    {{-- Tabel Ringkas --}}
    <div class="bg-zinc-800 rounded-xl shadow-sm overflow-hidden card-hover">
        <table class="w-full text-white">
            <thead class="bg-zinc-900">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase">No. Pesanan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase">Pembeli</th>
                    <th class="px-6 py-3 text-xs font-medium uppercase">Tanggal</th>
                    <th class="px-6 py-3 text-xs font-medium uppercase">Total</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-zinc-700">
                @forelse($orders as $order)
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium">{{ $order->order_number }}</td>
                        <td class="px-6 py-4 text-sm">{{ $order->recipient_name }}</td>
                        <td class="px-6 py-4 text-sm">{{ $order->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-soft-green">
                            Rp {{ number_format($order->grand_total) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-8 text-zinc-400">Tidak ada data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="text-white">
        {{ $orders->appends(request()->query())->links() }}
    </div>

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
            scales: {
                x: { ticks: { color: '#fff' }, grid: { color: 'rgba(255,255,255,0.1)' } },
                y: { ticks: { color: '#fff' }, grid: { color: 'rgba(255,255,255,0.1)' }, beginAtZero: true }
            }
        }
    });
</script>
@endpush

@endsection
