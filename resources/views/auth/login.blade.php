<!DOCTYPE html>
<html class="light" lang="en">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title>Login - Lembah Hijau</title>
  <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>

  <script>
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
          colors: {
            primary: "#2E7D32",
            secondary: "#FDD835",
          },
          fontFamily: {
            display: ["Inter", "sans-serif"],
          },
        },
      },
    };
  </script>

  <style>
    .material-symbols-outlined {
      font-variation-settings: "FILL" 0, "wght" 400, "GRAD" 0, "opsz" 24;
    }

    .glass {
      background: rgba(255, 255, 255, 0.15);
      backdrop-filter: blur(30px);
      -webkit-backdrop-filter: blur(30px);
      border: 1px solid rgba(255, 255, 255, 0.25);
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.25);
    }

    .gradient-mesh {
      background: 
        radial-gradient(at 0% 0%, rgba(46, 125, 50, 0.25) 0px, transparent 50%),
        radial-gradient(at 100% 100%, rgba(253, 216, 53, 0.25) 0px, transparent 50%);
    }

    @keyframes float {
      0%, 100% { transform: translateY(0) rotate(0deg); }
      50% { transform: translateY(-20px) rotate(5deg); }
    }

    .animate-float {
      animation: float 6s ease-in-out infinite;
    }
  </style>
</head>

<body class="font-display gradient-mesh flex items-center justify-center h-screen overflow-hidden relative">

  <!-- Efek glassmorphism di background -->
  <div class="absolute inset-0 glass -z-10"></div>

  <!-- Efek dekoratif -->
  <div class="fixed top-0 right-0 w-80 h-80 bg-primary/30 rounded-full blur-3xl animate-float -z-10"></div>
  <div class="fixed bottom-0 left-0 w-80 h-80 bg-secondary/30 rounded-full blur-3xl animate-float -z-10" style="animation-delay: 3s;"></div>

  <!-- Wrapper utama -->
  <div class="flex flex-col lg:flex-row w-[750px] h-[480px] rounded-3xl overflow-hidden glass shadow-2xl border border-white/20 bg-white/70 dark:bg-gray-900/60">
    
    <!-- Bagian kiri -->
    <div class="hidden lg:block w-1/2 relative">
      <img 
        src="{{ asset('images/login.png') }}" 
        alt="Farm Livestock" 
        class="h-full w-full object-cover brightness-90"
      />
      <div class="absolute inset-0 bg-gradient-to-tr from-green-900/50 to-transparent"></div>
      <div class="absolute bottom-5 left-5 text-white">
        <h2 class="text-xl font-bold drop-shadow-md">Lembah Hijau</h2>
        <p class="text-xs text-gray-100">Sustainable & Natural Livestock</p>
      </div>
    </div>

    <!-- Bagian kanan -->
    <div class="flex flex-col justify-center w-full lg:w-1/2 p-8">
      <div class="flex items-center gap-3 mb-5">
        <div class="size-10 bg-gradient-to-br from-primary to-green-600 rounded-2xl flex items-center justify-center shadow-lg">
          <span class="material-symbols-outlined text-2xl text-white">eco</span>
        </div>
        <div>
          <h1 class="text-lg font-black text-gray-900 dark:text-white">Lembah Hijau</h1>
          <p class="text-xs text-gray-600 dark:text-gray-300">Premium Livestock</p>
        </div>
      </div>

      <h2 class="text-xl font-black text-gray-900 dark:text-white mb-1">Sign In</h2>
      <p class="text-gray-600 dark:text-gray-400 mb-5 text-xs">Welcome back! Please enter your details</p>

          @if ($errors->any())
      <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-3 text-xs">
          {{ $errors->first() }}
      </div>
        @endif

      <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf
        <div>
          <label for="email" class="block text-xs font-semibold text-gray-900 dark:text-white mb-1">
            Email Address
          </label>
          <div class="relative">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm">mail</span>
            <input 
              type="email" 
              id="email" 
              name="email" 
              required
              class="w-full h-10 pl-10 pr-3 rounded-xl bg-white/70 dark:bg-gray-800/60 border border-gray-300 focus:border-primary focus:ring-0 text-gray-900 dark:text-white placeholder-gray-400 text-sm"
              placeholder="Enter Your Email"
            />
          </div>
        </div>

        <div>
          <label for="password" class="block text-xs font-semibold text-gray-900 dark:text-white mb-1">
            Password
          </label>
          <div class="relative">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm">lock</span>
            <input 
              type="password" 
              id="password" 
              name="password" 
              required
              class="w-full h-10 pl-10 pr-10 rounded-xl bg-white/70 dark:bg-gray-800/60 border border-gray-300 focus:border-primary focus:ring-0 text-gray-900 dark:text-white placeholder-gray-400 text-sm"
              placeholder="Enter Your Password"
            />
            <button 
              type="button" 
              onclick="togglePassword(event)"
              class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 text-sm"
            >
              <span class="material-symbols-outlined text-sm">visibility</span>
            </button>
          </div>
        </div>

        <div class="flex items-center justify-between text-xs">
          <a href="#" class="text-primary hover:underline font-semibold">
            Forgot Password?
          </a>
        </div>

                <button 
            type="submit"
            class="w-full h-10 rounded-xl bg-gradient-to-r from-green-700 to-green-500 text-white font-bold shadow-md hover:shadow-lg hover:scale-[1.03] transition-all duration-300 flex items-center justify-center gap-1 text-xs"
          >
            <span>Sign In</span>
            <span class="material-symbols-outlined text-xs">arrow_forward</span>
          </button>

          <!-- Garis pemisah -->
          <div class="relative flex items-center my-5">
            <div class="flex-grow border-t border-gray-300"></div>
            <span class="mx-2 text-xs text-gray-500">OR</span>
            <div class="flex-grow border-t border-gray-300"></div>
          </div>

          <!-- Tombol Login via Google -->
          <a href="{{ route('google.redirect') }}"
            class="w-full flex items-center justify-center gap-2 border border-gray-300 rounded-xl py-3 hover:bg-gray-50 transition">
            <img src="https://www.svgrepo.com/show/355037/google.svg" alt="Google" class="w-5 h-5"/>
            <span class="text-sm font-semibold text-gray-700">Login with Google</span>
          </a>

          <!-- Link ke halaman register -->
          <div class="text-center mt-6 text-xs">
            <p class="text-gray-700 dark:text-gray-300">
              Don’t have an account? 
              <a href="{{ route('register') }}" class="text-green-700 font-bold hover:underline">
                Sign Up
              </a>
            </p>
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
  </script>
</body>

</html>
