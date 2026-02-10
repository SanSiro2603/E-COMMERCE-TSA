<!-- resources/views/superadmin/admins/index.blade.php -->
@extends('layouts.superadmin')

@section('page-title', 'Manajemen Admin')
@section('page-subtitle', 'Kelola data administrator sistem')

@section('content')
<div class="space-y-6">
    
    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Daftar Admin</h3>
            <p class="text-xs text-gray-500 dark:text-zinc-400 mt-1">Total: {{ $admins->total() }} admin</p>
        </div>
        
        <a href="{{ route('superadmin.admins.create') }}" 
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-soft-green to-primary hover:from-primary hover:to-soft-green text-white rounded-xl font-medium text-sm shadow-lg shadow-soft-green/30 transition-all">
            <span class="material-symbols-outlined text-[20px]">add</span>
            Tambah Admin
        </a>
    </div>
    
    <!-- Search Bar -->
    <div class="bg-white dark:bg-zinc-900 rounded-xl p-4 shadow-soft border border-gray-100 dark:border-zinc-800">
        <form method="GET" action="{{ route('superadmin.admins.index') }}" class="flex gap-3">
            <div class="flex-1 relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-zinc-500 text-[20px]">search</span>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Cari nama, email, atau telepon admin..." 
                       class="w-full pl-10 pr-4 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-lg text-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-zinc-500 focus:ring-2 focus:ring-soft-green focus:border-transparent">
            </div>
            <button type="submit" 
                    class="px-5 py-2.5 bg-soft-green hover:bg-primary text-white rounded-lg font-medium text-sm transition-colors">
                Cari
            </button>
            @if(request('search'))
            <a href="{{ route('superadmin.admins.index') }}" 
               class="px-5 py-2.5 bg-gray-200 dark:bg-zinc-700 hover:bg-gray-300 dark:hover:bg-zinc-600 text-gray-700 dark:text-zinc-300 rounded-lg font-medium text-sm transition-colors">
                Reset
            </a>
            @endif
        </form>
    </div>
    
    <!-- Admin Table -->
    <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-soft border border-gray-100 dark:border-zinc-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-zinc-800/50 border-b border-gray-100 dark:border-zinc-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-[10px] font-semibold text-gray-600 dark:text-zinc-400 uppercase tracking-wider">Admin</th>
                        <th class="px-6 py-3 text-left text-[10px] font-semibold text-gray-600 dark:text-zinc-400 uppercase tracking-wider">Kontak</th>
                        <th class="px-6 py-3 text-center text-[10px] font-semibold text-gray-600 dark:text-zinc-400 uppercase tracking-wider">Pesanan Ditangani</th>
                        <th class="px-6 py-3 text-left text-[10px] font-semibold text-gray-600 dark:text-zinc-400 uppercase tracking-wider">Terdaftar</th>
                        <th class="px-6 py-3 text-center text-[10px] font-semibold text-gray-600 dark:text-zinc-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-zinc-800">
                    @forelse($admins as $admin)
                    <tr class="hover:bg-gray-50 dark:hover:bg-zinc-800/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center text-white font-bold shadow-lg shadow-purple-500/30">
                                    {{ strtoupper(substr($admin->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $admin->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-zinc-500">{{ $admin->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="space-y-1">
                                @if($admin->phone)
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-gray-400 dark:text-zinc-500 text-[16px]">call</span>
                                    <span class="text-xs text-gray-700 dark:text-zinc-300">{{ $admin->phone }}</span>
                                </div>
                                @endif
                                @if($admin->address)
                                <div class="flex items-start gap-2">
                                    <span class="material-symbols-outlined text-gray-400 dark:text-zinc-500 text-[16px]">location_on</span>
                                    <span class="text-xs text-gray-700 dark:text-zinc-300">{{ Str::limit($admin->address, 30) }}</span>
                                </div>
                                @endif
                                @if(!$admin->phone && !$admin->address)
                                <span class="text-xs text-gray-400 dark:text-zinc-600">-</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-400 rounded-full text-xs font-semibold">
                                <span class="material-symbols-outlined text-[14px]">shopping_cart</span>
                                {{ number_format($admin->orders_handled ?? 0) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-xs text-gray-900 dark:text-white">{{ $admin->created_at->format('d M Y') }}</p>
                            <p class="text-[10px] text-gray-500 dark:text-zinc-500">{{ $admin->created_at->diffForHumans() }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('superadmin.admins.show', $admin) }}" 
                                   class="p-2 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-500/10 rounded-lg transition-colors"
                                   title="Detail">
                                    <span class="material-symbols-outlined text-[18px]">visibility</span>
                                </a>
                                <a href="{{ route('superadmin.admins.edit', $admin) }}" 
                                   class="p-2 text-orange-600 dark:text-orange-400 hover:bg-orange-50 dark:hover:bg-orange-500/10 rounded-lg transition-colors"
                                   title="Edit">
                                    <span class="material-symbols-outlined text-[18px]">edit</span>
                                </a>
                                <button onclick="confirmDelete({{ $admin->id }})" 
                                        class="p-2 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 rounded-lg transition-colors"
                                        title="Hapus">
                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                </button>
                                <form id="delete-form-{{ $admin->id }}" 
                                      action="{{ route('superadmin.admins.destroy', $admin) }}" 
                                      method="POST" 
                                      class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <span class="material-symbols-outlined text-gray-300 dark:text-zinc-700 text-5xl">group</span>
                            <p class="text-sm text-gray-500 dark:text-zinc-500 mt-2">Tidak ada admin ditemukan</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($admins->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 dark:border-zinc-800">
            {{ $admins->links() }}
        </div>
        @endif
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