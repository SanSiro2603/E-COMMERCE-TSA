@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto p-6 bg-white rounded-lg shadow">
    <h1 class="text-xl font-bold mb-4">Ganti Password</h1>

    <form method="POST">
        @csrf

        <div class="mb-4">
            <label>Password Lama</label>
            <input type="password" class="border p-2 w-full">
        </div>

        <div class="mb-4">
            <label>Password Baru</label>
            <input type="password" class="border p-2 w-full">
        </div>

        <button class="bg-blue-500 text-white px-4 py-2 rounded">
            Simpan
        </button>
    </form>
</div>
@endsection