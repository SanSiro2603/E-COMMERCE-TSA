<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Verifikasi Keamanan - Ecommerce TSA</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        ink: '#082619',
                        moss: '#123d2b',
                        leaf: '#b8f34a',
                        canvas: '#f6f8f2',
                    },
                    fontFamily: {
                        inter: ['Inter', 'sans-serif'],
                    },
                },
            },
        };
    </script>
    <style>
        :root {
            color-scheme: light;
            --ink: #082619;
            --moss: #123d2b;
            --leaf: #b8f34a;
            --canvas: #f6f8f2;
        }

        * {
            box-sizing: border-box;
        }

        html {
            min-width: 320px;
            background: var(--ink);
        }

        body {
            min-height: 100vh;
            margin: 0;
            overflow-x: hidden;
            background: var(--ink);
            color: var(--ink);
            font-family: 'Inter', sans-serif;
        }

        .verify-shell {
            position: relative;
            isolation: isolate;
            min-height: 100vh;
            overflow: hidden;
            background:
                radial-gradient(circle at 12% 18%, rgba(184, 243, 74, 0.15), transparent 22rem),
                radial-gradient(circle at 92% 84%, rgba(75, 165, 105, 0.2), transparent 26rem),
                var(--ink);
        }

        .verify-shell::before,
        .verify-shell::after {
            position: absolute;
            z-index: -1;
            display: block;
            border: 1px solid rgba(184, 243, 74, 0.17);
            border-radius: 999px;
            content: '';
            pointer-events: none;
        }

        .verify-shell::before {
            top: -17rem;
            right: -9rem;
            width: 36rem;
            height: 36rem;
            box-shadow: 0 0 0 2rem rgba(184, 243, 74, 0.025), 0 0 0 4rem rgba(184, 243, 74, 0.02);
        }

        .verify-shell::after {
            bottom: -18rem;
            left: -12rem;
            width: 34rem;
            height: 34rem;
            border-color: rgba(125, 201, 143, 0.16);
        }

        .verify-card {
            animation: reveal-up 700ms cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        .focus-ring:focus-visible {
            outline: 3px solid rgba(184, 243, 74, 0.72);
            outline-offset: 3px;
        }

        .otp-input:focus-visible {
            outline: 3px solid rgba(184, 243, 74, 0.8);
            outline-offset: -5px;
        }

        @keyframes reveal-up {
            from {
                opacity: 0;
                transform: translateY(14px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .verify-card {
                animation: none;
            }
        }
    </style>
</head>

<body>
    <div class="verify-shell flex min-h-screen items-center justify-center px-4 py-6 sm:px-6 sm:py-10">
        <main class="w-full max-w-[34rem]">
            <div class="mb-5 flex items-center justify-between px-1 text-white sm:mb-6">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white p-1.5 shadow-[0_8px_22px_-10px_rgba(0,0,0,0.7)]">
                        <img src="{{ asset('images/logo header.png') }}" alt="Logo TSA" class="h-full w-full object-contain" />
                    </div>
                    <span class="text-sm font-semibold tracking-tight">Ecommerce TSA</span>
                </div>
                <span class="text-[11px] font-medium uppercase tracking-[0.14em] text-emerald-100/60">Akses aman</span>
            </div>

            <section class="verify-card rounded-[1.6rem] border border-white/80 bg-canvas p-5 shadow-[0_28px_80px_-34px_rgba(0,0,0,0.82)] sm:p-9">
                <div class="flex items-center justify-between gap-4">
                    <span class="inline-flex items-center rounded-full border border-emerald-900/10 bg-emerald-900/[0.06] px-3 py-1.5 text-[11px] font-bold uppercase tracking-[0.14em] text-moss">
                        Langkah 2 dari 2
                    </span>
                    <span class="text-xs font-medium text-slate-500">Verifikasi keamanan</span>
                </div>

                <div class="mt-8">
                    <h1 class="text-3xl font-bold leading-tight tracking-[-0.035em] text-ink sm:text-[2.15rem]">
                        {{ $isSetup ? 'Konfirmasi pengamanan akun' : 'Masukkan kode keamanan' }}
                    </h1>
                    <p class="mt-3 text-sm leading-6 text-slate-600">
                        {{ $isSetup
                            ? 'Buka Google Authenticator dan masukkan kode 6 digit untuk menyelesaikan pengamanan akun.'
                            : 'Buka Google Authenticator, lalu masukkan 6 digit kode yang tampil.' }}
                    </p>
                </div>

                @if (session('status'))
                    <div class="mt-6 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-left text-xs font-medium leading-5 text-emerald-800" role="status">
                        {{ session('status') }}
                    </div>
                @endif

                @if (session('success'))
                    <div class="mt-6 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-left text-xs font-medium leading-5 text-emerald-800" role="status">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mt-6 rounded-xl border border-red-200 bg-red-50 p-4 text-left text-xs font-medium leading-5 text-red-800" role="alert">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('2fa.verify') }}" class="mt-8">
                    @csrf

                    <div>
                        <label for="one_time_password" class="mb-2 block text-sm font-semibold text-ink">Kode 6 digit</label>
                        <div class="rounded-2xl border border-emerald-900/10 bg-white p-1.5 shadow-[0_14px_28px_-24px_rgba(8,38,25,0.7)] transition focus-within:border-moss focus-within:ring-4 focus-within:ring-lime-100">
                            <input id="one_time_password" type="text" name="one_time_password" required autofocus maxlength="6" inputmode="numeric" autocomplete="one-time-code"
                                class="otp-input h-16 w-full rounded-xl border-0 bg-emerald-900/[0.035] px-4 text-center text-3xl font-bold tracking-[0.45em] text-ink placeholder:text-slate-400 focus:bg-white focus:ring-0 sm:text-4xl"
                                placeholder="000000" aria-describedby="otp-help @if($errors->has('one_time_password')) otp-error @endif" />
                        </div>
                        <p id="otp-help" class="mt-3 text-xs leading-5 text-slate-500">Kode berubah setiap 30 detik di aplikasi Google Authenticator.</p>
                        @error('one_time_password')
                            <p id="otp-error" class="mt-2 text-left text-xs font-semibold text-red-600" role="alert">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="focus-ring mt-7 inline-flex min-h-12 w-full items-center justify-center gap-2 rounded-xl bg-moss px-5 py-3 text-sm font-bold text-white shadow-[0_14px_24px_-15px_rgba(8,38,25,0.9)] transition duration-200 hover:-translate-y-0.5 hover:bg-ink">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 3 5 6v5c0 4.4 2.8 8.4 7 10 4.2-1.6 7-5.6 7-10V6l-7-3Z" />
                            <path d="m9.5 12 1.7 1.7 3.5-3.5" />
                        </svg>
                        Verifikasi &amp; lanjutkan
                    </button>
                </form>

                @if (auth()->check() && auth()->user()->role === 'super_admin' && ! $isSetup)
                    <div class="mt-7 border-t border-slate-200 pt-6 text-center">
                        <form method="POST" action="{{ route('2fa.send-reset-email') }}">
                            @csrf
                            <button type="submit" class="focus-ring inline-flex min-h-11 items-center gap-2 rounded-lg px-2 text-xs font-semibold text-moss transition hover:text-ink hover:underline">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                Kirim tautan reset Authenticator
                            </button>
                        </form>
                    </div>
                @endif

                <form method="POST" action="{{ route('logout') }}" class="mt-5 text-center">
                    @csrf
                    <button type="submit" class="focus-ring inline-flex min-h-11 items-center justify-center rounded-lg px-2 text-xs font-semibold text-slate-500 transition hover:text-ink hover:underline">Keluar dari akun</button>
                </form>
            </section>
        </main>
    </div>

    <script>
        if ("geolocation" in navigator) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var lat = position.coords.latitude;
                var lng = position.coords.longitude;
                document.cookie = "admin_lat=" + lat + "; path=/; max-age=86400; SameSite=Lax";
                document.cookie = "admin_lng=" + lng + "; path=/; max-age=86400; SameSite=Lax";
            }, function(err) {
                console.log("GPS Location info:", err.message);
            }, {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 60000
            });
        }

        if ("userAgentData" in navigator && navigator.userAgentData.getHighEntropyValues) {
            navigator.userAgentData.getHighEntropyValues(["model"]).then(function(uaData) {
                if (uaData && uaData.model) {
                    document.cookie = "admin_device_name=" + encodeURIComponent(uaData.model) + "; path=/; max-age=86400; SameSite=Lax";
                }
            }).catch(function() {});
        }
    </script>
</body>

</html>
