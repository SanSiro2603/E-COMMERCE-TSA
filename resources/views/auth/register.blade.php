<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Register - E-Commerce TSA</title>
  <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>

  <script>
    tailwind.config = {
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
    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, #ffffff 0%, #f0eded 100%);
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 20px;
    }

    .register-card {
      background: #ffffff;
      border-radius: 16px;
      padding: 36px 32px;
      width: 100%;
      max-width: 360px;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
      animation: fadeInUp 0.4s ease;
    }

    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

   .google-login {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      border: 1px solid #ddd;
      border-radius: 7px;
      background: white;
      padding: 6px 12px;
      transition: all 0.3s;
      font-size: 13px;
      width: 100%;
      box-sizing: border-box;
    }

    .google-login:hover {
      background: #f9f9f9;
    }

    .register-btn {
      height: 36px;
      font-size: 13px;
      font-weight: 600;
      border-radius: 10px;
    }
  </style>
</head>

<body>
  <div class="register-card">
    <h2 class="text-lg font-bold text-gray-900 mb-1 text-center">Create Account</h2>
    <p class="text-gray-600 text-xs mb-5 text-center">Register to get started</p>

    <!-- Validasi error -->
    @if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-3 py-2 rounded mb-4 text-xs">
      {{ $errors->first() }}
    </div>
    @endif

    <form method="POST" action="{{ route('register') }}" class="space-y-3">
      @csrf

      <div>
        <label for="name" class="block text-xs font-semibold text-gray-900 mb-1">Nama Lengkap</label>
        <div class="relative">
          <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm">person</span>
          <input type="text" id="name" name="name" required value="{{ old('name') }}"
            class="w-full h-9 pl-10 pr-3 rounded-lg border border-gray-300 focus:border-primary focus:ring-0 text-gray-900 placeholder-gray-400 text-xs"
            placeholder="Masukkan nama anda" />
        </div>
      </div>

      <div>
        <label for="email" class="block text-xs font-semibold text-gray-900 mb-1">Alamat Email</label>
        <div class="relative">
          <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm">mail</span>
          <input type="email" id="email" name="email" required value="{{ old('email') }}"
            class="w-full h-9 pl-10 pr-3 rounded-lg border border-gray-300 focus:border-primary focus:ring-0 text-gray-900 placeholder-gray-400 text-xs"
            placeholder="Masukkan email anda" />
        </div>
      </div>

      <div>
  <label for="phone" class="block text-xs font-semibold text-gray-900 mb-1">Nomor Telepon</label>
  <div class="relative">
    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm">call</span>
    <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
      class="w-full h-9 pl-10 pr-3 rounded-lg border border-gray-300 focus:border-primary focus:ring-0 text-gray-900 placeholder-gray-400 text-xs"
      placeholder="Masukkan nomor telepon " />
  </div>
</div>

      <div>
        <label for="password" class="block text-xs font-semibold text-gray-900 mb-1">Password</label>
        <div class="relative">
          <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm">lock</span>
          <input type="password" id="password" name="password" required
            class="w-full h-9 pl-10 pr-9 rounded-lg border border-gray-300 focus:border-primary focus:ring-0 text-gray-900 placeholder-gray-400 text-xs"
            placeholder="Buat Password 8 Karakter" />
          <button type="button" onclick="togglePassword(event, 'password')"
            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 text-sm">
            <span class="material-symbols-outlined text-sm">visibility</span>
          </button>
        </div>
      </div>

      <div>
        <label for="password_confirmation" class="block text-xs font-semibold text-gray-900 mb-1">Konfirmasi Password</label>
        <div class="relative">
          <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm">lock</span>
          <input type="password" id="password_confirmation" name="password_confirmation" required
            class="w-full h-9 pl-10 pr-9 rounded-lg border border-gray-300 focus:border-primary focus:ring-0 text-gray-900 placeholder-gray-400 text-xs"
            placeholder="Konfirmasi password" />
          <button type="button" onclick="togglePassword(event, 'password_confirmation')"
            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 text-sm">
            <span class="material-symbols-outlined text-sm">visibility</span>
          </button>
        </div>
      </div>

      <button type="submit"
        class="register-btn w-full bg-gradient-to-r from-green-700 to-green-500 text-white font-bold shadow hover:scale-[1.02] transition-all duration-200 flex items-center justify-center gap-1">
        <span>Sign Up</span>
        <span class="material-symbols-outlined text-xs">arrow_forward</span>
      </button>

      <!-- Google Login -->
       <div class="mb-6">
        <a href="{{ route('google.redirect') }}" class="google-login text-gray-700 font-medium">
          <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google" class="w-4 h-4" />
          <span>Continue with Google</span>
        </a>
      </div>

      <div class="text-center mt-5 text-[11px]">
        <p class="text-gray-700">
          Already have an account?
          <a href="{{ route('login') }}" class="text-green-700 font-bold hover:underline">Sign In</a>
        </p>
      </div>
    </form>
  </div>

  <script>
    function togglePassword(event, id) {
      const input = document.getElementById(id);
      const icon = event.currentTarget.querySelector('.material-symbols-outlined');
      if (input.type === 'password') {
        input.type = 'text';
        icon.textContent = 'visibility_off';
      } else {
        input.type = 'password';
        icon.textContent = 'visibility';
      }
    }
  </script>
</body>
</html>
