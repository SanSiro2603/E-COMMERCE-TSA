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
            class="inline-flex items-center px-5 py-2.5 bg-primary hover:bg-green-500 text-white rounded-lg font-medium transition shadow-sm">
            <span class="material-symbols-outlined mr-2 text-xl">edit</span>
            Edit Profil
        </a>
    </div>

    <div class="grid md:grid-cols-3 gap-6">

        {{-- LEFT: Profile Card + Menu --}}
        <div class="md:col-span-1 space-y-4">

            {{-- Profile Card --}}
            <div class="bg-white dark:bg-zinc-800 rounded-xl shadow p-6 text-center border border-gray-100 dark:border-zinc-700">
                <div class="relative inline-block">
                    <img
                        src="{{ $user->profile_photo ? asset('storage/'.$user->profile_photo) : asset('images/default-avatar.png') }}"
                        class="w-28 h-28 rounded-full object-cover border-4 border-primary/30 shadow mx-auto">
                    @if($user->is_verified)
                        <span class="absolute bottom-1 right-1 bg-green-500 text-white text-xs px-2 py-0.5 rounded-full border-2 border-white dark:border-zinc-800">
                            Verified
                        </span>
                    @endif
                </div>
                <h2 class="mt-4 text-lg font-bold text-gray-900 dark:text-white">{{ $user->name }}</h2>
                <p class="text-gray-500 dark:text-zinc-400 text-sm mt-1">{{ $user->email }}</p>
                <p class="text-xs text-gray-400 dark:text-zinc-500 mt-1">
                    Bergabung sejak {{ $user->created_at->format('d M Y') }}
                </p>
            </div>

            {{-- Navigation Menu --}}
            <div class="bg-white dark:bg-zinc-800 rounded-xl shadow p-4 border border-gray-100 dark:border-zinc-700 hidden md:block">
                <nav class="space-y-1">
                    <a href="{{ route('pembeli.profile.show') }}"
                        class="flex items-center gap-3 px-4 py-2.5 rounded-lg bg-primary/10 text-primary font-semibold">
                        <span class="material-symbols-outlined text-xl">person</span>
                        Profil
                    </a>
                    <a href="{{ route('pembeli.pesanan.index') }}"
                        class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-gray-700 dark:text-zinc-300 hover:bg-gray-100 dark:hover:bg-zinc-700 transition">
                        <span class="material-symbols-outlined text-xl">receipt_long</span>
                        Riwayat Pesanan
                    </a>
                    <a href="{{ route('pembeli.alamat.index') }}"
                        class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-gray-700 dark:text-zinc-300 hover:bg-gray-100 dark:hover:bg-zinc-700 transition">
                        <span class="material-symbols-outlined text-xl">location_on</span>
                        Atur Alamat
                    </a>
                </nav>
            </div>
        </div>

        {{-- RIGHT: Info + Stats --}}
        <div class="md:col-span-2 space-y-6">

            {{-- Personal Info --}}
            <div class="bg-white dark:bg-zinc-800 rounded-xl shadow p-6 border border-gray-100 dark:border-zinc-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-5 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">person</span>
                    Informasi Pribadi
                </h2>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-5 text-sm">
                    <div>
                        <dt class="text-gray-500 dark:text-zinc-400">Nama Lengkap</dt>
                        <dd class="mt-1 font-semibold text-gray-900 dark:text-white">{{ $user->name ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-zinc-400">Email</dt>
                        <dd class="mt-1 font-semibold text-gray-900 dark:text-white">{{ $user->email }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-zinc-400">Nomor HP</dt>
                         <dd class="mt-1 font-semibold text-gray-900 dark:text-white">
                                @if($user->phone)
                                    {{ $user->phone }}
                                @else
                                    <span class="text-gray-400 font-normal">Belum diisi</span>
                                @endif
                            </dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-zinc-400">Tanggal Lahir</dt>
                        <dd class="mt-1 font-semibold text-gray-900 dark:text-white">
                            @if($user->birth_date)
                                {{ \Carbon\Carbon::parse($user->birth_date)->translatedformat('d F Y') }}
                            @else
                                <span class="text-gray-400 font-normal">Belum diisi</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-zinc-400">Jenis Kelamin</dt>
                        <dd class="mt-1 font-semibold text-gray-900 dark:text-white">
                            @if($user->gender)
                                {{ $user->gender }}
                            @else
                                <span class="text-gray-400 font-normal">Belum diisi</span>
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>

            {{-- Stats --}}
            <div class="grid grid-cols-3 gap-4">
                {{-- Pesanan --}}
                <div class="bg-white dark:bg-zinc-800 border border-blue-100 dark:border-blue-900 rounded-xl shadow p-5 text-center">
                    <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/50 rounded-full flex items-center justify-center mx-auto mb-3">
                        <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-xl">shopping_bag</span>
                    </div>
                    <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $user->orders_count ?? 0 }}</p>
                    <p class="text-xs text-gray-500 dark:text-zinc-400 mt-1">Pesanan</p>
                </div>

                {{-- Selesai --}}
                <div class="bg-white dark:bg-zinc-800 border border-green-100 dark:border-green-900 rounded-xl shadow p-5 text-center">
                    <div class="w-10 h-10 bg-green-100 dark:bg-green-900/50 rounded-full flex items-center justify-center mx-auto mb-3">
                        <span class="material-symbols-outlined text-green-600 dark:text-green-400 text-xl">check_circle</span>
                    </div>
                    <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $user->completed_orders ?? 0 }}</p>
                    <p class="text-xs text-gray-500 dark:text-zinc-400 mt-1">Selesai</p>
                </div>

                {{-- Total Belanja --}}
                <div class="bg-white dark:bg-zinc-800 border border-amber-100 dark:border-amber-900 rounded-xl shadow p-5 text-center">
                    <div class="w-10 h-10 bg-amber-100 dark:bg-amber-900/50 rounded-full flex items-center justify-center mx-auto mb-3">
                        <span class="material-symbols-outlined text-amber-600 dark:text-amber-400 text-xl">payments</span>
                    </div>
                    <p class="text-lg font-bold text-amber-600 dark:text-amber-400">
                        Rp {{ number_format($user->total_spent ?? 0, 0, ',', '.') }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-zinc-400 mt-1">Total Belanja</p>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Mobile Bottom Nav --}}
<div class="fixed bottom-0 left-0 right-0 bg-white dark:bg-zinc-900 border-t border-gray-200 dark:border-zinc-700 md:hidden z-50">
    <div class="max-w-6xl mx-auto px-4">
        <div class="flex justify-around py-3">
            <a href="{{ route('pembeli.profile.show') }}" class="text-primary flex flex-col items-center">
                <span class="material-symbols-outlined text-2xl">person</span>
                <span class="text-xs mt-1">Profil</span>
            </a>
            <a href="{{ route('pembeli.pesanan.index') }}" class="text-gray-500 dark:text-zinc-400 flex flex-col items-center">
                <span class="material-symbols-outlined text-2xl">receipt_long</span>
                <span class="text-xs mt-1">Pesanan</span>
            </a>
            <a href="{{ route('pembeli.alamat.index') }}" class="text-gray-500 dark:text-zinc-400 flex flex-col items-center">
                <span class="material-symbols-outlined text-2xl">location_on</span>
                <span class="text-xs mt-1">Alamat</span>
            </a>
        </div>
    </div>
</div>
@endsection