<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Lembah Hijau - Product Catalog</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#2E7D32",
                        "secondary": "#FDD835",
                        "accent": "#81D4FA",
                    },
                    fontFamily: {
                        "display": ["Inter", "sans-serif"]
                    },
                },
            },
        }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24
        }
        
        /* Glassmorphism */
        .glass {
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.4);
        }
        .dark .glass {
            background: rgba(10, 15, 10, 0.75);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .glass-strong {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(30px);
            -webkit-backdrop-filter: blur(30px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
        .dark .glass-strong {
            background: rgba(10, 15, 10, 0.95);
            border: 1px solid rgba(255, 255, 255, 0.15);
        }
        
        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }
        
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 20px rgba(46, 125, 50, 0.3); }
            50% { box-shadow: 0 0 40px rgba(46, 125, 50, 0.6); }
        }
        
        .animate-fade-in {
            animation: fadeIn 0.6s ease-out forwards;
        }
        
        .animate-float {
            animation: float 3s ease-in-out infinite;
        }
        
        .card-hover {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .card-hover:hover {
            transform: translateY(-12px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #2E7D32 0%, #4CAF50 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .badge-pulse {
            animation: pulse-glow 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        
        .gradient-mesh {
            background: 
                radial-gradient(at 0% 0%, rgba(46, 125, 50, 0.1) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(253, 216, 53, 0.1) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(129, 212, 250, 0.1) 0px, transparent 50%);
        }
        
        /* Scrollbar */
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        
        /* Custom scrollbar for main content */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: rgba(46, 125, 50, 0.3);
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(46, 125, 50, 0.5);
        }
    </style>
</head>
<body class="bg-gray-50 dark:bg-[#0a0f0a] font-display text-gray-900 dark:text-white transition-colors duration-300 gradient-mesh">
    <div class="relative flex h-auto min-h-screen w-full flex-col overflow-x-hidden">
        <!-- Decorative Blobs -->
        <div class="fixed top-0 right-0 w-96 h-96 bg-primary/10 dark:bg-primary/5 rounded-full blur-3xl animate-float -z-10"></div>
        <div class="fixed bottom-0 left-0 w-96 h-96 bg-secondary/10 dark:bg-secondary/5 rounded-full blur-3xl animate-float -z-10" style="animation-delay: 1.5s;"></div>

        <!-- Header -->
        <header class="sticky top-0 z-50 glass shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between py-4">
                    <!-- Logo -->
                    <div class="flex items-center gap-3 group cursor-pointer">
                        <div class="size-11 bg-gradient-to-br from-primary to-green-600 rounded-2xl flex items-center justify-center transform group-hover:rotate-12 transition-all duration-300 shadow-lg">
                            <span class="material-symbols-outlined text-3xl text-white">eco</span>
                        </div>
                        <div>
                            <a href="{{route("landing")}}" class="text-2xl font-bold gradient-text">Lembah Hijau</a>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Premium Livestock</p>
                        </div>
                    </div>

                    <!-- Search Bar (Desktop) -->
                    <div class="hidden md:flex flex-1 max-w-2xl mx-8">
                        <div class="relative w-full">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400">search</span>
                            <input 
                                class="w-full h-12 pl-12 pr-4 rounded-2xl glass-strong border-2 border-transparent focus:border-primary focus:ring-0 text-gray-900 dark:text-white placeholder:text-gray-500 dark:placeholder:text-gray-400 shadow-sm transition-all duration-300"
                                placeholder="Search for animals, breeds, or categories..."
                                type="text"
                            />
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center gap-3">
                        <a href="{{ route('login') }}" 
                        class="hidden sm:flex items-center gap-2 px-6 h-11 rounded-2xl bg-gradient-to-r from-secondary to-yellow-400 text-primary font-bold shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-300">
                            <span class="material-symbols-outlined">person</span>
                            <span>Login</span>
                        </a>

                        {{-- <button class="relative flex items-center justify-center w-11 h-11 rounded-2xl glass-strong shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-300">
                            <span class="material-symbols-outlined text-primary dark:text-white">shopping_cart</span>
                            <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center font-bold">3</span>
                        </button> --}}
                        
                        <button 
                            onclick="document.documentElement.classList.toggle('dark')" 
                            class="flex items-center justify-center w-11 h-11 rounded-2xl glass-strong shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-300">
                            <span class="material-symbols-outlined text-primary dark:text-white">dark_mode</span>
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <!-- Hero Banner -->
        <div class="relative py-16 overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center animate-fade-in">
                    <div class="inline-block mb-4">
                        <div class="glass-strong px-5 py-2 rounded-full shadow-lg">
                            <p class="text-sm font-bold text-primary dark:text-secondary flex items-center gap-2">
                                <span class="size-2 bg-green-500 rounded-full animate-pulse"></span>
                                Premium Quality 
                            </p>
                        </div>
                    </div>
                    <h2 class="text-5xl md:text-6xl font-black mb-4">
                        <span class="gradient-text">Our Gallery Animals</span>
                    </h2>
                    <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                        Ethically raised, certified livestock for your farm. Quality guaranteed.
                    </p>
                </div>
            </div>
        </div>

        <!-- Filters -->
        {{-- <div class="sticky top-[89px] z-40 glass border-y border-white/10 shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex items-center gap-3 overflow-x-auto scrollbar-hide pb-2">
                    <!-- Category Filters -->
                    <button class="flex items-center gap-2 px-5 h-11 rounded-2xl bg-gradient-to-r from-primary to-green-600 text-white font-semibold shadow-lg hover:shadow-xl transition-all duration-300 whitespace-nowrap">
                        <span>All Products</span>
                        <span class="bg-white/20 px-2 py-0.5 rounded-full text-xs font-bold">12</span>
                    </button>
                    <button class="flex items-center gap-2 px-5 h-11 rounded-2xl glass-strong font-semibold hover:bg-primary hover:text-white transition-all duration-300 whitespace-nowrap">
                        <span>🐐 Goats</span>
                    </button>
                    <button class="flex items-center gap-2 px-5 h-11 rounded-2xl glass-strong font-semibold hover:bg-primary hover:text-white transition-all duration-300 whitespace-nowrap">
                        <span>🐄 Cows</span>
                    </button>
                    <button class="flex items-center gap-2 px-5 h-11 rounded-2xl glass-strong font-semibold hover:bg-primary hover:text-white transition-all duration-300 whitespace-nowrap">
                        <span>🐰 Rabbits</span>
                    </button>
                    <button class="flex items-center gap-2 px-5 h-11 rounded-2xl glass-strong font-semibold hover:bg-primary hover:text-white transition-all duration-300 whitespace-nowrap">
                        <span>🦜 Birds</span>
                    </button>
                    
                    <div class="w-px h-8 bg-gray-300 dark:bg-gray-700 mx-2"></div>
                    
                    <!-- Action Filters -->
                    <button class="flex items-center gap-2 px-5 h-11 rounded-2xl glass-strong font-semibold hover:shadow-lg transition-all duration-300 whitespace-nowrap">
                        <span class="material-symbols-outlined text-lg">tune</span>
                        <span>More Filters</span>
                    </button>
                    <button class="flex items-center gap-2 px-5 h-11 rounded-2xl glass-strong font-semibold hover:shadow-lg transition-all duration-300 whitespace-nowrap">
                        <span>Sort By: Popular</span>
                        <span class="material-symbols-outlined text-lg">expand_more</span>
                    </button>
                </div>
            </div>
        </div> --}}

        <!-- Products Grid -->
        <main class="flex-1 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    
                    <!-- Product Card 6 -->
                    <div class="group card-hover glass-strong rounded-3xl overflow-hidden shadow-xl animate-fade-in" style="animation-delay: 0.5s;">
                        <div class="relative overflow-hidden">
                            <div class="aspect-square bg-cover bg-center transition-transform duration-500 group-hover:scale-110" 
                                 style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCG38J6NGcRX_R7e7vK8MalwEXqqeR4EWamcNY9seK_G9jNUiEoQzQoLRp2mEARNxxMSOvWE2Cw8EBcQPNnqnHDMlasW4znGwmjPcTbnOzXuhxBeEbdqEUwtlG5zdz2JOQW86cPZjQldCq--Y1SV2oduI5uZ4eKONqp8IpN6PiMiOqmSZALZmk1BWJovQ58fx-yEF3w0NzZOkonTYA5EhyXfEcfh6gap2d5ct5xsKA6mPGFpdw1Nb3ovywY4oCr9JmL1aUK6kfFJ9vc");'>
                            </div>
                            <span class="badge-pulse absolute top-4 right-4 px-3 py-1.5 bg-blue-500 text-white text-xs font-bold rounded-full shadow-xl">FEATURED</span>
                            <button class="absolute top-4 left-4 w-10 h-10 bg-white/90 dark:bg-gray-800/90 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300 hover:scale-110">
                                <span class="material-symbols-outlined text-red-500">favorite</span>
                            </button>
                            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent p-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <button class="w-full px-4 py-2 rounded-xl bg-white/20 backdrop-blur-md text-white font-semibold hover:bg-white/30 transition-colors">
                                    Quick View
                                </button>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-2">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Jersey Cow</h3>
                                <span class="px-2.5 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-xs font-bold rounded-full">In Stock</span>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3 line-clamp-2">Rich, creamy milk and smaller frame.</p>
                            <div class="flex items-center gap-2 mb-4">
                                <div class="flex text-yellow-400">
                                    <span class="material-symbols-outlined text-base">star</span>
                                    <span class="material-symbols-outlined text-base">star</span>
                                    <span class="material-symbols-outlined text-base">star</span>
                                    <span class="material-symbols-outlined text-base">star</span>
                                    <span class="material-symbols-outlined text-base">star_half</span>
                                </div>
                                <span class="text-xs text-gray-600 dark:text-gray-400 font-semibold">(21 reviews)</span>
                            </div>
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <p class="text-3xl font-black gradient-text">$1,500</p>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <button class="flex-1 h-12 rounded-xl bg-primary/10 dark:bg-primary/20 text-primary dark:text-secondary font-bold hover:bg-primary/20 dark:hover:bg-primary/30 transition-all duration-300">
                                    Details
                                </button>
                                <button class="flex-1 h-12 rounded-xl bg-gradient-to-r from-primary to-green-600 text-white font-bold shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-300">
                                    Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- Product Card 6 -->
                    <div class="group card-hover glass-strong rounded-3xl overflow-hidden shadow-xl animate-fade-in" style="animation-delay: 0.5s;">
                        <div class="relative overflow-hidden">
                            <div class="aspect-square bg-cover bg-center transition-transform duration-500 group-hover:scale-110" 
                                 style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCG38J6NGcRX_R7e7vK8MalwEXqqeR4EWamcNY9seK_G9jNUiEoQzQoLRp2mEARNxxMSOvWE2Cw8EBcQPNnqnHDMlasW4znGwmjPcTbnOzXuhxBeEbdqEUwtlG5zdz2JOQW86cPZjQldCq--Y1SV2oduI5uZ4eKONqp8IpN6PiMiOqmSZALZmk1BWJovQ58fx-yEF3w0NzZOkonTYA5EhyXfEcfh6gap2d5ct5xsKA6mPGFpdw1Nb3ovywY4oCr9JmL1aUK6kfFJ9vc");'>
                            </div>
                            <span class="badge-pulse absolute top-4 right-4 px-3 py-1.5 bg-blue-500 text-white text-xs font-bold rounded-full shadow-xl">FEATURED</span>
                            <button class="absolute top-4 left-4 w-10 h-10 bg-white/90 dark:bg-gray-800/90 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300 hover:scale-110">
                                <span class="material-symbols-outlined text-red-500">favorite</span>
                            </button>
                            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent p-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <button class="w-full px-4 py-2 rounded-xl bg-white/20 backdrop-blur-md text-white font-semibold hover:bg-white/30 transition-colors">
                                    Quick View
                                </button>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-2">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Jersey Cow</h3>
                                <span class="px-2.5 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-xs font-bold rounded-full">In Stock</span>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3 line-clamp-2">Rich, creamy milk and smaller frame.</p>
                            <div class="flex items-center gap-2 mb-4">
                                <div class="flex text-yellow-400">
                                    <span class="material-symbols-outlined text-base">star</span>
                                    <span class="material-symbols-outlined text-base">star</span>
                                    <span class="material-symbols-outlined text-base">star</span>
                                    <span class="material-symbols-outlined text-base">star</span>
                                    <span class="material-symbols-outlined text-base">star_half</span>
                                </div>
                                <span class="text-xs text-gray-600 dark:text-gray-400 font-semibold">(21 reviews)</span>
                            </div>
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <p class="text-3xl font-black gradient-text">$1,500</p>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <button class="flex-1 h-12 rounded-xl bg-primary/10 dark:bg-primary/20 text-primary dark:text-secondary font-bold hover:bg-primary/20 dark:hover:bg-primary/30 transition-all duration-300">
                                    Details
                                </button>
                                <button class="flex-1 h-12 rounded-xl bg-gradient-to-r from-primary to-green-600 text-white font-bold shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-300">
                                    Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- Product Card 6 -->
                    <div class="group card-hover glass-strong rounded-3xl overflow-hidden shadow-xl animate-fade-in" style="animation-delay: 0.5s;">
                        <div class="relative overflow-hidden">
                            <div class="aspect-square bg-cover bg-center transition-transform duration-500 group-hover:scale-110" 
                                 style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCG38J6NGcRX_R7e7vK8MalwEXqqeR4EWamcNY9seK_G9jNUiEoQzQoLRp2mEARNxxMSOvWE2Cw8EBcQPNnqnHDMlasW4znGwmjPcTbnOzXuhxBeEbdqEUwtlG5zdz2JOQW86cPZjQldCq--Y1SV2oduI5uZ4eKONqp8IpN6PiMiOqmSZALZmk1BWJovQ58fx-yEF3w0NzZOkonTYA5EhyXfEcfh6gap2d5ct5xsKA6mPGFpdw1Nb3ovywY4oCr9JmL1aUK6kfFJ9vc");'>
                            </div>
                            <span class="badge-pulse absolute top-4 right-4 px-3 py-1.5 bg-blue-500 text-white text-xs font-bold rounded-full shadow-xl">FEATURED</span>
                            <button class="absolute top-4 left-4 w-10 h-10 bg-white/90 dark:bg-gray-800/90 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300 hover:scale-110">
                                <span class="material-symbols-outlined text-red-500">favorite</span>
                            </button>
                            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent p-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <button class="w-full px-4 py-2 rounded-xl bg-white/20 backdrop-blur-md text-white font-semibold hover:bg-white/30 transition-colors">
                                    Quick View
                                </button>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-2">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Jersey Cow</h3>
                                <span class="px-2.5 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-xs font-bold rounded-full">In Stock</span>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3 line-clamp-2">Rich, creamy milk and smaller frame.</p>
                            <div class="flex items-center gap-2 mb-4">
                                <div class="flex text-yellow-400">
                                    <span class="material-symbols-outlined text-base">star</span>
                                    <span class="material-symbols-outlined text-base">star</span>
                                    <span class="material-symbols-outlined text-base">star</span>
                                    <span class="material-symbols-outlined text-base">star</span>
                                    <span class="material-symbols-outlined text-base">star_half</span>
                                </div>
                                <span class="text-xs text-gray-600 dark:text-gray-400 font-semibold">(21 reviews)</span>
                            </div>
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <p class="text-3xl font-black gradient-text">$1,500</p>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <button class="flex-1 h-12 rounded-xl bg-primary/10 dark:bg-primary/20 text-primary dark:text-secondary font-bold hover:bg-primary/20 dark:hover:bg-primary/30 transition-all duration-300">
                                    Details
                                </button>
                                <button class="flex-1 h-12 rounded-xl bg-gradient-to-r from-primary to-green-600 text-white font-bold shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-300">
                                    Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- Product Card 6 -->
                    <div class="group card-hover glass-strong rounded-3xl overflow-hidden shadow-xl animate-fade-in" style="animation-delay: 0.5s;">
                        <div class="relative overflow-hidden">
                            <div class="aspect-square bg-cover bg-center transition-transform duration-500 group-hover:scale-110" 
                                 style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCG38J6NGcRX_R7e7vK8MalwEXqqeR4EWamcNY9seK_G9jNUiEoQzQoLRp2mEARNxxMSOvWE2Cw8EBcQPNnqnHDMlasW4znGwmjPcTbnOzXuhxBeEbdqEUwtlG5zdz2JOQW86cPZjQldCq--Y1SV2oduI5uZ4eKONqp8IpN6PiMiOqmSZALZmk1BWJovQ58fx-yEF3w0NzZOkonTYA5EhyXfEcfh6gap2d5ct5xsKA6mPGFpdw1Nb3ovywY4oCr9JmL1aUK6kfFJ9vc");'>
                            </div>
                            <span class="badge-pulse absolute top-4 right-4 px-3 py-1.5 bg-blue-500 text-white text-xs font-bold rounded-full shadow-xl">FEATURED</span>
                            <button class="absolute top-4 left-4 w-10 h-10 bg-white/90 dark:bg-gray-800/90 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300 hover:scale-110">
                                <span class="material-symbols-outlined text-red-500">favorite</span>
                            </button>
                            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent p-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <button class="w-full px-4 py-2 rounded-xl bg-white/20 backdrop-blur-md text-white font-semibold hover:bg-white/30 transition-colors">
                                    Quick View
                                </button>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-2">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Jersey Cow</h3>
                                <span class="px-2.5 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-xs font-bold rounded-full">In Stock</span>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3 line-clamp-2">Rich, creamy milk and smaller frame.</p>
                            <div class="flex items-center gap-2 mb-4">
                                <div class="flex text-yellow-400">
                                    <span class="material-symbols-outlined text-base">star</span>
                                    <span class="material-symbols-outlined text-base">star</span>
                                    <span class="material-symbols-outlined text-base">star</span>
                                    <span class="material-symbols-outlined text-base">star</span>
                                    <span class="material-symbols-outlined text-base">star_half</span>
                                </div>
                                <span class="text-xs text-gray-600 dark:text-gray-400 font-semibold">(21 reviews)</span>
                            </div>
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <p class="text-3xl font-black gradient-text">$1,500</p>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <button class="flex-1 h-12 rounded-xl bg-primary/10 dark:bg-primary/20 text-primary dark:text-secondary font-bold hover:bg-primary/20 dark:hover:bg-primary/30 transition-all duration-300">
                                    Details
                                </button>
                                <button class="flex-1 h-12 rounded-xl bg-gradient-to-r from-primary to-green-600 text-white font-bold shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-300">
                                    Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- Product Card 6 -->
                    <div class="group card-hover glass-strong rounded-3xl overflow-hidden shadow-xl animate-fade-in" style="animation-delay: 0.5s;">
                        <div class="relative overflow-hidden">
                            <div class="aspect-square bg-cover bg-center transition-transform duration-500 group-hover:scale-110" 
                                 style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCG38J6NGcRX_R7e7vK8MalwEXqqeR4EWamcNY9seK_G9jNUiEoQzQoLRp2mEARNxxMSOvWE2Cw8EBcQPNnqnHDMlasW4znGwmjPcTbnOzXuhxBeEbdqEUwtlG5zdz2JOQW86cPZjQldCq--Y1SV2oduI5uZ4eKONqp8IpN6PiMiOqmSZALZmk1BWJovQ58fx-yEF3w0NzZOkonTYA5EhyXfEcfh6gap2d5ct5xsKA6mPGFpdw1Nb3ovywY4oCr9JmL1aUK6kfFJ9vc");'>
                            </div>
                            <span class="badge-pulse absolute top-4 right-4 px-3 py-1.5 bg-blue-500 text-white text-xs font-bold rounded-full shadow-xl">FEATURED</span>
                            <button class="absolute top-4 left-4 w-10 h-10 bg-white/90 dark:bg-gray-800/90 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300 hover:scale-110">
                                <span class="material-symbols-outlined text-red-500">favorite</span>
                            </button>
                            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent p-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <button class="w-full px-4 py-2 rounded-xl bg-white/20 backdrop-blur-md text-white font-semibold hover:bg-white/30 transition-colors">
                                    Quick View
                                </button>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-2">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Jersey Cow</h3>
                                <span class="px-2.5 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-xs font-bold rounded-full">In Stock</span>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3 line-clamp-2">Rich, creamy milk and smaller frame.</p>
                            <div class="flex items-center gap-2 mb-4">
                                <div class="flex text-yellow-400">
                                    <span class="material-symbols-outlined text-base">star</span>
                                    <span class="material-symbols-outlined text-base">star</span>
                                    <span class="material-symbols-outlined text-base">star</span>
                                    <span class="material-symbols-outlined text-base">star</span>
                                    <span class="material-symbols-outlined text-base">star_half</span>
                                </div>
                                <span class="text-xs text-gray-600 dark:text-gray-400 font-semibold">(21 reviews)</span>
                            </div>
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <p class="text-3xl font-black gradient-text">$1,500</p>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <button class="flex-1 h-12 rounded-xl bg-primary/10 dark:bg-primary/20 text-primary dark:text-secondary font-bold hover:bg-primary/20 dark:hover:bg-primary/30 transition-all duration-300">
                                    Details
                                </button>
                                <button class="flex-1 h-12 rounded-xl bg-gradient-to-r from-primary to-green-600 text-white font-bold shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-300">
                                    Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- Product Card 6 -->
                    <div class="group card-hover glass-strong rounded-3xl overflow-hidden shadow-xl animate-fade-in" style="animation-delay: 0.5s;">
                        <div class="relative overflow-hidden">
                            <div class="aspect-square bg-cover bg-center transition-transform duration-500 group-hover:scale-110" 
                                 style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCG38J6NGcRX_R7e7vK8MalwEXqqeR4EWamcNY9seK_G9jNUiEoQzQoLRp2mEARNxxMSOvWE2Cw8EBcQPNnqnHDMlasW4znGwmjPcTbnOzXuhxBeEbdqEUwtlG5zdz2JOQW86cPZjQldCq--Y1SV2oduI5uZ4eKONqp8IpN6PiMiOqmSZALZmk1BWJovQ58fx-yEF3w0NzZOkonTYA5EhyXfEcfh6gap2d5ct5xsKA6mPGFpdw1Nb3ovywY4oCr9JmL1aUK6kfFJ9vc");'>
                            </div>
                            <span class="badge-pulse absolute top-4 right-4 px-3 py-1.5 bg-blue-500 text-white text-xs font-bold rounded-full shadow-xl">FEATURED</span>
                            <button class="absolute top-4 left-4 w-10 h-10 bg-white/90 dark:bg-gray-800/90 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300 hover:scale-110">
                                <span class="material-symbols-outlined text-red-500">favorite</span>
                            </button>
                            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent p-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <button class="w-full px-4 py-2 rounded-xl bg-white/20 backdrop-blur-md text-white font-semibold hover:bg-white/30 transition-colors">
                                    Quick View
                                </button>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-2">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Jersey Cow</h3>
                                <span class="px-2.5 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-xs font-bold rounded-full">In Stock</span>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3 line-clamp-2">Rich, creamy milk and smaller frame.</p>
                            <div class="flex items-center gap-2 mb-4">
                                <div class="flex text-yellow-400">
                                    <span class="material-symbols-outlined text-base">star</span>
                                    <span class="material-symbols-outlined text-base">star</span>
                                    <span class="material-symbols-outlined text-base">star</span>
                                    <span class="material-symbols-outlined text-base">star</span>
                                    <span class="material-symbols-outlined text-base">star_half</span>
                                </div>
                                <span class="text-xs text-gray-600 dark:text-gray-400 font-semibold">(21 reviews)</span>
                            </div>
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <p class="text-3xl font-black gradient-text">$1,500</p>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <button class="flex-1 h-12 rounded-xl bg-primary/10 dark:bg-primary/20 text-primary dark:text-secondary font-bold hover:bg-primary/20 dark:hover:bg-primary/30 transition-all duration-300">
                                    Details
                                </button>
                                <button class="flex-1 h-12 rounded-xl bg-gradient-to-r from-primary to-green-600 text-white font-bold shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-300">
                                    Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- Product Card 6 -->
                    <div class="group card-hover glass-strong rounded-3xl overflow-hidden shadow-xl animate-fade-in" style="animation-delay: 0.5s;">
                        <div class="relative overflow-hidden">
                            <div class="aspect-square bg-cover bg-center transition-transform duration-500 group-hover:scale-110" 
                                 style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCG38J6NGcRX_R7e7vK8MalwEXqqeR4EWamcNY9seK_G9jNUiEoQzQoLRp2mEARNxxMSOvWE2Cw8EBcQPNnqnHDMlasW4znGwmjPcTbnOzXuhxBeEbdqEUwtlG5zdz2JOQW86cPZjQldCq--Y1SV2oduI5uZ4eKONqp8IpN6PiMiOqmSZALZmk1BWJovQ58fx-yEF3w0NzZOkonTYA5EhyXfEcfh6gap2d5ct5xsKA6mPGFpdw1Nb3ovywY4oCr9JmL1aUK6kfFJ9vc");'>
                            </div>
                            <span class="badge-pulse absolute top-4 right-4 px-3 py-1.5 bg-blue-500 text-white text-xs font-bold rounded-full shadow-xl">FEATURED</span>
                            <button class="absolute top-4 left-4 w-10 h-10 bg-white/90 dark:bg-gray-800/90 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300 hover:scale-110">
                                <span class="material-symbols-outlined text-red-500">favorite</span>
                            </button>
                            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent p-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <button class="w-full px-4 py-2 rounded-xl bg-white/20 backdrop-blur-md text-white font-semibold hover:bg-white/30 transition-colors">
                                    Quick View
                                </button>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-2">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Jersey Cow</h3>
                                <span class="px-2.5 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-xs font-bold rounded-full">In Stock</span>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3 line-clamp-2">Rich, creamy milk and smaller frame.</p>
                            <div class="flex items-center gap-2 mb-4">
                                <div class="flex text-yellow-400">
                                    <span class="material-symbols-outlined text-base">star</span>
                                    <span class="material-symbols-outlined text-base">star</span>
                                    <span class="material-symbols-outlined text-base">star</span>
                                    <span class="material-symbols-outlined text-base">star</span>
                                    <span class="material-symbols-outlined text-base">star_half</span>
                                </div>
                                <span class="text-xs text-gray-600 dark:text-gray-400 font-semibold">(21 reviews)</span>
                            </div>
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <p class="text-3xl font-black gradient-text">$1,500</p>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <button class="flex-1 h-12 rounded-xl bg-primary/10 dark:bg-primary/20 text-primary dark:text-secondary font-bold hover:bg-primary/20 dark:hover:bg-primary/30 transition-all duration-300">
                                    Details
                                </button>
                                <button class="flex-1 h-12 rounded-xl bg-gradient-to-r from-primary to-green-600 text-white font-bold shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-300">
                                    Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- Product Card 6 -->
                    <div class="group card-hover glass-strong rounded-3xl overflow-hidden shadow-xl animate-fade-in" style="animation-delay: 0.5s;">
                        <div class="relative overflow-hidden">
                            <div class="aspect-square bg-cover bg-center transition-transform duration-500 group-hover:scale-110" 
                                 style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCG38J6NGcRX_R7e7vK8MalwEXqqeR4EWamcNY9seK_G9jNUiEoQzQoLRp2mEARNxxMSOvWE2Cw8EBcQPNnqnHDMlasW4znGwmjPcTbnOzXuhxBeEbdqEUwtlG5zdz2JOQW86cPZjQldCq--Y1SV2oduI5uZ4eKONqp8IpN6PiMiOqmSZALZmk1BWJovQ58fx-yEF3w0NzZOkonTYA5EhyXfEcfh6gap2d5ct5xsKA6mPGFpdw1Nb3ovywY4oCr9JmL1aUK6kfFJ9vc");'>
                            </div>
                            <span class="badge-pulse absolute top-4 right-4 px-3 py-1.5 bg-blue-500 text-white text-xs font-bold rounded-full shadow-xl">FEATURED</span>
                            <button class="absolute top-4 left-4 w-10 h-10 bg-white/90 dark:bg-gray-800/90 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300 hover:scale-110">
                                <span class="material-symbols-outlined text-red-500">favorite</span>
                            </button>
                            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent p-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <button class="w-full px-4 py-2 rounded-xl bg-white/20 backdrop-blur-md text-white font-semibold hover:bg-white/30 transition-colors">
                                    Quick View
                                </button>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-2">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Jersey Cow</h3>
                                <span class="px-2.5 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-xs font-bold rounded-full">In Stock</span>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3 line-clamp-2">Rich, creamy milk and smaller frame.</p>
                            <div class="flex items-center gap-2 mb-4">
                                <div class="flex text-yellow-400">
                                    <span class="material-symbols-outlined text-base">star</span>
                                    <span class="material-symbols-outlined text-base">star</span>
                                    <span class="material-symbols-outlined text-base">star</span>
                                    <span class="material-symbols-outlined text-base">star</span>
                                    <span class="material-symbols-outlined text-base">star_half</span>
                                </div>
                                <span class="text-xs text-gray-600 dark:text-gray-400 font-semibold">(21 reviews)</span>
                            </div>
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <p class="text-3xl font-black gradient-text">$1,500</p>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <button class="flex-1 h-12 rounded-xl bg-primary/10 dark:bg-primary/20 text-primary dark:text-secondary font-bold hover:bg-primary/20 dark:hover:bg-primary/30 transition-all duration-300">
                                    Details
                                </button>
                                <button class="flex-1 h-12 rounded-xl bg-gradient-to-r from-primary to-green-600 text-white font-bold shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-300">
                                    Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- Product Card 6 -->
                    <div class="group card-hover glass-strong rounded-3xl overflow-hidden shadow-xl animate-fade-in" style="animation-delay: 0.5s;">
                        <div class="relative overflow-hidden">
                            <div class="aspect-square bg-cover bg-center transition-transform duration-500 group-hover:scale-110" 
                                 style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCG38J6NGcRX_R7e7vK8MalwEXqqeR4EWamcNY9seK_G9jNUiEoQzQoLRp2mEARNxxMSOvWE2Cw8EBcQPNnqnHDMlasW4znGwmjPcTbnOzXuhxBeEbdqEUwtlG5zdz2JOQW86cPZjQldCq--Y1SV2oduI5uZ4eKONqp8IpN6PiMiOqmSZALZmk1BWJovQ58fx-yEF3w0NzZOkonTYA5EhyXfEcfh6gap2d5ct5xsKA6mPGFpdw1Nb3ovywY4oCr9JmL1aUK6kfFJ9vc");'>
                            </div>
                            <span class="badge-pulse absolute top-4 right-4 px-3 py-1.5 bg-blue-500 text-white text-xs font-bold rounded-full shadow-xl">FEATURED</span>
                            <button class="absolute top-4 left-4 w-10 h-10 bg-white/90 dark:bg-gray-800/90 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300 hover:scale-110">
                                <span class="material-symbols-outlined text-red-500">favorite</span>
                            </button>
                            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent p-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <button class="w-full px-4 py-2 rounded-xl bg-white/20 backdrop-blur-md text-white font-semibold hover:bg-white/30 transition-colors">
                                    Quick View
                                </button>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-2">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Jersey Cow</h3>
                                <span class="px-2.5 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-xs font-bold rounded-full">In Stock</span>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3 line-clamp-2">Rich, creamy milk and smaller frame.</p>
                            <div class="flex items-center gap-2 mb-4">
                                <div class="flex text-yellow-400">
                                    <span class="material-symbols-outlined text-base">star</span>
                                    <span class="material-symbols-outlined text-base">star</span>
                                    <span class="material-symbols-outlined text-base">star</span>
                                    <span class="material-symbols-outlined text-base">star</span>
                                    <span class="material-symbols-outlined text-base">star_half</span>
                                </div>
                                <span class="text-xs text-gray-600 dark:text-gray-400 font-semibold">(21 reviews)</span>
                            </div>
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <p class="text-3xl font-black gradient-text">$1,500</p>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <button class="flex-1 h-12 rounded-xl bg-primary/10 dark:bg-primary/20 text-primary dark:text-secondary font-bold hover:bg-primary/20 dark:hover:bg-primary/30 transition-all duration-300">
                                    Details
                                </button>
                                <button class="flex-1 h-12 rounded-xl bg-gradient-to-r from-primary to-green-600 text-white font-bold shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-300">
                                    Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- Product Card 6 -->
                    <div class="group card-hover glass-strong rounded-3xl overflow-hidden shadow-xl animate-fade-in" style="animation-delay: 0.5s;">
                        <div class="relative overflow-hidden">
                            <div class="aspect-square bg-cover bg-center transition-transform duration-500 group-hover:scale-110" 
                                 style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCG38J6NGcRX_R7e7vK8MalwEXqqeR4EWamcNY9seK_G9jNUiEoQzQoLRp2mEARNxxMSOvWE2Cw8EBcQPNnqnHDMlasW4znGwmjPcTbnOzXuhxBeEbdqEUwtlG5zdz2JOQW86cPZjQldCq--Y1SV2oduI5uZ4eKONqp8IpN6PiMiOqmSZALZmk1BWJovQ58fx-yEF3w0NzZOkonTYA5EhyXfEcfh6gap2d5ct5xsKA6mPGFpdw1Nb3ovywY4oCr9JmL1aUK6kfFJ9vc");'>
                            </div>
                            <span class="badge-pulse absolute top-4 right-4 px-3 py-1.5 bg-blue-500 text-white text-xs font-bold rounded-full shadow-xl">FEATURED</span>
                            <button class="absolute top-4 left-4 w-10 h-10 bg-white/90 dark:bg-gray-800/90 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300 hover:scale-110">
                                <span class="material-symbols-outlined text-red-500">favorite</span>
                            </button>
                            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent p-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <button class="w-full px-4 py-2 rounded-xl bg-white/20 backdrop-blur-md text-white font-semibold hover:bg-white/30 transition-colors">
                                    Quick View
                                </button>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-2">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Jersey Cow</h3>
                                <span class="px-2.5 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-xs font-bold rounded-full">In Stock</span>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3 line-clamp-2">Rich, creamy milk and smaller frame.</p>
                            <div class="flex items-center gap-2 mb-4">
                                <div class="flex text-yellow-400">
                                    <span class="material-symbols-outlined text-base">star</span>
                                    <span class="material-symbols-outlined text-base">star</span>
                                    <span class="material-symbols-outlined text-base">star</span>
                                    <span class="material-symbols-outlined text-base">star</span>
                                    <span class="material-symbols-outlined text-base">star_half</span>
                                </div>
                                <span class="text-xs text-gray-600 dark:text-gray-400 font-semibold">(21 reviews)</span>
                            </div>
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <p class="text-3xl font-black gradient-text">$1,500</p>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <button class="flex-1 h-12 rounded-xl bg-primary/10 dark:bg-primary/20 text-primary dark:text-secondary font-bold hover:bg-primary/20 dark:hover:bg-primary/30 transition-all duration-300">
                                    Details
                                </button>
                                <button class="flex-1 h-12 rounded-xl bg-gradient-to-r from-primary to-green-600 text-white font-bold shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-300">
                                    Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- Product Card 6 -->
                    <div class="group card-hover glass-strong rounded-3xl overflow-hidden shadow-xl animate-fade-in" style="animation-delay: 0.5s;">
                        <div class="relative overflow-hidden">
                            <div class="aspect-square bg-cover bg-center transition-transform duration-500 group-hover:scale-110" 
                                 style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCG38J6NGcRX_R7e7vK8MalwEXqqeR4EWamcNY9seK_G9jNUiEoQzQoLRp2mEARNxxMSOvWE2Cw8EBcQPNnqnHDMlasW4znGwmjPcTbnOzXuhxBeEbdqEUwtlG5zdz2JOQW86cPZjQldCq--Y1SV2oduI5uZ4eKONqp8IpN6PiMiOqmSZALZmk1BWJovQ58fx-yEF3w0NzZOkonTYA5EhyXfEcfh6gap2d5ct5xsKA6mPGFpdw1Nb3ovywY4oCr9JmL1aUK6kfFJ9vc");'>
                            </div>
                            <span class="badge-pulse absolute top-4 right-4 px-3 py-1.5 bg-blue-500 text-white text-xs font-bold rounded-full shadow-xl">FEATURED</span>
                            <button class="absolute top-4 left-4 w-10 h-10 bg-white/90 dark:bg-gray-800/90 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300 hover:scale-110">
                                <span class="material-symbols-outlined text-red-500">favorite</span>
                            </button>
                            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent p-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <button class="w-full px-4 py-2 rounded-xl bg-white/20 backdrop-blur-md text-white font-semibold hover:bg-white/30 transition-colors">
                                    Quick View
                                </button>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-2">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Jersey Cow</h3>
                                <span class="px-2.5 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-xs font-bold rounded-full">In Stock</span>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3 line-clamp-2">Rich, creamy milk and smaller frame.</p>
                            <div class="flex items-center gap-2 mb-4">
                                <div class="flex text-yellow-400">
                                    <span class="material-symbols-outlined text-base">star</span>
                                    <span class="material-symbols-outlined text-base">star</span>
                                    <span class="material-symbols-outlined text-base">star</span>
                                    <span class="material-symbols-outlined text-base">star</span>
                                    <span class="material-symbols-outlined text-base">star_half</span>
                                </div>
                                <span class="text-xs text-gray-600 dark:text-gray-400 font-semibold">(21 reviews)</span>
                            </div>
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <p class="text-3xl font-black gradient-text">$1,500</p>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <button class="flex-1 h-12 rounded-xl bg-primary/10 dark:bg-primary/20 text-primary dark:text-secondary font-bold hover:bg-primary/20 dark:hover:bg-primary/30 transition-all duration-300">
                                    Details
                                </button>
                                <button class="flex-1 h-12 rounded-xl bg-gradient-to-r from-primary to-green-600 text-white font-bold shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-300">
                                    Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- Product Card 6 -->
                    <div class="group card-hover glass-strong rounded-3xl overflow-hidden shadow-xl animate-fade-in" style="animation-delay: 0.5s;">
                        <div class="relative overflow-hidden">
                            <div class="aspect-square bg-cover bg-center transition-transform duration-500 group-hover:scale-110" 
                                 style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCG38J6NGcRX_R7e7vK8MalwEXqqeR4EWamcNY9seK_G9jNUiEoQzQoLRp2mEARNxxMSOvWE2Cw8EBcQPNnqnHDMlasW4znGwmjPcTbnOzXuhxBeEbdqEUwtlG5zdz2JOQW86cPZjQldCq--Y1SV2oduI5uZ4eKONqp8IpN6PiMiOqmSZALZmk1BWJovQ58fx-yEF3w0NzZOkonTYA5EhyXfEcfh6gap2d5ct5xsKA6mPGFpdw1Nb3ovywY4oCr9JmL1aUK6kfFJ9vc");'>
                            </div>
                            <span class="badge-pulse absolute top-4 right-4 px-3 py-1.5 bg-blue-500 text-white text-xs font-bold rounded-full shadow-xl">FEATURED</span>
                            <button class="absolute top-4 left-4 w-10 h-10 bg-white/90 dark:bg-gray-800/90 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300 hover:scale-110">
                                <span class="material-symbols-outlined text-red-500">favorite</span>
                            </button>
                            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent p-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <button class="w-full px-4 py-2 rounded-xl bg-white/20 backdrop-blur-md text-white font-semibold hover:bg-white/30 transition-colors">
                                    Quick View
                                </button>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-2">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Jersey Cow</h3>
                                <span class="px-2.5 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-xs font-bold rounded-full">In Stock</span>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3 line-clamp-2">Rich, creamy milk and smaller frame.</p>
                            <div class="flex items-center gap-2 mb-4">
                                <div class="flex text-yellow-400">
                                    <span class="material-symbols-outlined text-base">star</span>
                                    <span class="material-symbols-outlined text-base">star</span>
                                    <span class="material-symbols-outlined text-base">star</span>
                                    <span class="material-symbols-outlined text-base">star</span>
                                    <span class="material-symbols-outlined text-base">star_half</span>
                                </div>
                                <span class="text-xs text-gray-600 dark:text-gray-400 font-semibold">(21 reviews)</span>
                            </div>
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <p class="text-3xl font-black gradient-text">$1,500</p>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <button class="flex-1 h-12 rounded-xl bg-primary/10 dark:bg-primary/20 text-primary dark:text-secondary font-bold hover:bg-primary/20 dark:hover:bg-primary/30 transition-all duration-300">
                                    Details
                                </button>
                                <button class="flex-1 h-12 rounded-xl bg-gradient-to-r from-primary to-green-600 text-white font-bold shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-300">
                                    Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>


                    </div>
                </div>

                <!-- Load More Section -->
                <div class="flex justify-center mt-16">
                    <button class="group flex items-center gap-3 px-8 h-14 rounded-2xl glass-strong font-bold shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-300">
                        <span class="material-symbols-outlined text-primary dark:text-secondary">eco</span>
                        <span>Load More Animals</span>
                        <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">arrow_forward</span>
                    </button>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="relative py-12 glass border-t border-white/10 mt-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                    <!-- Brand -->
                    <div class="md:col-span-2">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="size-12 bg-gradient-to-br from-primary to-green-600 rounded-2xl flex items-center justify-center shadow-lg">
                                <span class="material-symbols-outlined text-3xl text-white">eco</span>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold gradient-text">Lembah Hijau</h3>
                                <p class="text-xs text-gray-600 dark:text-gray-400">Premium </p>
                            </div>
                        </div>
                        <p class="text-gray-600 dark:text-gray-400 mb-6 max-w-md">Your trusted partner in eco-friendly and sustainable livestock marketplace.</p>
                        <div class="flex gap-3">
                            {{-- <a href="#" class="size-10 rounded-xl glass-strong flex items-center justify-center hover:scale-110 transition-transform">
                                <span class="material-symbols-outlined text-primary dark:text-secondary">facebook</span>
                            </a>
                            {{-- <a href="#" class="size-10 rounded-xl glass-strong flex items-center justify-center hover:scale-110 transition-transform">
                                <span class="material-symbols-outlined text-primary dark:text-secondary">instagram</span>
                            </a> --}}
                            {{-- <a href="#" class="size-10 rounded-xl glass-strong flex items-center justify-center hover:scale-110 transition-transform">
                                <span class="material-symbols-outlined text-primary dark:text-secondary">mail</span>
                            </a> -- --}}
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div>
                        <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Quick Links</h4>
                        <ul class="space-y-3">
                            <li><a href="#" class="text-gray-600 dark:text-gray-400 hover:text-primary dark:hover:text-secondary transition-colors">About Us</a></li>
                            <li><a href="#" class="text-gray-600 dark:text-gray-400 hover:text-primary dark:hover:text-secondary transition-colors">Products</a></li>
                            <li><a href="#" class="text-gray-600 dark:text-gray-400 hover:text-primary dark:hover:text-secondary transition-colors">Blog</a></li>
                            <li><a href="#" class="text-gray-600 dark:text-gray-400 hover:text-primary dark:hover:text-secondary transition-colors">Contact</a></li>
                        </ul>
                    </div>

                    <!-- Contact -->
                    <div>
                        <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Contact</h4>
                        <ul class="space-y-3 text-gray-600 dark:text-gray-400 text-sm">
                            <li>123 Green Valley</li>
                            <li>Farmville, Indonesia</li>
                            <li>contact@lembahhijau.com</li>
                            <li>(123) 456-7890</li>
                        </ul>
                    </div>
                </div>

                <!-- Bottom Bar -->
                <div class="border-t border-white/10 pt-8">
                    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                        <p class="text-sm text-gray-600 dark:text-gray-400">© 2025 Lembah Hijau. All rights reserved.</p>
                        <div class="flex gap-6 text-sm">
                            <a href="#" class="text-gray-600 dark:text-gray-400 hover:text-primary dark:hover:text-secondary transition-colors">Privacy Policy</a>
                            <a href="#" class="text-gray-600 dark:text-gray-400 hover:text-primary dark:hover:text-secondary transition-colors">Terms of Service</a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>