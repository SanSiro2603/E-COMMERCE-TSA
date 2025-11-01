<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login - Lembah Hijau</title>
  <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />

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
      background: linear-gradient(135deg, #C8E6C9 0%, #A5D6A7 100%);
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .container-login {
      display: flex;
      align-items: stretch;
      justify-content: center;
      width: 780px;
      height: 460px;
      background: rgba(255, 255, 255, 0.15);
      border-radius: 24px;
      backdrop-filter: blur(15px);
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
      overflow: hidden;
    }

    .image-side {
      flex: 1;
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
    }

    .form-side {
      flex: 1;
      padding: 40px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      background: rgba(255, 255, 255, 0.35);
      backdrop-filter: blur(25px);
    }

    /* Tombol Google kecil bulat */
    .google-btn {
      display: flex;
      justify-content: center;
      align-items: center;
      border: 1px solid #ccc;
      border-radius: 9999px;
      width: 40px;
      height: 40px;
      transition: background 0.3s;
      background: #fff;
    }

    .google-btn:hover {
      background: #f3f4f6;
    }

    @media (max-width: 1024px) {
      .container-login {
        flex-direction: column;
        width: 90%;
        height: auto;
      }

      .form-side {
        padding: 30px;
      }

      .image-side {
        height: 220px;
      }
    }
  </style>
</head>

<body>
  <div class="container-login">
    <!-- Gambar samping kiri -->
    <div class="image-side" style="background-image: url('{{ asset('images/login.png') }}');"></div>

    <!-- Form login -->
    <div class="form-side">
      <h2 class="text-2xl font-bold text-gray-900 mb-1">Welcome Back</h2>
      <p class="text-gray-600 text-sm mb-6">Login to your account</p>

      @if ($errors->any())
      <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-3 text-xs">
        {{ $errors->first() }}
      </div>
      @endif

      <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div>
          <label for="email" class="block text-xs font-semibold text-gray-900 mb-1">Email Address</label>
          <div class="relative">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm">mail</span>
            <input type="email" id="email" name="email" required
              class="w-full h-10 pl-10 pr-3 rounded-xl border border-gray-300 focus:border-primary focus:ring-0 text-gray-900 placeholder-gray-400 text-sm"
              placeholder="Enter Your Email" />
          </div>
        </div>

        <div>
          <label for="password" class="block text-xs font-semibold text-gray-900 mb-1">Password</label>
          <div class="relative">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm">lock</span>
            <input type="password" id="password" name="password" required
              class="w-full h-10 pl-10 pr-10 rounded-xl border border-gray-300 focus:border-primary focus:ring-0 text-gray-900 placeholder-gray-400 text-sm"
              placeholder="Enter Your Password" />
            <button type="button" onclick="togglePassword(event)"
              class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 text-sm">
              <span class="material-symbols-outlined text-sm">visibility</span>
            </button>
          </div>
        </div>

        <div class="flex items-center justify-between text-xs">
          <a href="#" class="text-primary hover:underline font-semibold">Forgot Password?</a>
        </div>

        <button type="submit"
          class="w-full h-10 rounded-xl bg-gradient-to-r from-green-700 to-green-500 text-white font-bold shadow-md hover:shadow-lg hover:scale-[1.03] transition-all duration-300 flex items-center justify-center gap-1 text-xs">
          <span>Sign In</span>
          <span class="material-symbols-outlined text-xs">arrow_forward</span>
        </button>

        <div class="relative flex items-center my-4">
          <div class="flex-grow border-t border-gray-300"></div>
          <span class="mx-2 text-xs text-gray-500">or sign in with</span>
          <div class="flex-grow border-t border-gray-300"></div>
        </div>

        <!-- Google button kecil -->
        <div class="flex justify-center">
          <a href="{{ route('google.redirect') }}" onclick="openGoogleLogin(event)" class="google-btn">
            <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google" class="w-5 h-5" />
          </a>
        </div>

        <div class="text-center mt-4 text-xs">
          <p class="text-gray-700">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-green-700 font-bold hover:underline">Sign Up</a>
          </p>
        </div>
      </form>
    </div>
  </div>

  <script>
    function togglePassword(event) {
      const input = document.getElementById('password');
      const icon = event.currentTarget.querySelector('.material-symbols-outlined');
      if (input.type === 'password') {
        input.type = 'text';
        icon.textContent = 'visibility_off';
      } else {
        input.type = 'password';
        icon.textContent = 'visibility';
      }
    }
    function openGoogleLogin(event) {
      event.preventDefault();
      const width = 500, height = 600;
      const left = (window.innerWidth - width) / 2;
      const top = (window.innerHeight - height) / 2;
      const popup = window.open(
        "{{ route('google.redirect') }}",
        "GoogleLogin",
        `width=${width},height=${height},top=${top},left=${left},resizable=no,scrollbars=yes,status=no`
      );
      const timer = setInterval(() => {
        if (popup.closed) {
          clearInterval(timer);
          window.location.reload();
        }
      }, 1000);
    }
  </script>
</body>
</html>
