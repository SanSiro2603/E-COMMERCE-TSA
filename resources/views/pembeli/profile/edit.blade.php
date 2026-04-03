@extends('layouts.app')

@section('title', 'Edit Profil - E-Commerce TSA')

@section('content')
<div class="max-w-6xl mx-auto p-4 md:p-6 space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
                Edit Profil
            </h1>
            <p class="text-gray-600 dark:text-zinc-400 mt-1">
                Ubah informasi akun Anda
            </p>
        </div>

        <a href="{{ route('pembeli.profile.show') }}"
            class="px-4 py-2 bg-gray-200 dark:bg-zinc-800 rounded-lg text-sm">
            Kembali
        </a>
    </div>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    {{-- Form --}}
    <div class="bg-white dark:bg-zinc-900 rounded-xl shadow p-6">
        <form action="{{ route('pembeli.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- 🔥 AVATAR (PINDAH KE SINI) --}}
            <div class="mb-6 text-center">
                <img id="previewImage"
                    src="{{ $user->profile_photo ? asset('storage/'.$user->profile_photo) : asset('images/default-avatar.png') }}"
                    class="w-32 h-32 rounded-full object-cover mx-auto mb-3">

                <input type="file" name="profile_photo" id="profile_photo"
                    class="block mx-auto text-sm">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Nama --}}
                <div>
                    <label class="block text-sm font-medium">Nama Lengkap</label>
                    <input type="text" name="name"
                        value="{{ old('name', $user->name) }}"
                        class="mt-2 w-full px-4 py-2 border rounded-lg">

                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-medium">Email</label>
                    <input type="email"
                        value="{{ $user->email }}"
                        readonly
                        class="mt-2 w-full px-4 py-2 border rounded-lg bg-gray-100">
                </div>

                {{-- Phone --}}
                <div>
                    <label class="block text-sm font-medium">Nomor HP</label>
                    <input type="text" name="phone"
                        value="{{ old('phone', $user->phone) }}"
                        class="mt-2 w-full px-4 py-2 border rounded-lg">
                </div>

                {{-- Gender --}}
                <div>
                    <label class="block text-sm font-medium">Jenis Kelamin</label>
                    <select name="gender"
                        class="mt-2 w-full px-4 py-2 border rounded-lg">
                        <option value="">Pilih</option>
                        <option value="Laki-laki" {{ $user->gender == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ $user->gender == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>

                {{-- Tanggal Lahir --}}
                <div>
                    <label class="block text-sm font-medium">Tanggal Lahir</label>
                    <input type="date" name="birth_date"
                        value="{{ $user->birth_date }}"
                        class="mt-2 w-full px-4 py-2 border rounded-lg">
                </div>

            </div>

            {{-- Tombol --}}
            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('pembeli.profile.show') }}"
                    class="px-5 py-2 bg-gray-300 rounded-lg">
                    Batal
                </a>

                <button type="submit"
                    class="px-5 py-2 bg-primary text-white rounded-lg">
                    Simpan Perubahan
                </button>
            </div>

        </form>
    </div>
</div>

{{-- 🔥 PREVIEW JS --}}
<script>
document.getElementById('profile_photo').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const reader = new FileReader();

    reader.onload = function(e) {
        document.getElementById('previewImage').src = e.target.result;
    }

    if(file){
        reader.readAsDataURL(file);
    }
});
</script>

@endsection