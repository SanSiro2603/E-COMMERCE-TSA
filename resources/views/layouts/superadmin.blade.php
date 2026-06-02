<!-- resources/views/layouts/superadmin.blade.php -->
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Super Admin - E-Commerce TSA')</title>

    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=block" rel="stylesheet">

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

        /* Scrollbar minimal */
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(114, 226, 54, 0.3);
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(114, 226, 54, 0.5);
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
            to { opacity: 1; }
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-8px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-slide-down {
            animation: slideDown 0.4s cubic-bezier(0.4, 0, 0.2, 1);
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
</head>

<body class="bg-gray-50 dark:bg-zinc-950">

    <!-- Mobile Overlay -->
    <div class="mobile-overlay" id="mobileOverlay" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <aside id="sidebar"
        class="sidebar fixed left-0 top-0 z-50 h-screen w-72 bg-[#03150B] border-r border-green-500/10 flex flex-col shadow-2xl overflow-hidden">

        <!-- Background Glow -->
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(114,226,54,0.15),transparent_40%)] pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-full h-64 bg-gradient-to-t from-green-500/10 to-transparent pointer-events-none"></div>

        <!-- Logo -->
        {{-- PERBAIKAN: logo transparan (hapus bg-white/10), ukuran lebih besar --}}
        <div class="relative px-5 py-6 border-b border-white/10 flex items-center gap-3 z-10">
            <div class="w-14 h-14 flex items-center justify-center overflow-hidden flex-shrink-0">
                <img src="{{ asset('images/logo header.png') }}"
                    alt="Logo TSA"
                    class="h-14 w-14 object-contain">
            </div>
            <div>
                <h1 class="text-white text-[18px] font-bold leading-tight">E-Commerce TSA</h1>
                <p class="text-green-400 text-[11px] uppercase tracking-[2px] font-semibold">Super Admin</p>
            </div>
        </div>

        @php
            $menu = [
                [
                    'route' => 'superadmin.dashboard',
                    'icon'  => 'dashboard',
                    'label' => 'Dashboard',
                    'image' => 'images/sidebar/dashboard.png',
                ],
                [
                    'route' => 'superadmin.admins.index',
                    'icon'  => 'group',
                    'label' => 'Manajemen Admin',
                    'image' => 'images/sidebar/manajemen-admin.png',
                ],
                [
                    'route' => 'superadmin.reports.index',
                    'icon'  => 'analytics',
                    'label' => 'Laporan',
                    'image' => 'images/sidebar/laporan.png',
                ],
            ];

            $activeMenu = collect($menu)->first(fn($item) => request()->routeIs($item['route'].'*'))
                ?? $menu[0];
        @endphp

        <!-- Navigation (di atas gambar) -->
        {{-- PERBAIKAN: rounded-2xl diganti rounded-none (tidak ada border radius) --}}
        <nav class="relative px-3 pt-4 pb-2 space-y-2 z-10">
            @foreach ($menu as $item)
                @php $active = request()->routeIs($item['route'].'*'); @endphp

                <a href="{{ route($item['route']) }}"
                    data-image="{{ asset($item['image']) }}"
                    class="sidebar-nav-link group relative flex items-center gap-3 px-4 py-3 rounded-none transition-all duration-300 overflow-hidden
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

        <!-- Dynamic Illustration (di bawah navigasi, transparan tanpa kotak) -->
        <div class="relative flex-1 flex items-end justify-center px-4 pb-4 z-10">
            <img
                id="sidebar-feature-image"
                src="{{ asset($activeMenu['image']) }}"
                alt="{{ $activeMenu['label'] }}"
                class="w-full max-h-[220px] object-contain drop-shadow-2xl">
        </div>

        <!-- Footer User -->
        <div class="relative px-4 py-4 border-t border-white/10 z-10">
            <div class="flex items-center gap-3 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-xl p-3">

                <div class="w-11 h-11 rounded-full bg-gradient-to-br from-green-400 to-lime-300 text-black font-bold flex items-center justify-center shadow-lg shadow-green-500/30 flex-shrink-0">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>

                <div class="flex-1 min-w-0">
                    <p class="text-white text-[13px] font-semibold truncate">{{ Auth::user()->name }}</p>
                    <p class="text-green-300 text-[10px] uppercase tracking-wider">Super Admin</p>
                </div>

                {{-- PERBAIKAN: background merah jelas (bg-red-600 hover:bg-red-700), text putih --}}
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
                    <button onclick="toggleSidebar()" class="lg:hidden p-2.5 rounded-xl hover:bg-gray-100 dark:hover:bg-zinc-800 transition-all">
                        <span class="material-symbols-outlined text-gray-600 dark:text-zinc-300 text-[22px]">menu</span>
                    </button>

                    <!-- Page Title -->
                    <div class="hidden lg:block">
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">@yield('page-title', 'Dashboard')</h2>
                        <p class="text-xs text-gray-500 dark:text-zinc-400 mt-0.5">@yield('page-subtitle', 'Selamat datang di Super Admin Panel')</p>
                    </div>

                    <!-- Right Section -->
                    <div class="flex items-center gap-2">
                        <!-- Date Time -->
                        <div class="hidden md:block text-right mr-4">
                            <p class="text-[11px] font-semibold text-gray-900 dark:text-white">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</p>
                            <p class="text-[10px] text-gray-500 dark:text-zinc-500">{{ now()->format('H:i') }} WIB</p>
                        </div>

                        <!-- Theme Toggle -->
                        <button onclick="toggleTheme()" class="p-2.5 rounded-xl hover:bg-gray-100 dark:hover:bg-zinc-800 transition-all">
                            <span id="themeIcon" class="material-symbols-outlined text-gray-600 dark:text-zinc-300 text-[20px]">light_mode</span>
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="p-4 lg:p-8 animate-slide-down">
            <div class="max-w-7xl mx-auto">

                <!-- Success Alert -->
                @if(session('success'))
                <div data-auto-dismiss class="mb-6 bg-green-50 dark:bg-green-500/10 border border-green-200 dark:border-green-500/20 text-green-800 dark:text-green-400 px-6 py-4 rounded-xl flex items-center gap-3 shadow-soft animate-slide-down">
                    <span class="material-symbols-outlined text-green-600 dark:text-green-400">check_circle</span>
                    <span class="font-medium text-sm">{{ session('success') }}</span>
                </div>
                @endif

                <!-- Error Alert -->
                @if(session('error'))
                <div data-auto-dismiss class="mb-6 bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 text-red-800 dark:text-red-400 px-6 py-4 rounded-xl flex items-center gap-3 shadow-soft animate-slide-down">
                    <span class="material-symbols-outlined text-red-600 dark:text-red-400">error</span>
                    <span class="font-medium text-sm">{{ session('error') }}</span>
                </div>
                @endif

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

    <!-- Chart.js & SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Sidebar Toggle for Mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobileOverlay');
            sidebar.classList.toggle('hidden-mobile');
            overlay.classList.toggle('active');
        }

        // Theme Toggle
        function toggleTheme() {
            const html = document.documentElement;
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

        // Load theme from localStorage
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.classList.add('dark');
            document.getElementById('themeIcon').textContent = 'dark_mode';
        }

        document.addEventListener('DOMContentLoaded', () => {

            // Auto-dismiss notifikasi setelah 4 detik
            document.querySelectorAll('[data-auto-dismiss]').forEach(el => {
                setTimeout(() => {
                    el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                    el.style.opacity = '0';
                    el.style.transform = 'translateY(-8px)';
                    setTimeout(() => el.remove(), 500);
                }, 4000);
            });

            // PERBAIKAN: Hapus event hover untuk gambar sidebar
            // Gambar sidebar hanya mengikuti menu yang sedang aktif (route), tidak berubah saat hover

        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobileOverlay');
            const menuButton = event.target.closest('button[onclick="toggleSidebar()"]');
            if (!sidebar.contains(event.target) && !menuButton && window.innerWidth < 1024) {
                sidebar.classList.add('hidden-mobile');
                overlay.classList.remove('active');
            }
        });

        @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            timer: 2000,
            showConfirmButton: false
        });
        @endif

        @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '{{ session('error') }}'
        });
        @endif
    </script>

    @stack('scripts')
</body>
</html>
