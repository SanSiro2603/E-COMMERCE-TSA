{{-- resources/views/layouts/admin.blade.php --}}
<!DOCTYPE html>
<html lang="id" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - Lembah Hijau')</title>

    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;700;900&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">

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
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            overflow-x: hidden;
        }

        .gradient-button {
            background-image: linear-gradient(to right, #8fcf72, #7BB661);
        }
        .gradient-button:hover {
            background-image: linear-gradient(to right, #9bd980, #8fcf72);
        }

        /* Sidebar Animation */
        .sidebar {
            transition: transform 0.3s ease-in-out;
        }

        @media (max-width: 768px) {
            .sidebar.hidden-mobile {
                transform: translateX(-100%);
            }
        }

        /* Active menu item */
        .menu-item-active {
            background: linear-gradient(90deg, rgba(123, 182, 97, 0.15) 0%, rgba(123, 182, 97, 0.05) 100%);
            border-left: 3px solid #7BB661;
            color: #7BB661 !important;
        }

        /* Hover effect */
        .menu-item:hover {
            background: rgba(123, 182, 97, 0.08);
            transform: translateX(4px);
            transition: all 0.2s ease;
        }

        /* Scrollbar styling */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #7BB661;
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #6a9f56;
        }

        /* Card hover effect */
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(0,0,0,0.1);
        }

        /* Badge styling */
        .badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.25rem 0.625rem;
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: 9999px;
        }

        /* Breadcrumb */
        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            color: #6b7280;
        }

        .dark .breadcrumb {
            color: #9ca3af;
        }

        /* Mobile menu overlay */
        .mobile-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 40;
        }

        .mobile-overlay.active {
            display: block;
        }

        /* Animation */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-slide-in {
            animation: slideIn 0.3s ease-out;
        }

        /* Dark mode text fixes */
        .dark .text-dark-fix {
            color: #e4e4e7 !important;
        }

        .dark input::placeholder {
            color: #71717a !important;
        }
    </style>
</head>
<body class="bg-gray-50 dark:bg-zinc-950 font-display">

    <!-- Mobile Overlay -->
    <div class="mobile-overlay" id="mobileOverlay" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <aside class="sidebar fixed left-0 top-0 z-50 h-screen w-64 bg-white dark:bg-zinc-900 border-r border-gray-200 dark:border-zinc-800 flex flex-col shadow-xl" id="sidebar">
        <!-- Logo Section -->
        <div class="px-6 py-5 border-b border-gray-200 dark:border-zinc-800">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 group">
                <div class="w-10 h-10 bg-gradient-to-br from-soft-green to-primary rounded-lg flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined text-white text-2xl">eco</span>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-gray-900 dark:text-white font-be-vietnam">Lembah Hijau</h1>
                    <p class="text-xs text-gray-500 dark:text-zinc-400">Admin Panel</p>
                </div>
            </a>
        </div>

        <!-- Navigation Menu -->
        <nav class="flex-1 px-3 py-4 overflow-y-auto custom-scrollbar">
            <div class="space-y-1">
                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}" class="menu-item menu-item-active flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all">
                    <span class="material-symbols-outlined text-xl">dashboard</span>
                    <span>Dashboard</span>
                </a>

                <!-- Categories -->
                <a href="{{ route('admin.categories.index') }}" class="menu-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-700 dark:text-zinc-300 transition-all">
                    <span class="material-symbols-outlined text-xl">category</span>
                    <span>Kategori</span>
                </a>

                <!-- Products -->
                <a href="{{ route('admin.products.index') }}" class="menu-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-700 dark:text-zinc-300 transition-all">
                    <span class="material-symbols-outlined text-xl">inventory_2</span>
                    <span>Produk</span>
                </a>

                <!-- Orders -->
                <a href="{{ route('admin.orders.index') }}" class="menu-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-700 dark:text-zinc-300 transition-all">
                    <span class="material-symbols-outlined text-xl">shopping_cart</span>
                    <span>Pesanan</span>
                    <span class="ml-auto badge bg-soft-green/20 text-soft-green dark:bg-soft-green/30 dark:text-soft-green">12</span>
                </a>

                <!-- Reports -->
                <a href="{{ route('admin.reports.index') }}" class="menu-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-700 dark:text-zinc-300 transition-all">
                    <span class="material-symbols-outlined text-xl">analytics</span>
                    <span>Laporan</span>
                </a>
            </div>
        </nav>

        <!-- User Profile Section -->
        <div class="px-3 py-4 border-t border-gray-200 dark:border-zinc-800">
            <div class="flex items-center gap-3 px-3 py-2 rounded-lg bg-gray-50 dark:bg-zinc-800/50">
                <div class="w-9 h-9 bg-gradient-to-br from-soft-green to-primary rounded-full flex items-center justify-center text-white font-bold text-sm shadow-md">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-500 dark:text-zinc-400">Administrator</p>
                </div>
                <a href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                   class="text-gray-400 dark:text-zinc-500 hover:text-red-500 dark:hover:text-red-400 transition-colors"
                   title="Logout">
                    <span class="material-symbols-outlined text-xl">logout</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="lg:pl-64">
        <!-- Top Navigation Bar -->
        <header class="sticky top-0 z-30 bg-white dark:bg-zinc-900 border-b border-gray-200 dark:border-zinc-800 shadow-sm">
            <div class="px-4 lg:px-8 py-4">
                <div class="flex items-center justify-between">
                    <!-- Mobile Menu Button -->
                    <button onclick="toggleSidebar()" class="lg:hidden p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-zinc-800 transition-colors">
                        <span class="material-symbols-outlined text-gray-600 dark:text-zinc-300">menu</span>
                    </button>

                    <!-- Breadcrumb -->
                    <div class="breadcrumb hidden lg:flex">
                        <span class="text-gray-900 dark:text-white font-medium">Dashboard</span>
                        <span class="material-symbols-outlined text-sm text-gray-400 dark:text-zinc-500">chevron_right</span>
                        <span class="text-gray-600 dark:text-zinc-400">Overview</span>
                    </div>

                    <!-- Right Section -->
                    <div class="flex items-center gap-3">
                        <!-- Search Bar -->
                        {{-- <div class="hidden md:flex items-center gap-2 px-4 py-2 bg-gray-100 dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 w-64">
                            <span class="material-symbols-outlined text-gray-400 dark:text-zinc-500 text-xl">search</span>
                            <input type="text" placeholder="Cari..." class="bg-transparent border-none outline-none text-sm text-gray-700 dark:text-zinc-300 w-full placeholder:text-gray-400 dark:placeholder:text-zinc-500">
                        </div> --}}

                        <!-- Notifications -->
                        {{-- <button class="relative p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-zinc-800 transition-colors">
                            <span class="material-symbols-outlined text-gray-600 dark:text-zinc-300">notifications</span>
                            <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full"></span>
                        </button> --}}

                        <!-- Theme Toggle -->
                        <button onclick="toggleTheme()" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-zinc-800 transition-colors">
                            <span id="themeIcon" class="material-symbols-outlined text-gray-600 dark:text-zinc-300">light_mode</span>
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="p-4 lg:p-8 animate-slide-in">
            <div class="max-w-7xl mx-auto">
                @yield('content')
            </div>
        </main>

        <!-- Footer -->
        <footer class="px-4 lg:px-8 py-6 border-t border-gray-200 dark:border-zinc-800 bg-white dark:bg-zinc-900">
            <div class="max-w-7xl mx-auto">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-gray-500 dark:text-zinc-400">
                    <p>© 2024 Lembah Hijau. All rights reserved.</p>
                    <div class="flex items-center gap-4">
                        <a href="#" class="hover:text-soft-green transition-colors">Dokumentasi</a>
                        <span>•</span>
                        <a href="#" class="hover:text-soft-green transition-colors">Support</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
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

        // Active menu detection
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            const menuItems = document.querySelectorAll('.menu-item');
            
            menuItems.forEach(item => {
                const href = item.getAttribute('href');
                if (currentPath.includes(href) && href !== '#') {
                    item.classList.add('menu-item-active');
                    item.classList.remove('text-gray-700', 'dark:text-zinc-300');
                }
            });
        });
    </script>

    @stack('scripts')
</body>
</html>