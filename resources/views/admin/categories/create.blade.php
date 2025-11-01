{{-- resources/views/admin/categories/create.blade.php --}}
@extends('layouts.admin')
@section('title', 'Tambah Kategori')
@section('content')
<h1 class="text-2xl font-bold mb-6">Tambah Kategori Baru</h1>
@include('admin.categories._form', [
    'action' => route('admin.categories.store'),
    'buttonText' => 'Simpan Kategori'
])
@endsection