{{-- resources/views/pembeli/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="id" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark font-display min-h-screen">

    <!-- Navbar -->
    <nav class="bg-white dark:bg-zinc-900 shadow-lg border-b border-gray-200 dark:border-zinc-700">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <a href="{{ route('pembeli.dashboard') }}" class="text-2xl font-bold text-charcoal dark:text-white font-be-vietnam">
                Lembah Hijau
            </a>
            <div class="flex items-center gap-4">
                <a href="{{ route('pembeli.profil.edit') }}" class="flex items-center gap-2 text-charcoal dark:text-zinc-300">
                    <img src="{{ auth()->user()->profile_photo ? asset('storage/' . auth()->user()->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=7BB661&color=fff' }}"
                         class="w-8 h-8 rounded-full object-cover">
                    <span class="hidden md:block">{{ auth()->user()->name }}</span>
                </a>
                <a href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                   class="text-soft-green hover:text-warm-yellow text-sm underline">
                    Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
            </div>
        </div>
    </nav>

    <!-- Sidebar + Content -->
    <div class="flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-white dark:bg-zinc-900 h-screen shadow-r-lg p-6 space-y-4">
            <ul class="space-y-2">
                <li>
                    <a href="{{ route('pembeli.dashboard') }}" class="flex items-center gap-3 p-3 rounded-lg bg-soft-green/10 text-soft-green font-medium">
                        <span class="material-symbols-outlined">home</span>
                        Beranda
                    </a>
                </li>
                <li>
                    <a href="{{ route('pembeli.produk.index') }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-soft-green/10 text-charcoal dark:text-zinc-300">
                        <span class="material-symbols-outlined">inventory_2</span>
                        Produk
                    </a>
                </li>
                <li>
                    <a href="{{ route('pembeli.keranjang') }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-soft-green/10 text-charcoal dark:text-zinc-300">
                        <span class="material-symbols-outlined">shopping_cart</span>
                        Keranjang
                    </a>
                </li>
                <li>
                    <a href="{{ route('pembeli.pesanan') }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-soft-green/10 text-charcoal dark:text-zinc-300">
                        <span class="material-symbols-outlined">receipt_long</span>
                        Pesanan Saya
                    </a>
                </li>
                <li>
                    <a href="{{ route('pembeli.profil.edit') }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-soft-green/10 text-charcoal dark:text-zinc-300">
                        <span class="material-symbols-outlined">person</span>
                        Profil
                    </a>
                </li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6">
            @yield('content')
        </main>
    </div>
</body>
</html>