{{-- resources/views/layouts/admin.blade.php --}}
<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - E-Commerce TSA')</title>

    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
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
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
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
    </style>
</head>
<body class="bg-gray-50 dark:bg-zinc-950">

    

    <!-- Mobile Overlay -->
    <div class="mobile-overlay" id="mobileOverlay" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <aside id="sidebar"
        class="sidebar fixed left-0 top-0 z-50 h-screen w-64 bg-white/95 dark:bg-zinc-900/95 border-r border-gray-100 dark:border-zinc-800/50 flex flex-col shadow-soft">

        <!-- Logo -->
        <div class="logo-container px-5 py-6 border-b border-gray-100 dark:border-zinc-800/50 flex items-center gap-3 cursor-default">
            <div class="logo-icon w-10 h-10 bg-gradient-to-br from-soft-green to-primary rounded-xl flex items-center justify-center shadow-lg shadow-soft-green/20">
                <span class="material-symbols-outlined text-white text-[22px]">eco</span>
            </div>
            <div>
                <h1 class="text-[15px] font-bold text-gray-900 dark:text-white tracking-tight">Lembah Hijau</h1>
                <p class="text-[10px] text-gray-400 dark:text-zinc-500 font-medium tracking-wide uppercase">Admin Panel</p>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-3 py-6 overflow-y-auto custom-scrollbar space-y-1.5">
            @php
                $menu = [
                    ['route' => 'admin.dashboard', 'icon' => 'dashboard', 'label' => 'Dashboard'],
                    ['route' => 'admin.categories.index', 'icon' => 'category', 'label' => 'Kategori'],
                    ['route' => 'admin.products.index', 'icon' => 'inventory_2', 'label' => 'Produk'],
                    ['route' => 'admin.orders.index', 'icon' => 'shopping_cart', 'label' => 'Pesanan'],
                    ['route' => 'admin.reports.index', 'icon' => 'analytics', 'label' => 'Laporan'],
                ];
            @endphp

            @foreach ($menu as $item)
                @php $active = request()->routeIs($item['route'].'*'); @endphp
                <a href="{{ route($item['route']) }}"
                   class="menu-item flex items-center gap-3 px-4 py-2.5 rounded-xl text-[13px] font-medium transition-all
                   {{ $active 
                       ? 'menu-item-active text-soft-green font-semibold' 
                       : 'text-gray-600 dark:text-zinc-400 hover:bg-gray-50 dark:hover:bg-zinc-800/50 hover:text-soft-green' }}">
                   
                    <span class="material-symbols-outlined text-[20px] transition-colors
                        {{ $active ? 'text-soft-green' : 'text-gray-400 dark:text-zinc-500' }}">
                        {{ $item['icon'] }}
                    </span>
                    <span>{{ $item['label'] }}</span>
                </a>
            @endforeach
        </nav>

        <!-- User Profile -->
        <div class="px-4 py-4 border-t border-gray-100 dark:border-zinc-800/50">
            <div class="flex items-center gap-3 px-3 py-3 rounded-xl bg-gradient-to-br from-gray-50 to-gray-100/50 dark:from-zinc-800/40 dark:to-zinc-800/20 hover:shadow-md transition-all duration-300">
                <div class="w-9 h-9 bg-gradient-to-br from-soft-green to-primary rounded-full flex items-center justify-center text-white font-bold text-[13px] shadow-lg shadow-soft-green/30">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[13px] font-semibold text-gray-900 dark:text-white truncate">{{ Auth::user()->name }}</p>
                    <p class="text-[10px] text-gray-500 dark:text-zinc-500 uppercase tracking-wide">Admin</p>
                </div>
                <a href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                   class="p-1.5 text-gray-400 dark:text-zinc-500 hover:text-red-500 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 rounded-lg transition-all"
                   title="Logout">
                    <span class="material-symbols-outlined text-[18px]">logout</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="lg:pl-64">
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
                        <!-- Theme Toggle -->
                        <button onclick="toggleTheme()" 
                            class="p-2.5 rounded-xl hover:bg-gray-100 dark:hover:bg-zinc-800 transition-all">
                            <span id="themeIcon" class="material-symbols-outlined text-gray-600 dark:text-zinc-300 text-[20px]">light_mode</span>
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="p-4 lg:p-8 animate-slide-down">
            <div class="max-w-7xl mx-auto">
                @yield('content')
            </div>
        </main>

        <!-- Footer -->
        <footer class="px-4 lg:px-8 py-6 border-t border-gray-100 dark:border-zinc-800/50 bg-white/50 dark:bg-zinc-900/50">
            <div class="max-w-7xl mx-auto">
                <div class="flex flex-col md:flex-row justify-between items-center gap-3 text-[12px] text-gray-500 dark:text-zinc-500">
                    <p class="font-medium">© 2025 Lembah Hijau. All rights reserved.</p>
                    <div class="flex items-center gap-4">
                        <a href="#" class="hover:text-soft-green transition-colors font-medium">Dokumentasi</a>
                        <span class="text-gray-300 dark:text-zinc-700">•</span>
                        <a href="#" class="hover:text-soft-green transition-colors font-medium">Support</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Chart.js -->
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
        text: '{{ session("success") }}',
        timer: 2000,
        showConfirmButton: false
    });

@endif

@if(session('error'))

    Swal.fire({
        icon: 'error',
        title: 'Login gagal!',
        text: '{{ session("error") }}'
    });

@endif

    </script>

    @stack('scripts')
</body>
</html>