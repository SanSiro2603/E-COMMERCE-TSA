<!-- resources/views/superadmin/admins/show.blade.php -->
@extends('layouts.superadmin')

@section('page-title', 'Detail Admin')
@section('page-subtitle', 'Informasi lengkap administrator')

@section('content')
<div class="space-y-6">
    
    <!-- Back Button -->
    <a href="{{ route('superadmin.admins.index') }}" 
       class="inline-flex items-center gap-2 text-sm text-gray-600 dark:text-zinc-400 hover:text-soft-green dark:hover:text-soft-green transition-colors">
        <span class="material-symbols-outlined text-[20px]">arrow_back</span>
        Kembali ke Daftar Admin
    </a>
    
    <!-- Admin Profile Card -->
    <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-soft border border-gray-100 dark:border-zinc-800 p-6">
        <div class="flex flex-col md:flex-row md:items-start gap-6">
            <!-- Avatar -->
            <div class="flex-shrink-0">
                <div class="w-24 h-24 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center text-white font-bold text-3xl shadow-xl shadow-purple-500/30">
                    {{ strtoupper(substr($admin->name, 0, 1)) }}
                </div>
            </div>
            
            <!-- Info -->
            <div class="flex-1 space-y-4">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $admin->name }}</h3>
                    <p class="text-sm text-gray-500 dark:text-zinc-400 mt-1">{{ $admin->email }}</p>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-500 dark:text-zinc-500 uppercase tracking-wide mb-1">Telepon</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $admin->phone ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-zinc-500 uppercase tracking-wide mb-1">Terdaftar</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $admin->created_at->format('d M Y') }}</p>
                    </div>
                    <div class="sm:col-span-2">
                        <p class="text-xs text-gray-500 dark:text-zinc-500 uppercase tracking-wide mb-1">Alamat</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $admin->address ?? '-' }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="flex md:flex-col gap-2">
                <a href="{{ route('superadmin.admins.edit', $admin) }}" 
                   class="flex-1 md:flex-none inline-flex items-center justify-center gap-2 px-4 py-2 bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400 hover:bg-orange-100 dark:hover:bg-orange-500/20 rounded-lg text-sm font-medium transition-colors">
                    <span class="material-symbols-outlined text-[18px]">edit</span>
                    Edit
                </a>
                <button onclick="confirmDelete({{ $admin->id }})" 
                        class="flex-1 md:flex-none inline-flex items-center justify-center gap-2 px-4 py-2 bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-500/20 rounded-lg text-sm font-medium transition-colors">
                    <span class="material-symbols-outlined text-[18px]">delete</span>
                    Hapus
                </button>
                <form id="delete-form-{{ $admin->id }}" 
                      action="{{ route('superadmin.admins.destroy', $admin) }}" 
                      method="POST" 
                      class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
    </div>
    
    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-zinc-900 rounded-xl p-6 shadow-soft border border-gray-100 dark:border-zinc-800">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/30">
                    <span class="material-symbols-outlined text-white text-[22px]">shopping_cart</span>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-zinc-400 uppercase tracking-wide">Total Pesanan</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ number_format($stats['total_orders']) }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-zinc-900 rounded-xl p-6 shadow-soft border border-gray-100 dark:border-zinc-800">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg shadow-green-500/30">
                    <span class="material-symbols-outlined text-white text-[22px]">check_circle</span>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-zinc-400 uppercase tracking-wide">Selesai</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ number_format($stats['completed_orders']) }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-zinc-900 rounded-xl p-6 shadow-soft border border-gray-100 dark:border-zinc-800">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-purple-500/30">
                    <span class="material-symbols-outlined text-white text-[22px]">payments</span>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-zinc-400 uppercase tracking-wide">Total Revenue</p>
                    <p class="text-xl font-bold text-gray-900 dark:text-white mt-1">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Orders -->
    <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-soft border border-gray-100 dark:border-zinc-800">
        <div class="p-6 border-b border-gray-100 dark:border-zinc-800">
            <h3 class="text-base font-bold text-gray-900 dark:text-white">Pesanan Terbaru</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-zinc-800/50 border-b border-gray-100 dark:border-zinc-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-[10px] font-semibold text-gray-600 dark:text-zinc-400 uppercase tracking-wider">No. Pesanan</th>
                        <th class="px-6 py-3 text-left text-[10px] font-semibold text-gray-600 dark:text-zinc-400 uppercase tracking-wider">Pembeli</th>
                        <th class="px-6 py-3 text-left text-[10px] font-semibold text-gray-600 dark:text-zinc-400 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-center text-[10px] font-semibold text-gray-600 dark:text-zinc-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-[10px] font-semibold text-gray-600 dark:text-zinc-400 uppercase tracking-wider">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-zinc-800">
                    @forelse($admin->orders as $order)
                    <tr class="hover:bg-gray-50 dark:hover:bg-zinc-800/50 transition-colors">
                        <td class="px-6 py-4">
                            <span class="text-xs font-semibold text-blue-600 dark:text-blue-400">#{{ $order->order_number }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-xs font-medium text-gray-900 dark:text-white">{{ $order->user->name }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-bold text-gray-900 dark:text-white">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-100 dark:bg-yellow-500/20 text-yellow-700 dark:text-yellow-400',
                                    'paid' => 'bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-400',
                                    'processing' => 'bg-purple-100 dark:bg-purple-500/20 text-purple-700 dark:text-purple-400',
                                    'shipped' => 'bg-indigo-100 dark:bg-indigo-500/20 text-indigo-700 dark:text-indigo-400',
                                    'completed' => 'bg-green-100 dark:bg-green-500/20 text-green-700 dark:text-green-400',
                                    'cancelled' => 'bg-red-100 dark:bg-red-500/20 text-red-700 dark:text-red-400',
                                ];
                            @endphp
                            <span class="px-2.5 py-1 text-[10px] font-semibold rounded-full {{ $statusColors[$order->status] ?? 'bg-gray-100 dark:bg-gray-500/20 text-gray-700 dark:text-gray-400' }}">
                                {{ $order->status_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-xs text-gray-900 dark:text-white">{{ $order->created_at->format('d M Y') }}</p>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <span class="material-symbols-outlined text-gray-300 dark:text-zinc-700 text-5xl">shopping_bag</span>
                            <p class="text-xs text-gray-500 dark:text-zinc-500 mt-2">Belum ada pesanan</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
</div>
@endsection

@push('scripts')
<script>
function confirmDelete(adminId) {
    Swal.fire({
        title: 'Hapus Admin?',
        text: "Data admin akan dihapus permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#EF4444',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + adminId).submit();
        }
    });
}
</script>
@endpush