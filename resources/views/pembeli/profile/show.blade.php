@extends('layouts.app')

@section('title', 'Profil Saya - E-Commerce TSA')

@section('content')
<div class="max-w-6xl mx-auto p-4 md:p-6 space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
                Profil Saya
            </h1>
            <p class="text-gray-600 dark:text-zinc-400 mt-1">
                Kelola informasi pribadi dan alamat pengiriman Anda
            </p>
        </div>

        <a href="{{ route('pembeli.profile.edit') }}"
           class="inline-flex items-center px-5 py-2.5 bg-primary hover:bg-primary-dark text-white rounded-lg font-medium transition shadow-sm">
            <span class="material-symbols-outlined mr-2 text-xl">edit</span>
            Edit Profil
        </a>
    </div>

    <div class="grid md:grid-cols-3 gap-6">
        {{-- Left: Profile Card + Menu --}}
        <div class="md:col-span-1 space-y-6">
            {{-- Profile Card --}}
            <div class="bg-white dark:bg-zinc-900 rounded-xl shadow p-6 text-center">
                <div class="relative inline-block">
                    <img src="{{ $user->avatar_url ?? asset('images/default-avatar.png') }}"
                         alt="{{ $user->name }}"
                         class="w-32 h-32 rounded-full object-cover border-4 border-white dark:border-zinc-800 shadow-lg mx-auto">

                    @if($user->is_verified)
                    <span class="absolute bottom-2 right-1 bg-green-500 text-white text-xs px-2 py-1 rounded-full border-2 border-white">
                        Verified
                    </span>
                    @endif
                </div>

                <h2 class="mt-4 text-xl font-bold text-gray-900 dark:text-white">
                    {{ $user->name }}
                </h2>
                <p class="text-gray-600 dark:text-zinc-400 text-sm mt-1">
                    {{ $user->email }}
                </p>
                <p class="text-sm text-gray-500 dark:text-zinc-500 mt-1">
                    Bergabung sejak {{ $user->created_at->format('d M Y') }}
                </p>

                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-zinc-700">
                    <a href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                       class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 font-medium flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined">logout</span>
                        Keluar
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                        @csrf
                    </form>
                </div>
            </div>

            {{-- Navigation Menu --}}
            <div class="bg-white dark:bg-zinc-900 rounded-xl shadow p-4 hidden md:block">
                <nav class="space-y-1">
                    <a href="{{ route('pembeli.profile.show') }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-lg bg-primary/10 text-primary font-medium">
                        <span class="material-symbols-outlined">person</span>
                        Profil
                    </a>

                    <a href="{{ route('pembeli.profile.address') }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 dark:text-zinc-300 hover:bg-gray-100 dark:hover:bg-zinc-800 transition">
                        <span class="material-symbols-outlined">location_on</span>
                        Alamat Pengiriman
                    </a>

                    <a href="{{ route('pembeli.pesanan.index') }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 dark:text-zinc-300 hover:bg-gray-100 dark:hover:bg-zinc-800 transition">
                        <span class="material-symbols-outlined">receipt_long</span>
                        Riwayat Pesanan
                    </a>

                    <a href="{{ route('pembeli.profile.change-password') }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 dark:text-zinc-300 hover:bg-gray-100 dark:hover:bg-zinc-800 transition">
                        <span class="material-symbols-outlined">lock_reset</span>
                        Ganti Password
                    </a>
                </nav>
            </div>
        </div>

        {{-- Right: Main Content --}}
        <div class="md:col-span-2 space-y-6">
            {{-- Personal Information --}}
            <div class="bg-white dark:bg-zinc-900 rounded-xl shadow p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6 flex items-center gap-3">
                    <span class="material-symbols-outlined text-primary">person</span>
                    Informasi Pribadi
                </h2>

                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-sm">
                    <div>
                        <dt class="text-gray-600 dark:text-zinc-400">Nama Lengkap</dt>
                        <dd class="mt-1 font-medium text-gray-900 dark:text-white">{{ $user->name ?? '-' }}</dd>
                    </div>

                    <div>
                        <dt class="text-gray-600 dark:text-zinc-400">Email</dt>
                        <dd class="mt-1 font-medium text-gray-900 dark:text-white">{{ $user->email }}</dd>
                    </div>

                    <div>
                        <dt class="text-gray-600 dark:text-zinc-400">Nomor HP</dt>
                        <dd class="mt-1 font-medium text-gray-900 dark:text-white">
                            {{ $user->phone ?? '<span class="text-gray-500">Belum diisi</span>' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-gray-600 dark:text-zinc-400">Tanggal Lahir</dt>
                        <dd class="mt-1 font-medium text-gray-900 dark:text-white">
                            {{ $user->birth_date?->format('d F Y') ?? '<span class="text-gray-500">Belum diisi</span>' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-gray-600 dark:text-zinc-400">Jenis Kelamin</dt>
                        <dd class="mt-1 font-medium text-gray-900 dark:text-white">
                            {{ $user->gender ?? '<span class="text-gray-500">Belum diisi</span>' }}
                        </dd>
                    </div>
                </dl>
            </div>

            {{-- Quick Stats --}}
            <div class="grid sm:grid-cols-3 gap-4">
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-950 dark:to-blue-900 p-6 rounded-xl shadow text-center">
                    <p class="text-3xl font-bold text-blue-700 dark:text-blue-300">
                        {{ $user->orders_count ?? 0 }}
                    </p>
                    <p class="text-sm text-blue-800 dark:text-blue-400 mt-1">Pesanan</p>
                </div>

                <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-950 dark:to-green-900 p-6 rounded-xl shadow text-center">
                    <p class="text-3xl font-bold text-green-700 dark:text-green-300">
                        {{ $user->completed_orders ?? 0 }}
                    </p>
                    <p class="text-sm text-green-800 dark:text-green-400 mt-1">Selesai</p>
                </div>

                <div class="bg-gradient-to-br from-amber-50 to-amber-100 dark:from-amber-950 dark:to-amber-900 p-6 rounded-xl shadow text-center">
                    <p class="text-3xl font-bold text-amber-700 dark:text-amber-300">
                        Rp {{ number_format($user->total_spent ?? 0, 0, ',', '.') }}
                    </p>
                    <p class="text-sm text-amber-800 dark:text-amber-400 mt-1">Total Belanja</p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Mobile Bottom Navigation (opsional) --}}
<div class="fixed bottom-0 left-0 right-0 bg-white dark:bg-zinc-900 border-t border-gray-200 dark:border-zinc-700 md:hidden z-50">
    <div class="max-w-6xl mx-auto px-4">
        <div class="flex justify-around py-3">
            <a href="{{ route('pembeli.profile.show') }}" class="text-primary flex flex-col items-center">
                <span class="material-symbols-outlined text-2xl">person</span>
                <span class="text-xs mt-1">Profil</span>
            </a>
            <a href="{{ route('pembeli.profile.address') }}" class="text-gray-600 dark:text-zinc-400 flex flex-col items-center">
                <span class="material-symbols-outlined text-2xl">location_on</span>
                <span class="text-xs mt-1">Alamat</span>
            </a>
            <a href="{{ route('pembeli.pesanan.index') }}" class="text-gray-600 dark:text-zinc-400 flex flex-col items-center">
                <span class="material-symbols-outlined text-2xl">receipt_long</span>
                <span class="text-xs mt-1">Pesanan</span>
            </a>
        </div>
    </div>
</div>
@endsection