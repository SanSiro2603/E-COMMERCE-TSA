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
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

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
            0% { transform: scale(0); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }
        .cart-badge {
            animation: badge-pop 0.3s ease-out;
        }
    </style>
</head>

<body class="bg-background-light dark:bg-background-dark text-[#0d1b13] dark:text-white transition-colors duration-300">

    <!-- Header / Navbar -->
    <header class="sticky top-0 z-50 bg-white/80 dark:bg-background-dark/80 backdrop-blur-md border-b border-[#e7f3ec] dark:border-primary/20">
        <div class="max-w-[1280px] mx-auto px-4 md:px-10 py-3 flex items-center justify-between gap-4">
            
            <!-- Logo -->
            <div class="flex items-center gap-3 shrink-0">
                <!-- Mobile Menu Toggle -->
                <button onclick="toggleMobileMenu()" class="md:hidden text-[#0d1b13] dark:text-white hover:text-primary transition-colors">
                    <span class="material-symbols-outlined text-3xl">menu</span>
                </button>

                <a href="{{ route('pembeli.dashboard') }}" class="flex items-center gap-3">
                    <div class="size-10 bg-primary rounded-lg flex items-center justify-center text-white">
                        <span class="material-symbols-outlined text-2xl">agriculture</span>
                    </div>
                    <h2 class="hidden md:block text-[#0d1b13] dark:text-white text-lg font-extrabold leading-tight tracking-tight">
                        Tunas Sejahtera<br/>
                        <span class="text-primary/80 text-sm">Adi Perkasa</span>
                    </h2>
                </a>
            </div>

            <!-- Search Bar (Desktop Only) -->
            <div class="hidden lg:block flex-1 max-w-xl">
                <form action="{{ route('pembeli.produk.index') }}" method="GET">
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-primary/70">
                            <span class="material-symbols-outlined">search</span>
                        </div>
                        <input 
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            class="block w-full pl-10 pr-3 py-2 border-none bg-[#e7f3ec] dark:bg-primary/10 rounded-lg focus:ring-2 focus:ring-primary/50 text-sm placeholder:text-primary/60 text-[#0d1b13] dark:text-white" 
                            placeholder="Cari Produk Kami Disini...">
                    </div>
                </form>
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-2 md:gap-4 shrink-0">
                
                <!-- Search Icon (Mobile) -->
                <a href="{{ route('pembeli.produk.index') }}" class="lg:hidden p-2 hover:bg-primary/10 rounded-lg text-[#0d1b13] dark:text-white">
                    <span class="material-symbols-outlined">search</span>
                </a>

                <!-- Dark Mode Toggle -->
                <button onclick="toggleDarkMode()" class="p-2 hover:bg-primary/10 rounded-lg text-[#0d1b13] dark:text-white transition-colors">
                    <span class="material-symbols-outlined dark-mode-icon">light_mode</span>
                </button>

                <!-- Cart with Badge -->
                @php
                    $cartCount = 0;
                    if(session('cart')) {
                        $cartCount = array_sum(array_column(session('cart'), 'quantity'));
                    }
                @endphp
                <a href="{{ route('pembeli.keranjang.index') }}" class="relative p-2 hover:bg-primary/10 rounded-lg text-[#0d1b13] dark:text-white cursor-pointer transition-colors">
                    <span class="material-symbols-outlined">shopping_cart</span>
                    <span id="cart-count-badge" class="cart-badge absolute top-1 right-1 flex h-4 w-4 items-center justify-center rounded-full bg-primary text-[10px] font-bold text-white {{ $cartCount > 0 ? '' : 'hidden' }}">
                        {{ $cartCount }}
                    </span>
                </a>

                <!-- Profile Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center gap-3 pl-2 border-l border-gray-200 dark:border-primary/20">
                        <div class="hidden lg:block text-right">
                            <p class="text-xs font-bold leading-none text-[#0d1b13] dark:text-white">{{ Str::limit(auth()->user()->name, 15) }}</p>
                            <p class="text-[10px] text-primary font-medium">Mitra Utama</p>
                        </div>
                        <div class="size-10 rounded-full bg-primary/20 border-2 border-primary overflow-hidden">
                            <img 
                                src="{{ auth()->user()->profile_photo ? asset('storage/' . auth()->user()->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=13ec6d&color=fff' }}"
                                alt="{{ auth()->user()->name }}" 
                                class="w-full h-full object-cover">
                        </div>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="open" @click.away="open = false" x-transition
                         class="absolute right-0 mt-2 w-56 bg-white dark:bg-[#0d1b13] rounded-lg shadow-lg border border-gray-200 dark:border-primary/20 py-2 z-50">
                        
                        <div class="px-4 py-3 border-b border-gray-200 dark:border-primary/20">
                            <p class="text-sm font-semibold text-[#0d1b13] dark:text-white">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ auth()->user()->email }}</p>
                        </div>

                        <a href="{{ route('pembeli.profil.edit') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-[#0d1b13] dark:text-white hover:bg-primary/10">
                            <span class="material-symbols-outlined text-lg">person</span>
                            Profil Saya
                        </a>
                        <a href="{{ route('pembeli.pesanan.index') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-[#0d1b13] dark:text-white hover:bg-primary/10">
                            <span class="material-symbols-outlined text-lg">receipt_long</span>
                            Pesanan Saya
                        </a>
                        <a href="{{ route('pembeli.alamat.index') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-[#0d1b13] dark:text-white hover:bg-primary/10">
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
    <div class="md:hidden mobile-menu fixed inset-y-0 left-0 w-64 bg-white dark:bg-background-dark shadow-xl z-40 overflow-y-auto">
        <div class="p-4">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-2">
                    <div class="w-10 h-10 bg-primary rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-white text-xl">agriculture</span>
                    </div>
                    <span class="text-xl font-bold text-[#0d1b13] dark:text-white">Menu</span>
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
                        ['icon' => 'person', 'label' => 'Profil', 'route' => 'pembeli.profil.edit'],
                    ];
                @endphp
                
                @foreach($menu as $item)
                    @php $active = request()->routeIs($item['route'].'*'); @endphp
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
    <footer class="bg-white dark:bg-background-dark border-t border-[#e7f3ec] dark:border-primary/20 py-12">
        <div class="max-w-[1280px] mx-auto px-4 md:px-10 grid grid-cols-1 md:grid-cols-4 gap-10">
            
            <!-- About -->
            <div>
                <div class="flex items-center gap-3 mb-6">
                    <div class="size-8 bg-primary rounded flex items-center justify-center text-white">
                        <span class="material-symbols-outlined text-xl">agriculture</span>
                    </div>
                    <h2 class="text-[#0d1b13] dark:text-white text-lg font-extrabold leading-tight">Tunas Sejahtera</h2>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
                    Platform e-commerce ternak terdepan di Indonesia. Menghubungkan peternak lokal dengan pembeli melalui sistem yang aman dan transparan.
                </p>
            </div>

            <!-- Navigation -->
            <div>
                <h4 class="font-bold mb-6 text-[#0d1b13] dark:text-white">Navigasi</h4>
                <ul class="space-y-4 text-sm text-gray-500 dark:text-gray-400">
                    <li><a href="{{ route('pembeli.dashboard') }}" class="hover:text-primary transition-colors">Beranda</a></li>
                    <li><a href="{{ route('pembeli.produk.index') }}" class="hover:text-primary transition-colors">Katalog</a></li>
                    <li><a href="{{ route('pembeli.pesanan.index') }}" class="hover:text-primary transition-colors">Pesanan</a></li>
                    <li><a href="{{ route('pembeli.profil.edit') }}" class="hover:text-primary transition-colors">Profil</a></li>
                </ul>
            </div>

            <!-- Help -->
            <div>
                <h4 class="font-bold mb-6 text-[#0d1b13] dark:text-white">Bantuan</h4>
                <ul class="space-y-4 text-sm text-gray-500 dark:text-gray-400">
                    <li><a href="#" class="hover:text-primary transition-colors">Cara Pembelian</a></li>
                    <li><a href="#" class="hover:text-primary transition-colors">Pengiriman Ternak</a></li>
                    <li><a href="#" class="hover:text-primary transition-colors">Syarat & Ketentuan</a></li>
                    <li><a href="#" class="hover:text-primary transition-colors">Kebijakan Privasi</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div>
                <h4 class="font-bold mb-6 text-[#0d1b13] dark:text-white">Hubungi Kami</h4>
                <div class="space-y-4 text-sm text-gray-500 dark:text-gray-400">
                    <p class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-sm">mail</span>
                        support@tsap-ternak.id
                    </p>
                    <p class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-sm">call</span>
                        +62 812-3456-7890
                    </p>
                    <p class="flex items-start gap-2">
                        <span class="material-symbols-outlined text-primary text-sm">location_on</span>
                        Jl. Agribisnis No. 45,<br/>Jakarta Selatan, Indonesia
                    </p>
                </div>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="max-w-[1280px] mx-auto px-4 md:px-10 mt-12 pt-8 border-t border-gray-100 dark:border-primary/10 flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-xs text-gray-400">© 2024 Tunas Sejahtera Adi Perkasa. All rights reserved.</p>
            <div class="flex gap-6">
                <span class="material-symbols-outlined text-gray-400 hover:text-primary cursor-pointer transition-colors">public</span>
                <span class="material-symbols-outlined text-gray-400 hover:text-primary cursor-pointer transition-colors">share</span>
            </div>
        </div>
    </footer>

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
        (function() {
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
        window.updateCartCount = function(count) {
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
        document.addEventListener('DOMContentLoaded', function() {
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
</body>
</html>