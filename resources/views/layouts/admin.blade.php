{{-- resources/views/layouts/admin.blade.php --}}
<!DOCTYPE html>
<html lang="id" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - Lembah Hijau')</title>

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
        /* Animated Gradient Background */
        body {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 25%, #bbf7d0 50%, #86efac 75%, #4ade80 100%);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Glassmorphism Effect */
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
        }

        .glass-strong {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(30px);
            -webkit-backdrop-filter: blur(30px);
            border: 1px solid rgba(255, 255, 255, 0.6);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.2);
        }

        .glass-dark {
            background: rgba(23, 33, 17, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Gradient Button */
        .gradient-button {
            background: linear-gradient(135deg, #86efac 0%, #4ade80 50%, #22c55e 100%);
            background-size: 200% 200%;
            transition: all 0.3s ease;
        }
        
        .gradient-button:hover {
            background-position: 100% 0;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(34, 197, 94, 0.3);
        }

        /* Sidebar Active Effect */
        .sidebar-active {
            background: linear-gradient(135deg, rgba(134, 239, 172, 0.2) 0%, rgba(74, 222, 128, 0.2) 100%);
            border-left: 4px solid #22c55e;
            font-weight: 600;
        }

        /* Hover Effects */
        .hover-lift {
            transition: all 0.3s ease;
        }

        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(31, 38, 135, 0.2);
        }

        /* Smooth Transitions */
        * {
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(34, 197, 94, 0.5);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(34, 197, 94, 0.7);
        }

        /* Dark Mode Adjustments */
        .dark body {
            background: linear-gradient(135deg, #0f1810 0%, #172111 25%, #1a2e14 50%, #1d3a17 75%, #20461a 100%);
        }

        .dark .glass {
            background: rgba(23, 33, 17, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .dark .glass-strong {
            background: rgba(23, 33, 17, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        /* Logo Animation */
        .logo-shine {
            background: linear-gradient(90deg, #22c55e 0%, #86efac 50%, #22c55e 100%);
            background-size: 200% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: shine 3s linear infinite;
        }

        @keyframes shine {
            to {
                background-position: 200% center;
            }
        }
    </style>
</head>
<body class="font-display min-h-screen">

    <!-- Navbar with Glassmorphism -->
    <nav class="glass-strong sticky top-0 z-50">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <a href="{{ route('admin.dashboard') }}" class="text-2xl font-bold font-be-vietnam flex items-center gap-2">
                <span class="logo-shine">Lembah Hijau</span>
                <span class="text-xs px-2 py-1 bg-gradient-to-r from-green-400 to-emerald-500 text-white rounded-full">Admin</span>
            </a>
            <div class="flex items-center gap-6">
                <!-- Notifications -->
                <button class="relative p-2 hover:bg-green-50 dark:hover:bg-green-900/20 rounded-full transition">
                    <span class="material-symbols-outlined text-gray-700 dark:text-gray-300">notifications</span>
                    <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                </button>
                
                <!-- User Menu -->
                <div class="flex items-center gap-3">
                    <div class="text-right">
                        <p class="text-sm font-semibold text-gray-800 dark:text-white">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Administrator</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-green-400 to-emerald-600 flex items-center justify-center text-white font-bold">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                </div>

                <!-- Logout -->
                <a href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                   class="flex items-center gap-2 px-4 py-2 text-sm bg-red-50 hover:bg-red-100 text-red-600 rounded-lg transition">
                    <span class="material-symbols-outlined text-sm">logout</span>
                    Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
            </div>
        </div>
    </nav>

    <!-- Sidebar + Content -->
    <div class="flex min-h-screen">
        <!-- Sidebar with Glassmorphism -->
        <aside class="w-72 p-6 glass m-4 rounded-2xl h-fit sticky top-24">
            <div class="mb-8">
                <h3 class="text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 font-semibold mb-4">Menu Utama</h3>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="sidebar-active flex items-center gap-3 p-3 rounded-xl text-green-700 dark:text-green-400">
                            <span class="material-symbols-outlined">dashboard</span>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center gap-3 p-3 rounded-xl hover:bg-green-50 dark:hover:bg-green-900/20 text-gray-700 dark:text-gray-300 transition">
                            <span class="material-symbols-outlined">inventory_2</span>
                            <span>Produk</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center gap-3 p-3 rounded-xl hover:bg-green-50 dark:hover:bg-green-900/20 text-gray-700 dark:text-gray-300 transition">
                            <span class="material-symbols-outlined">shopping_cart</span>
                            <span>Pesanan</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center gap-3 p-3 rounded-xl hover:bg-green-50 dark:hover:bg-green-900/20 text-gray-700 dark:text-gray-300 transition">
                            <span class="material-symbols-outlined">people</span>
                            <span>Pelanggan</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center gap-3 p-3 rounded-xl hover:bg-green-50 dark:hover:bg-green-900/20 text-gray-700 dark:text-gray-300 transition">
                            <span class="material-symbols-outlined">bar_chart</span>
                            <span>Laporan</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="mt-8 pt-8 border-t border-gray-200 dark:border-gray-700">
                <h3 class="text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 font-semibold mb-4">Pengaturan</h3>
                <ul class="space-y-2">
                    <li>
                        <a href="#" class="flex items-center gap-3 p-3 rounded-xl hover:bg-green-50 dark:hover:bg-green-900/20 text-gray-700 dark:text-gray-300 transition">
                            <span class="material-symbols-outlined">settings</span>
                            <span>Pengaturan</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center gap-3 p-3 rounded-xl hover:bg-green-50 dark:hover:bg-green-900/20 text-gray-700 dark:text-gray-300 transition">
                            <span class="material-symbols-outlined">help</span>
                            <span>Bantuan</span>
                        </a>
                    </li>
                </ul>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6">
            @yield('content')
        </main>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @stack('scripts')
</body>
</html>