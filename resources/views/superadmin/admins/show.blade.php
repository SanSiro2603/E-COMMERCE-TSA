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
                <button onclick="confirmDelete({{ $admin->id }}, '{{ addslashes($admin->name) }}')" 
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
            
            <!-- Tambahan Action Khusus Keamanan Admin -->
            <div class="flex md:flex-col gap-2 border-t md:border-t-0 md:border-l border-gray-100 dark:border-zinc-800 pt-4 md:pt-0 md:pl-6">
                <!-- Reset 2FA -->
                <form id="reset-2fa-form-{{ $admin->id }}" action="{{ route('superadmin.admins.reset-2fa', $admin) }}" method="POST" class="inline-block flex-1 md:flex-none">
                    @csrf
                    @method('PATCH')
                    <button type="button" onclick="confirmReset2FA({{ $admin->id }}, '{{ addslashes($admin->name) }}')" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-500/20 rounded-lg text-sm font-medium transition-colors">
                        <span class="material-symbols-outlined text-[18px]">key</span>
                        Reset 2FA
                    </button>
                </form>

                <!-- Reset Password Trigger -->
                <button onclick="document.getElementById('reset-password-modal').classList.remove('hidden')"
                        class="flex-1 md:flex-none inline-flex items-center justify-center gap-2 px-4 py-2 bg-purple-50 dark:bg-purple-500/10 text-purple-600 dark:text-purple-400 hover:bg-purple-100 dark:hover:bg-purple-500/20 rounded-lg text-sm font-medium transition-colors">
                    <span class="material-symbols-outlined text-[18px]">lock_reset</span>
                    Reset Password
                </button>
            </div>
            
        </div>
    </div>
    
    <!-- Modal Reset Password -->
    <div id="reset-password-modal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 flex items-center justify-center backdrop-blur-sm">
        <div class="bg-white dark:bg-zinc-900 rounded-xl p-6 shadow-xl w-full max-w-md mx-4">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Reset Password Admin</h3>
            <form action="{{ route('superadmin.admins.reset-password', $admin) }}" method="POST">
                @csrf
                @method('PATCH')
                
                <div class="mb-4">
                    <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Password Baru</label>
                    <input type="password" name="password" required
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-zinc-700 dark:border-zinc-600 dark:text-white">
                </div>
                
                <div class="mb-4">
                    <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" required
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-zinc-700 dark:border-zinc-600 dark:text-white">
                </div>

                <div class="flex items-center gap-3 justify-end mt-6">
                    <button type="button" onclick="document.getElementById('reset-password-modal').classList.add('hidden')"
                            class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-zinc-700 dark:text-gray-300 dark:border-zinc-500 dark:hover:text-white dark:hover:bg-zinc-600 dark:focus:ring-zinc-600">
                        Batal
                    </button>
                    <button type="submit"
                            class="text-white bg-primary hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:hover:bg-green-600">
                        Simpan Password Baru
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    
</div>
@endsection

@push('scripts')
<script>
function confirmDelete(adminId, adminName) {
    Swal.fire({
        title: `Hapus Admin ${adminName}?`,
        text: 'Data admin akan dihapus permanen!',
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

function confirmReset2FA(adminId, adminName) {
    Swal.fire({
        title: `Reset 2FA Admin ${adminName}?`,
        text: 'Apakah Anda yakin ingin mereset 2FA admin ini?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#2563EB',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Ya, Reset 2FA!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('reset-2fa-form-' + adminId).submit();
        }
    });
}
</script>
@endpush
