<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - Ecommerce TSA</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />

    <!-- reCAPTCHA -->
    <!-- reCAPTCHA -->
    {!! NoCaptcha::renderJs() !!}
    {{-- <script src="https://www.google.com/recaptcha/api.js" async defer></script> --}}

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
        html,
        body {
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

        /* Card compact */
        .login-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 28px 26px;
            width: 100%;
            max-width: 310px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            animation: fadeInUp 0.4s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Captcha center kecil */
        .captcha-wrapper {
            display: flex;
            justify-content: center;
            margin-top: 4px;
        }

        .g-recaptcha {
            transform: scale(0.80);
            -webkit-transform: scale(0.80);
            transform-origin: center;
            -webkit-transform-origin: center;
        }

        .space-y-3>*+* {
            margin-top: 0.55rem !important;
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

        .login-btn {
            height: 30px;
            font-size: 12.5px;
            font-weight: 600;
            border-radius: 7px;
            margin-top: 14px !important;
            margin-bottom: 18px !important;
        }
    </style>
</head>

<body>
    <div class="login-card text-center">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        @if (session('success'))
            <script>
                Swal.fire({
                    title: "Berhasil!",
                    text: "{{ session('success') }}",
                    icon: "success",
                    confirmButtonText: "OK"
                });
            </script>
        @endif



        <!-- Logo -->
        <div class="flex justify-center mb-2 mt-1">
            <img src="images/logo.png" alt="Logo" class="w-12 h-12 rounded-full object-cover" />
        </div>

        <h2 class="text-lg font-bold text-gray-900 mb-0">Welcome Back</h2>
        <p class="text-gray-600 text-[11px] mb-4">Login to your account</p>

        <form method="POST" action="{{ route('login') }}" class="space-y-3">
            @csrf

            <!-- Email -->
            <div>
                <label for="email" class="block text-xs font-semibold text-gray-900 mb-1 text-left">Alamat
                    Email</label>
                <div class="relative">
                    <span
                        class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm">mail</span>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                        class="w-full h-9 pl-10 pr-3 rounded-lg border 
              @error('email') border-red-500 @else border-gray-300 @enderror
              focus:border-primary focus:ring-0 text-gray-900 placeholder-gray-400 text-xs"
                        placeholder="Masukkan email anda" />
                </div>
                @error('email')
                    <p class="text-red-500 text-[11px] mt-1 text-left">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-xs font-semibold text-gray-900 mb-1 text-left">Password</label>
                <div class="relative">
                    <span
                        class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm">lock</span>
                    <input type="password" id="password" name="password" required
                        class="w-full h-9 pl-10 pr-9 rounded-lg border 
              @error('password') border-red-500 @else border-gray-300 @enderror
              focus:border-primary focus:ring-0 text-gray-900 placeholder-gray-400 text-xs"
                        placeholder="Masukkan password anda" />
                    <button type="button" onclick="togglePassword(event)"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 text-sm">
                        <span class="material-symbols-outlined text-sm">visibility</span>
                    </button>
                </div>
                @error('password')
                    <p class="text-red-500 text-[11px] mt-1 text-left">{{ $message }}</p>
                @enderror
            </div>

            <!-- Lupa Password -->
            <div class="flex items-center justify-end text-[10.5px]">
                <a href="{{ route('password.request') }}" class="text-primary hover:underline font-semibold">Lupa
                    Password?</a>
            </div>

            <!-- reCAPTCHA -->
            <!-- reCAPTCHA -->
            {{-- <div class="captcha-wrapper">
                {!! NoCaptcha::display() !!}
            </div>
            @error('g-recaptcha-response')
                <p class="text-red-500 text-[11px] mt-1 text-left">{{ $message }}</p>
            @enderror --}}

            <!-- Login Button -->
            <button type="submit"
                class="login-btn w-full bg-gradient-to-r from-green-700 to-green-500 text-white font-bold shadow hover:scale-[1.02] transition-all duration-200 flex items-center justify-center gap-1">
                <span>Login</span>
            </button>

            <!-- Garis OR -->
            <div class="flex items-center mb-5">
                <div class="flex-grow border-t border-gray-300"></div>
                <span class="mx-2 text-[10.5px] text-gray-500">OR</span>
                <div class="flex-grow border-t border-gray-300"></div>
            </div>

            <!-- Google -->
            <div class="mb-4">
                <a href="{{ route('google.redirect') }}" class="google-login text-gray-700 font-medium">
                    <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google logo in blue, red, yellow, and green colors" class="w-4 h-4" />
                    <span>Continue with Google</span>
                </a>
            </div>

            <div class="text-center mt-4 text-[11px]">
                <p class="text-gray-700">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="text-green-700 font-bold hover:underline">Sign Up</a>
                </p>
            </div>
        </form>
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
