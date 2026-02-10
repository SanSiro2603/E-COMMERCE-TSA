{{-- resources/views/pembeli/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Pembeli - E-Commerce TSA')</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;700;900&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">

    <!-- Tailwind Config -->
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

        * { transition: background-color 0.2s ease, color 0.2s ease; }
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #7BB661; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #72e236; }

        .mobile-menu {
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }
        .mobile-menu.active {
            transform: translateX(0);
        }

        /* Cart Badge Animation */
        .cart-badge {
            animation: badge-pop 0.3s ease-out;
        }
        
        @keyframes badge-pop {
            0% { transform: scale(0); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }

        /* Active nav link indicator */
        .nav-link-active::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(to right, #7BB661, #72e236);
            border-radius: 2px 2px 0 0;
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
                    <button onclick="toggleMobileMenu()" class="lg:hidden text-gray-600 dark:text-zinc-300 hover:text-soft-green transition-colors">
                        <span class="material-symbols-outlined text-3xl">menu</span>
                    </button>

                    <a href="{{ route('pembeli.dashboard') }}" class="flex items-center gap-2">
                        <div class="w-10 h-10 bg-gradient-to-br from-soft-green to-primary rounded-lg flex items-center justify-center">
                            <span class="material-symbols-outlined text-white text-xl">eco</span>
                        </div>
                        <span class="text-xl font-bold text-gray-900 dark:text-white font-be-vietnam hidden sm:block">E-Commerce TSA</span>
                    </a>
                </div>

                {{-- ===== FEATURE NAV LINKS (Desktop) ===== --}}
                @php
                    $cartCount = 0;
                    if(session('cart')) {
                        $cartCount = array_sum(array_column(session('cart'), 'quantity'));
                    }

                    $menu = [
                        ['icon' => 'home',         'label' => 'Beranda',    'route' => 'pembeli.dashboard'],
                        ['icon' => 'inventory_2',  'label' => 'Produk',     'route' => 'pembeli.produk.index'],
                        ['icon' => 'receipt_long', 'label' => 'Pesanan',    'route' => 'pembeli.pesanan.index'],
                        ['icon' => 'location_on',  'label' => 'Alamat',     'route' => 'pembeli.alamat.index'],
                        ['icon' => 'person',       'label' => 'Profil',     'route' => 'pembeli.profil.edit'],
                    ];
                @endphp

                <div class="hidden lg:flex items-center gap-1">
                    @foreach($menu as $item)
                        @php $active = request()->routeIs($item['route'].'*'); @endphp
                        <a href="{{ route($item['route']) }}"
                           class="relative flex items-center gap-1.5 px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200
                           {{ $active
                               ? 'nav-link-active text-soft-green bg-soft-green/10'
                               : 'text-gray-600 dark:text-zinc-300 hover:text-soft-green hover:bg-gray-100 dark:hover:bg-zinc-800' }}">
                            <span class="material-symbols-outlined text-[18px]">{{ $item['icon'] }}</span>
                            <span>{{ $item['label'] }}</span>
                        </a>
                    @endforeach
                </div>

                <!-- Right Side Actions -->
                <div class="flex items-center gap-2 sm:gap-4">
                    <!-- Dark Mode Toggle -->
                    <button onclick="toggleDarkMode()" 
                            class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-zinc-800 text-gray-600 dark:text-zinc-300 transition-colors">
                        <span class="material-symbols-outlined dark-mode-icon">light_mode</span>
                    </button>

                    <!-- Cart with Real-time Badge -->
                    <a href="{{ route('pembeli.keranjang.index') }}" 
                       class="relative p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-zinc-800 text-gray-600 dark:text-zinc-300 transition-colors group">
                        <span class="material-symbols-outlined group-hover:scale-110 transition-transform">shopping_cart</span>
                        
                        <span id="cart-count-badge" 
                              class="cart-badge absolute -top-1 -right-1 min-w-[20px] h-5 px-1.5 bg-gradient-to-r from-red-500 to-red-600 text-white text-xs rounded-full flex items-center justify-center font-bold shadow-lg
                                     {{ $cartCount > 0 ? '' : 'hidden' }}">
                            {{ $cartCount }}
                        </span>
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
                        <div x-show="open" @click.away="open = false" x-transition
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
                            <a href="{{ route('pembeli.alamat.index') }}" 
                               class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-zinc-300 hover:bg-gray-100 dark:hover:bg-zinc-700">
                                <span class="material-symbols-outlined text-lg">location_on</span>
                                Alamat Saya
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

        </div>
    </nav>

    <div class="flex relative">
        <!-- Mobile Sidebar -->
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
                    @foreach($menu as $item)
                        @php $active = request()->routeIs($item['route'].'*'); @endphp
                        <a href="{{ route($item['route']) }}"
                           class="flex items-center gap-3 px-4 py-3 rounded-lg {{ $active ? 'bg-soft-green/10 text-soft-green font-semibold' : 'text-gray-700 dark:text-zinc-300 hover:bg-gray-100 dark:hover:bg-zinc-800' }}">
                            <span class="material-symbols-outlined">{{ $item['icon'] }}</span>
                            <span class="flex-1">{{ $item['label'] }}</span>
                        </a>
                    @endforeach
                </nav>
            </div>
        </div>

        <!-- Mobile Overlay -->
        <div class="lg:hidden fixed inset-0 bg-black/50 z-30 hidden mobile-overlay" onclick="toggleMobileMenu()"></div>

        <!-- Main Content -->
        <main class="flex-1 p-4 lg:p-6 min-h-[calc(100vh-4rem)]">
            @yield('content')
        </main>
    </div>

    <!-- Logout Form -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>

    <!-- Footer -->
    <footer class="px-4 lg:px-8 py-6 border-t border-gray-200 dark:border-zinc-800 bg-white dark:bg-zinc-900">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-gray-500 dark:text-zinc-400">
                <p>© 2025 E-Commerce TSA. All rights reserved.</p>
                <div class="flex items-center gap-4">
                    <a href="#" class="hover:text-soft-green transition-colors">Dokumentasi</a>
                    <span>•</span>
                    <a href="#" class="hover:text-soft-green transition-colors">Support</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    @stack('scripts')

    <!-- Alpine.js -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        // Dark Mode Toggle
        function toggleDarkMode() {
            const html = document.documentElement;
            const icon = document.querySelector('.dark-mode-icon');
            if (html.classList.contains('dark')) {
                html.classList.remove('dark'); html.classList.add('light');
                icon.textContent = 'light_mode';
                localStorage.setItem('theme', 'light');
            } else {
                html.classList.remove('light'); html.classList.add('dark');
                icon.textContent = 'dark_mode';
                localStorage.setItem('theme', 'dark');
            }
        }

        // Load Theme
        (function () {
            const theme = localStorage.getItem('theme') || 'light';
            const html = document.documentElement;
            const icon = document.querySelector('.dark-mode-icon');
            html.classList.remove('light', 'dark');
            html.classList.add(theme);
            icon.textContent = theme === 'dark' ? 'dark_mode' : 'light_mode';
        })();

        // Mobile Menu Toggle
        function toggleMobileMenu() {
            document.querySelector('.mobile-menu').classList.toggle('active');
            document.querySelector('.mobile-overlay').classList.toggle('hidden');
        }

        // Update Cart Count (Real-time)
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

        // Fetch and update cart count on page load
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

        // Initialize cart count on page load
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

</body>
</html>