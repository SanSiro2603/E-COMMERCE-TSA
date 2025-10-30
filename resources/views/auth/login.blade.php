{{-- resources/views/auth/login.blade.php --}}
<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Lembah Hijau - Login</title>

    <!-- Tailwind CDN + Plugins -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;700;900&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />

    <!-- Tailwind Config -->
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#72e236",
                        "background-light": "#FDFBF5",
                        "background-dark": "#172111",
                        "soft-green": "#7BB661",
                        "warm-yellow": "#FFD54F",
                        "charcoal": "#333333",
                    },
                    fontFamily: {
                        "display": ["Poppins", "sans-serif"],
                        "be-vietnam": ["Be Vietnam Pro", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.5rem",
                        "lg": "1rem",
                        "xl": "1.5rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>

    <style>
        .gradient-button {
            background-image: linear-gradient(to right, #8fcf72, #7BB661);
        }
        .gradient-button:hover {
            background-image: linear-gradient(to right, #9bd980, #8fcf72);
        }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark" style="font-family: 'Poppins', sans-serif;">
    <div class="relative flex min-h-screen w-full flex-col overflow-x-hidden"
         style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'rgba(123, 182, 97, 0.1)\' fill-opacity=\'0.4\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');">
        
        <div class="flex-1 flex items-center justify-center p-4">
            <div class="w-full max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 shadow-xl rounded-xl overflow-hidden">
                
                <!-- Gambar Samping (Desktop Only) -->
                <div class="hidden md:block">
                    <div class="w-full h-full bg-center bg-no-repeat bg-cover"
                         style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuCTnwWAa7Kc0tsxdroI5T72snwgiMs88mNFlMzimh2Mv1xLRR7AaDd5pBErL2E0wxxzDX2zmC_qjzLpCIKn_rTUmevXkvWaiafarNg0lFuwP5olkp-kqz9O2obu2nBl3cuFn3H1RA1PaKgLBLTwueq1l5rMWtf9ya-2iRLtmSCqV5OO5NS7oHX3mfENmwM_19FwRwhHvdvBSGezounPpnUuyMAymWuQRBGQlOsRYr-GHRSmUvYU1wJZW5o0h3c6zfp3GWV6lpa0XrqI');">
                    </div>
                </div>

                <!-- Form Login -->
                <div class="bg-white dark:bg-zinc-900 p-8 md:p-12 flex flex-col justify-center">
                    <div class="flex flex-col gap-6 w-full max-w-md mx-auto">
                        
                        <!-- Logo & Tagline -->
                        <div class="flex flex-col items-center text-center gap-2">
                            <h1 class="text-charcoal dark:text-white text-3xl font-bold font-be-vietnam">Lembah Hijau</h1>
                            <h2 class="text-charcoal dark:text-zinc-300 text-sm font-normal font-be-vietnam">
                                Belanja Hewan dengan Mudah dan Aman
                            </h2>
                        </div>

                        <!-- Form -->
                        <form method="POST" action="{{ route('login') }}" class="flex flex-col gap-4">
                            @csrf

                            <!-- Email -->
                            <label class="flex flex-col">
                                <p class="text-charcoal dark:text-zinc-200 text-base font-medium pb-2">Email</p>
                                <div class="flex w-full flex-1 items-stretch rounded-lg">
                                    <input
                                        name="email"
                                        type="email"
                                        required
                                        autocomplete="email"
                                        value="{{ old('email') }}"
                                        placeholder="Enter your email"
                                        class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-charcoal dark:text-zinc-100 focus:outline-0 focus:ring-2 focus:ring-warm-yellow border @error('email') border-red-500 @else border-gray-300 dark:border-zinc-700 @enderror bg-white dark:bg-zinc-800 h-14 placeholder:text-gray-500 dark:placeholder-zinc-400 p-[15px] text-base font-normal leading-normal"
                                    />
                                </div>
                                @error('email')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </label>

                            <!-- Password -->
                            <label class="flex flex-col">
                                <p class="text-charcoal dark:text-zinc-200 text-base font-medium pb-2">Password</p>
                                <div class="flex w-full flex-1 items-stretch rounded-lg">
                                    <input
                                        name="password"
                                        type="password"
                                        required
                                        autocomplete="current-password"
                                        placeholder="Enter your password"
                                        class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-l-lg text-charcoal dark:text-zinc-100 focus:outline-0 focus:ring-2 focus:ring-warm-yellow border @error('password') border-red-500 @else border-gray-300 dark:border-zinc-700 @enderror bg-white dark:bg-zinc-800 h-14 placeholder:text-gray-500 dark:placeholder-zinc-400 p-[15px] border-r-0 text-base font-normal leading-normal"
                                    />
                                    <div class="text-gray-500 dark:text-zinc-400 flex border border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 items-center justify-center pr-[15px] rounded-r-lg border-l-0 cursor-pointer">
                                        <span class="material-symbols-outlined">visibility_off</span>
                                    </div>
                                </div>
                                @error('password')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </label>

                            <!-- Lupa Password -->
                            <p class="text-right text-soft-green text-sm font-normal leading-normal underline cursor-pointer hover:text-warm-yellow dark:hover:text-warm-yellow">
                                <a href="{{ route('password.request') }}">Forgot Password?</a>
                            </p>

                            <!-- Remember Me -->
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="remember" class="w-4 h-4 text-primary rounded focus:ring-warm-yellow" {{ old('remember') ? 'checked' : '' }} />
                                <span class="text-charcoal dark:text-zinc-300 text-sm">Ingat saya</span>
                            </label>

                            <!-- Submit Button -->
                            <div class="flex flex-col gap-4 items-center mt-4">
                                <button type="submit"
                                        class="flex w-full min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-5 gradient-button text-white text-base font-bold leading-normal tracking-[0.015em] transition-all duration-300">
                                    <span class="truncate">Login</span>
                                </button>

                                <p class="text-charcoal dark:text-zinc-300 text-base font-normal leading-normal">
                                    Don't have an account?
                                    <a href="{{ route('register') }}" class="text-soft-green font-medium underline hover:text-warm-yellow dark:hover:text-warm-yellow">
                                        Register Now
                                    </a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>