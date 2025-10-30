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
  </style>
</head>

<body class="font-display gradient-mesh flex items-center justify-center min-h-screen overflow-auto relative p-4">

  <!-- Efek glassmorphism di background -->
  <div class="absolute inset-0 glass -z-10"></div>

  <!-- Efek dekoratif -->
  <div class="fixed top-0 right-0 w-80 h-80 bg-primary/30 rounded-full blur-3xl animate-float -z-10"></div>
  <div class="fixed bottom-0 left-0 w-80 h-80 bg-secondary/30 rounded-full blur-3xl animate-float -z-10" style="animation-delay: 3s;"></div>

  <!-- Wrapper utama -->
  <div class="flex flex-col md:flex-row w-full max-w-4xl rounded-3xl overflow-hidden glass shadow-2xl border border-white/20 bg-white/70 dark:bg-gray-900/60 my-8">
    
    <!-- Bagian kiri -->
    <div class="hidden md:block w-1/2 relative min-h-[600px]">
      <img 
        src="{{ asset('images/login.png') }}" 
        alt="Farm Livestock" 
        class="h-full w-full object-cover brightness-90"
      />
      <div class="absolute inset-0 bg-gradient-to-tr from-green-900/50 to-transparent"></div>
      <div class="absolute bottom-8 left-8 text-white">
        <h2 class="text-2xl font-bold drop-shadow-md">Lembah Hijau</h2>
        <p class="text-sm text-gray-100">Sustainable & Natural Livestock</p>
      </div>
    </div>

    <!-- Bagian kanan -->
    <div class="flex flex-col justify-center w-full md:w-1/2 p-8 md:p-10">
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

      <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf
        <div>
          <label for="name" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
            Full Name
          </label>
          <div class="relative">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">person</span>
            <input 
              type="text" 
              id="name" 
              name="name" 
              required
              class="w-full h-12 pl-12 pr-4 rounded-xl bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 focus:border-primary focus:ring-0 text-gray-900 dark:text-white placeholder-gray-400"
              placeholder="John Doe"
            />
          </div>
        </div>

        <div>
          <label for="email" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
            Email Address
          </label>
          <div class="relative">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">mail</span>
            <input 
              type="email" 
              id="email" 
              name="email" 
              required
              class="w-full h-12 pl-12 pr-4 rounded-xl bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 focus:border-primary focus:ring-0 text-gray-900 dark:text-white placeholder-gray-400"
              placeholder="your.email@example.com"
            />
          </div>
        </div>

        <div>
          <label for="password" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
            Password
          </label>
          <div class="relative">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">lock</span>
            <input 
              type="password" 
              id="password" 
              name="password" 
              required
              class="w-full h-12 pl-12 pr-12 rounded-xl bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 focus:border-primary focus:ring-0 text-gray-900 dark:text-white placeholder-gray-400"
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
        </div>

        <div>
          <label for="password_confirmation" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
            Confirm Password
          </label>
          <div class="relative">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">lock</span>
            <input 
              type="password" 
              id="password_confirmation" 
              name="password_confirmation" 
              required
              class="w-full h-12 pl-12 pr-12 rounded-xl bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 focus:border-primary focus:ring-0 text-gray-900 dark:text-white placeholder-gray-400"
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
        </div>

        <div class="flex items-start gap-2 text-sm pt-2">
          <input type="checkbox" name="terms" required class="size-4 mt-0.5 rounded border-gray-300 text-primary focus:ring-primary" />
          <label class="text-gray-600 dark:text-gray-300">
            I agree to the <a href="#" class="text-primary hover:underline font-semibold">Terms of Service</a> and <a href="#" class="text-primary hover:underline font-semibold">Privacy Policy</a>
          </label>
        </div>

        <button 
          type="submit"
          class="w-full h-12 rounded-xl bg-gradient-to-r from-green-700 to-green-500 text-white font-bold shadow-md hover:shadow-lg hover:scale-[1.02] transition-all duration-300 flex items-center justify-center gap-2"
        >
          <span>Sign Up</span>
          <span class="material-symbols-outlined">arrow_forward</span>
        </button>

        <div class="text-center mt-4 text-sm">
          <p class="text-gray-700 dark:text-gray-300">
            Already have an account? 
            <a href="{{ route('login') }}" class="text-green-700 font-bold hover:underline">
              Sign In
            </a>
          </p>
        </div>
      </form>
    </div>
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
  </script>
</body>

</html>