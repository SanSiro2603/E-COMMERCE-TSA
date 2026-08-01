<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Verifikasi 2FA - Ecommerce TSA</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        inter: ["Inter", "sans-serif"],
                    },
                },
            },
        };
    </script>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-50 via-zinc-50 to-zinc-100 font-inter antialiased">
    <main class="flex min-h-screen items-center justify-center px-2 py-3 sm:px-4 sm:py-5">
        <div class="w-full max-w-lg rounded-3xl border border-white/80 bg-white/80 p-10 text-center shadow-[0_10px_30px_-15px_rgba(15,23,42,0.25)] backdrop-blur-sm sm:p-12">
            <h2 class="text-2xl font-semibold tracking-tight text-slate-900">
                {{ $isSetup ? 'Konfirmasi Setup 2FA' : 'Verifikasi 2FA' }}
            </h2>
            <p class="mt-2 text-sm leading-relaxed text-slate-500">
                {{ $isSetup
                    ? 'Masukkan 6 digit kode pertama dari aplikasi Google Authenticator untuk mengaktifkan 2FA.'
                    : 'Masukkan 6 digit kode dari aplikasi Google Authenticator Anda.' }}
            </p>

            @if (session('status'))
                <div class="mt-4 rounded-2xl bg-emerald-50 border border-emerald-200 p-4 text-left text-xs font-medium text-emerald-800 leading-relaxed shadow-sm">
                    {{ session('status') }}
                </div>
            @endif

            @if (session('success'))
                <div class="mt-4 rounded-2xl bg-emerald-50 border border-emerald-200 p-4 text-left text-xs font-medium text-emerald-800 leading-relaxed shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mt-4 rounded-2xl bg-red-50 border border-red-200 p-4 text-left text-xs font-medium text-red-800 leading-relaxed shadow-sm">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('2fa.verify') }}" class="mt-8">
                @csrf
            
                <div class="rounded-3xl border border-slate-100 bg-white/70 p-2 shadow-[inset_0_1px_0_rgba(255,255,255,0.9),0_8px_24px_-20px_rgba(15,23,42,0.35)]">
                    <input type="text" name="one_time_password" required autofocus maxlength="6"
                        class="h-16 w-full rounded-2xl border border-slate-200 bg-slate-50/90 px-5 text-center text-3xl font-semibold tracking-[0.42em] text-slate-900 placeholder:text-slate-400 shadow-[0_4px_14px_-10px_rgba(15,23,42,0.35)] transition duration-200 focus:-translate-y-0.5 focus:border-emerald-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-emerald-100"
                        placeholder="000000" />
                    @error('one_time_password')
                        <p class="mt-2 text-left text-xs font-medium text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="mt-6 inline-flex h-12 w-full items-center justify-center rounded-2xl bg-emerald-600 text-sm font-semibold text-white shadow-[0_10px_20px_-12px_rgba(5,150,105,0.8)] transition duration-200 hover:scale-[1.01] hover:bg-emerald-500 focus:outline-none focus:ring-4 focus:ring-emerald-100">Verifikasi</button>
            </form>

            @if (auth()->check() && auth()->user()->role === 'super_admin' && ! $isSetup)
                <div class="mt-6 border-t border-slate-100 pt-6">
                    <form method="POST" action="{{ route('2fa.send-reset-email') }}">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-1.5 text-xs font-medium text-emerald-600 transition hover:text-emerald-700 hover:underline focus:outline-none">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Reset Authenticator via Email
                        </button>
                    </form>
                </div>
            @endif

            <form method="POST" action="{{ route('logout') }}" class="mt-6">
                @csrf
                <button type="submit" class="inline-flex items-center justify-center text-xs font-medium text-slate-500 transition hover:text-slate-700 hover:underline focus:outline-none">Batal & Logout</button>
            </form>
        </div>
    </main>

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
