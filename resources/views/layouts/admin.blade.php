{{-- resources/views/layouts/admin.blade.php --}}
<!DOCTYPE html>
<html lang="id">

<head>
    {{-- ⚡ PERTAMA SEBELUM APAPUN — cegah flash putih saat navigasi --}}
    <script>
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.classList.add('dark');
        }
    </script>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin - E-Commerce TSA')</title>

    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=block"
        rel="stylesheet">

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
                        "background-dark": "#0a0e08",
                    },
                    fontFamily: {
                        "display": ["Poppins", "sans-serif"],
                        "sans": ["Inter", "sans-serif"]
                    }
                }
            }
        }
    </script>

    <style>
        /* Cegah flash warna saat navigasi antar halaman */
        html {
            background-color: #09090b;
        }
        html:not(.dark) {
            background-color: #f9fafb;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            overflow-x: hidden;
            font-family: 'Inter', sans-serif;
        }

        .material-symbols-outlined {
            font-family: 'Material Symbols Outlined';
            font-weight: normal;
            font-style: normal;
            font-size: inherit;
            line-height: 1;
            letter-spacing: normal;
            text-transform: none;
            white-space: nowrap;
            word-wrap: normal;
            direction: ltr;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 1em;
            height: 1em;
            min-width: 1em;
            overflow: hidden;
            -webkit-font-feature-settings: 'liga';
            -webkit-font-smoothing: antialiased;
            font-feature-settings: 'liga';
            font-variation-settings:
                'FILL' 0,
                'wght' 400,
                'GRAD' 0,
                'opsz' 24;
        }

        /* Sidebar Animation */
        .sidebar {
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            backdrop-filter: blur(20px);
        }

        @media (max-width: 1024px) {
            .sidebar.hidden-mobile {
                transform: translateX(-100%);
            }
        }

        /* Menu Item Styling */
        .menu-item {
            position: relative;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .menu-item:hover {
            transform: translateX(2px);
        }

        .menu-item-active {
            background: linear-gradient(135deg, rgba(123, 182, 97, 0.12) 0%, rgba(123, 182, 97, 0.04) 100%);
            color: #7BB661 !important;
        }

        .menu-item-active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 60%;
            background: linear-gradient(180deg, #7BB661 0%, #72e236 100%);
            border-radius: 0 4px 4px 0;
        }

        /* Scrollbar minimal */
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(123, 182, 97, 0.3);
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(123, 182, 97, 0.5);
        }

        /* Card minimal hover */
        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(123, 182, 97, 0.08);
        }

        /* Mobile overlay */
        .mobile-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(4px);
            z-index: 40;
            transition: opacity 0.3s ease;
        }

        .mobile-overlay.active {
            display: block;
            animation: fadeIn 0.3s ease;
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; }
            to   { opacity: 1; }
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-8px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .animate-slide-down {
            animation: slideDown 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Logo animation */
        .logo-icon {
            transition: transform 0.3s ease;
        }

        .logo-container:hover .logo-icon {
            transform: rotate(12deg) scale(1.1);
        }

        /* Glass effect */
        .glass-effect {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .dark .glass-effect {
            background: rgba(24, 24, 27, 0.7);
            border: 1px solid rgba(63, 63, 70, 0.3);
        }

        /* Improved shadows */
        .shadow-soft {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .dark .shadow-soft {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        /* Dark mode improvements */
        .dark {
            color-scheme: dark;
        }

        .dark input::placeholder {
            color: #71717a !important;
        }

        /* Breadcrumb minimal */
        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.813rem;
        }

        /* Sidebar feature image transition */
        #sidebar-feature-image {
            transition: opacity 0.25s ease, transform 0.25s ease;
        }
    </style>

    @stack('styles')
</head>

<body class="bg-gray-50 dark:bg-zinc-950">

    <!-- Mobile Overlay -->
    <div class="mobile-overlay" id="mobileOverlay" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <aside id="sidebar"
        class="sidebar hidden-mobile fixed left-0 top-0 z-50 h-screen w-72 bg-[#03150B] border-r border-green-500/10 flex flex-col shadow-2xl overflow-hidden">

        <!-- Background Glow -->
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(114,226,54,0.15),transparent_40%)] pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-full h-64 bg-gradient-to-t from-green-500/10 to-transparent pointer-events-none"></div>

        <!-- Logo -->
        <div class="relative px-5 py-6 border-b border-white/10 flex items-center gap-3 z-10">
            <div class="w-14 h-14 flex items-center justify-center overflow-hidden flex-shrink-0">
                <img src="{{ asset('images/logo-header.png') }}"
                    alt="Logo TSA"
                    class="h-14 w-14 object-contain">
            </div>
            <div>
                <h1 class="text-white text-[18px] font-bold leading-tight">E-Commerce TSA</h1>
                <p class="text-green-400 text-[11px] uppercase tracking-[2px] font-semibold">Admin Panel</p>
            </div>
        </div>

        @php
            $menu = [
                [
                    'route' => 'admin.dashboard',
                    'icon'  => 'dashboard',
                    'label' => 'Dashboard',
                    'image' => 'images/sidebar/dashboard.png',
                ],
                [
                    'route' => 'admin.categories.index',
                    'icon'  => 'category',
                    'label' => 'Kategori',
                    'image' => 'images/sidebar/kategori.png',
                ],
                [
                    'route' => 'admin.products.index',
                    'icon'  => 'inventory_2',
                    'label' => 'Produk',
                    'image' => 'images/sidebar/produk.png',
                ],
                [
                    'route' => 'admin.orders.index',
                    'icon'  => 'shopping_cart',
                    'label' => 'Pesanan',
                    'image' => 'images/sidebar/manajemen-pesanan.png',
                ],
                [
                    'route' => 'admin.reports.index',
                    'icon'  => 'analytics',
                    'label' => 'Laporan',
                    'image' => 'images/sidebar/laporan.png',
                ],
                [
                    'route' => 'admin.settings.index',
                    'icon'  => 'settings',
                    'label' => 'Pengaturan',
                    'image' => null,
                ],
            ];

            $activeMenu = collect($menu)->first(fn($item) => request()->routeIs($item['route'].'*'))
                ?? $menu[0];
        @endphp

        <!-- Navigation -->
        <nav class="relative flex-shrink-0 px-3 pt-4 pb-2 space-y-2 z-10">
            @foreach ($menu as $item)
                @php
                    // Dashboard dicek exact, yang lain pakai prefix grup
                    if ($item['route'] === 'admin.dashboard') {
                        $active = request()->routeIs('admin.dashboard');
                    } else {
                        $routePrefix = implode('.', array_slice(explode('.', $item['route']), 0, 2));
                        $active = request()->routeIs($routePrefix . '.*');
                    }
                @endphp

                <a href="{{ route($item['route']) }}"
                    class="group relative flex items-center gap-3 px-4 py-3 rounded-none transition-all duration-300 overflow-hidden
                        {{ $active
                            ? 'bg-gradient-to-r from-green-500 to-green-400 text-white shadow-[0_10px_30px_rgba(114,226,54,0.35)]'
                            : 'text-white/75 hover:bg-white/10 hover:text-white' }}">

                    @if ($active)
                        <div class="absolute inset-0 bg-white/10 backdrop-blur-xl pointer-events-none"></div>
                    @endif

                    <div class="relative z-10 flex items-center gap-3">
                        <span class="material-symbols-outlined text-[21px]">{{ $item['icon'] }}</span>
                        <span class="text-[14px] font-semibold tracking-wide">{{ $item['label'] }}</span>
                    </div>
                </a>
            @endforeach
        </nav>

        <!-- Dynamic Illustration -->
        <div class="relative flex-1 min-h-0 flex items-end justify-center px-4 pb-4 z-10 overflow-hidden">
            @if($activeMenu['image'])
                <img
                    id="sidebar-feature-image"
                    src="{{ asset($activeMenu['image']) }}"
                    alt="{{ $activeMenu['label'] }}"
                    class="w-full max-h-full object-contain drop-shadow-2xl">
            @else
                <div id="sidebar-feature-image" class="w-full"></div>
            @endif
        </div>

        <!-- Footer User -->
        <div class="relative flex-shrink-0 px-4 py-4 border-t border-white/10 z-10">
            <div class="flex items-center gap-3 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-xl p-3">

                <div class="w-11 h-11 rounded-full bg-gradient-to-br from-green-400 to-lime-300 text-black font-bold flex items-center justify-center shadow-lg shadow-green-500/30 flex-shrink-0">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>

                <div class="flex-1 min-w-0">
                    <p class="text-white text-[13px] font-semibold truncate">{{ Auth::user()->name }}</p>
                    <p class="text-green-300 text-[10px] uppercase tracking-wider">Admin</p>
                </div>

                <a href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                    class="w-9 h-9 rounded-xl flex items-center justify-center bg-red-600 hover:bg-red-700 text-white transition-all duration-300 flex-shrink-0">
                    <span class="material-symbols-outlined text-[18px]">logout</span>
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="lg:pl-72">
        <!-- Top Navigation Bar -->
        <header class="sticky top-0 z-30 glass-effect shadow-soft">
            <div class="px-4 lg:px-8 py-4">
                <div class="flex items-center justify-between">
                    <!-- Mobile Menu Button -->
                    <button onclick="toggleSidebar()"
                        class="lg:hidden p-2.5 rounded-xl hover:bg-gray-100 dark:hover:bg-zinc-800 transition-all">
                        <span class="material-symbols-outlined text-gray-600 dark:text-zinc-300 text-[22px]">menu</span>
                    </button>

                    <!-- Breadcrumb -->
                    <div class="breadcrumb hidden lg:flex">
                        <span class="text-gray-900 dark:text-white font-semibold">Dashboard</span>
                        <span class="material-symbols-outlined text-[14px] text-gray-300 dark:text-zinc-600">chevron_right</span>
                        <span class="text-gray-500 dark:text-zinc-400">Overview</span>
                    </div>

                    <!-- Right Section -->
                    <div class="flex items-center gap-2">

                        @if(request()->routeIs('admin.dashboard'))
                            <button onclick="openSearch()"
                                    class="p-2.5 rounded-xl hover:bg-gray-100 dark:hover:bg-zinc-800 transition-all flex items-center gap-2">
                                <span class="material-symbols-outlined text-gray-600 dark:text-zinc-300 text-[20px]">search</span>
                                <span class="hidden lg:inline text-xs text-gray-400 dark:text-zinc-500 border border-gray-200 dark:border-zinc-700 px-2 py-0.5 rounded-md">
                                    Ctrl+K
                                </span>
                            </button>
                        @endif

                        <!-- Theme Toggle -->
                        <button onclick="toggleTheme()"
                            class="p-2.5 rounded-xl hover:bg-gray-100 dark:hover:bg-zinc-800 transition-all">
                            <span id="themeIcon"
                                class="material-symbols-outlined text-gray-600 dark:text-zinc-300 text-[20px]">light_mode</span>
                        </button>

                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="p-4 lg:p-8 pb-32 animate-slide-down">
            <div class="max-w-7xl mx-auto">
                @yield('content')
            </div>
        </main>

        <!-- Footer -->
        <footer class="px-4 lg:px-8 py-6 border-t border-gray-100 dark:border-zinc-800/50 bg-white/50 dark:bg-zinc-900/50">
            <div class="max-w-7xl mx-auto">
                <div class="flex flex-col md:flex-row justify-between items-center gap-3 text-[12px] text-gray-500 dark:text-zinc-500">
                    <p class="font-medium">© 2025 E-Commerce TSA. All rights reserved.</p>
                    <div class="flex items-center gap-4">
                        <a href="#" class="hover:text-soft-green transition-colors font-medium">Dokumentasi</a>
                        <span class="text-gray-300 dark:text-zinc-700">•</span>
                        <a href="#" class="hover:text-soft-green transition-colors font-medium">Support</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Chart.js & SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div id="flash-message-config"
         data-success="{{ session('success') }}"
         data-error="{{ session('error') }}"
         hidden></div>

    <script>
        const flashMessageConfig = document.getElementById('flash-message-config').dataset;

        // ── Sidebar Toggle (Mobile) ───────────────────────────────
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobileOverlay');
            sidebar.classList.toggle('hidden-mobile');
            overlay.classList.toggle('active');
        }

        // ── Theme Toggle ──────────────────────────────────────────
        function toggleTheme() {
            const html      = document.documentElement;
            const themeIcon = document.getElementById('themeIcon');

            if (html.classList.contains('dark')) {
                html.classList.remove('dark');
                localStorage.setItem('theme', 'light');
                themeIcon.textContent = 'light_mode';
            } else {
                html.classList.add('dark');
                localStorage.setItem('theme', 'dark');
                themeIcon.textContent = 'dark_mode';
            }
        }

        // ── Sinkronisasi icon theme (class dark sudah dipasang di <head>) ──
        if (localStorage.getItem('theme') === 'dark') {
            document.getElementById('themeIcon').textContent = 'dark_mode';
        }

        // ── Tutup sidebar saat klik di luar (mobile) ──────────────
        document.addEventListener('click', function(event) {
            const sidebar    = document.getElementById('sidebar');
            const overlay    = document.getElementById('mobileOverlay');
            const menuButton = event.target.closest('button[onclick="toggleSidebar()"]');

            if (!sidebar.contains(event.target) && !menuButton && window.innerWidth < 1024) {
                sidebar.classList.add('hidden-mobile');
                overlay.classList.remove('active');
            }
        });

        // ── SweetAlert toast notifikasi ───────────────────────────
        if (flashMessageConfig.success) {
            Swal.fire({
                icon: 'success',
                title: flashMessageConfig.success,
                toast: true,
                position: 'top-end',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false,
            });
        }

        if (flashMessageConfig.error) {
            Swal.fire({
                icon: 'error',
                title: flashMessageConfig.error,
                toast: true,
                position: 'top-end',
                timer: 4000,
                timerProgressBar: true,
                showConfirmButton: false,
            });
        }
    </script>

    @stack('scripts')
</body>

</html>
