{{-- resources/views/pembeli/alamat/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Daftar Alamat')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Daftar Alamat</h1>
        <a href="{{ route('pembeli.alamat.create') }}"
           class="bg-gradient-to-r from-soft-green to-primary text-white px-5 py-2.5 rounded-lg font-medium hover:shadow-lg transition-shadow flex items-center gap-2">
            <span class="material-symbols-outlined text-lg">add_location_alt</span>
            Tambah Alamat
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 px-4 py-3 rounded-lg mb-6 flex items-center gap-2">
            <span class="material-symbols-outlined text-lg">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    @if($addresses->isEmpty())
        <div class="bg-white dark:bg-zinc-800 p-10 rounded-xl shadow-sm text-center border border-gray-200 dark:border-zinc-700">
            <span class="material-symbols-outlined text-7xl text-gray-300 dark:text-zinc-600 mb-4">location_off</span>
            <p class="text-gray-600 dark:text-zinc-400 text-lg">Belum ada alamat tersimpan.</p>
            <a href="{{ route('pembeli.alamat.create') }}" class="inline-flex items-center gap-2 mt-4 text-soft-green hover:underline">
                <span class="material-symbols-outlined text-lg">add</span>
                Tambah alamat pertama Anda
            </a>
        </div>
    @else
        <div class="space-y-5">
            @foreach($addresses as $alamat)
                <div class="bg-white dark:bg-zinc-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700 
                            {{ $alamat->is_default ? 'ring-2 ring-soft-green shadow-lg' : '' }} transition-all">
                    <div class="flex justify-between items-start gap-4">
                        <!-- Informasi Alamat -->
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $alamat->label }}</h3>
                                @if($alamat->is_default)
                                    <span class="bg-gradient-to-r from-soft-green to-primary text-white text-xs font-bold px-3 py-1 rounded-full flex items-center gap-1">
                                        <span class="material-symbols-outlined text-sm">star</span>
                                        Utama
                                    </span>
                                @endif
                            </div>

                            <p class="text-gray-700 dark:text-zinc-300 flex items-center gap-2">
                                <span class="material-symbols-outlined text-lg text-gray-500">person</span>
                                <strong>{{ $alamat->recipient_name }}</strong>
                                <span class="text-gray-500">({{ $alamat->recipient_phone }})</span>
                            </p>

                            <p class="text-sm text-gray-600 dark:text-zinc-400 mt-3 leading-relaxed flex items-start gap-2">
                                <span class="material-symbols-outlined text-lg text-gray-500 mt-0.5">location_on</span>
                                <span>
                                    {{ $alamat->full_address }},<br>
                                    <strong>{{ $alamat->city_type }} {{ $alamat->city_name }}</strong>, 
                                    {{ $alamat->province_name }}
                                    @if($alamat->postal_code)
                                        <span class="text-gray-500">• {{ $alamat->postal_code }}</span>
                                    @endif
                                </span>
                            </p>
                        </div>

                        <!-- Aksi -->
                        <div class="flex items-center gap-3 text-sm">
                            <a href="{{ route('pembeli.alamat.edit', $alamat) }}"
                               class="flex items-center gap-1.5 text-blue-600 hover:text-blue-700 font-medium transition-colors">
                                <span class="material-symbols-outlined text-lg">edit</span>
                                Edit
                            </a>

                            <form action="{{ route('pembeli.alamat.destroy', $alamat) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        onclick="return confirm('Yakin ingin menghapus alamat ini?')"
                                        class="flex items-center gap-1.5 text-red-600 hover:text-red-700 font-medium transition-colors">
                                    <span class="material-symbols-outlined text-lg">delete</span>
                                    Hapus
                                </button>
                            </form>

                            @if(!$alamat->is_default)
                                <form action="{{ route('pembeli.alamat.default', $alamat) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="flex items-center gap-1.5 text-soft-green hover:text-green-700 font-medium transition-colors">
                                        <span class="material-symbols-outlined text-lg">star_border</span>
                                        Jadikan Utama
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection