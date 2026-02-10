<!-- resources/views/superadmin/admins/create.blade.php -->
@extends('layouts.superadmin')

@section('page-title', 'Tambah Admin Baru')
@section('page-subtitle', 'Buat akun administrator baru')

@section('content')
<div class="max-w-3xl">
    
    <!-- Back Button -->
    <a href="{{ route('superadmin.admins.index') }}" 
       class="inline-flex items-center gap-2 text-sm text-gray-600 dark:text-zinc-400 hover:text-soft-green dark:hover:text-soft-green mb-6 transition-colors">
        <span class="material-symbols-outlined text-[20px]">arrow_back</span>
        Kembali ke Daftar Admin
    </a>
    
    <!-- Form Card -->
    <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-soft border border-gray-100 dark:border-zinc-800 p-6">
        <form method="POST" action="{{ route('superadmin.admins.store') }}" class="space-y-6">
            @csrf
            
            <!-- Name -->
            <div>
                <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="name" 
                       value="{{ old('name') }}" 
                       required
                       class="w-full px-4 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-lg text-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-zinc-500 focus:ring-2 focus:ring-soft-green focus:border-transparent"
                       placeholder="Masukkan nama lengkap">
                @error('name')
                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Email -->
            <div>
                <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                    Email <span class="text-red-500">*</span>
                </label>
                <input type="email" 
                       name="email" 
                       value="{{ old('email') }}" 
                       required
                       class="w-full px-4 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-lg text-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-zinc-500 focus:ring-2 focus:ring-soft-green focus:border-transparent"
                       placeholder="admin@example.com">
                @error('email')
                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Password -->
            <div>
                <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                    Password <span class="text-red-500">*</span>
                </label>
                <input type="password" 
                       name="password" 
                       required
                       class="w-full px-4 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-lg text-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-zinc-500 focus:ring-2 focus:ring-soft-green focus:border-transparent"
                       placeholder="Minimal 8 karakter">
                @error('password')
                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Password Confirmation -->
            <div>
                <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                    Konfirmasi Password <span class="text-red-500">*</span>
                </label>
                <input type="password" 
                       name="password_confirmation" 
                       required
                       class="w-full px-4 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-lg text-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-zinc-500 focus:ring-2 focus:ring-soft-green focus:border-transparent"
                       placeholder="Ulangi password">
            </div>
            
            <!-- Phone -->
            <div>
                <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                    Nomor Telepon
                </label>
                <input type="text" 
                       name="phone" 
                       value="{{ old('phone') }}"
                       class="w-full px-4 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-lg text-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-zinc-500 focus:ring-2 focus:ring-soft-green focus:border-transparent"
                       placeholder="08xxxxxxxxxx">
                @error('phone')
                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Address -->
            <div>
                <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                    Alamat
                </label>
                <textarea name="address" 
                          rows="3"
                          class="w-full px-4 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-lg text-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-zinc-500 focus:ring-2 focus:ring-soft-green focus:border-transparent"
                          placeholder="Masukkan alamat lengkap">{{ old('address') }}</textarea>
                @error('address')
                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Submit Buttons -->
            <div class="flex items-center gap-3 pt-4 border-t border-gray-100 dark:border-zinc-800">
                <button type="submit" 
                        class="flex-1 sm:flex-none px-6 py-2.5 bg-gradient-to-r from-soft-green to-primary hover:from-primary hover:to-soft-green text-white rounded-lg font-medium text-sm shadow-lg shadow-soft-green/30 transition-all">
                    Simpan Admin
                </button>
                <a href="{{ route('superadmin.admins.index') }}" 
                   class="flex-1 sm:flex-none px-6 py-2.5 bg-gray-200 dark:bg-zinc-700 hover:bg-gray-300 dark:hover:bg-zinc-600 text-gray-700 dark:text-zinc-300 rounded-lg font-medium text-sm text-center transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
    
</div>
@endsection