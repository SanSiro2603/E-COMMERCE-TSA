@extends('layouts.app')

@section('title', 'Edit Profil - E-Commerce TSA')

@section('content')
<div class="max-w-3xl mx-auto p-4 md:p-6 space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Profil</h1>
            <p class="text-gray-500 dark:text-zinc-400 mt-1 text-sm">Ubah informasi akun Anda</p>
        </div>
        <a href="{{ route('pembeli.profile.show') }}"
            class="px-4 py-2 bg-gray-100 dark:bg-zinc-700 hover:bg-gray-200 dark:hover:bg-zinc-600 text-gray-700 dark:text-zinc-200 rounded-lg text-sm transition">
            ← Kembali
        </a>
    </div>

    {{-- Form --}}
    <div class="bg-white dark:bg-zinc-800 rounded-xl shadow border border-gray-100 dark:border-zinc-700 p-6">
        <form action="{{ route('pembeli.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Avatar --}}
            <div class="mb-8 flex flex-col items-center gap-3">
                <img id="previewImage"
                    src="{{ $user->profile_photo ? asset('storage/'.$user->profile_photo) : asset('images/default-avatar.png') }}"
                    class="w-28 h-28 rounded-full object-cover border-4 border-primary/30 shadow">
                <label class="cursor-pointer text-sm text-primary hover:underline font-medium">
                    Ganti Foto
                    <input type="file" name="profile_photo" id="profile_photo" class="hidden" accept="image/*">
                </label>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- Nama --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300 mb-1">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-zinc-700 border border-gray-200 dark:border-zinc-600 rounded-lg text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-primary/50 focus:border-primary outline-none transition">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email (readonly) --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300 mb-1">Email</label>
                    <input type="email" value="{{ $user->email }}" readonly
                        class="w-full px-4 py-2.5 bg-gray-100 dark:bg-zinc-700/50 border border-gray-200 dark:border-zinc-600 rounded-lg text-gray-500 dark:text-zinc-400 text-sm cursor-not-allowed">
                </div>

                {{-- No HP --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300 mb-1">Nomor HP</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-zinc-700 border border-gray-200 dark:border-zinc-600 rounded-lg text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-primary/50 focus:border-primary outline-none transition">
                </div>

                {{-- Gender --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300 mb-1">Jenis Kelamin</label>
                    <select name="gender"
                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-zinc-700 border border-gray-200 dark:border-zinc-600 rounded-lg text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-primary/50 focus:border-primary outline-none transition">
                        <option value="">Pilih</option>
                        <option value="Laki-laki" {{ $user->gender == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ $user->gender == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>

                {{-- Tanggal Lahir --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300 mb-1">Tanggal Lahir</label>
                    <input type="date" name="birth_date" value="{{ $user->birth_date }}"
                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-zinc-700 border border-gray-200 dark:border-zinc-600 rounded-lg text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-primary/50 focus:border-primary outline-none transition">
                </div>

            </div>

            {{-- Tombol --}}
            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('pembeli.profile.show') }}"
                    class="px-5 py-2.5 bg-gray-100 dark:bg-zinc-700 hover:bg-gray-200 dark:hover:bg-zinc-600 text-gray-700 dark:text-zinc-200 rounded-lg text-sm transition">
                    Batal
                </a>
                <button type="submit"
                    class="px-5 py-2.5 bg-primary hover:bg-green-500 text-white rounded-lg text-sm font-medium transition shadow-sm">
                    Simpan Perubahan
                </button>
            </div>

        </form>
    </div>
</div>

<script>
document.getElementById('profile_photo').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            document.getElementById('previewImage').src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endsection