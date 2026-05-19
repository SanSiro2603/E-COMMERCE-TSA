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
            <h2 class="text-2xl font-semibold tracking-tight text-slate-900">Verifikasi 2FA</h2>
            <p class="mt-2 text-sm leading-relaxed text-slate-500">Masukkan 6 digit kode dari aplikasi Google Authenticator Anda.</p>

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

            <form method="POST" action="{{ route('logout') }}" class="mt-6">
                @csrf
                <button type="submit" class="inline-flex items-center justify-center text-xs font-medium text-slate-500 transition hover:text-slate-700 hover:underline focus:outline-none">Batal & Logout</button>
            </form>
        </div>
    </main>
</body>
</html>
