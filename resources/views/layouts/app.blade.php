{{-- resources/views/pembeli/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="id" class="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Tunas Sejahtera Adi Perkasa - E-Commerce Ternak')</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet">

    <!-- Tailwind Config -->
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#13ec6d",
                        "background-light": "#f6f8f7",
                        "background-dark": "#102218",
                        "soft-green": "#16a34a",
                    },
                    fontFamily: {
                        "display": ["Manrope", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>

    <style>
        body {
            font-family: 'Manrope', sans-serif;
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        /* Scrollbar Custom */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #13ec6d;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #10d161;
        }

        /* Mobile Menu Animation */
        .mobile-menu {
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }

        .mobile-menu.active {
            transform: translateX(0);
        }

        /* Cart Badge Animation */
        @keyframes badge-pop {
            0% {
                transform: scale(0);
            }

            50% {
                transform: scale(1.2);
            }

            100% {
                transform: scale(1);
            }
        }

        .cart-badge {
            animation: badge-pop 0.3s ease-out;
        }
    </style>
</head>

<body class="bg-background-light dark:bg-background-dark text-[#0d1b13] dark:text-white transition-colors duration-300">

    <!-- Header / Navbar -->
    <header
        class="sticky top-0 z-50 bg-white/80 dark:bg-background-dark/80 backdrop-blur-md border-b border-[#e7f3ec] dark:border-primary/20">
        <div class="max-w-[1280px] mx-auto px-4 md:px-10 py-3 flex items-center justify-between gap-4">

            <!-- Logo -->
            <div class="flex items-center gap-3 shrink-0">
                <!-- Mobile Menu Toggle -->
                <button onclick="toggleMobileMenu()"
                    class="md:hidden text-[#0d1b13] dark:text-white hover:text-primary transition-colors">
                    <span class="material-symbols-outlined text-3xl">menu</span>
                </button>

                <a href="{{ route('pembeli.dashboard') }}" class="flex items-center gap-3">
                    <div class="h-11 w-auto flex-shrink-0">
                        <img src="{{ asset('images/logo header.png') }}" alt="Logo TSA"
                            class="h-full w-auto object-contain">
                    </div>
                    <h2
                        class="hidden md:block text-[#0d1b13] dark:text-white text-base font-extrabold leading-tight tracking-tight">
                        E-COMMERCE TSA<br />
                        <span class="text-primary text-xs font-semibold">Tunas Sejahtera Adhiperkasa</span>
                    </h2>
                </a>
            </div>

            <!-- Search Bar (Desktop Only) dengan Autocomplete -->
            <div class="hidden lg:block flex-1 max-w-xl relative" id="navbar-autocomplete-wrapper">
                <form action="{{ route('pembeli.produk.index') }}" method="GET" id="navbar-search-form">
                    <div class="relative group">
                        <div
                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-primary/70">
                            <span class="material-symbols-outlined">search</span>
                        </div>
                        <input type="text" id="navbar-search-input" name="search" value="{{ request('search') }}"
                            autocomplete="off"
                            class="block w-full pl-10 pr-8 py-2 border-none bg-[#e7f3ec] dark:bg-primary/10 rounded-lg focus:ring-2 focus:ring-primary/50 text-sm placeholder:text-primary/60 text-[#0d1b13] dark:text-white"
                            placeholder="Cari Produk Kami Disini...">

                        <!-- Spinner -->
                        <div id="navbar-search-spinner" class="hidden absolute right-3 top-1/2 -translate-y-1/2">
                            <div class="w-4 h-4 border-2 border-primary border-t-transparent rounded-full animate-spin">
                            </div>
                        </div>

                        <!-- Clear -->
                        <button type="button" id="navbar-search-clear" onclick="clearNavbarSearch()"
                            class="hidden absolute right-3 top-1/2 -translate-y-1/2 text-primary/60 hover:text-primary">
                            <span class="material-symbols-outlined text-lg">close</span>
                        </button>
                    </div>
                </form>

                <!-- Dropdown -->
                <div id="navbar-autocomplete-dropdown"
                    class="hidden absolute top-full left-0 right-0 mt-2 bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-700 rounded-xl shadow-xl z-50 overflow-hidden">
                    <div id="navbar-autocomplete-results"></div>
                    <div id="navbar-autocomplete-footer"
                        class="hidden px-4 py-2.5 border-t border-gray-100 dark:border-zinc-800 bg-gray-50 dark:bg-zinc-800/50">
                        <button onclick="submitNavbarSearch()"
                            class="w-full text-center text-xs text-primary font-semibold hover:underline">
                            Lihat semua hasil untuk "<span id="navbar-query-text"></span>"
                        </button>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-2 md:gap-4 shrink-0">

                <!-- Search Icon (Mobile) -->
                <a href="{{ route('pembeli.produk.index') }}"
                    class="lg:hidden p-2 hover:bg-primary/10 rounded-lg text-[#0d1b13] dark:text-white">
                    <span class="material-symbols-outlined">search</span>
                </a>

                <!-- Dark Mode Toggle -->
                <button onclick="toggleDarkMode()"
                    class="p-2 hover:bg-primary/10 rounded-lg text-[#0d1b13] dark:text-white transition-colors">
                    <span class="material-symbols-outlined dark-mode-icon">light_mode</span>
                </button>

                <!-- Cart with Badge -->
                @php
                    $cartCount = 0;
                    if (session('cart')) {
                        $cartCount = array_sum(array_column(session('cart'), 'quantity'));
                    }
                @endphp
                <a href="{{ route('pembeli.keranjang.index') }}"
                    class="relative p-2 hover:bg-primary/10 rounded-lg text-[#0d1b13] dark:text-white cursor-pointer transition-colors">
                    <span class="material-symbols-outlined">shopping_cart</span>
                    <span id="cart-count-badge"
                        class="cart-badge absolute top-1 right-1 flex h-4 w-4 items-center justify-center rounded-full bg-primary text-[10px] font-bold text-white {{ $cartCount > 0 ? '' : 'hidden' }}">
                        {{ $cartCount }}
                    </span>
                </a>

                <!-- Profile Dropdown -->
                @auth
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="flex items-center gap-3 pl-2 border-l border-gray-200 dark:border-primary/20">
                            <div class="hidden lg:block text-right">
                                <p class="text-xs font-bold leading-none text-[#0d1b13] dark:text-white">
                                    {{ Str::limit(auth()->user()->name, 15) }}
                                </p>
                                <p class="text-[10px] text-primary font-medium">Mitra Utama</p>
                            </div>
                            <div class="size-10 rounded-full bg-primary/20 border-2 border-primary overflow-hidden">
                                <img src="{{ auth()->user()->profile_photo ? asset('storage/' . auth()->user()->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=13ec6d&color=fff' }}"
                                    alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                            </div>
                        </button>
                @endauth

                    <!-- Dropdown Menu -->
                    <div x-show="open" @click.away="open = false" x-transition
                        class="absolute right-0 mt-2 w-56 bg-white dark:bg-[#0d1b13] rounded-lg shadow-lg border border-gray-200 dark:border-primary/20 py-2 z-50">

                        <div class="px-4 py-3 border-b border-gray-200 dark:border-primary/20">
                            <p class="text-sm font-semibold text-[#0d1b13] dark:text-white">{{ auth()->user()->name }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ auth()->user()->email }}</p>
                        </div>

                        <a href="{{ route('pembeli.profile.show') }}"
                            class="flex items-center gap-3 px-4 py-2 text-sm text-[#0d1b13] dark:text-white hover:bg-primary/10">
                            <span class="material-symbols-outlined text-lg">person</span>
                            Profil Saya
                        </a>
                        <a href="{{ route('pembeli.pesanan.index') }}"
                            class="flex items-center gap-3 px-4 py-2 text-sm text-[#0d1b13] dark:text-white hover:bg-primary/10">
                            <span class="material-symbols-outlined text-lg">receipt_long</span>
                            Riwayat Pesanan
                        </a>
                        <a href="{{ route('pembeli.alamat.index') }}"
                            class="flex items-center gap-3 px-4 py-2 text-sm text-[#0d1b13] dark:text-white hover:bg-primary/10">
                            <span class="material-symbols-outlined text-lg">location_on</span>
                            Alamat Saya
                        </a>

                        <div class="border-t border-gray-200 dark:border-primary/20 mt-2 pt-2">
                            <a href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                class="flex items-center gap-3 px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10">
                                <span class="material-symbols-outlined text-lg">logout</span>
                                Keluar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Mobile Sidebar -->
    <div
        class="md:hidden mobile-menu fixed inset-y-0 left-0 w-64 bg-white dark:bg-background-dark shadow-xl z-40 overflow-y-auto">
        <div class="p-4">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-2">
                    <div class="h-10 w-auto flex-shrink-0">
                        <img src="{{ asset('images/logo header.png') }}" alt="Logo TSA"
                            class="h-full w-auto object-contain">
                    </div>
                    <span class="text-base font-bold text-[#0d1b13] dark:text-white leading-tight">PT. Tunas
                        Sejahtera<br><span class="text-primary text-xs">Adhiperkasa</span></span>
                </div>
                <button onclick="toggleMobileMenu()" class="text-[#0d1b13] dark:text-white">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <nav class="space-y-1">
                @php
                    $menu = [
                        ['icon' => 'home', 'label' => 'Beranda', 'route' => 'pembeli.dashboard'],
                        ['icon' => 'inventory_2', 'label' => 'Produk', 'route' => 'pembeli.produk.index'],
                        ['icon' => 'receipt_long', 'label' => 'Pesanan', 'route' => 'pembeli.pesanan.index'],
                        ['icon' => 'location_on', 'label' => 'Alamat', 'route' => 'pembeli.alamat.index'],
                        ['icon' => 'person', 'label' => 'Profil', 'route' => 'pembeli.profile.show'],
                    ];
                @endphp

                @foreach($menu as $item)
                    @php $active = request()->routeIs($item['route'] . '*'); @endphp
                    <a href="{{ route($item['route']) }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-lg {{ $active ? 'bg-primary/10 text-primary font-semibold' : 'text-[#0d1b13] dark:text-white hover:bg-gray-100 dark:hover:bg-primary/10' }}">
                        <span class="material-symbols-outlined">{{ $item['icon'] }}</span>
                        <span class="flex-1">{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </nav>
        </div>
    </div>

    <!-- Mobile Overlay -->
    <div class="md:hidden fixed inset-0 bg-black/50 z-30 hidden mobile-overlay" onclick="toggleMobileMenu()"></div>

    <!-- Main Content -->
    <main class="max-w-[1280px] mx-auto px-4 md:px-10 pb-20">
        @yield('content')
    </main>

    <!-- Footer -->
    @if(!request()->routeIs('pembeli.keranjang.index', 'pembeli.pesanan.checkout', 'pembeli.payment.show', 'pembeli.pesanan.index', 'pembeli.pesanan.show', 'pembeli.pesanan.edit'))
    <footer style="background-color:#102218;" class="text-white">
        <div class="h-1 w-full" style="background:linear-gradient(to right, rgba(19,236,109,0.6), rgba(255,255,255,0.2), rgba(19,236,109,0.6));"></div>

        <div class="mx-auto w-[94%] max-w-[1240px] py-12">

            {{-- Brand + socials --}}
            <div class="mb-10 flex flex-col items-start gap-5 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logo header.png') }}" alt="TSA logo"
                         class="h-12 w-12 rounded-full object-cover" style="outline:2px solid rgba(255,255,255,0.2);">
                    <div>
                        <p class="text-base font-extrabold leading-tight text-white">Tunas Sejahtera Adhi Perkasa</p>
                        <p class="mt-0.5 text-xs" style="color:rgba(255,255,255,0.55);">Livestock Breeding Center & Export-Import Specialist</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <a href="#" aria-label="Facebook" class="inline-flex h-9 w-9 items-center justify-center rounded-full transition-colors" style="background:rgba(255,255,255,0.1);" onmouseover="this.style.background='rgba(255,255,255,0.2)'" onmouseout="this.style.background='rgba(255,255,255,0.1)'">
                        <svg viewBox="0 0 24 24" class="h-4 w-4" fill="currentColor"><path d="M13.5 21v-8h2.7l.4-3h-3.1V8.2c0-.9.3-1.5 1.6-1.5h1.7V4c-.3 0-1.4-.1-2.6-.1-2.6 0-4.4 1.6-4.4 4.5V10H7v3h2.7v8h3.8z"/></svg>
                    </a>
                    <a href="#" aria-label="Instagram" class="inline-flex h-9 w-9 items-center justify-center rounded-full transition-colors" style="background:rgba(255,255,255,0.1);" onmouseover="this.style.background='rgba(255,255,255,0.2)'" onmouseout="this.style.background='rgba(255,255,255,0.1)'">
                        <svg viewBox="0 0 24 24" class="h-4 w-4" fill="currentColor"><path d="M12 7a5 5 0 100 10 5 5 0 000-10zm0 8.3A3.3 3.3 0 1112 8.7a3.3 3.3 0 010 6.6zm6.3-8.5a1.2 1.2 0 11-2.4 0 1.2 1.2 0 012.4 0zM12 2.9c2.9 0 3.3 0 4.5.1 1 .1 1.6.2 2 .4.5.2.9.4 1.3.8.4.4.6.8.8 1.3.2.4.3 1 .4 2 .1 1.2.1 1.6.1 4.5s0 3.3-.1 4.5c-.1 1-.2 1.6-.4 2a3.8 3.8 0 01-2.1 2.1c-.4.2-1 .3-2 .4-1.2.1-1.6.1-4.5.1s-3.3 0-4.5-.1c-1-.1-1.6-.2-2-.4a3.8 3.8 0 01-2.1-2.1c-.2-.4-.3-1-.4-2C2.9 15.3 2.9 14.9 2.9 12s0-3.3.1-4.5c.1-1 .2-1.6.4-2 .2-.5.4-.9.8-1.3.4-.4.8-.6 1.3-.8.4-.2 1-.3 2-.4 1.2-.1 1.6-.1 4.5-.1zm0-1.7c-2.9 0-3.3 0-4.6.1-1.3.1-2.2.3-3 .6-.8.3-1.5.7-2.1 1.3A5.5 5.5 0 001.2 5.3c-.3.8-.5 1.7-.6 3C.5 9.6.5 10 .5 12c0 2 .1 2.4.1 3.7.1 1.3.3 2.2.6 3 .3.8.7 1.5 1.3 2.1.6.6 1.3 1 2.1 1.3.8.3 1.7.5 3 .6 1.3.1 1.7.1 3.7.1s2.4-.1 3.7-.1c1.3-.1 2.2-.3 3-.6.8-.3 1.5-.7 2.1-1.3.6-.6 1-1.3 1.3-2.1.3-.8.5-1.7.6-3 .1-1.3.1-1.7.1-3.7s-.1-2.4-.1-3.7c-.1-1.3-.3-2.2-.6-3-.3-.8-.7-1.5-1.3-2.1-.6-.6-1.3-1-2.1-1.3-.8-.3-1.7-.5-3-.6-1.3-.1-1.7-.1-3.7-.1z"/></svg>
                    </a>
                    <a href="https://wa.me/6282183948148" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp" class="inline-flex h-9 w-9 items-center justify-center rounded-full transition-colors" style="background:rgba(255,255,255,0.1);" onmouseover="this.style.background='rgba(255,255,255,0.2)'" onmouseout="this.style.background='rgba(255,255,255,0.1)'">
                        <svg viewBox="0 0 24 24" class="h-4 w-4" fill="currentColor"><path d="M12 2a9.9 9.9 0 00-8.4 15.1L2 22l5-1.5A9.9 9.9 0 1012 2zm0 18a8.1 8.1 0 01-4.2-1.2l-.3-.2-3 .9.9-2.9-.2-.3A8.1 8.1 0 1112 20zm4.5-6.1c-.2-.1-1.3-.6-1.5-.7-.2-.1-.3-.1-.4.1-.1.2-.6.7-.7.8-.1.1-.2.1-.4 0-1.1-.5-1.8-1-2.5-2.2-.2-.2 0-.3.1-.5.1-.1.2-.2.3-.3.1-.1.1-.2.2-.3.1-.1 0-.3 0-.4 0-.1-.4-1.1-.6-1.6-.2-.4-.3-.4-.4-.4h-.4c-.1 0-.4.1-.6.3-.2.2-.8.7-.8 1.7s.8 2 1 2.3c.1.2 1.4 2.2 3.4 3.1 2 .9 2 .6 2.4.6.4 0 1.3-.5 1.4-1 .2-.5.2-1 .1-1 0-.1-.2-.2-.4-.3z"/></svg>
                    </a>
                </div>
            </div>

            {{-- 3-column grid --}}
            <div class="grid gap-8 pt-10 md:grid-cols-3" style="border-top:1px solid rgba(255,255,255,0.15);">
                <section>
                    <h2 class="mb-4 text-xs font-semibold uppercase tracking-widest" style="color:#13ec6d;">Get In Touch</h2>
                    <ul class="space-y-2.5 text-sm leading-relaxed" style="color:rgba(255,255,255,0.8);">
                        <li class="flex items-center gap-2">
                            <svg class="h-3.5 w-3.5 shrink-0" style="color:#13ec6d;" viewBox="0 0 20 20" fill="currentColor"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/></svg>
                            +62721 8050354
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="h-3.5 w-3.5 shrink-0" style="color:#13ec6d;" viewBox="0 0 20 20" fill="currentColor"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/></svg>
                            +6282183948148
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="h-3.5 w-3.5 shrink-0" style="color:#13ec6d;" viewBox="0 0 20 20" fill="currentColor"><path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/><path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/></svg>
                            pt.tsalampung@gmail.com
                        </li>
                    </ul>
                </section>

                <section>
                    <h2 class="mb-4 text-xs font-semibold uppercase tracking-widest" style="color:#13ec6d;">Address</h2>
                    <p class="text-sm leading-relaxed" style="color:rgba(255,255,255,0.8);">
                        JL. Raden Imba Kusumaratu, NO: 22, RT: 005, Lk.I,<br>
                        Sukadana Ham, Tanjung Karang Barat,<br>
                        Bandar Lampung, Lampung, Indonesia.
                    </p>
                    <div class="mt-5">
                        <h2 class="mb-3 text-xs font-semibold uppercase tracking-widest" style="color:#13ec6d;">Navigasi</h2>
                        <ul class="space-y-1.5 text-sm" style="color:rgba(255,255,255,0.8);">
                            <li><a href="{{ route('pembeli.dashboard') }}" class="hover:text-white transition-colors">Beranda</a></li>
                            <li><a href="{{ route('pembeli.produk.index') }}" class="hover:text-white transition-colors">Katalog</a></li>
                            <li><a href="{{ route('pembeli.pesanan.index') }}" class="hover:text-white transition-colors">Pesanan</a></li>
                            <li><a href="{{ route('pembeli.profile.show') }}" class="hover:text-white transition-colors">Profil</a></li>
                        </ul>
                    </div>
                </section>

                <section>
                    <h2 class="mb-4 text-xs font-semibold uppercase tracking-widest" style="color:#13ec6d;">Find Us</h2>
                    <div class="overflow-hidden rounded-xl" style="border:1px solid rgba(255,255,255,0.15);">
                        <iframe
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            src="https://maps.google.com/maps?q=JL.%20Raden%20Imba%20Kusumaratu%20NO%2022%20Bandar%20Lampung&t=&z=13&ie=UTF8&iwloc=&output=embed"
                            title="Tunas Sejahtera Adhi Perkasa map"
                            class="block h-44 w-full border-0">
                        </iframe>
                    </div>
                </section>
            </div>

            {{-- Bottom bar --}}
            <div class="mt-8 flex flex-col items-center justify-between gap-2 pt-6 text-xs sm:flex-row" style="border-top:1px solid rgba(255,255,255,0.15); color:rgba(255,255,255,0.5);">
                <p>&copy; {{ now()->year }} Tunas Sejahtera Adhi Perkasa. All rights reserved.</p>
                <p>Livestock Breeding Center & Export-Import Specialist</p>
            </div>
        </div>
    </footer>

    @endif

    {{-- WA Floating Button --}}
    <a href="https://wa.me/6282183948148"
       target="_blank"
       rel="noopener noreferrer"
       aria-label="Chat via WhatsApp"
       class="fixed bottom-6 right-6 z-50 flex h-14 w-14 items-center justify-center rounded-full shadow-lg transition-transform duration-200 hover:scale-110 hover:shadow-xl"
       style="background-color:#25D366;">
        <svg viewBox="0 0 24 24" class="h-7 w-7" fill="white" aria-hidden="true">
            <path d="M12 2a9.9 9.9 0 00-8.4 15.1L2 22l5-1.5A9.9 9.9 0 1012 2zm0 18a8.1 8.1 0 01-4.2-1.2l-.3-.2-3 .9.9-2.9-.2-.3A8.1 8.1 0 1112 20zm4.5-6.1c-.2-.1-1.3-.6-1.5-.7-.2-.1-.3-.1-.4.1-.1.2-.6.7-.7.8-.1.1-.2.1-.4 0-1.1-.5-1.8-1-2.5-2.2-.2-.2 0-.3.1-.5.1-.1.2-.2.3-.3.1-.1.1-.2.2-.3.1-.1 0-.3 0-.4 0-.1-.4-1.1-.6-1.6-.2-.4-.3-.4-.4-.4h-.4c-.1 0-.4.1-.6.3-.2.2-.8.7-.8 1.7s.8 2 1 2.3c.1.2 1.4 2.2 3.4 3.1 2 .9 2 .6 2.4.6.4 0 1.3-.5 1.4-1 .2-.5.2-1 .1-1 0-.1-.2-.2-.4-.3z"/>
        </svg>
    </a>

    <!-- Logout Form -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Scripts -->
    <script>
        // Dark Mode Toggle
        function toggleDarkMode() {
            const html = document.documentElement;
            const icon = document.querySelector('.dark-mode-icon');

            if (html.classList.contains('dark')) {
                html.classList.remove('dark');
                html.classList.add('light');
                icon.textContent = 'light_mode';
                localStorage.setItem('theme', 'light');
            } else {
                html.classList.remove('light');
                html.classList.add('dark');
                icon.textContent = 'dark_mode';
                localStorage.setItem('theme', 'dark');
            }
        }

        // Load Theme on Page Load
        (function () {
            const theme = localStorage.getItem('theme') || 'light';
            const html = document.documentElement;
            const icon = document.querySelector('.dark-mode-icon');

            html.classList.remove('light', 'dark');
            html.classList.add(theme);

            if (icon) {
                icon.textContent = theme === 'dark' ? 'dark_mode' : 'light_mode';
            }
        })();

        // Mobile Menu Toggle
        function toggleMobileMenu() {
            document.querySelector('.mobile-menu').classList.toggle('active');
            document.querySelector('.mobile-overlay').classList.toggle('hidden');
        }

        // Update Cart Count
        window.updateCartCount = function (count) {
            const badge = document.getElementById('cart-count-badge');

            if (badge) {
                if (count > 0) {
                    badge.textContent = count;
                    badge.classList.remove('hidden');
                    badge.style.animation = 'none';
                    setTimeout(() => {
                        badge.style.animation = 'badge-pop 0.3s ease-out';
                    }, 10);
                } else {
                    badge.classList.add('hidden');
                }
            }
        };

        // Fetch Cart Count
        function fetchCartCount() {
            fetch('/pembeli/keranjang/count', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.count !== undefined) {
                        updateCartCount(data.count);
                    }
                })
                .catch(error => console.error('Error fetching cart count:', error));
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function () {
            fetchCartCount();
        });
    </script>

    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: '{{ session("success") }}',
                toast: true,
                position: 'top-end',
                timer: 2000,
                showConfirmButton: false,
            });
        </script>
    @endif

    @stack('scripts')

    <script>
        (function () {
            const searchInput = document.getElementById('navbar-search-input');
            if (!searchInput) return;

            const dropdown = document.getElementById('navbar-autocomplete-dropdown');
            const results = document.getElementById('navbar-autocomplete-results');
            const footer = document.getElementById('navbar-autocomplete-footer');
            const spinner = document.getElementById('navbar-search-spinner');
            const clearBtn = document.getElementById('navbar-search-clear');
            const queryText = document.getElementById('navbar-query-text');

            let debounceTimer;
            let currentFocus = -1;

            searchInput.addEventListener('input', function () {
                const val = this.value.trim();

                clearBtn.classList.toggle('hidden', val.length === 0);
                spinner.classList.add('hidden');

                if (val.length === 0) { closeDropdown(); return; }

                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    if (val.length >= 2) fetchSuggestions(val);
                    else closeDropdown();
                }, 300);
            });

            function fetchSuggestions(query) {
                spinner.classList.remove('hidden');
                clearBtn.classList.add('hidden');

                fetch(`/pembeli/produk/search/autocomplete?q=${encodeURIComponent(query)}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                    .then(res => res.json())
                    .then(data => {
                        spinner.classList.add('hidden');
                        clearBtn.classList.remove('hidden');
                        renderResults(data, query);
                    })
                    .catch(() => {
                        spinner.classList.add('hidden');
                        clearBtn.classList.remove('hidden');
                        closeDropdown();
                    });
            }

            function renderResults(data, query) {
                currentFocus = -1;

                if (data.length === 0) {
                    results.innerHTML = `
                <div class="px-4 py-6 text-center">
                    <span class="material-symbols-outlined text-gray-300 dark:text-zinc-600 text-4xl mb-2 block">search_off</span>
                    <p class="text-sm text-gray-500 dark:text-zinc-400">Tidak ada hasil untuk "<strong>${query}</strong>"</p>
                </div>`;
                    footer.classList.add('hidden');
                    dropdown.classList.remove('hidden');
                    return;
                }

                let html = '';
                data.forEach((product, index) => {
                    const imageHtml = product.image
                        ? `<img src="${product.image}" alt="${product.name}" class="w-10 h-10 object-cover rounded-lg flex-shrink-0">`
                        : `<div class="w-10 h-10 bg-gray-100 dark:bg-zinc-800 rounded-lg flex items-center justify-center flex-shrink-0">
                       <span class="material-symbols-outlined text-gray-400 text-lg">image</span>
                   </div>`;

                    const categoryHtml = product.sub_category
                        ? `<span class="text-blue-500">${product.category}</span> › <span class="text-purple-500">${product.sub_category}</span>`
                        : `<span class="text-blue-500">${product.category}</span>`;

                    const highlighted = product.name.replace(
                        new RegExp(`(${query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'gi'),
                        '<mark class="bg-primary/20 text-primary rounded px-0.5">$1</mark>'
                    );

                    html += `
                <a href="${product.url}"
                   class="navbar-autocomplete-item flex items-center gap-3 px-4 py-3 hover:bg-gray-50 dark:hover:bg-zinc-800 transition-colors border-b border-gray-50 dark:border-zinc-800/50 last:border-0"
                   data-index="${index}">
                    ${imageHtml}
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-[#0d1b13] dark:text-white truncate">${highlighted}</p>
                        <p class="text-xs text-gray-500 dark:text-zinc-400 mt-0.5">${categoryHtml}</p>
                    </div>
                    <p class="text-xs font-bold text-primary flex-shrink-0">${product.price}</p>
                </a>`;
                });

                results.innerHTML = html;
                queryText.textContent = query;
                footer.classList.remove('hidden');
                dropdown.classList.remove('hidden');
            }

            // Navigasi keyboard
            searchInput.addEventListener('keydown', function (e) {
                const items = dropdown.querySelectorAll('.navbar-autocomplete-item');

                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    currentFocus = Math.min(currentFocus + 1, items.length - 1);
                    highlightItem(items);
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    currentFocus = Math.max(currentFocus - 1, -1);
                    highlightItem(items);
                } else if (e.key === 'Enter') {
                    if (currentFocus >= 0 && items[currentFocus]) {
                        e.preventDefault();
                        items[currentFocus].click();
                    } else {
                        submitNavbarSearch();
                    }
                } else if (e.key === 'Escape') {
                    closeDropdown();
                }
            });

            function highlightItem(items) {
                items.forEach((item, i) => {
                    item.classList.toggle('bg-gray-50', i === currentFocus);
                    item.classList.toggle('dark:bg-zinc-800', i === currentFocus);
                });
                if (currentFocus >= 0 && items[currentFocus]) {
                    items[currentFocus].scrollIntoView({ block: 'nearest' });
                }
            }

            function closeDropdown() {
                dropdown.classList.add('hidden');
                results.innerHTML = '';
                footer.classList.add('hidden');
                currentFocus = -1;
            }

            window.submitNavbarSearch = function () {
                document.getElementById('navbar-search-form').submit();
            }

            window.clearNavbarSearch = function () {
                searchInput.value = '';
                clearBtn.classList.add('hidden');
                closeDropdown();
                searchInput.focus();
            }

            // Tutup saat klik di luar
            document.addEventListener('click', function (e) {
                if (!document.getElementById('navbar-autocomplete-wrapper').contains(e.target)) {
                    closeDropdown();
                }
            });

            // Buka lagi saat focus
            searchInput.addEventListener('focus', function () {
                if (this.value.trim().length >= 2) fetchSuggestions(this.value.trim());
            });
        })();
    </script>

    {{-- Cegah halaman muncul dari cache browser setelah logout --}}
    <script>
        window.addEventListener('pageshow', function (e) {
            if (e.persisted) {
                window.location.reload();
            }
        });
    </script>

</body>

</html>