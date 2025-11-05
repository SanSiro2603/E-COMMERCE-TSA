{{-- resources/views/pembeli/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="id" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Pembeli - Lembah Hijau')</title>

    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;700;900&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">

    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#72e236",
                        "soft-green": "#7BB661",
                        "warm-yellow": "#FFD54F",
                        "charcoal": "#333333",
                        "background-light": "#FDFBF5",
                        "background-dark": "#172111",
                    },
                    fontFamily: {
                        "display": ["Poppins", "sans-serif"],
                        "be-vietnam": ["Be Vietnam Pro", "sans-serif"]
                    }
                }
            }
        }
    </script>

    <style>
        .gradient-button {
            background-image: linear-gradient(to right, #8fcf72, #7BB661);
        }
        .gradient-button:hover {
            background-image: linear-gradient(to right, #9bd980, #8fcf72);
        }
        
        /* Smooth transitions */
        * {
            transition: background-color 0.2s ease, color 0.2s ease;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: #7BB661;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #72e236;
        }

        
    



        /* Mobile menu animation */
        .mobile-menu {
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }
        .mobile-menu.active {
            transform: translateX(0);
        }
    </style>
</head>
<body class="bg-gray-50 dark:bg-zinc-950 font-display min-h-screen">

    <!-- Top Navbar -->
    <nav class="bg-white dark:bg-zinc-900 shadow-sm border-b border-gray-200 dark:border-zinc-800 sticky top-0 z-50">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <!-- Logo & Mobile Menu Button -->
                <div class="flex items-center gap-4">
                    <!-- Mobile Menu Toggle -->
                    <button onclick="toggleMobileMenu()" class="lg:hidden text-gray-600 dark:text-zinc-300 hover:text-soft-green transition-colors">
                        <span class="material-symbols-outlined text-3xl">menu</span>
                    </button>
                    
                    <!-- Logo -->
                    <a href="{{ route('pembeli.dashboard') }}" class="flex items-center gap-2">
                        <div class="w-10 h-10 bg-gradient-to-br from-soft-green to-primary rounded-lg flex items-center justify-center">
                            <span class="material-symbols-outlined text-white text-xl">eco</span>
                        </div>
                        <span class="text-xl font-bold text-gray-900 dark:text-white font-be-vietnam hidden sm:block">Lembah Hijau</span>
                    </a>
                </div>

                {{-- <!-- Center Search (Desktop) -->
                <div class="hidden md:flex flex-1 max-w-2xl mx-8">
                    <div class="relative w-full">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-zinc-500">search</span>
                        <input type="search" 
                               placeholder="Cari produk..." 
                               class="w-full pl-10 pr-4 py-2 bg-gray-100 dark:bg-zinc-800 border-0 rounded-lg text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-zinc-400 focus:ring-2 focus:ring-soft-green focus:bg-white dark:focus:bg-zinc-700 transition-all">
                    </div>
                </div> --}}

                <!-- Right Side Actions -->
                <div class="flex items-center gap-2 sm:gap-4">
                    <!-- Dark Mode Toggle -->
                    <button onclick="toggleDarkMode()" 
                            class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-zinc-800 text-gray-600 dark:text-zinc-300 transition-colors">
                        <span class="material-symbols-outlined dark-mode-icon">light_mode</span>
                    </button>

                    <!-- Cart -->
                    <a href="{{ route('pembeli.keranjang.index') }}" 
                       class="relative p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-zinc-800 text-gray-600 dark:text-zinc-300 transition-colors">
                        <span class="material-symbols-outlined">shopping_cart</span>
                        @if(session('cart') && count(session('cart')) > 0)
                            <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center font-bold">
                                {{ count(session('cart')) }}
                            </span>
                        @endif
                    </a>

                    <!-- User Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" 
                                class="flex items-center gap-2 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-zinc-800 transition-colors">
                            <img src="{{ auth()->user()->profile_photo ? asset('storage/' . auth()->user()->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=7BB661&color=fff' }}"
                                 class="w-8 h-8 rounded-full object-cover border-2 border-soft-green">
                            <span class="hidden md:block text-sm font-medium text-gray-900 dark:text-white">{{ Str::limit(auth()->user()->name, 15) }}</span>
                            <span class="material-symbols-outlined text-gray-600 dark:text-zinc-300 text-lg">expand_more</span>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="open" 
                             @click.away="open = false"
                             x-transition
                             class="absolute right-0 mt-2 w-56 bg-white dark:bg-zinc-800 rounded-lg shadow-lg border border-gray-200 dark:border-zinc-700 py-2 z-50">
                            <div class="px-4 py-3 border-b border-gray-200 dark:border-zinc-700">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-zinc-400 mt-0.5">{{ auth()->user()->email }}</p>
                            </div>
                            <a href="{{ route('pembeli.profil.edit') }}" 
                               class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-zinc-300 hover:bg-gray-100 dark:hover:bg-zinc-700">
                                <span class="material-symbols-outlined text-lg">person</span>
                                Profil Saya
                            </a>
                            <a href="{{ route('pembeli.pesanan.index') }}" 
                               class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-zinc-300 hover:bg-gray-100 dark:hover:bg-zinc-700">
                                <span class="material-symbols-outlined text-lg">receipt_long</span>
                                Pesanan Saya
                            </a>
                            <div class="border-t border-gray-200 dark:border-zinc-700 mt-2 pt-2">
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

            <!-- Mobile Search -->
            <div class="md:hidden pb-3">
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-zinc-500">search</span>
                    <input type="search" 
                           placeholder="Cari produk..." 
                           class="w-full pl-10 pr-4 py-2 bg-gray-100 dark:bg-zinc-800 border-0 rounded-lg text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-zinc-400 focus:ring-2 focus:ring-soft-green focus:bg-white dark:focus:bg-zinc-700">
                </div>
            </div>
        </div>
    </nav>

    <div class="flex relative">
        <!-- Desktop Sidebar -->
       <!-- Desktop Sidebar -->
<aside class="hidden lg:flex flex-col w-56 bg-white dark:bg-zinc-900 min-h-[calc(100vh-4rem)] border-r border-gray-200 dark:border-zinc-800 sticky top-16">
    <nav class="flex-1 py-4 space-y-1">
        @php
            $menu = [
                ['icon' => 'home', 'label' => 'Beranda', 'route' => 'pembeli.dashboard'],
                ['icon' => 'inventory_2', 'label' => 'Produk', 'route' => 'pembeli.produk.index'],
                ['icon' => 'shopping_cart', 'label' => 'Keranjang', 'route' => 'pembeli.keranjang.index'],
                ['icon' => 'receipt_long', 'label' => 'Pesanan', 'route' => 'pembeli.pesanan.index'],
                ['icon' => 'person', 'label' => 'Profil', 'route' => 'pembeli.profil.edit'],
            ];
        @endphp

        @foreach($menu as $item)
            @php $active = request()->routeIs($item['route'].'*'); @endphp
            <a href="{{ route($item['route']) }}"
               class="group relative flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg 
               {{ $active 
                    ? 'text-soft-green bg-soft-green/10 font-semibold' 
                    : 'text-gray-700 dark:text-zinc-300 hover:bg-gray-100 dark:hover:bg-zinc-800' }}
               transition-all duration-200">

                <!-- Indikator hijau aktif -->
                @if($active)
                    <span class="absolute left-0 w-1 h-8 bg-soft-green rounded-r-full"></span>
                @endif

                <span class="material-symbols-outlined text-[20px] 
                    {{ $active ? 'text-soft-green' : 'text-gray-500 dark:text-zinc-400 group-hover:text-soft-green' }}">
                    {{ $item['icon'] }}
                </span>
                <span class="truncate">{{ $item['label'] }}</span>
            </a>
        @endforeach
    </nav>
</aside>

               
        <!-- Mobile Sidebar Overlay -->
        <div class="lg:hidden mobile-menu fixed inset-y-0 left-0 w-64 bg-white dark:bg-zinc-900 shadow-xl z-40 overflow-y-auto">
            <div class="p-4">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-2">
                        <div class="w-10 h-10 bg-gradient-to-br from-soft-green to-primary rounded-lg flex items-center justify-center">
                            <span class="material-symbols-outlined text-white text-xl">eco</span>
                        </div>
                        <span class="text-xl font-bold text-gray-900 dark:text-white font-be-vietnam">Menu</span>
                    </div>
                    <button onclick="toggleMobileMenu()" class="text-gray-600 dark:text-zinc-300">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                <nav class="space-y-1">
                    <a href="{{ route('pembeli.dashboard') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('pembeli.dashboard') ? 'bg-soft-green/10 text-soft-green font-semibold' : 'text-gray-700 dark:text-zinc-300 hover:bg-gray-100 dark:hover:bg-zinc-800' }}">
                        <span class="material-symbols-outlined">home</span>
                        <span>Beranda</span>
                    </a>
                    <a href="{{ route('pembeli.produk.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('pembeli.produk.*') ? 'bg-soft-green/10 text-soft-green font-semibold' : 'text-gray-700 dark:text-zinc-300 hover:bg-gray-100 dark:hover:bg-zinc-800' }}">
                        <span class="material-symbols-outlined">inventory_2</span>
                        <span>Produk</span>
                    </a>
                    <a href="{{ route('pembeli.keranjang.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('pembeli.keranjang.index') ? 'bg-soft-green/10 text-soft-green font-semibold' : 'text-gray-700 dark:text-zinc-300 hover:bg-gray-100 dark:hover:bg-zinc-800' }}">
                        <span class="material-symbols-outlined">shopping_cart</span>
                        <span>Keranjang</span>
                    </a>
                    <a href="{{ route('pembeli.pesanan.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('pembeli.pesanan.index') ? 'bg-soft-green/10 text-soft-green font-semibold' : 'text-gray-700 dark:text-zinc-300 hover:bg-gray-100 dark:hover:bg-zinc-800' }}">
                        <span class="material-symbols-outlined">receipt_long</span>
                        <span>Pesanan Saya</span>
                    </a>
                    <a href="{{ route('pembeli.profil.edit') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('pembeli.profil.*') ? 'bg-soft-green/10 text-soft-green font-semibold' : 'text-gray-700 dark:text-zinc-300 hover:bg-gray-100 dark:hover:bg-zinc-800' }}">
                        <span class="material-symbols-outlined">person</span>
                        <span>Profil</span>
                    </a>
                </nav>
            </div>
        </div>

        <!-- Mobile Overlay Background -->
        <div class="lg:hidden fixed inset-0 bg-black/50 z-30 hidden mobile-overlay" onclick="toggleMobileMenu()"></div>

        <!-- Main Content -->
        <main class="flex-1 p-4 lg:p-6 min-h-[calc(100vh-4rem)]">
            @yield('content')
        </main>
    </div>

    <!-- Logout Form -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>

    <!-- Alpine.js for dropdowns -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

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

        // Load saved theme
        (function() {
            const theme = localStorage.getItem('theme') || 'light';
            const html = document.documentElement;
            const icon = document.querySelector('.dark-mode-icon');
            
            html.classList.remove('light', 'dark');
            html.classList.add(theme);
            icon.textContent = theme === 'dark' ? 'dark_mode' : 'light_mode';
        })();

        // Mobile Menu Toggle
        function toggleMobileMenu() {
            const menu = document.querySelector('.mobile-menu');
            const overlay = document.querySelector('.mobile-overlay');
            
            menu.classList.toggle('active');
            overlay.classList.toggle('hidden');
        }
    </script>
</body>

@stack('scripts')
    <!-- Alpine.js for dropdowns -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

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

        // Load saved theme
        (function() {
            const theme = localStorage.getItem('theme') || 'light';
            const html = document.documentElement;
            const icon = document.querySelector('.dark-mode-icon');
            
            html.classList.remove('light', 'dark');
            html.classList.add(theme);
            icon.textContent = theme === 'dark' ? 'dark_mode' : 'light_mode';
        })();

        // Mobile Menu Toggle
        function toggleMobileMenu() {
            const menu = document.querySelector('.mobile-menu');
            const overlay = document.querySelector('.mobile-overlay');
            
            menu.classList.toggle('active');
            overlay.classList.toggle('hidden');
        }
    </script>

    {{-- Tambahkan baris ini --}}
    @stack('scripts')
</body>
</html>