{{-- resources/views/admin/products/create.blade.php --}}
@extends('layouts.admin')
@section('title', 'Tambah Produk')
@section('content')
<h1 class="text-2xl font-bold mb-6">Tambah Produk Baru</h1>
@include('admin.products._form', [
    'action' => route('admin.products.store'),
    'categories' => $categories,
    'buttonText' => 'Simpan Produk'
])
@endsection