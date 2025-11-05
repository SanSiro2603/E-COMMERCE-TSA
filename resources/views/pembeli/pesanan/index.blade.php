@extends('layouts.app')

@section('title', 'Pesanan Saya - Lembah Hijau')

@section('content')
<div class="space-y-6">

    <!-- Success/Error Alerts -->
    @if(session('success'))
        <div class="bg-green-50 dark:bg-green-500/10 border border-green-200 dark:border-green-500/20 rounded-lg p-4 animate-fade-in">
            <div class="flex items-start gap-3">
                <span class="material-symbols-outlined text-green-600 dark:text-green-400 text-2xl">check_circle</span>
                <div class="flex-1">
                    <h3 class="text-sm font-semibold text-green-900 dark:text-green-300">Berhasil!</h3>
                    <p class="text-sm text-green-800 dark:text-green-400 mt-1">{{ session('success') }}</p>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" 
                        class="text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 rounded-lg p-4 animate-fade-in">
            <div class="flex items-start gap-3">
                <span class="material-symbols-outlined text-red-600 dark:text-red-400 text-2xl">error</span>
                <div class="flex-1">
                    <h3 class="text-sm font-semibold text-red-900 dark:text-red-300">Gagal!</h3>
                    <p class="text-sm text-red-800 dark:text-red-400 mt-1">{{ session('error') }}</p>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" 
                        class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
        </div>
    @endif

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-zinc-400">
        <a href="{{ route('pembeli.dashboard') }}" class="hover:text-soft-green transition-colors">Beranda</a>
        <span class="material-symbols-outlined text-lg">chevron_right</span>
        <span class="text-gray-900 dark:text-white font-medium">Pesanan Saya</span>
    </nav>

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white font-be-vietnam">
                Pesanan Saya
            </h1>
            <p class="text-sm text-gray-600 dark:text-zinc-400 mt-1">
                Kelola dan pantau pesanan Anda
            </p>
        </div>
        <a href="{{ route('pembeli.produk.index') }}"
           class="flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-soft-green to-primary text-white rounded-lg text-sm font-medium hover:shadow-lg transition-all">
            <span class="material-symbols-outlined text-lg">storefront</span>
            Belanja Lagi
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-zinc-900 p-4 rounded-lg border border-gray-200 dark:border-zinc-800">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-yellow-100 dark:bg-yellow-500/10 rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined text-yellow-600 dark:text-yellow-400">hourglass_top</span>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-zinc-400">Pending</p>
                    <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $orders->where('status', 'pending')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-900 p-4 rounded-lg border border-gray-200 dark:border-zinc-800">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-purple-100 dark:bg-purple-500/10 rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined text-purple-600 dark:text-purple-400">inventory</span>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-zinc-400">Diproses</p>
                    <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $orders->where('status', 'processing')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-900 p-4 rounded-lg border border-gray-200 dark:border-zinc-800">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-500/10 rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined text-indigo-600 dark:text-indigo-400">local_shipping</span>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-zinc-400">Dikirim</p>
                    <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $orders->where('status', 'shipped')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-900 p-4 rounded-lg border border-gray-200 dark:border-zinc-800">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-green-100 dark:bg-green-500/10 rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined text-green-600 dark:text-green-400">check_circle</span>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-zinc-400">Selesai</p>
                    <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $orders->where('status', 'completed')->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm p-4">
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('pembeli.pesanan.index') }}" 
               class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ !request('status') ? 'bg-gradient-to-r from-soft-green to-primary text-white shadow-md' : 'bg-gray-100 dark:bg-zinc-800 text-gray-700 dark:text-zinc-300 hover:bg-gray-200 dark:hover:bg-zinc-700' }}">
                Semua
            </a>
            @foreach($statuses as $key => $label)
                @if($key !== 'all')
                    <a href="{{ route('pembeli.pesanan.index', ['status' => $key]) }}" 
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ request('status') == $key ? 'bg-gradient-to-r from-soft-green to-primary text-white shadow-md' : 'bg-gray-100 dark:bg-zinc-800 text-gray-700 dark:text-zinc-300 hover:bg-gray-200 dark:hover:bg-zinc-700' }}">
                        {{ $label }}
                    </a>
                @endif
            @endforeach
        </div>
    </div>

    <!-- Orders List -->
    @if($orders->count() > 0)
        <div class="space-y-4">
            @foreach($orders as $order)
                <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                    <!-- Order Header -->
                    <div class="p-4 sm:p-6 border-b border-gray-200 dark:border-zinc-800 bg-gray-50 dark:bg-zinc-800/50">
                        <div class="flex flex-col sm:flex-row justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="material-symbols-outlined text-soft-green">receipt_long</span>
                                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">
                                        #{{ $order->order_number }}
                                    </h3>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium rounded-full
                                        @if($order->status === 'pending') bg-yellow-100 dark:bg-yellow-500/20 text-yellow-700 dark:text-yellow-400
                                        @elseif($order->status === 'paid') bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-400
                                        @elseif($order->status === 'processing') bg-purple-100 dark:bg-purple-500/20 text-purple-700 dark:text-purple-400
                                        @elseif($order->status === 'shipped') bg-indigo-100 dark:bg-indigo-500/20 text-indigo-700 dark:text-indigo-400
                                        @elseif($order->status === 'completed') bg-green-100 dark:bg-green-500/20 text-green-700 dark:text-green-400
                                        @else bg-red-100 dark:bg-red-500/20 text-red-700 dark:text-red-400
                                        @endif">
                                        <span class="w-1.5 h-1.5 rounded-full
                                            @if($order->status === 'pending') bg-yellow-500
                                            @elseif($order->status === 'paid') bg-blue-500
                                            @elseif($order->status === 'processing') bg-purple-500
                                            @elseif($order->status === 'shipped') bg-indigo-500
                                            @elseif($order->status === 'completed') bg-green-500
                                            @else bg-red-500
                                            @endif"></span>
                                        {{ $order->status_label }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-zinc-400">
                                    <span class="material-symbols-outlined text-sm align-middle">calendar_today</span>
                                    {{ $order->created_at->format('d M Y, H:i') }} • {{ $order->items->count() }} produk
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-500 dark:text-zinc-400 mb-1">Total Bayar</p>
                                <p class="text-xl font-bold text-soft-green">
                                    Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items Preview -->
                    <div class="p-4 sm:p-6">
                        <div class="space-y-3">
                            @foreach($order->items->take(2) as $item)
                                <div class="flex gap-3">
                                    <div class="w-16 h-16 bg-gray-100 dark:bg-zinc-800 rounded-lg overflow-hidden flex-shrink-0">
                                        @if($item->product && $item->product->image)
                                            <img src="{{ asset('storage/' . $item->product->image) }}" 
                                                 alt="{{ $item->product->name }}"
                                                 class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <span class="material-symbols-outlined text-gray-400 text-xl">image</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white line-clamp-1">
                                            {{ $item->product->name ?? 'Produk tidak tersedia' }}
                                        </h4>
                                        <p class="text-xs text-gray-600 dark:text-zinc-400 mt-1">
                                            {{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-bold text-gray-900 dark:text-white">
                                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach

                            @if($order->items->count() > 2)
                                <p class="text-xs text-gray-500 dark:text-zinc-400 text-center py-2">
                                    +{{ $order->items->count() - 2 }} produk lainnya
                                </p>
                            @endif
                        </div>
                    </div>

                    <!-- Order Actions -->
                    <div class="p-4 sm:p-6 border-t border-gray-200 dark:border-zinc-800 bg-gray-50 dark:bg-zinc-800/50">
                        <div class="flex flex-wrap gap-2 justify-end">
                            <a href="{{ route('pembeli.pesanan.show', $order) }}"
                               class="inline-flex items-center gap-1 px-4 py-2 bg-gray-100 dark:bg-zinc-700 text-gray-700 dark:text-zinc-300 hover:bg-gray-200 dark:hover:bg-zinc-600 rounded-lg text-sm font-medium transition-colors">
                                <span class="material-symbols-outlined text-base">visibility</span>
                                Detail
                            </a>

                            @if($order->canBeCancelled())
                                <form action="{{ route('pembeli.pesanan.cancel', $order) }}" method="POST" 
                                      onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?')" class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="inline-flex items-center gap-1 px-4 py-2 bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-500/20 rounded-lg text-sm font-medium transition-colors">
                                        <span class="material-symbols-outlined text-base">cancel</span>
                                        Batalkan
                                    </button>
                                </form>
                            @endif

                            @if($order->status === 'pending')
    <a href="{{ route('pembeli.pesanan.edit', $order) }}"
       class="inline-flex items-center gap-1 px-4 py-2 bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-500/20 rounded-lg text-sm font-medium transition-colors">
        <span class="material-symbols-outlined text-base">edit</span>
        Edit
    </a>
@endif


                            @if($order->canBeCompleted())
                                <form action="{{ route('pembeli.pesanan.complete', $order) }}" method="POST" 
                                      onsubmit="return confirm('Konfirmasi pesanan telah diterima?')" class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="inline-flex items-center gap-1 px-4 py-2 bg-green-50 dark:bg-green-500/10 text-green-600 dark:text-green-400 hover:bg-green-100 dark:hover:bg-green-500/20 rounded-lg text-sm font-medium transition-colors">
                                        <span class="material-symbols-outlined text-base">check_circle</span>
                                        Pesanan Diterima
                                    </button>
                                </form>
                            @endif

                            @if($order->status === 'pending')
    <a href="{{ route('pembeli.payment.show', $order->id) }}"
       class="inline-flex items-center gap-1 px-4 py-2 bg-gradient-to-r from-soft-green to-primary text-white hover:shadow-lg rounded-lg text-sm font-medium transition-all">
        <span class="material-symbols-outlined text-base">payment</span>
        Bayar Sekarang
    </a>
@endif

                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($orders->hasPages())
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm p-4">
                {{ $orders->appends(request()->query())->links() }}
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-sm">
            <div class="text-center py-16 px-4">
                <div class="w-24 h-24 bg-gray-100 dark:bg-zinc-800 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined text-gray-300 dark:text-zinc-600 text-6xl">receipt_long</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                    Belum Ada Pesanan
                </h3>
                <p class="text-gray-600 dark:text-zinc-400 mb-6">
                    Anda belum memiliki pesanan. Yuk, mulai belanja sekarang!
                </p>
                <a href="{{ route('pembeli.produk.index') }}" 
                   class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-soft-green to-primary text-white font-medium rounded-lg hover:shadow-lg transition-all">
                    <span class="material-symbols-outlined">storefront</span>
                    Mulai Belanja
                </a>
            </div>
        </div>
    @endif

</div>

<style>
    @keyframes fade-in {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-fade-in {
        animation: fade-in 0.3s ease-out;
    }
</style>
@endsection