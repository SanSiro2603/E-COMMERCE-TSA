@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto p-6 bg-white rounded-lg shadow">
    <h1 class="text-xl font-bold mb-4">Ganti Password</h1>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('pembeli.profile.update-password') }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Password Lama --}}
        <div class="mb-4">
            <label class="block mb-1">Password Lama</label>
            <input type="password" name="current_password"
                class="border p-2 w-full rounded">

            @error('current_password')
                <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password Baru --}}
        <div class="mb-4">
            <label class="block mb-1">Password Baru</label>
            <input type="password" name="new_password"
                class="border p-2 w-full rounded">

            @error('new_password')
                <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror
        </div>

        {{-- Konfirmasi Password --}}
        <div class="mb-4">
            <label class="block mb-1">Konfirmasi Password</label>
            <input type="password" name="new_password_confirmation"
                class="border p-2 w-full rounded">
        </div>

        <button type="submit"
            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
            Simpan
        </button>
    </form>
</div>
@endsection