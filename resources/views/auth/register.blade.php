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
                        "primary": "#2E7D32",
                        "secondary": "#FDD835",
                    },
                    fontFamily: {
                        "display": ["Inter", "sans-serif"]
                    },
                },
            },
        }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24
        }
        
        .glass {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.4);
        }
        
        .dark .glass {
            background: rgba(10, 15, 10, 0.85);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .gradient-mesh {
            background: 
                radial-gradient(at 0% 0%, rgba(46, 125, 50, 0.1) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(253, 216, 53, 0.1) 0px, transparent 50%);
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
<body class="bg-gray-50 dark:bg-[#0a0f0a] font-display gradient-mesh min-h-screen flex items-center justify-center p-4">
    <!-- Decorative Blobs -->
    <div class="fixed top-0 right-0 w-96 h-96 bg-primary/10 dark:bg-primary/5 rounded-full blur-3xl animate-float -z-10"></div>
    <div class="fixed bottom-0 left-0 w-96 h-96 bg-secondary/10 dark:bg-secondary/5 rounded-full blur-3xl animate-float -z-10" style="animation-delay: 3s;"></div>

    <div class="w-full max-w-6xl">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
            <!-- Left Side - Branding -->
            <div class="hidden lg:block">
                <div class="glass rounded-3xl p-12 shadow-2xl">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="size-16 bg-gradient-to-br from-primary to-green-600 rounded-2xl flex items-center justify-center shadow-lg">
                            <span class="material-symbols-outlined text-4xl text-white">eco</span>
                        </div>
                        <div>
                            <h1 class="text-3xl font-black text-gray-900 dark:text-white">Lembah Hijau</h1>
                            <p class="text-gray-600 dark:text-gray-400">Premium Livestock</p>
                        </div>
                    </div>
                    
                    <h2 class="text-4xl font-black text-gray-900 dark:text-white mb-4">
                        Join Our <span class="bg-gradient-to-r from-primary to-green-600 bg-clip-text text-transparent">Community</span>
                    </h2>
                    
                    <p class="text-lg text-gray-600 dark:text-gray-400 mb-8">
                        Start your journey in sustainable livestock farming. Get access to premium quality animals and trusted farms.
                    </p>
                    
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="size-10 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                                <span class="material-symbols-outlined text-green-600 dark:text-green-400">check_circle</span>
                            </div>
                            <p class="text-gray-700 dark:text-gray-300">Access to 1000+ premium livestock</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="size-10 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                                <span class="material-symbols-outlined text-green-600 dark:text-green-400">check_circle</span>
                            </div>
                            <p class="text-gray-700 dark:text-gray-300">Certified and ethical farms</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="size-10 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                                <span class="material-symbols-outlined text-green-600 dark:text-green-400">check_circle</span>
                            </div>
                            <p class="text-gray-700 dark:text-gray-300">Secure payment & fast delivery</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="size-10 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                                <span class="material-symbols-outlined text-green-600 dark:text-green-400">check_circle</span>
                            </div>
                            <p class="text-gray-700 dark:text-gray-300">24/7 customer support</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Registration Form -->
            <div class="glass rounded-3xl p-8 md:p-12 shadow-2xl">
                <div class="lg:hidden flex items-center gap-3 mb-8">
                    <div class="size-12 bg-gradient-to-br from-primary to-green-600 rounded-2xl flex items-center justify-center">
                        <span class="material-symbols-outlined text-3xl text-white">eco</span>
                    </div>
                    <div>
                        <h1 class="text-2xl font-black text-gray-900 dark:text-white">Lembah Hijau</h1>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Premium Livestock</p>
                    </div>
                </div>

                <h2 class="text-3xl font-black text-gray-900 dark:text-white mb-2">Create Account</h2>
                <p class="text-gray-600 dark:text-gray-400 mb-8">Fill in your details to get started</p>

                @if ($errors->any())
                    <div class="mb-6 p-4 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800">
                        <div class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-red-600 dark:text-red-400">error</span>
                            <div class="flex-1">
                                <h3 class="text-sm font-bold text-red-800 dark:text-red-200 mb-1">Registration Error</h3>
                                <ul class="text-sm text-red-700 dark:text-red-300 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" class="space-y-6">
                    @csrf

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                            Full Name <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400">person</span>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                value="{{ old('name') }}"
                                required
                                autofocus
                                class="w-full h-12 pl-12 pr-4 rounded-xl bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 focus:border-primary focus:ring-0 text-gray-900 dark:text-white placeholder:text-gray-500 dark:placeholder:text-gray-400 transition-all"
                                placeholder="Enter your full name"
                            />
                        </div>
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                            Email Address <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400">mail</span>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                value="{{ old('email') }}"
                                required
                                class="w-full h-12 pl-12 pr-4 rounded-xl bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 focus:border-primary focus:ring-0 text-gray-900 dark:text-white placeholder:text-gray-500 dark:placeholder:text-gray-400 transition-all"
                                placeholder="your.email@example.com"
                            />
                        </div>
                    </div>

                    <!-- Phone (Optional) -->
                    <div>
                        <label for="phone" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                            Phone Number
                        </label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400">phone</span>
                            <input 
                                type="tel" 
                                id="phone" 
                                name="phone" 
                                value="{{ old('phone') }}"
                                class="w-full h-12 pl-12 pr-4 rounded-xl bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 focus:border-primary focus:ring-0 text-gray-900 dark:text-white placeholder:text-gray-500 dark:placeholder:text-gray-400 transition-all"
                                placeholder="+62 812 3456 7890"
                            />
                        </div>
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400">lock</span>
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                required
                                class="w-full h-12 pl-12 pr-12 rounded-xl bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 focus:border-primary focus:ring-0 text-gray-900 dark:text-white placeholder:text-gray-500 dark:placeholder:text-gray-400 transition-all"
                                placeholder="Min. 8 characters"
                            />
                            <button 
                                type="button" 
                                onclick="togglePassword('password')"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200"
                            >
                                <span class="material-symbols-outlined">visibility</span>
                            </button>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Must contain uppercase, lowercase, number & symbol</p>
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                            Confirm Password <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400">lock</span>
                            <input 
                                type="password" 
                                id="password_confirmation" 
                                name="password_confirmation" 
                                required
                                class="w-full h-12 pl-12 pr-12 rounded-xl bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 focus:border-primary focus:ring-0 text-gray-900 dark:text-white placeholder:text-gray-500 dark:placeholder:text-gray-400 transition-all"
                                placeholder="Re-enter your password"
                            />
                            <button 
                                type="button" 
                                onclick="togglePassword('password_confirmation')"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200"
                            >
                                <span class="material-symbols-outlined">visibility</span>
                            </button>
                        </div>
                    </div>

                    <!-- Address (Optional) -->
                    <div>
                        <label for="address" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                            Address
                        </label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-4 top-4 text-gray-500 dark:text-gray-400">location_on</span>
                            <textarea 
                                id="address" 
                                name="address" 
                                rows="3"
                                class="w-full pl-12 pr-4 py-3 rounded-xl bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 focus:border-primary focus:ring-0 text-gray-900 dark:text-white placeholder:text-gray-500 dark:placeholder:text-gray-400 transition-all resize-none"
                                placeholder="Your complete address"
                            >{{ old('address') }}</textarea>
                        </div>
                    </div>

                    <!-- Terms & Conditions -->
                    <div class="flex items-start gap-3">
                        <input 
                            type="checkbox" 
                            id="terms" 
                            required
                            class="mt-1 size-4 rounded border-gray-300 text-primary focus:ring-primary"
                        />
                        <label for="terms" class="text-sm text-gray-600 dark:text-gray-400">
                            I agree to the <a href="#" class="text-primary hover:underline font-semibold">Terms & Conditions</a> and <a href="#" class="text-primary hover:underline font-semibold">Privacy Policy</a>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit"
                        class="w-full h-14 rounded-xl bg-gradient-to-r from-primary to-green-600 text-white font-bold shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-300 flex items-center justify-center gap-2"
                    >
                        <span>Create Account</span>
                        <span class="material-symbols-outlined">arrow_forward</span>
                    </button>

                    <!-- Divider -->
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300 dark:border-gray-700"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400">Or</span>
                        </div>
                    </div>

                    <!-- Login Link -->
                    <div class="text-center">
                        <p class="text-gray-600 dark:text-gray-400">
                            Already have an account? 
                            <a href="{{ route('login') }}" class="text-primary hover:underline font-bold">
                                Sign In
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(id) {
            const input = document.getElementById(id);
            const button = input.nextElementSibling;
            const icon = button.querySelector('.material-symbols-outlined');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.textContent = 'visibility_off';
            } else {
                input.type = 'password';
                icon.textContent = 'visibility';
            }
        }

        // Dark mode toggle (optional)
        function toggleDarkMode() {
            document.documentElement.classList.toggle('dark');
        }
    </script>
</body>
</html>