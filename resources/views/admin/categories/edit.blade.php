{{-- resources/views/admin/categories/edit.blade.php --}}
@extends('layouts.admin')
@section('title', 'Edit Kategori')
@section('content')
<h1 class="text-2xl font-bold mb-6">Edit Kategori</h1>
@include('admin.categories._form', [
    'action' => route('admin.categories.update', $category),
    'category' => $category,
    'buttonText' => 'Update Kategori'
])
@endsection