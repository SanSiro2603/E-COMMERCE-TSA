<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Lupa Password - Ecommerce TSA</title>
  <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />

  <script>
    tailwind.config =                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           {
      theme: {
        extend: {
          colors: {
            primary: "#2E7D32",
            secondary: "#A5D6A7",
          },
          fontFamily: {
            inter: ["Inter", "sans-serif"],
          },
        },
      },
    };
  </script>

  <style>
    html, body {
      height: 100%;
      margin: 0;
      overflow: hidden;
    }

    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, #ffffff 0%, #f0eded 100%);
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .login-card {
      background: #ffffff;
      border-radius: 16px;
      padding: 56px 40px;
      width: 100%;
      max-width: 340px;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
      animation: fadeInUp 0.4s ease;
    }

    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .login-btn {
      height: 32px;
      font-size: 13px;
      font-weight: 600;
      border-radius: 7px;
    }

    .back-to-login {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
      font-size: 11px;
      color: #666;
      margin-top: 16px;
    }

    .back-to-login:hover {
      color: #2E7D32;
    }
  </style>
</head>

<body>
  <div class="login-card text-center">
    <!-- Logo -->
    <div class="flex justify-center mb-2 mt-2">
      <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-12 h-12 rounded-full object-cover" />
    </div>

    <h2 class="text-lg font-bold text-gray-900 mb-1">Lupa Password?</h2>
    <p class="text-gray-600 text-xs mb-6">
      Masukkan email Anda, kami akan kirim link reset password
    </p>

    <form method="POST" action="{{ route('password.email') }}" class="space-y-3">
      @csrf

      <!-- Email -->
      <div>
        <label for="email" class="block text-xs font-semibold text-gray-900 mb-1 text-left">Alamat Email</label>
        <div class="relative">
          <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm">mail</span>
          <input type="email" id="email" name="email"
            value="{{ old('email') }}" required autofocus
            class="w-full h-9 pl-10 pr-3 rounded-lg border 
              @error('email') border-red-500 @else border-gray-300 @enderror
              focus:border-primary focus:ring-0 text-gray-900 placeholder-gray-400 text-xs"
            placeholder="Masukkan email anda" />
        </div>
        @error('email')
          <p class="text-red-500 text-[11px] mt-1 text-left">{{ $message }}</p>
        @enderror
      </div>

      <!-- Tombol Kirim Link -->
      <button type="submit"
        class="login-btn w-full bg-gradient-to-r from-green-700 to-green-500 text-white font-bold shadow hover:scale-[1.02] transition-all duration-200 flex items-center justify-center gap-1 mt-6">
        <span class="material-symbols-outlined text-sm">send</span>
        <span>Kirim Link Reset</span>
      </button>

      <!-- Kembali ke Login -->
      <div class="back-to-login">
        <span class="material-symbols-outlined text-sm">arrow_back</span>
        <a href="{{ route('login') }}" class="font-medium">Kembali ke Login</a>
      </div>
    </form>

    <!-- Success Message -->
    @if (session('status'))
      <div class="mt-4 p-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-xs flex items-center gap-2">
        <span class="material-symbols-outlined text-sm">check_circle</span>
        <span>{{ session('status') }}</span>
      </div>
    @endif
  </div>
</body>
</html>