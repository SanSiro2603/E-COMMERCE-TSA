{{-- resources/views/admin/orders/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Kelola Pesanan - E-Commerce TSA')

@section('content')

<style>
    /* ── Pagination: tombol angka biasa ── */
    nav svg {
        color: #2D6A4F !important;
    }
    nav a.relative.inline-flex {
        color: #2D6A4F !important;
        background-color: #ffffff !important;
        border-color: #2D6A4F !important;
    }
    nav a.relative.inline-flex:hover {
        background-color: #2D6A4F !important;
        color: #ffffff !important;
        border-color: #2D6A4F !important;
    }
    /* ── Tombol aktif ── */
    nav span[aria-current="page"] span.relative.inline-flex {
        background: #2D6A4F !important;
        color: #ffffff !important;
        border-color: #2D6A4F !important;
    }
    /* ── Tombol disabled ── */
    nav span[aria-disabled="true"] span {
        color: #9ca3af !important;
        border-color: #e5e7eb !important;
        background-color: #ffffff !important;
    }
    /* ── Dark mode: tombol biasa ── */
    .dark nav a.relative.inline-flex {
        color: #4ade80 !important;
        background-color: #27272a !important;
        border-color: #2D6A4F !important;
    }
    .dark nav a.relative.inline-flex:hover {
        background-color: #2D6A4F !important;
        color: #ffffff !important;
        border-color: #2D6A4F !important;
    }
    /* ── Dark mode: tombol aktif ── */
    .dark nav span[aria-current="page"] span.relative.inline-flex {
        background: #2D6A4F !important;
        color: #ffffff !important;
        border-color: #2D6A4F !important;
    }
    /* ── Dark mode: tombol disabled ── */
    .dark nav span[aria-disabled="true"] span {
        color: #52525b !important;
        border-color: #3f3f46 !important;
        background-color: #27272a !important;
    }

    @keyframes fade-in {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in { animation: fade-in 0.3s ease-out; }
</style>

<div class="space-y-6">

    @if(session('success'))
        <div class="bg-green-50 dark:bg-green-500/10 border border-green-200 dark:border-green-500/20 rounded-lg p-4 animate-fade-in" data-auto-dismiss>
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0">
                    <span class="material-symbols-outlined text-green-600 dark:text-green-400 text-2xl">check_circle</span>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-semibold text-green-900 dark:text-green-300">Berhasil!</h3>
                    <p class="text-sm text-green-800 dark:text-green-400 mt-1">{{ session('success') }}</p>
                </div>
                <button onclick="this.parentElement.parentElement.remove()"
                        class="flex-shrink-0 text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300 transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 rounded-lg p-4 animate-fade-in" data-auto-dismiss>
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0">
                    <span class="material-symbols-outlined text-red-600 dark:text-red-400 text-2xl">error</span>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-semibold text-red-900 dark:text-red-300">Gagal!</h3>
                    <p class="text-sm text-red-800 dark:text-red-400 mt-1">{{ session('error') }}</p>
                </div>
                <button onclick="this.parentElement.parentElement.remove()"
                        class="flex-shrink-0 text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
        </div>
    @endif

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-zinc-400">
        <a href="{{ route('admin.dashboard') }}" class="hover:text-soft-green transition-colors">Dashboard</a>
        <span class="material-symbols-outlined text-lg">chevron_right</span>
        <span class="text-gray-900 dark:text-white font-medium">Pesanan</span>
    </nav>

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white font-be-vietnam">Kelola Pesanan</h1>
            <p class="text-sm text-gray-500 dark:text-zinc-400 mt-1">Kelola semua pesanan dari pelanggan</p>
        </div>
    </div>

    {{-- STATS CARDS — background soft per status
         [+] Tambah kartu baru di sini jika ada status baru
         Sesuaikan juga: $stats di OrderController, $statuses, dan kolom grid (grid-cols-7) --}}
    <div class="grid grid-cols-2 lg:grid-cols-7 gap-4">

        {{-- Semua — abu-abu --}}
        <div class="bg-gradient-to-br from-gray-500 to-gray-700 rounded-xl p-4 shadow-sm border border-gray-400 dark:border-zinc-600" data-stat="all">
            <div class="flex items-center justify-between mb-3">
                <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined text-white text-lg">shopping_bag</span>
                </div>
            </div>
            <p class="text-2xl font-bold text-white">{{ $stats['all'] }}</p>
            <p class="text-[10px] font-semibold text-gray-200 uppercase tracking-wide mt-1">Semua</p>
        </div>

        {{-- Menunggu — kuning --}}
        <div class="bg-gradient-to-br from-yellow-400 to-amber-500 rounded-xl p-4 shadow-sm border border-yellow-400 dark:border-yellow-500/50" data-stat="pending">
            <div class="flex items-center justify-between mb-3">
                <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined text-white text-lg">hourglass_top</span>
                </div>
            </div>
            <p class="text-2xl font-bold text-white">{{ $stats['pending'] }}</p>
            <p class="text-[10px] font-semibold text-yellow-100 uppercase tracking-wide mt-1">Menunggu</p>
        </div>

        {{-- Dibayar — biru --}}
        <div class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-xl p-4 shadow-sm border border-blue-400 dark:border-blue-500/50" data-stat="paid">
            <div class="flex items-center justify-between mb-3">
                <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined text-white text-lg">payments</span>
                </div>
            </div>
            <p class="text-2xl font-bold text-white">{{ $stats['paid'] }}</p>
            <p class="text-[10px] font-semibold text-blue-100 uppercase tracking-wide mt-1">Dibayar</p>
        </div>

        {{-- Diproses — ungu --}}
        <div class="bg-gradient-to-br from-purple-500 to-violet-600 rounded-xl p-4 shadow-sm border border-purple-400 dark:border-purple-500/50" data-stat="processing">
            <div class="flex items-center justify-between mb-3">
                <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined text-white text-lg">inventory</span>
                </div>
            </div>
            <p class="text-2xl font-bold text-white">{{ $stats['processing'] }}</p>
            <p class="text-[10px] font-semibold text-purple-100 uppercase tracking-wide mt-1">Diproses</p>
        </div>

        {{-- Dikirim — indigo --}}
        <div class="bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-xl p-4 shadow-sm border border-indigo-400 dark:border-indigo-500/50" data-stat="shipped">
            <div class="flex items-center justify-between mb-3">
                <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined text-white text-lg">local_shipping</span>
                </div>
            </div>
            <p class="text-2xl font-bold text-white">{{ $stats['shipped'] }}</p>
            <p class="text-[10px] font-semibold text-indigo-100 uppercase tracking-wide mt-1">Dikirim</p>
        </div>

        {{-- Selesai — hijau --}}
        <div class="bg-gradient-to-br from-emerald-500 to-green-600 rounded-xl p-4 shadow-sm border border-emerald-400 dark:border-emerald-500/50" data-stat="completed">
            <div class="flex items-center justify-between mb-3">
                <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined text-white text-lg">check_circle</span>
                </div>
            </div>
            <p class="text-2xl font-bold text-white">{{ $stats['completed'] }}</p>
            <p class="text-[10px] font-semibold text-emerald-100 uppercase tracking-wide mt-1">Selesai</p>
        </div>

        {{-- Dibatalkan — merah --}}
        <div class="bg-gradient-to-br from-red-500 to-rose-600 rounded-xl p-4 shadow-sm border border-red-400 dark:border-red-500/50" data-stat="cancelled">
            <div class="flex items-center justify-between mb-3">
                <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined text-white text-lg">cancel</span>
                </div>
            </div>
            <p class="text-2xl font-bold text-white">{{ $stats['cancelled'] }}</p>
            <p class="text-[10px] font-semibold text-red-100 uppercase tracking-wide mt-1">Dibatalkan</p>
        </div>

    </div>

    <!-- Filter & Search -->
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm p-4">
        <form method="GET" class="flex flex-col md:flex-row gap-3">

            {{-- [+] Tambah input filter baru di sini (mis: filter by tanggal, kurir) --}}
            <div class="flex-1">
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-zinc-500">search</span>
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Cari nomor pesanan / nama pembeli..."
                           class="w-full pl-10 pr-4 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 rounded-lg text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-zinc-500 focus:ring-2 focus:ring-soft-green focus:border-soft-green transition-colors">
                </div>
            </div>

            <div class="w-full md:w-48">
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-zinc-500">filter_list</span>
                    <select name="status"
                            class="w-full pl-10 pr-4 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-soft-green focus:border-soft-green transition-colors appearance-none">
                        @foreach($statuses as $value => $label)
                            <option value="{{ $value }}" {{ request('status', 'all') == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <button type="submit"
                    class="flex items-center justify-center gap-2 px-6 py-2.5 bg-soft-green hover:bg-primary text-white font-medium rounded-lg transition-colors">
                <span class="material-symbols-outlined text-lg">search</span>
                Filter
            </button>

            @if(request('search') || (request('status') && request('status') != 'all'))
                <a href="{{ route('admin.orders.index') }}"
                   class="flex items-center justify-center gap-2 px-6 py-2.5 bg-gray-100 dark:bg-zinc-800 hover:bg-gray-200 dark:hover:bg-zinc-700 text-gray-700 dark:text-zinc-300 font-medium rounded-lg transition-colors">
                    <span class="material-symbols-outlined text-lg">close</span>
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Tabel Pesanan -->
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                {{-- HEADER TABEL — background hijau tua #2D6A4F seperti laporan --}}
                <thead>
                    <tr style="background-color: #2D6A4F;">
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">No. Pesanan</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Pembeli</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Total</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-white uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-white uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-zinc-800">
                    @forelse($orders as $order)
                        @php
                            // Badge "BARU!" jika baru dibayar dalam 2 menit terakhir
                            $isNewPaid = $order->status === 'paid' && $order->paid_at && $order->paid_at->diffInMinutes(now()) < 2;

                            // Status yang TIDAK pakai animasi ping (sudah final)
                            $noPing = in_array($order->status, ['completed', 'cancelled']);
                        @endphp

                        <tr class="hover:bg-gray-50 dark:hover:bg-zinc-800/50 transition-all {{ $isNewPaid ? 'ring-2 ring-green-400 ring-opacity-50 animate-pulse' : '' }}">

                            {{-- KOLOM 1: Nomor Pesanan --}}
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.orders.show', $order) }}"
                                   class="text-sm font-semibold text-soft-green hover:text-primary transition-colors">
                                    #{{ $order->order_number }}
                                </a>
                                @if($isNewPaid)
                                    <span class="ml-2 inline-flex items-center gap-1 px-2 py-0.5 text-xs font-bold text-green-700 bg-green-100 dark:bg-green-500/20 dark:text-green-300 rounded-full animate-bounce">BARU!</span>
                                @endif
                            </td>

                            {{-- KOLOM 2: Tanggal --}}
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-900 dark:text-white">{{ $order->created_at->format('d M Y') }}</p>
                                <p class="text-xs text-gray-500 dark:text-zinc-400">{{ $order->created_at->format('H:i') }}</p>
                            </td>

                            {{-- KOLOM 3: Pembeli — foto profil dari storage jika ada, fallback ke inisial --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if($order->user->profile_photo)
                                        <img src="{{ Storage::url($order->user->profile_photo) }}"
                                             alt="{{ $order->user->name }}"
                                             class="w-10 h-10 rounded-full object-cover border-2 border-soft-green/30 flex-shrink-0">
                                    @else
                                        <div class="w-10 h-10 bg-gradient-to-br from-soft-green to-primary rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                                            {{ strtoupper(substr($order->user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $order->user->name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-zinc-400">{{ $order->user->email }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- KOLOM 4: Total Bayar + Jumlah Item --}}
                            <td class="px-6 py-4">
                                <p class="text-sm font-bold text-gray-900 dark:text-white">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</p>
                                <p class="text-xs text-gray-500 dark:text-zinc-400">{{ $order->items->count() }} item</p>
                            </td>

                            {{-- KOLOM 5: Badge Status
                                 completed & cancelled → tidak pakai animate-ping
                                 [+] Tambah @case baru di @switch jika ada status baru --}}
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium rounded-full
                                    @switch($order->status)
                                        @case('pending') bg-yellow-100 dark:bg-yellow-500/20 text-yellow-700 dark:text-yellow-400 @break
                                        @case('paid') bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-400 @break
                                        @case('processing') bg-purple-100 dark:bg-purple-500/20 text-purple-700 dark:text-purple-400 @break
                                        @case('shipped') bg-indigo-100 dark:bg-indigo-500/20 text-indigo-700 dark:text-indigo-400 @break
                                        @case('completed') bg-green-100 dark:bg-green-500/20 text-green-700 dark:text-green-400 @break
                                        @case('cancelled') bg-red-100 dark:bg-red-500/20 text-red-700 dark:text-red-400 @break
                                    @endswitch">
                                    {{-- Dot: ping hanya untuk status non-final --}}
                                    <span class="w-1.5 h-1.5 rounded-full {{ $noPing ? '' : 'animate-ping' }}
                                        @switch($order->status)
                                            @case('pending') bg-yellow-500 @break
                                            @case('paid') bg-blue-500 @break
                                            @case('processing') bg-purple-500 @break
                                            @case('shipped') bg-indigo-500 @break
                                            @case('completed') bg-green-500 @break
                                            @case('cancelled') bg-red-500 @break
                                        @endswitch"></span>
                                    {{ $order->status_label }}
                                </span>
                            </td>

                            {{-- KOLOM 6: Aksi — background hijau seperti button filter --}}
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('admin.orders.show', $order) }}"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 bg-soft-green hover:bg-primary text-white rounded-lg text-xs font-medium transition-colors shadow-sm shadow-soft-green/30">
                                    <span class="material-symbols-outlined text-base">visibility</span>
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <span class="material-symbols-outlined text-gray-300 dark:text-zinc-600 text-6xl mb-3">shopping_bag</span>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Belum ada pesanan</p>
                                    <p class="text-xs text-gray-500 dark:text-zinc-400 mt-1">Pesanan dari pelanggan akan muncul di sini</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-zinc-800 flex items-center justify-between">
                <p class="text-sm text-gray-500 dark:text-zinc-400">
                    Menampilkan {{ $orders->firstItem() }}–{{ $orders->lastItem() }} dari {{ $orders->total() }} pesanan
                </p>
                {{ $orders->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Auto-dismiss notifikasi setelah 4 detik
    document.querySelectorAll('[data-auto-dismiss]').forEach(el => {
        setTimeout(() => {
            el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            el.style.opacity = '0';
            el.style.transform = 'translateY(-8px)';
            setTimeout(() => el.remove(), 500);
        }, 4000);
    });
});
</script>

@endsection