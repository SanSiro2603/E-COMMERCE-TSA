{{-- resources/views/layouts/admin.blade.php --}}
<!DOCTYPE html>
<html lang="id" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - Lembah Hijau')</title>

    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">

    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            200: '#bbf7d0',
                            300: '#86efac',
                            400: '#4ade80',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#166534',
                            900: '#14532d',
                        }
                    },
                    fontFamily: {
                        "sans": ["Inter", "sans-serif"]
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        /* Sidebar Transitions */
        .sidebar {
            transition: transform 0.3s ease-in-out;
        }

        @media (max-width: 1024px) {
            .sidebar:not(.open) {
                transform: translateX(-100%);
            }
        }

        /* Active Link Indicator */
        .nav-link {
            position: relative;
            transition: all 0.2s ease;
        }

        .nav-link.active {
            background-color: rgb(240 253 244);
            color: rgb(22 163 74);
            font-weight: 600;
        }

        .dark .nav-link.active {
            background-color: rgb(20 83 45 / 0.3);
            color: rgb(134 239 172);
        }

        .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            height: 60%;
            width: 3px;
            background: linear-gradient(to bottom, #22c55e, #16a34a);
            border-radius: 0 4px 4px 0;
        }

        /* Dropdown Animation */
        .dropdown-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }

        .dropdown-content.open {
            max-height: 500px;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: rgb(229 231 235);
            border-radius: 10px;
        }

        .dark ::-webkit-scrollbar-thumb {
            background: rgb(55 65 81);
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgb(209 213 219);
        }

        .dark ::-webkit-scrollbar-thumb:hover {
            background: rgb(75 85 99);
        }

        /* Notification Badge Pulse */
        .notification-badge {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: .5;
            }
        }

        /* Dark Mode Smooth Transition */
        * {
            transition: background-color 0.2s ease, border-color 0.2s ease, color 0.2s ease;
        }

        /* Focus Styles */
        button:focus-visible, a:focus-visible {
            outline: 2px solid rgb(34 197 94);
            outline-offset: 2px;
        }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-950 text-gray-900 dark:text-gray-100">

    <!-- Sidebar -->
    <aside class="sidebar fixed top-0 left-0 z-40 w-64 h-screen bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-800">
        <!-- Logo -->
        <div class="flex items-center justify-between h-16 px-4 border-b border-gray-200 dark:border-gray-800">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                <div class="w-8 h-8 bg-gradient-to-br from-primary-500 to-primary-600 rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined text-white text-xl">eco</span>
                </div>
                <span class="text-lg font-bold text-gray-900 dark:text-white">Lembah Hijau</span>
            </a>
            <button class="lg:hidden p-2 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg" onclick="toggleSidebar()">
                <span class="material-symbols-outlined text-gray-500">close</span>
            </button>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto p-4 space-y-1">
            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard') }}" class="nav-link active flex items-center gap-3 px-3 py-2 rounded-lg text-sm">
                <span class="material-symbols-outlined text-xl">dashboard</span>
                <span>Dashboard</span>
            </a>

            <!-- Products Dropdown -->
            <div>
                <button onclick="toggleDropdown('products')" class="nav-link flex items-center justify-between w-full px-3 py-2 rounded-lg text-sm hover:bg-gray-100 dark:hover:bg-gray-800">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-xl">inventory_2</span>
                        <span>Produk</span>
                    </div>
                    <span class="material-symbols-outlined text-lg transition-transform" id="products-icon">expand_more</span>
                </button>
                <div class="dropdown-content ml-11 mt-1 space-y-1" id="products-dropdown">
                    <a href="#" class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800/50">Daftar Produk</a>
                    <a href="#" class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800/50">Kategori</a>
                    <a href="#" class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800/50">Stok</a>
                </div>
            </div>

            <!-- Orders -->
            <a href="#" class="nav-link flex items-center gap-3 px-3 py-2 rounded-lg text-sm hover:bg-gray-100 dark:hover:bg-gray-800">
                <span class="material-symbols-outlined text-xl">shopping_cart</span>
                <span>Pesanan</span>
                <span class="ml-auto px-2 py-0.5 text-xs font-semibold bg-primary-100 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400 rounded-full">12</span>
            </a>

            <!-- Customers -->
            <a href="#" class="nav-link flex items-center gap-3 px-3 py-2 rounded-lg text-sm hover:bg-gray-100 dark:hover:bg-gray-800">
                <span class="material-symbols-outlined text-xl">people</span>
                <span>Pelanggan</span>
            </a>

            <!-- Analytics -->
            <a href="#" class="nav-link flex items-center gap-3 px-3 py-2 rounded-lg text-sm hover:bg-gray-100 dark:hover:bg-gray-800">
                <span class="material-symbols-outlined text-xl">bar_chart</span>
                <span>Analitik</span>
            </a>

            <div class="pt-4 mt-4 border-t border-gray-200 dark:border-gray-800">
                <p class="px-3 mb-2 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Pengaturan</p>
                
                <a href="#" class="nav-link flex items-center gap-3 px-3 py-2 rounded-lg text-sm hover:bg-gray-100 dark:hover:bg-gray-800">
                    <span class="material-symbols-outlined text-xl">settings</span>
                    <span>Pengaturan</span>
                </a>

                <a href="#" class="nav-link flex items-center gap-3 px-3 py-2 rounded-lg text-sm hover:bg-gray-100 dark:hover:bg-gray-800">
                    <span class="material-symbols-outlined text-xl">help</span>
                    <span>Bantuan</span>
                </a>
            </div>
        </nav>

        <!-- User Profile in Sidebar -->
        <div class="p-4 border-t border-gray-200 dark:border-gray-800">
            <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 cursor-pointer">
                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center text-white font-semibold text-sm">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">Administrator</p>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="lg:pl-64">
        <!-- Top Navigation Bar -->
        <header class="sticky top-0 z-30 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800">
            <div class="flex items-center justify-between h-16 px-4 lg:px-6">
                <!-- Mobile Menu Button -->
                <button class="lg:hidden p-2 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg" onclick="toggleSidebar()">
                    <span class="material-symbols-outlined text-gray-600 dark:text-gray-400">menu</span>
                </button>

                <!-- Search Bar -->
                <div class="hidden md:flex flex-1 max-w-md">
                    <div class="relative w-full">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xl">search</span>
                        <input type="text" placeholder="Search..." class="w-full pl-10 pr-4 py-2 bg-gray-100 dark:bg-gray-800 border-0 rounded-lg text-sm focus:ring-2 focus:ring-primary-500 dark:text-white placeholder-gray-500">
                    </div>
                </div>

                <!-- Right Section -->
                <div class="flex items-center gap-2">
                    <!-- Dark Mode Toggle -->
                    <button onclick="toggleDarkMode()" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition">
                        <span class="material-symbols-outlined text-gray-600 dark:text-gray-400 dark:hidden">dark_mode</span>
                        <span class="material-symbols-outlined text-gray-400 hidden dark:inline">light_mode</span>
                    </button>

                    <!-- Notifications -->
                    <button class="relative p-2 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition">
                        <span class="material-symbols-outlined text-gray-600 dark:text-gray-400">notifications</span>
                        <span class="notification-badge absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full"></span>
                    </button>

                    <!-- Logout -->
                    <a href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                       class="flex items-center gap-2 px-3 py-2 text-sm bg-red-50 hover:bg-red-100 dark:bg-red-900/20 dark:hover:bg-red-900/30 text-red-600 dark:text-red-400 rounded-lg transition">
                        <span class="material-symbols-outlined text-lg">logout</span>
                        <span class="hidden sm:inline">Logout</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="p-4 lg:p-6">
            @yield('content')
        </main>
    </div>

    <!-- Overlay for mobile -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-gray-900/50 z-30 lg:hidden hidden" onclick="toggleSidebar()"></div>

    <script>
        // Toggle Sidebar on Mobile
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.toggle('open');
            overlay.classList.toggle('hidden');
        }

        // Toggle Dropdown
        function toggleDropdown(id) {
            const dropdown = document.getElementById(id + '-dropdown');
            const icon = document.getElementById(id + '-icon');
            dropdown.classList.toggle('open');
            icon.style.transform = dropdown.classList.contains('open') ? 'rotate(180deg)' : 'rotate(0)';
        }

        // Dark Mode Toggle
        function toggleDarkMode() {
            document.documentElement.classList.toggle('dark');
            localStorage.setItem('darkMode', document.documentElement.classList.contains('dark'));
        }

        // Initialize Dark Mode from localStorage
        if (localStorage.getItem('darkMode') === 'true') {
            document.documentElement.classList.add('dark');
        }
    </script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @stack('scripts')
</body>
</html>