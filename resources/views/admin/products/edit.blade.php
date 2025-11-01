{{-- resources/views/admin/products/edit.blade.php --}}
@extends('layouts.admin')
@section('title', 'Edit Produk')
@section('content')
<h1 class="text-2xl font-bold mb-6">Edit Produk</h1>
@include('admin.products._form', [
    'action' => route('admin.products.update', $product),
    'product' => $product,
    'categories' => $categories,
    'buttonText' => 'Update Produk'
])
@endsection