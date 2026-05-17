<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - Ecommerce TSA</title>

    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    {!! NoCaptcha::renderJs() !!}

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: "#166534",
                        accent: "#22c55e",
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
        }

        .captcha-wrapper {
            display: flex;
            justify-content: center;
        }

        .captcha-wrapper .g-recaptcha {
            transform: scale(0.92);
            transform-origin: center;
        }

        @media (max-width: 420px) {
            .captcha-wrapper .g-recaptcha {
                transform: scale(0.78);
                transform-origin: center;
            }
        }
    </style>
</head>

<body class="min-h-screen bg-[#d8d8d8] text-slate-800">
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

    @if (session('error'))
        <script>
            Swal.fire({
                title: "Gagal!",
                text: "{{ session('error') }}",
                icon: "error",
                confirmButtonText: "OK"
            });
        </script>
    @endif

    <main class="min-h-screen px-4 py-4 sm:px-6 sm:py-6 lg:px-8 lg:py-8">
        <section
            class="mx-auto grid w-full max-w-[1120px] gap-3 rounded-[1.75rem] bg-[#f3f3f3] p-3 shadow-[0_14px_28px_rgba(0,0,0,0.08)] sm:gap-4 sm:p-4 lg:grid-cols-[1.45fr_1fr] lg:gap-6 lg:p-5">
            <div class="relative min-h-[240px] overflow-hidden rounded-[1.4rem] sm:min-h-[320px] lg:min-h-[600px]">
                <img src="https://upload.wikimedia.org/wikipedia/commons/a/a2/Blue-and-yellow_Macaw.jpg"
                    alt="Blue and yellow macaw bird"
                    class="absolute inset-0 h-full w-full object-cover" />
                <div class="absolute inset-0 bg-gradient-to-t from-black/65 via-black/10 to-black/20"></div>
                <div class="relative flex h-full flex-col justify-between p-4 sm:p-5 lg:p-6">
                    <div
                        class="inline-flex w-max items-center gap-2 rounded-full border border-white/30 bg-white/20 px-3 py-1.5 text-[11px] font-medium text-white backdrop-blur-sm sm:text-sm">
                        <img src="images/logo header.png" alt="Logo Tunas Sejahtera Adhi Perkasa"
                            class="h-6 w-6 rounded-full bg-white/25 object-contain p-0.5" />
                        Tunas Sejahtera Adhi Perkasa
                    </div>

                    <div class="text-white">
                        <h1 class="max-w-[430px] text-[24px] font-bold leading-tight sm:text-[30px] lg:text-[38px]">
                            Manage Parrots Efficiently
                        </h1>
                        <p class="mt-2.5 max-w-[430px] text-[11px] leading-relaxed text-white/90 sm:text-xs lg:text-sm">
                            Easily track parrot health, breeding records, and sanctuary communications in one place.
                            Say goodbye to manual management.
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex items-center">
                <div class="mx-auto w-full max-w-[350px] px-2 py-2 sm:px-3 lg:px-1">
                    <div class="mb-5">
                        <div class="mb-3 flex">
                            <img src="images/logo header.png" alt="Logo Tunas Sejahtera Adhi Perkasa"
                                class="h-10 w-10 object-contain" />
                        </div>
                        <h2 class="text-[28px] font-bold leading-tight text-slate-800">Welcome Back</h2>
                        <p class="mt-1 text-xs text-slate-500">Sign in your account</p>
                    </div>

                    <form method="POST" action="{{ route('login') }}" class="space-y-4">
                        @csrf

                        <div>
                            <label for="email" class="mb-1.5 block text-xs font-medium text-slate-700">Your Email</label>
                            <div class="relative">
                                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                    class="h-10 w-full rounded-full border border-slate-300 bg-transparent px-4 pr-10 text-xs text-slate-800 transition focus:border-teal-500 focus:ring-2 focus:ring-teal-200 @error('email') border-red-500 @enderror"
                                    placeholder="" />
                                <span
                                    class="material-symbols-outlined pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-[17px] text-slate-400">mail</span>
                            </div>
                            @error('email')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="mb-1.5 block text-xs font-medium text-slate-700">Password</label>
                            <div class="relative">
                                <input type="password" id="password" name="password" required
                                    class="h-10 w-full rounded-full border border-slate-300 bg-transparent px-4 pr-10 text-xs text-slate-800 transition focus:border-teal-500 focus:ring-2 focus:ring-teal-200 @error('password') border-red-500 @enderror"
                                    placeholder="" />
                                <button type="button" onclick="togglePassword(event)"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 transition hover:text-slate-700"
                                    aria-label="Toggle password visibility">
                                    <span class="material-symbols-outlined text-[17px]">visibility</span>
                                </button>
                            </div>
                            @error('password')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between gap-3 text-xs">
                            <label class="inline-flex items-center gap-2 text-slate-700">
                                <input type="checkbox" name="remember"
                                    class="h-4 w-4 rounded border-slate-300 text-teal-600 focus:ring-teal-500" />
                                <span>Remember Me</span>
                            </label>
                            <a href="{{ route('password.request') }}"
                                class="font-medium text-teal-700 transition hover:text-teal-600 hover:underline">
                                Forgot Password?
                            </a>
                        </div>

                        <div class="captcha-wrapper pt-0.5">
                            {!! NoCaptcha::display() !!}
                        </div>
                        @error('g-recaptcha-response')
                            <p class="text-xs text-red-500">{{ $message }}</p>
                        @enderror

                        <button type="submit"
                            class="flex h-10 w-full items-center justify-center rounded-full bg-gradient-to-r from-[#11c0be] to-[#10a7a7] text-base font-semibold text-white shadow-[0_8px_18px_rgba(16,167,167,0.35)] transition hover:-translate-y-0.5 hover:shadow-[0_12px_22px_rgba(16,167,167,0.35)]">
                            Login
                        </button>

                        <div class="flex items-center pt-2">
                            <div class="h-px flex-1 bg-slate-300"></div>
                            <span class="mx-3 text-xs text-slate-500">Instant Login</span>
                            <div class="h-px flex-1 bg-slate-300"></div>
                        </div>

                        <div class="grid gap-3">
                            <a href="{{ route('google.redirect') }}"
                                class="flex h-10 w-full items-center justify-center gap-2 rounded-full border border-slate-300 bg-white px-3 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                                <img src="https://www.svgrepo.com/show/475656/google-color.svg"
                                    alt="Google logo in blue, red, yellow, and green colors" class="h-5 w-5" />
                                <span>Sign in with Google</span>
                            </a>
                        </div>

                        <p class="pt-2 text-center text-sm text-slate-500">
                            Don't have any account?
                            <a href="{{ route('register') }}" class="font-semibold text-teal-700 hover:underline">Register</a>
                        </p>
                    </form>
                </div>
            </div>
        </section>
    </main>

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
