@extends('layouts.admin')
@section('title', 'Pengaturan Toko')

@section('content')
    <div class="bg-white dark:bg-zinc-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-zinc-700">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Pengaturan Operasional Toko</h2>

        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf

            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-zinc-700/50 rounded-lg">
                <div>
                    <h3 class="font-semibold text-gray-900 dark:text-white">Fitur Belanja (Checkout & Pembayaran)</h3>
                    <p class="text-sm text-gray-500 dark:text-zinc-400">
                        Jika dimatikan, pelanggan tidak bisa membuat pesanan baru atau melakukan pembayaran.
                    </p>
                </div>

                {{-- TOGGLE SWITCH --}}
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="shopping_enabled" value="1" class="sr-only peer"
                        {{ $shoppingEnabled === '1' ? 'checked' : '' }}>
                    <div
                        class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 dark:peer-focus:ring-green-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-green-600">
                    </div>
                </label>
            </div>

            <div class="mt-6">
                <button type="submit"
                    class="bg-primary text-white px-6 py-2 rounded-lg font-semibold hover:bg-green-600 transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
@endsection
