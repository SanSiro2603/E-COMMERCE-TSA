<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pengaturan Keamanan - Ecommerce TSA</title>
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
            --muted: #60756a;
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

        .security-shell {
            position: relative;
            isolation: isolate;
            min-height: 100vh;
            overflow: hidden;
            background:
                radial-gradient(circle at 8% 18%, rgba(184, 243, 74, 0.12), transparent 27rem),
                radial-gradient(circle at 95% 86%, rgba(82, 168, 108, 0.18), transparent 25rem),
                var(--ink);
        }

        .security-shell::before,
        .security-shell::after {
            position: absolute;
            z-index: -1;
            display: block;
            border: 1px solid rgba(184, 243, 74, 0.18);
            border-radius: 999px;
            content: '';
            pointer-events: none;
        }

        .security-shell::before {
            top: -16rem;
            left: -12rem;
            width: 38rem;
            height: 38rem;
            box-shadow: 0 0 0 2.5rem rgba(184, 243, 74, 0.025), 0 0 0 5rem rgba(184, 243, 74, 0.02);
        }

        .security-shell::after {
            right: -18rem;
            bottom: -20rem;
            width: 42rem;
            height: 42rem;
            border-color: rgba(125, 201, 143, 0.16);
        }

        .security-panel {
            position: relative;
            overflow: hidden;
            background:
                linear-gradient(145deg, rgba(32, 89, 58, 0.92), rgba(8, 38, 25, 0.98) 62%),
                var(--moss);
        }

        .security-panel::after {
            position: absolute;
            right: -8rem;
            bottom: -9rem;
            width: 23rem;
            height: 23rem;
            border: 1px solid rgba(184, 243, 74, 0.17);
            border-radius: 999px;
            box-shadow: 0 0 0 2rem rgba(184, 243, 74, 0.025), 0 0 0 4rem rgba(184, 243, 74, 0.02);
            content: '';
            pointer-events: none;
        }

        .qr-code svg,
        .qr-code img {
            display: block;
            width: min(100%, 16.5rem);
            height: auto;
            max-height: 16.5rem;
            margin-inline: auto;
        }

        .focus-ring:focus-visible {
            outline: 3px solid rgba(184, 243, 74, 0.72);
            outline-offset: 3px;
        }

        @media (prefers-reduced-motion: no-preference) {
            .security-panel,
            .setup-card {
                animation: reveal-up 700ms cubic-bezier(0.16, 1, 0.3, 1) both;
            }

            .setup-card {
                animation-delay: 90ms;
            }
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
    </style>
</head>

<body>
    <div class="security-shell flex min-h-screen items-center px-4 py-5 sm:px-6 sm:py-8 lg:px-10">
        <main class="mx-auto w-full max-w-5xl">
            <section class="grid overflow-hidden rounded-[1.75rem] border border-white/10 bg-canvas shadow-[0_28px_80px_-34px_rgba(0,0,0,0.75)] lg:grid-cols-[0.82fr_1.18fr]">
                <div class="security-panel flex min-h-[24rem] flex-col justify-between p-6 text-white sm:p-7 lg:min-h-[36rem] lg:p-8">
                    <div class="relative z-10">
                        <div class="flex items-center gap-3">
                            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-white p-1.5 shadow-[0_8px_22px_-10px_rgba(0,0,0,0.7)]">
                                <img src="{{ asset('images/logo header.png') }}" alt="Logo TSA" class="h-full w-full object-contain" />
                            </div>
                            <div>
                                <p class="text-sm font-semibold tracking-tight text-white">Ecommerce TSA</p>
                                <p class="mt-0.5 text-[11px] text-emerald-100/70">Area keamanan akun</p>
                            </div>
                        </div>

                        <div class="mt-12 max-w-sm lg:mt-16">
                            <p class="mb-4 inline-flex items-center gap-2 text-[11px] font-semibold uppercase tracking-[0.18em] text-lime-200">
                                <span class="h-2 w-2 rounded-full bg-leaf shadow-[0_0_0_5px_rgba(184,243,74,0.12)]"></span>
                                Perlindungan tambahan
                            </p>
                            <h1 class="text-3xl font-bold leading-[1.08] tracking-[-0.035em] text-white sm:text-4xl">
                                Authentikasi Dua Langkah
                            </h1>
                            <p class="mt-5 max-w-xs text-sm leading-6 text-emerald-50/70">
                                Hubungkan aplikasi authenticator sebelum masuk ke panel pengelolaan Ecommerce TSA.
                            </p>
                        </div>
                    </div>

                    <div class="relative z-10 mt-12">
                        <p class="mb-4 text-[11px] font-semibold uppercase tracking-[0.18em] text-emerald-100/60">Alur pengamanan</p>
                        <ol class="space-y-4" aria-label="Tahapan pengamanan akun">
                            <li class="flex items-center gap-3">
                                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-leaf text-xs font-bold text-ink">01</span>
                                <span class="text-sm font-semibold text-white">Pindai kode QR</span>
                            </li>
                            <li class="flex items-center gap-3 opacity-45">
                                <span class="flex h-8 w-8 items-center justify-center rounded-full border border-white/30 text-xs font-bold text-white">02</span>
                                <span class="text-sm font-medium text-white">Masukkan kode verifikasi</span>
                            </li>
                        </ol>
                    </div>
                </div>

                <div class="setup-card flex items-center bg-canvas p-5 sm:p-7 lg:p-9">
                    <div class="mx-auto w-full max-w-[27rem]">
                        <div class="flex items-center justify-between gap-4">
                            <span class="inline-flex items-center rounded-full border border-emerald-900/10 bg-emerald-900/[0.06] px-3 py-1.5 text-[11px] font-bold uppercase tracking-[0.14em] text-moss">
                                Langkah 1 dari 2
                            </span>
                            <span class="text-xs font-medium text-slate-500">Pengaturan keamanan</span>
                        </div>

                        <div class="mt-6">
                            <h2 class="text-3xl font-bold leading-tight tracking-[-0.035em] text-ink sm:text-[1.9rem]">
                                Hubungkan aplikasi authenticator
                            </h2>
                            <p class="mt-3 max-w-xl text-sm leading-6 text-slate-600">
                                Pindai kode QR menggunakan Google Authenticator. Setelah terhubung, lanjutkan dengan kode 6 digit.
                            </p>
                        </div>

                        <div class="qr-code mt-6 rounded-[1.25rem] border border-slate-200/90 bg-white p-4 shadow-[0_18px_34px_-24px_rgba(8,38,25,0.5)] sm:p-5">
                            {!! $qrCode !!}
                        </div>

                        <div class="mt-5 rounded-2xl border border-emerald-900/10 bg-emerald-900/[0.045] p-4">
                            <div class="flex items-start gap-3">
                                <span class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-moss text-leaf" aria-hidden="true">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path d="M12 3 5 6v5c0 4.4 2.8 8.4 7 10 4.2-1.6 7-5.6 7-10V6l-7-3Z" />
                                        <path d="m9.5 12 1.7 1.7 3.5-3.5" />
                                    </svg>
                                </span>
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-ink">Tidak bisa memindai?</p>
                                    <p class="mt-1 text-xs leading-5 text-slate-600">Masukkan kunci ini secara manual di aplikasi authenticator Anda.</p>
                                    <code class="mt-3 block overflow-x-auto rounded-xl border border-slate-200 bg-white px-3 py-3 text-center text-xs font-bold tracking-[0.16em] text-moss sm:text-sm">{{ $secret }}</code>
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('2fa.setup.continue') }}" method="POST" class="mt-6">
                            @csrf
                            <button type="submit" class="focus-ring inline-flex min-h-12 w-full items-center justify-center gap-2 rounded-xl bg-moss px-5 py-3 text-sm font-bold text-white shadow-[0_14px_24px_-15px_rgba(8,38,25,0.9)] transition duration-200 hover:-translate-y-0.5 hover:bg-ink">
                                Lanjut ke kode verifikasi
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M5 12h14M13 6l6 6-6 6" />
                                </svg>
                            </button>
                        </form>

                        <form action="{{ route('logout') }}" method="POST" class="mt-3 text-center">
                            @csrf
                            <button type="submit" class="focus-ring inline-flex min-h-11 w-full items-center justify-center rounded-lg border border-slate-300 bg-white px-5 text-xs font-bold text-slate-700 shadow-[0_6px_14px_-12px_rgba(8,38,25,0.7)] transition hover:-translate-y-0.5 hover:border-moss hover:bg-emerald-50 hover:text-ink">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </section>
        </main>
    </div>
</body>

</html>
