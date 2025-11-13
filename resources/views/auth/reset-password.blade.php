<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Reset Password - Lembah Hijau</title>
  <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />

  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: { primary: "#2E7D32", secondary: "#A5D6A7" },
          fontFamily: { inter: ["Inter", "sans-serif"] },
        },
      },
    };
  </script>

  <style>
    html, body { height: 100%; margin: 0; overflow: hidden; }
    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, #ffffff 0%, #f0eded 100%);
      display: flex; justify-content: center; align-items: center;
    }
    .card {
      background: #fff; border-radius: 16px; padding: 56px 40px;
      width: 100%; max-width: 340px; box-shadow: 0 8px 25px rgba(0,0,0,0.08);
      animation: fadeInUp 0.4s ease;
    }
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .btn { height: 32px; font-size: 13px; font-weight: 600; border-radius: 7px; }
  </style>
</head>
<body>
  <div class="card text-center">
    <div class="flex justify-center mb-2 mt-2">
      <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-12 h-12 rounded-full object-cover" />
    </div>

    <h2 class="text-lg font-bold text-gray-900 mb-1">Buat Password Baru</h2>
    <p class="text-gray-600 text-xs mb-6">Masukkan kata sandi baru untuk akun Anda</p>

    <form method="POST" action="{{ route('password.update') }}" class="space-y-3">
      @csrf
      <input type="hidden" name="token" value="{{ $request->route('token') }}">

      <div>
        <label class="block text-xs font-semibold text-gray-900 mb-1 text-left">Email</label>
        <div class="relative">
          <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm">mail</span>
          <input type="email" name="email" value="{{ old('email', $request->email) }}" required
            class="w-full h-9 pl-10 pr-3 rounded-lg border @error('email') border-red-500 @else border-gray-300 @enderror
                   focus:border-primary focus:ring-0 text-xs" placeholder="Email Anda" />
        </div>
        @error('email') <p class="text-red-500 text-[11px] mt-1 text-left">{{ $message }}</p> @enderror
      </div>

      <div>
        <label class="block text-xs font-semibold text-gray-900 mb-1 text-left">Password Baru</label>
        <div class="relative">
          <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm">lock</span>
          <input type="password" name="password" required
            class="w-full h-9 pl-10 pr-3 rounded-lg border @error('password') border-red-500 @else border-gray-300 @enderror
                   focus:border-primary focus:ring-0 text-xs" placeholder="Min. 6 karakter" />
        </div>
        @error('password') <p class="text-red-500 text-[11px] mt-1 text-left">{{ $message }}</p> @enderror
      </div>

      <div>
        <label class="block text-xs font-semibold text-gray-900 mb-1 text-left">Konfirmasi Password</label>
        <div class="relative">
          <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm">lock</span>
          <input type="password" name="password_confirmation" required
            class="w-full h-9 pl-10 pr-3 rounded-lg border border-gray-300 focus:border-primary focus:ring-0 text-xs"
            placeholder="Ulangi password" />
        </div>
      </div>

      <button type="submit"
        class="btn w-full bg-gradient-to-r from-green-700 to-green-500 text-white font-bold shadow hover:scale-[1.02] transition-all duration-200 mt-6">
        Simpan Password Baru
      </button>

      <div class="text-[11px] mt-4">
        <a href="{{ route('login') }}" class="text-primary hover:underline font-medium">Kembali ke Login</a>
      </div>
    </form>

    @if (session('status'))
      <div class="mt-4 p-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-xs flex items-center gap-2">
        <span class="material-symbols-outlined text-sm">check_circle</span>
        <span>{{ session('status') }}</span>
      </div>
    @endif
  </div>
</body>
</html>