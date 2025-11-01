<!DOCTYPE html>
<html class="light" lang="en">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title>Register - Lembah Hijau</title>
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
  </style>
</head>

<body class="font-display gradient-mesh flex items-center justify-center min-h-screen overflow-auto relative p-4">

  <!-- Efek glass -->
  <div class="absolute inset-0 glass -z-10"></div>

  <!-- Background dekoratif -->
  <div class="fixed top-0 right-0 w-80 h-80 bg-primary/30 rounded-full blur-3xl animate-float -z-10"></div>
  <div class="fixed bottom-0 left-0 w-80 h-80 bg-secondary/30 rounded-full blur-3xl animate-float -z-10" style="animation-delay: 3s;"></div>

  <div class="flex flex-col md:flex-row w-full max-w-5xl rounded-3xl overflow-hidden glass shadow-2xl border border-white/20 bg-white/70 dark:bg-gray-900/60 my-8">
    
    <!-- Kiri -->
    <div class="hidden md:block w-2/5 relative min-h-[700px]">
      <img src="{{ asset('images/login.png') }}" alt="Farm Livestock" class="h-full w-full object-cover brightness-90"/>
      <div class="absolute inset-0 bg-gradient-to-tr from-green-900/50 to-transparent"></div>
      <div class="absolute bottom-8 left-8 text-white">
        <h2 class="text-2xl font-bold drop-shadow-md">Lembah Hijau</h2>
        <p class="text-sm text-gray-100">Sustainable & Natural Livestock</p>
      </div>
    </div>

    <!-- Kanan -->
    <div class="flex flex-col justify-center w-full md:w-3/5 p-8 md:p-10">
      <div class="flex items-center gap-3 mb-6">
        <div class="size-12 bg-gradient-to-br from-primary to-green-600 rounded-2xl flex items-center justify-center shadow-lg">
          <span class="material-symbols-outlined text-3xl text-white">eco</span>
        </div>
        <div>
          <h1 class="text-xl font-black text-gray-900 dark:text-white">Lembah Hijau</h1>
          <p class="text-sm text-gray-600 dark:text-gray-300">Premium Livestock</p>
        </div>
      </div>

      <h2 class="text-2xl font-black text-gray-900 dark:text-white mb-2">Sign Up</h2>
      <p class="text-gray-600 dark:text-gray-400 mb-6 text-sm">Create an account to get started</p>

      {{-- ✅ Tampilkan error global --}}
      @if ($errors->any())
        <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl">
          <ul class="text-sm text-red-600 dark:text-red-400 space-y-1">
            @foreach ($errors->all() as $error)
              <li>• {{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf
        
        <!-- Nama -->
        <div>
          <label for="name" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
            Full Name <span class="text-red-500">*</span>
          </label>
          <div class="relative">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">person</span>
            <input 
              type="text" 
              id="name" 
              name="name" 
              value="{{ old('name') }}"
              required
              class="w-full h-12 pl-12 pr-4 rounded-xl bg-white dark:bg-gray-800 border @error('name') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror focus:border-primary focus:ring-0 text-gray-900 dark:text-white placeholder-gray-400"
              placeholder="John Doe"
            />
          </div>
          @error('name')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        <!-- Email -->
        <div>
          <label for="email" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
            Email Address <span class="text-red-500">*</span>
          </label>
          <div class="relative">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">mail</span>
            <input 
              type="email" 
              id="email" 
              name="email" 
              value="{{ old('email') }}"
              required
              class="w-full h-12 pl-12 pr-4 rounded-xl bg-white dark:bg-gray-800 border @error('email') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror focus:border-primary focus:ring-0 text-gray-900 dark:text-white placeholder-gray-400"
              placeholder="your.email@example.com"
            />
          </div>
          @error('email')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        <!-- Password -->
        <div>
          <label for="password" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
            Password <span class="text-red-500">*</span>
          </label>
          <div class="relative">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">lock</span>
            <input 
              type="password" 
              id="password" 
              name="password" 
              required
              class="w-full h-12 pl-12 pr-12 rounded-xl bg-white dark:bg-gray-800 border @error('password') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror focus:border-primary focus:ring-0 text-gray-900 dark:text-white placeholder-gray-400"
              placeholder="Create a strong password"
            />
            <button 
              type="button" 
              onclick="togglePassword(event, 'password')"
              class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700"
            >
              <span class="material-symbols-outlined">visibility</span>
            </button>
          </div>
          @error('password')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        <!-- Konfirmasi -->
        <div>
          <label for="password_confirmation" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
            Confirm Password <span class="text-red-500">*</span>
          </label>
          <div class="relative">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">lock</span>
            <input 
              type="password" 
              id="password_confirmation" 
              name="password_confirmation" 
              required
              class="w-full h-12 pl-12 pr-12 rounded-xl bg-white dark:bg-gray-800 border @error('password_confirmation') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror focus:border-primary focus:ring-0 text-gray-900 dark:text-white placeholder-gray-400"
              placeholder="Confirm your password"
            />
            <button 
              type="button" 
              onclick="togglePassword(event, 'password_confirmation')"
              class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700"
            >
              <span class="material-symbols-outlined">visibility</span>
            </button>
          </div>
          @error('password_confirmation')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        <!-- Submit -->
        <button type="submit"
          class="w-full h-12 rounded-xl bg-gradient-to-r from-green-700 to-green-500 text-white font-bold shadow-md hover:shadow-lg hover:scale-[1.02] transition-all duration-300 flex items-center justify-center gap-2">
          <span>Sign Up</span>
          <span class="material-symbols-outlined">arrow_forward</span>
        </button>

        <!-- Garis pemisah -->
        <div class="relative flex items-center my-4">
          <div class="flex-grow border-t border-gray-300"></div>
          <span class="mx-2 text-xs text-gray-500">OR</span>
          <div class="flex-grow border-t border-gray-300"></div>
        </div>

        <!-- Tombol Registrasi via Google -->
          <div class="flex justify-center">
          <a href="{{ route('google.redirect') }}" onclick="openGoogleLogin(event)" class="google-btn">
            <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google" class="w-5 h-5" />
          </a>
        </div>
        
        <!-- Bagian link ke login -->
        <div class="text-center mt-4 text-sm">
          <p class="text-gray-700 dark:text-gray-300">
            Already have an account? 
            <a href="{{ route('login') }}" class="text-green-700 font-bold hover:underline">
              Sign In
            </a>
          </p>
        </div>


  <script>
    function togglePassword(event, inputId) {
      const input = document.getElementById(inputId);
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
