<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ locale: '{{ app()->getLocale() }}' }">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Ecommerce TSA Website Resmi</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
                        "display": ["Plus Jakarta Sans", "sans-serif"]
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'slide-up': 'slideUp 0.8s ease-out',
                        'fade-in': 'fadeIn 1s ease-out',
                        'scale-in': 'scaleIn 0.5s ease-out',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': {
                                transform: 'translateY(0px)'
                            },
                            '50%': {
                                transform: 'translateY(-20px)'
                            },
                        },
                        slideUp: {
                            '0%': {
                                transform: 'translateY(100px)',
                                opacity: '0'
                            },
                            '100%': {
                                transform: 'translateY(0)',
                                opacity: '1'
                            },
                        },
                        fadeIn: {
                            '0%': {
                                opacity: '0'
                            },
                            '100%': {
                                opacity: '1'
                            },
                        },
                        scaleIn: {
                            '0%': {
                                transform: 'scale(0.9)',
                                opacity: '0'
                            },
                            '100%': {
                                transform: 'scale(1)',
                                opacity: '1'
                            },
                        }
                    }
                },
            },
        }
    </script>
    <style>
        .glass {
            background: rgba(236, 236, 236, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .dark .glass {
            background: rgba(19, 31, 19, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .glass-strong {
            background: rgba(254, 249, 249, 0.9);
            backdrop-filter: blur(30px);
            -webkit-backdrop-filter: blur(30px);
            border: 1px solid rgba(244, 236, 236, 0.5);
        }

        .dark .glass-strong {
            background: rgba(19, 31, 19, 0.9);
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        .gradient-mesh {
            background:
                radial-gradient(at 0% 0%, rgba(46, 125, 50, 0.15) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(253, 216, 53, 0.15) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(129, 212, 250, 0.15) 0px, transparent 50%),
                radial-gradient(at 0% 100%, rgba(46, 125, 50, 0.1) 0px, transparent 50%);
        }

        .card-hover {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card-hover:hover {
            transform: translateY(-12px) scale(1.02);
        }

        .text-gradient {
            background: linear-gradient(135deg, #2E7D32 0%, #4CAF50 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .blob {
            border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
            animation: blob 8s ease-in-out infinite;
        }

        @keyframes blob {

            0%,
            100% {
                border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
            }

            25% {
                border-radius: 58% 42% 75% 25% / 76% 46% 54% 24%;
            }

            50% {
                border-radius: 50% 50% 33% 67% / 55% 27% 73% 45%;
            }

            75% {
                border-radius: 33% 67% 58% 42% / 63% 68% 32% 37%;
            }
        }
    </style>
</head>

<body class="bg-white dark:bg-[#0a0f0a] font-display text-gray-900 dark:text-white overflow-x-hidden">

    <div class="relative min-h-screen gradient-mesh">
        <!-- Decorative Blobs -->
        <div class="fixed top-20 left-10 w-96 h-96 bg-primary/20 dark:bg-primary/10 blur-3xl blob -z-10"></div>
        <div class="fixed bottom-20 right-10 w-96 h-96 bg-secondary/20 dark:bg-secondary/10 blur-3xl blob -z-10"
            style="animation-delay: 2s;"></div>
        <div class="fixed top-1/2 left-1/2 w-96 h-96 bg-accent/20 dark:bg-accent/10 blur-3xl blob -z-10"
            style="animation-delay: 4s;"></div>

        <!-- HEADER -->
        <header class="fixed top-0 left-0 right-0 z-50 glass shadow-lg">
            <div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between py-4">
                    <!-- Logo -->
                    <div class="flex items-center gap-3 group cursor-pointer">
                        <div
                            class="size-11 bg-gradient-to-br from-primary to-green-600 rounded-2xl flex items-center justify-center transform group-hover:rotate-12 transition-all duration-300 shadow-lg">
                            <span class="material-symbols-outlined text-3xl text-white">storefront</span>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gradient">E-Commerce TSA</h2>
                            <p class="text-xs text-gray-600 dark:text-gray-400">{{ __('Livestock Trade') }}</p>
                        </div>
                    </div>

                    <!-- Desktop Nav -->
                    <nav class="hidden md:flex items-center gap-8">
                        <a href="#home"
                            class="nav-link text-sm font-semibold text-gray-700 dark:text-gray-300 hover:text-primary dark:hover:text-secondary transition-colors">{{ __('Home') }}</a>
                        <a href="#about"
                            class="nav-link text-sm font-semibold text-gray-700 dark:text-gray-300 hover:text-primary dark:hover:text-secondary transition-colors">{{ __('About') }}</a>
                        <a href="#shop"
                            class="nav-link text-sm font-semibold text-gray-700 dark:text-gray-300 hover:text-primary dark:hover:text-secondary transition-colors">{{ __('Livestock') }}</a>
                        <a href="#why"
                            class="nav-link text-sm font-semibold text-gray-700 dark:text-gray-300 hover:text-primary dark:hover:text-secondary transition-colors">{{ __('Advantages') }}</a>
                        <a href="#contact"
                            class="nav-link text-sm font-semibold text-gray-700 dark:text-gray-300 hover:text-primary dark:hover:text-secondary transition-colors">{{ __('Contact') }}</a>
                    </nav>

                    <!-- CTA Buttons -->
                    <div class="hidden md:flex items-center gap-3">
                        <!-- Language Switcher -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" 
                                    class="size-11 rounded-full glass-strong flex items-center justify-center hover:scale-110 transition-transform duration-300 text-primary dark:text-secondary">
                                <span class="material-symbols-outlined">language</span>
                            </button>
                            <div x-show="open" @click.away="open = false" x-transition
                                 class="absolute right-0 mt-2 w-48 bg-white dark:bg-zinc-800 rounded-2xl shadow-xl border border-gray-200 dark:border-zinc-700 py-2 z-50">
                                <a href="{{ route('lang.switch', 'id') }}" 
                                   class="flex items-center justify-between px-4 py-2 text-sm {{ app()->getLocale() == 'id' ? 'text-primary font-bold bg-primary/5' : 'text-gray-700 dark:text-gray-300 hover:bg-primary/5' }}">
                                    <span>Bahasa Indonesia</span>
                                    @if(app()->getLocale() == 'id') <span class="material-symbols-outlined text-sm">check</span> @endif
                                </a>
                                <a href="{{ route('lang.switch', 'en') }}" 
                                   class="flex items-center justify-between px-4 py-2 text-sm {{ app()->getLocale() == 'en' ? 'text-primary font-bold bg-primary/5' : 'text-gray-700 dark:text-gray-300 hover:bg-primary/5' }}">
                                    <span>English</span>
                                    @if(app()->getLocale() == 'en') <span class="material-symbols-outlined text-sm">check</span> @endif
                                </a>
                            </div>
                        </div>

                        <button onclick="document.documentElement.classList.toggle('dark')"
                            class="size-11 rounded-full glass-strong flex items-center justify-center hover:scale-110 transition-transform duration-300">
                            <span class="material-symbols-outlined text-primary dark:text-secondary">dark_mode</span>
                        </button>
                        <a href="{{ route('login') }}"
                            class="px-6 h-11 rounded-full bg-gradient-to-r from-primary to-green-600 text-white font-bold shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-300 flex items-center gap-2">
                            <span>{{ __('Shop Now') }}</span>
                            <span class="material-symbols-outlined">arrow_forward</span>
                        </a>
                    </div>

                    <!-- Mobile Menu Button -->
                    <button id="mobile-menu-btn"
                        class="md:hidden size-11 rounded-full glass-strong flex items-center justify-center">
                        <span class="material-symbols-outlined text-primary dark:text-secondary">menu</span>
                    </button>
                </div>

                <!-- Mobile Menu -->
                <div id="mobile-menu" class="hidden md:hidden pb-4">
                    <nav class="flex flex-col gap-3 glass-strong rounded-2xl p-4 mt-2">
                        <a href="#home"
                            class="nav-link text-sm font-semibold text-gray-700 dark:text-gray-300 py-2 px-4 rounded-lg hover:bg-primary/10 transition-colors">{{ __('Home') }}</a>
                        <a href="#about"
                            class="nav-link text-sm font-semibold text-gray-700 dark:text-gray-300 py-2 px-4 rounded-lg hover:bg-primary/10 transition-colors">{{ __('About') }}</a>
                        <a href="#shop"
                            class="nav-link text-sm font-semibold text-gray-700 dark:text-gray-300 py-2 px-4 rounded-lg hover:bg-primary/10 transition-colors">{{ __('Livestock') }}</a>
                        <a href="#why"
                            class="nav-link text-sm font-semibold text-gray-700 dark:text-gray-300 py-2 px-4 rounded-lg hover:bg-primary/10 transition-colors">{{ __('Advantages') }}</a>
                        <a href="#contact"
                            class="nav-link text-sm font-semibold text-gray-700 dark:text-gray-300 py-2 px-4 rounded-lg hover:bg-primary/10 transition-colors">{{ __('Contact') }}</a>
                        <div class="flex gap-2 mt-2" x-data="{ open: false }">
                            <div class="relative flex-1">
                                <button @click="open = !open" 
                                    class="w-full h-11 rounded-full glass-strong flex items-center justify-center gap-2 text-primary dark:text-secondary">
                                    <span class="material-symbols-outlined">language</span>
                                    <span class="text-xs font-bold uppercase">{{ app()->getLocale() }}</span>
                                </button>
                                <div x-show="open" @click.away="open = false" x-transition
                                     class="absolute bottom-full left-0 right-0 mb-2 bg-white dark:bg-zinc-800 rounded-2xl shadow-xl border border-gray-200 dark:border-zinc-700 py-2 z-50">
                                    <a href="{{ route('lang.switch', 'id') }}" 
                                       class="flex items-center justify-between px-4 py-2 text-sm {{ app()->getLocale() == 'id' ? 'text-primary font-bold bg-primary/5' : 'text-gray-700 dark:text-gray-300 hover:bg-primary/5' }}">
                                        <span>{{ app()->getLocale() == 'id' ? 'Bahasa Indonesia' : 'English' }}</span>
                                    </a>
                                </div>
                            </div>
                            <button onclick="document.documentElement.classList.toggle('dark')"
                                class="flex-1 h-11 rounded-full glass-strong flex items-center justify-center gap-2">
                                <span
                                    class="material-symbols-outlined text-primary dark:text-secondary">dark_mode</span>
                            </button>
                            <a href="#shop"
                                class="flex-1 h-11 rounded-full bg-gradient-to-r from-primary to-green-600 text-white font-bold flex items-center justify-center gap-2">
                                <span>{{ __('Shop') }}</span>
                            </a>
                        </div>
                    </nav>
                </div>
            </div>
        </header>

        <!-- HERO SECTION -->
        <section id="home"
            class="relative min-h-screen flex items-center justify-center pt-20 bg-center bg-cover bg-no-repeat"
            style="background-image: url('images/gajah.jpg');">
            <!-- Overlay gradient -->
            <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/50 to-black/30"></div>

            <!-- Konten utama -->
            <div class="relative z-10 container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-20">
                <div class="text-center animate-fade-in">

                    <!-- Label atas -->
                    <div class="inline-block mb-6">
                        <div class="glass-strong px-6 py-3 rounded-full">
                            <p class="text-sm font-semibold text-primary dark:text-secondary flex items-center gap-2">
                                <span class="size-2 bg-green-500 rounded-full animate-pulse"></span>
                                {{ __('Guaranteed Premium Quality') }}
                            </p>
                        </div>
                    </div>

                    <!-- Judul utama -->
                    <h1 class="text-5xl sm:text-6xl md:text-7xl lg:text-8xl font-black leading-tight mb-6 text-white">
                        <span class="text-gradient">E-Commerce TSA</span><br />
                        <span class="text-gray-100">{{ __('Trusted Livestock Market') }}</span>
                    </h1>

                    <!-- Deskripsi -->
                    <p class="text-lg sm:text-xl text-gray-200 max-w-3xl mx-auto mb-12">
                        {{ __('Welcome to Ecommerce TSA, your trusted source for naturally raised livestock with full transparency and high ethical standards.') }}
                    </p>

                    <!-- Tombol -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                        <a href="{{ route('gallery.hewan') }}"
                            class="group px-8 h-14 rounded-full bg-gradient-to-r from-primary to-green-600 text-white font-bold shadow-xl hover:shadow-2xl hover:scale-105 transition-all duration-300 flex items-center gap-3">
                            <span>{{ __('Explore Animals') }}</span>
                            <span
                                class="material-symbols-outlined group-hover:translate-x-1 transition-transform">arrow_forward</span>
                        </a>
                        <a href="#about"
                            class="px-8 h-14 rounded-full glass-strong font-bold hover:scale-105 transition-all duration-300 flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary dark:text-secondary">play_circle</span>
                            <span>{{ __('Learn More') }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- STATS SECTION -->
        <section class="bg-dark dark:bg-gray-900 py-24">
            <div class="container mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div class="rounded-2xl p-8 bg-white dark:bg-gray-800 shadow-xl text-center card-hover">
                        <p class="text-4xl font-black text-gradient mb-2">500+</p>
                        <p class="text-sm text-gray-700 dark:text-gray-300 font-semibold">{{ __('Happy Customers') }}</p>
                    </div>
                    <div class="rounded-2xl p-8 bg-white dark:bg-gray-800 shadow-xl text-center card-hover">
                        <p class="text-4xl font-black text-gradient mb-2">1000+</p>
                        <p class="text-sm text-gray-700 dark:text-gray-300 font-semibold">{{ __('Animals Sold') }}</p>
                    </div>
                    <div class="rounded-2xl p-8 bg-white dark:bg-gray-800 shadow-xl text-center card-hover">
                        <p class="text-4xl font-black text-gradient mb-2">50+</p>
                        <p class="text-sm text-gray-700 dark:text-gray-300 font-semibold">{{ __('Farm Partners') }}</p>
                    </div>
                    <div class="rounded-2xl p-8 bg-white dark:bg-gray-800 shadow-xl text-center card-hover">
                        <p class="text-4xl font-black text-gradient mb-2">100%</p>
                        <p class="text-sm text-gray-700 dark:text-gray-300 font-semibold">{{ __('Eco Friendly') }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ABOUT SECTION -->
        <section id="about" class="py-24 relative">
            <div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <!-- Image with Glass Frame -->
                    <div class="relative animate-slide-up">
                        <div class="glass-strong rounded-3xl p-3 shadow-2xl">
                            <div class="aspect-[4/3] rounded-2xl overflow-hidden">
                                <img src="images/gajah.jpg" alt="Peternakan" class="w-full h-full object-cover">
                            </div>
                        </div>
                        <!-- Floating Badge -->
                        <div class="absolute -top-6 -right-6 glass-strong rounded-2xl p-4 shadow-xl animate-float">
                            <div class="flex items-center gap-3">
                                <div
                                    class="size-12 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center">
                                    <span class="material-symbols-outlined text-2xl text-white">verified</span>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900 dark:text-white">{{ __('Certified') }}</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">{{ __('100% Organic') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="animate-slide-up" style="animation-delay: 0.2s;">
                        <div class="inline-block mb-6">
                            <div class="glass-strong px-4 py-2 rounded-full">
                                <p class="text-sm font-semibold text-primary dark:text-secondary">{{ __('About Us') }}</p>
                            </div>
                        </div>

                        <h2 class="text-4xl md:text-5xl font-black text-gray-900 dark:text-white mb-6">
                            {{ __('Your Trusted Partner in') }} <span class="text-gradient">{{ __('Sustainable Livestock') }}</span>
                        </h2>

                        <p class="text-lg text-gray-600 dark:text-gray-400 mb-6 leading-relaxed">
                            {{ __('Ecommerce TSA is dedicated to providing the freshest and naturally raised livestock. Our commitment to eco-friendly farming practices ensures that our animals are healthy, happy, and raised in a sustainable environment.') }}
                        </p>

                        <p class="text-lg text-gray-600 dark:text-gray-400 mb-8 leading-relaxed">
                            {{ __('We believe in a transparent and ethical approach to livestock sales, connecting you directly with trusted farms that share the same values.') }}
                        </p>

                        <!-- Features -->
                        <div class="grid grid-cols-2 gap-4 mb-8">
                            <div class="flex items-center gap-3">
                                <div
                                    class="size-10 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                                    <span
                                        class="material-symbols-outlined text-green-600 dark:text-green-400">check_circle</span>
                                </div>
                                <p class="font-semibold text-gray-900 dark:text-white">{{ __('Eco Friendly') }}</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <div
                                    class="size-10 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                                    <span
                                        class="material-symbols-outlined text-green-600 dark:text-green-400">check_circle</span>
                                </div>
                                <p class="font-semibold text-gray-900 dark:text-white">{{ __('100% Natural') }}</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <div
                                    class="size-10 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                                    <span
                                        class="material-symbols-outlined text-green-600 dark:text-green-400">check_circle</span>
                                </div>
                                <p class="font-semibold text-gray-900 dark:text-white">{{ __('Certified Farms') }}</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <div
                                    class="size-10 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                                    <span
                                        class="material-symbols-outlined text-green-600 dark:text-green-400">check_circle</span>
                                </div>
                                <p class="font-semibold text-gray-900 dark:text-white">{{ __('Ethical Trade') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- FEATURED ANIMALS -->
        <section id="shop" class="py-24 relative overflow-hidden">
            <div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <div class="inline-block mb-4">
                        <div class="glass-strong px-4 py-2 rounded-full">
                            <p class="text-sm font-semibold text-primary dark:text-secondary">{{ __('Featured Collection') }}</p>
                        </div>
                    </div>
                    <h2 class="text-4xl md:text-5xl font-black text-gray-900 dark:text-white mb-4">
                        {{ __('Livestock') }} <span class="text-gradient">{{ __('Premium') }}</span>
                    </h2>
                        {{ __('Explore carefully selected collection of healthy, naturally raised animals') }}
                </div>

                <!-- Grid Layout tanpa scroll horizontal -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Card 1 -->
                    <div class="glass-strong rounded-3xl overflow-hidden shadow-xl card-hover">
                        <div class="relative aspect-square overflow-hidden">
                            <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuDHqWW5BgZoA9HFpPNUrLR2y4tdyy0MKq6tz1_c4Tc2XuaYuEpNETCpDMcLFNp4cCzi5zwOdm-rYvW_E-_7NfSO_x7Zb3adGQ7wc4i2K_XF8Hx4gF9j7mQGu6AqpwSyn1YvWZyBzHtofn7e8zLB0veeVTa3nMuB0q9VvzdGMQWBrKpfpWny5GGpXBMqsNP0Hu9QKLR8ujPhFGC9tZ1TgQN6YrfedUwMYzulx9Smb-dqqzVjL6nLnrQMrlLewfzsgpcZG8U__JNweNKk"
                                alt="Kambing" class="w-full h-full object-cover">
                            <div class="absolute top-4 left-4">
                                <div class="glass-strong px-3 py-1 rounded-full">
                                    <p class="text-xs font-bold text-green-600 dark:text-green-400">Tersedia</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Kambing Premium</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Jenis sehat, cocok untuk
                                peternakan</p>
                            <div class="flex items-center justify-between">
                                <p class="text-2xl font-black text-gradient">Rp 3.500.000</p>
                                <button
                                    class="px-4 h-10 rounded-full bg-primary/10 dark:bg-primary/20 text-primary dark:text-secondary font-bold hover:bg-primary hover:text-white transition-all duration-300">
                                    Detail
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2 -->
                    <div class="glass-strong rounded-3xl overflow-hidden shadow-xl card-hover">
                        <div class="relative aspect-square overflow-hidden">
                            <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuDHqWW5BgZoA9HFpPNUrLR2y4tdyy0MKq6tz1_c4Tc2XuaYuEpNETCpDMcLFNp4cCzi5zwOdm-rYvW_E-_7NfSO_x7Zb3adGQ7wc4i2K_XF8Hx4gF9j7mQGu6AqpwSyn1YvWZyBzHtofn7e8zLB0veeVTa3nMuB0q9VvzdGMQWBrKpfpWny5GGpXBMqsNP0Hu9QKLR8ujPhFGC9tZ1TgQN6YrfedUwMYzulx9Smb-dqqzVjL6nLnrQMrlLewfzsgpcZG8U__JNweNKk"
                                alt="Kambing" class="w-full h-full object-cover">
                            <div class="absolute top-4 left-4">
                                <div class="glass-strong px-3 py-1 rounded-full">
                                    <p class="text-xs font-bold text-green-600 dark:text-green-400">{{ __('Available') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ __('Premium Goat') }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">{{ __('Healthy breed, suitable for breeding') }}</p>
                            <div class="flex items-center justify-between">
                                <p class="text-2xl font-black text-gradient">Rp 3.500.000</p>
                                <button
                                    class="px-4 h-10 rounded-full bg-primary/10 dark:bg-primary/20 text-primary dark:text-secondary font-bold hover:bg-primary hover:text-white transition-all duration-300">
                                    {{ __('Detail') }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Card 3 -->
                    <div class="glass-strong rounded-3xl overflow-hidden shadow-xl card-hover">
                        <div class="relative aspect-square overflow-hidden">
                            <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuDHqWW5BgZoA9HFpPNUrLR2y4tdyy0MKq6tz1_c4Tc2XuaYuEpNETCpDMcLFNp4cCzi5zwOdm-rYvW_E-_7NfSO_x7Zb3adGQ7wc4i2K_XF8Hx4gF9j7mQGu6AqpwSyn1YvWZyBzHtofn7e8zLB0veeVTa3nMuB0q9VvzdGMQWBrKpfpWny5GGpXBMqsNP0Hu9QKLR8ujPhFGC9tZ1TgQN6YrfedUwMYzulx9Smb-dqqzVjL6nLnrQMrlLewfzsgpcZG8U__JNweNKk"
                                alt="Kambing" class="w-full h-full object-cover">
                            <div class="absolute top-4 left-4">
                                <div class="glass-strong px-3 py-1 rounded-full">
                                    <p class="text-xs font-bold text-green-600 dark:text-green-400">{{ __('Available') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ __('Premium Goat') }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">{{ __('Healthy breed, suitable for breeding') }}</p>
                            <div class="flex items-center justify-between">
                                <p class="text-2xl font-black text-gradient">Rp 3.500.000</p>
                                <button
                                    class="px-4 h-10 rounded-full bg-primary/10 dark:bg-primary/20 text-primary dark:text-secondary font-bold hover:bg-primary hover:text-white transition-all duration-300">
                                    {{ __('Detail') }}
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
    </div>

    <!-- View All Button -->
    <div class="text-center mt-12">
        <a href="{{ route('gallery.hewan') }}"
            class="inline-flex items-center gap-2 px-8 h-14 rounded-full glass-strong font-bold hover:scale-105 transition-all duration-300">
            <span>{{ __('View All Animals') }}</span>
            <span class="material-symbols-outlined">arrow_forward</span>
        </a>
    </div>
    </div>
    </section>

    <!-- WHY CHOOSE US -->
    <section id="why" class="py-24 relative">
        <div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <div class="inline-block mb-4">
                    <div class="glass-strong px-4 py-2 rounded-full">
                        <p class="text-sm font-semibold text-primary dark:text-secondary">{{ __('Advantages') }}</p>
                    </div>
                </div>
                <h2 class="text-4xl md:text-5xl font-black text-gray-900 dark:text-white mb-4">
                    {{ __('Mengapa Memilih') }} <span class="text-gradient">Ecommerce TSA</span>
                </h2>
                    {{ __('We provide unparalleled quality and service in the livestock market') }}
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="glass-strong rounded-3xl p-8 text-center card-hover">
                    <div
                        class="inline-flex items-center justify-center size-20 rounded-full bg-gradient-to-br from-green-400 to-green-600 mb-6 shadow-lg">
                        <span class="material-symbols-outlined text-4xl text-white">health_and_safety</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">{{ __('Healthy Animals') }}</h3>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                        {{ __('Our livestock are raised in natural, stress-free environments, ensuring their health and well-being with veterinary care.') }}
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="glass-strong rounded-3xl p-8 text-center card-hover">
                    <div
                        class="inline-flex items-center justify-center size-20 rounded-full bg-gradient-to-br from-yellow-400 to-yellow-600 mb-6 shadow-lg">
                        <span class="material-symbols-outlined text-4xl text-white">verified</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">{{ __('Certified Farms') }}</h3>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                        {{ __('We partner with verified farms that uphold the highest standards in ethical and sustainable farming practices.') }}
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="glass-strong rounded-3xl p-8 text-center card-hover">
                    <div
                        class="inline-flex items-center justify-center size-20 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 mb-6 shadow-lg">
                        <span class="material-symbols-outlined text-4xl text-white">shopping_cart</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">{{ __('Easy Purchase') }}</h3>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                        {{ __('Browse, select, and buy your livestock with our simple and secure online platform with full support.') }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- TRANSPARENCY -->
    <section class="py-24 relative">
        <div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Content -->
                <div>
                    <div class="inline-block mb-6">
                        <div class="glass-strong px-4 py-2 rounded-full">
                            <p class="text-sm font-semibold text-primary dark:text-secondary">{{ __('Our Commitment') }}</p>
                        </div>
                    </div>

                    <h2 class="text-4xl md:text-5xl font-black text-gray-900 dark:text-white mb-6">
                        <span class="text-gradient">{{ __('Transparency') }}</span> {{ __('& Complete Ethics') }}
                    </h2>

                    <p class="text-lg text-gray-600 dark:text-gray-400 mb-8 leading-relaxed">
                        {{ __('At Ecommerce TSA, we believe in complete transparency and adherence to the highest legal standards. We are committed to ethical practices, ensuring every animal is responsibly sourced and sold.') }}
                    </p>

                    <a href="#contact"
                        class="inline-flex items-center gap-2 px-6 h-12 rounded-full bg-gradient-to-r from-primary to-green-600 text-white font-bold shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-300">
                        <span>{{ __('View Certification') }}</span>
                        <span class="material-symbols-outlined">arrow_forward</span>
                    </a>
                </div>

                <!-- Grid Cards -->
                <div class="grid grid-cols-2 gap-6">
                    <div class="glass-strong rounded-3xl p-6 text-center card-hover">
                        <div
                            class="inline-flex items-center justify-center size-16 rounded-full bg-primary/10 dark:bg-primary/20 mb-4">
                            <span
                                class="material-symbols-outlined text-3xl text-primary dark:text-secondary">gavel</span>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">{{ __('Sales Regulation') }}</h3>
                        <a href="#"
                            class="text-sm text-primary dark:text-secondary hover:underline font-semibold">Pelajari
                            lebih lanjut →</a>
                    </div>

                    <div class="glass-strong rounded-3xl p-6 text-center card-hover">
                        <div
                            class="inline-flex items-center justify-center size-16 rounded-full bg-primary/10 dark:bg-primary/20 mb-4">
                            <span
                                class="material-symbols-outlined text-3xl text-primary dark:text-secondary">workspace_premium</span>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">{{ __('Quality Assurance') }}</h3>
                        <a href="#"
                            class="text-sm text-primary dark:text-secondary hover:underline font-semibold">Pelajari
                            {{ __('Learn more') }} →</a>
                    </div>

                    <div class="glass-strong rounded-3xl p-6 text-center card-hover">
                        <div
                            class="inline-flex items-center justify-center size-16 rounded-full bg-primary/10 dark:bg-primary/20 mb-4">
                            <span
                                class="material-symbols-outlined text-3xl text-primary dark:text-secondary">shield</span>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">{{ __('Ethical Guidelines') }}</h3>
                        <a href="#"
                            class="text-sm text-primary dark:text-secondary hover:underline font-semibold">Pelajari
                            {{ __('Learn more') }} →</a>
                    </div>

                    <div class="glass-strong rounded-3xl p-6 text-center card-hover">
                        <div
                            class="inline-flex items-center justify-center size-16 rounded-full bg-primary/10 dark:bg-primary/20 mb-4">
                            <span
                                class="material-symbols-outlined text-3xl text-primary dark:text-secondary">eco</span>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">{{ __('Eco-Friendly Farming') }}
                        </h3>
                        <a href="#"
                            class="text-sm text-primary dark:text-secondary hover:underline font-semibold">Pelajari
                            {{ __('Learn more') }} →</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- TESTIMONIALS -->
    <section class="py-24 relative">
        <div class="container mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <div class="inline-block mb-4">
                    <div class="glass-strong px-4 py-2 rounded-full">
                        <p class="text-sm font-semibold text-primary dark:text-secondary">{{ __('Testimonials') }}</p>
                    </div>
                </div>
                <h2 class="text-4xl md:text-5xl font-black text-gray-900 dark:text-white mb-4">
                    {{ __('What Our') }} <span class="text-gradient">{{ __('Customers Say') }}</span>
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Testimonial 1 -->
                <div class="glass-strong rounded-3xl p-8 card-hover">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="material-symbols-outlined text-yellow-400">star</span>
                        <span class="material-symbols-outlined text-yellow-400">star</span>
                        <span class="material-symbols-outlined text-yellow-400">star</span>
                        <span class="material-symbols-outlined text-yellow-400">star</span>
                        <span class="material-symbols-outlined text-yellow-400">star</span>
                    </div>
                    <p class="text-gray-600 dark:text-gray-400 italic mb-6 leading-relaxed">
                        "{{ __('The goat we bought was very healthy and well-cared for. The whole process was smooth and professional. Highly recommend Ecommerce TSA!') }}"
                    </p>
                    <div class="flex items-center gap-4">
                        <div
                            class="size-12 rounded-full bg-gradient-to-br from-primary to-green-600 flex items-center justify-center">
                            <span class="text-white font-bold">BW</span>
                        </div>
                        <div>
                            <p class="font-bold text-gray-900 dark:text-white">Budi Wibowo</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Farm Owner') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 2 -->
                <div class="glass-strong rounded-3xl p-8 card-hover">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="material-symbols-outlined text-yellow-400">star</span>
                        <span class="material-symbols-outlined text-yellow-400">star</span>
                        <span class="material-symbols-outlined text-yellow-400">star</span>
                        <span class="material-symbols-outlined text-yellow-400">star</span>
                        <span class="material-symbols-outlined text-yellow-400">star</span>
                    </div>
                    <p class="text-gray-600 dark:text-gray-400 italic mb-6 leading-relaxed">
                        "{{ __('Finally, a place that cares about animal welfare. Their livestock quality is unmatched. Will definitely be a regular customer.') }}"
                    </p>
                    <div class="flex items-center gap-4">
                        <div
                            class="size-12 rounded-full bg-gradient-to-br from-secondary to-yellow-600 flex items-center justify-center">
                            <span class="text-white font-bold">AS</span>
                        </div>
                        <div>
                            <p class="font-bold text-gray-900 dark:text-white">Ani Susanti</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Farmer') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CONTACT -->
    <section id="contact" class="py-24 relative">
        <div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <div class="inline-block mb-4">
                    <div class="glass-strong px-4 py-2 rounded-full">
                        <p class="text-sm font-semibold text-primary dark:text-secondary">{{ __('Contact Us') }}</p>
                    </div>
                </div>
                <h2 class="text-4xl md:text-5xl font-black text-gray-900 dark:text-white mb-4">
                    {{ __('Contact') }} <span class="text-gradient">{{ __('Us') }}</span>
                </h2>
                    {{ __('Got questions? We want to hear from you. Send us a message and we will respond as soon as possible.') }}
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Contact Form -->
                <div class="glass-strong rounded-3xl p-8">
                    <form class="space-y-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">{{ __('Your Name') }}</label>
                            <input type="text" placeholder="{{ __('Full Name') }}"
                                class="w-full px-4 h-12 rounded-xl bg-white/50 dark:bg-black/20 border-2 border-transparent focus:border-primary focus:ring-0 text-gray-900 dark:text-white placeholder:text-gray-500 dark:placeholder:text-gray-600 transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">{{ __('Your Email') }}</label>
                            <input type="email" placeholder="{{ __('email@example.com') }}"
                                class="w-full px-4 h-12 rounded-xl bg-white/50 dark:bg-black/20 border-2 border-transparent focus:border-primary focus:ring-0 text-gray-900 dark:text-white placeholder:text-gray-500 dark:placeholder:text-gray-600 transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">{{ __('Your Message') }}</label>
                            <textarea rows="5" placeholder="{{ __('Tell us what you are looking for...') }}"
                                class="w-full px-4 py-3 rounded-xl bg-white/50 dark:bg-black/20 border-2 border-transparent focus:border-primary focus:ring-0 text-gray-900 dark:text-white placeholder:text-gray-500 dark:placeholder:text-gray-600 transition-all"></textarea>
                        </div>
                        <button type="submit"
                            class="w-full h-14 rounded-xl bg-gradient-to-r from-primary to-green-600 text-white font-bold shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-300">
                            {{ __('Send Message') }}
                        </button>
                    </form>
                </div>

                <!-- Contact Info -->
                <div class="space-y-6">
                    <div class="glass-strong rounded-3xl p-8 card-hover">
                        <div class="flex items-start gap-4">
                            <div
                                class="size-12 rounded-full bg-primary/10 dark:bg-primary/20 flex items-center justify-center shrink-0">
                                <span
                                    class="material-symbols-outlined text-primary dark:text-secondary">location_on</span>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">{{ __('Visit Us') }}</h3>
                                <p class="text-gray-600 dark:text-gray-400">Lampung, Indonesia</p>
                            </div>
                        </div>
                    </div>

                    <div class="glass-strong rounded-3xl p-8 card-hover">
                        <div class="flex items-start gap-4">
                            <div
                                class="size-12 rounded-full bg-primary/10 dark:bg-primary/20 flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-primary dark:text-secondary">mail</span>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">{{ __('Email Us') }}</h3>
                                <p class="text-gray-600 dark:text-gray-400">kontak@ecommercetsa.com</p>
                            </div>
                        </div>
                    </div>

                    <div class="glass-strong rounded-3xl p-8 card-hover">
                        <div class="flex items-start gap-4">
                            <div
                                class="size-12 rounded-full bg-primary/10 dark:bg-primary/20 flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-primary dark:text-secondary">phone</span>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">{{ __('Call Us') }}</h3>
                                <p class="text-gray-600 dark:text-gray-400">+62 812-3456-7890</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="relative py-12 glass border-t border-white/10 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <!-- Brand -->
                <div class="md:col-span-2">
                    <div class="flex items-center gap-3 mb-4">
                        <div
                            class="size-12 bg-gradient-to-br from-primary to-green-600 rounded-2xl flex items-center justify-center shadow-lg">
                            <span class="material-symbols-outlined text-3xl text-white">eco</span>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gradient">Ecommerce TSA</h3>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Perdagangan Hewan Premium</p>
                        </div>
                    </div>
                    <p class="text-gray-600 dark:text-gray-400 mb-6 max-w-md">Mitra terpercaya Anda dalam pasar ternak
                        ramah lingkungan dan berkelanjutan.</p>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-4">{{ __('Quick Links') }}</h4>
                    <ul class="space-y-3">
                                class="text-gray-600 dark:text-gray-400 hover:text-primary dark:hover:text-secondary transition-colors">{{ __('About Us') }}</a></li>
                                class="text-gray-600 dark:text-gray-400 hover:text-primary dark:hover:text-secondary transition-colors">{{ __('Products') }}</a>
                        </li>
                                class="text-gray-600 dark:text-gray-400 hover:text-primary dark:hover:text-secondary transition-colors">{{ __('Advantages') }}</a>
                        </li>
                                class="text-gray-600 dark:text-gray-400 hover:text-primary dark:hover:text-secondary transition-colors">{{ __('Contact') }}</a>
                        </li>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-4">{{ __('Contact') }}</h4>
                    <ul class="space-y-3 text-gray-600 dark:text-gray-400 text-sm">
                        <li>Lampung, Indonesia</li>
                        <li>kontak@ecommercetsa.com</li>
                        <li>+62 812-3456-7890</li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="border-t border-white/10 pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('© 2025 Ecommerce TSA. All rights reserved.') }}</p>
                    <div class="flex gap-6 text-sm">
                        <a href="#"
                            class="text-gray-600 dark:text-gray-400 hover:text-primary dark:hover:text-secondary transition-colors">{{ __('Privacy Policy') }}</a>
                        <a href="#"
                            class="text-gray-600 dark:text-gray-400 hover:text-primary dark:hover:text-secondary transition-colors">{{ __('Terms of Service') }}</a>
                        <a href="#"
                            class="text-gray-600 dark:text-gray-400 hover:text-primary dark:hover:text-secondary transition-colors">{{ __('Cookies') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const headerHeight = 80;

            // Mobile Menu Toggle
            const mobileBtn = document.getElementById("mobile-menu-btn");
            const mobileMenu = document.getElementById("mobile-menu");

            if (mobileBtn && mobileMenu) {
                mobileBtn.addEventListener("click", () => {
                    const isHidden = mobileMenu.classList.contains("hidden");
                    mobileMenu.classList.toggle("hidden");
                    mobileBtn.innerHTML = isHidden ?
                        '<span class="material-symbols-outlined text-primary dark:text-secondary">close</span>' :
                        '<span class="material-symbols-outlined text-primary dark:text-secondary">menu</span>';
                });
            }

            // Smooth Scroll
            const scrollTriggers = document.querySelectorAll('a[href^="#"]');
            scrollTriggers.forEach(trigger => {
                trigger.addEventListener("click", function(e) {
                    e.preventDefault();
                    let targetId = this.getAttribute("href").substring(1);
                    if (!targetId) return;

                    const target = document.getElementById(targetId);
                    if (!target) return;

                    const elementPosition = target.getBoundingClientRect().top;
                    const offsetPosition = elementPosition + window.pageYOffset - headerHeight;

                    window.scrollTo({
                        top: offsetPosition,
                        behavior: "smooth"
                    });

                    // Close mobile menu if open
                    if (mobileMenu && !mobileMenu.classList.contains("hidden")) {
                        mobileMenu.classList.add("hidden");
                        mobileBtn.innerHTML =
                            '<span class="material-symbols-outlined text-primary dark:text-secondary">menu</span>';
                    }
                });
            });

            // Active Link on Scroll
            window.addEventListener("scroll", () => {
                let current = "";
                const sections = ["home", "about", "shop", "why", "contact"];

                sections.forEach(section => {
                    const el = document.getElementById(section);
                    if (el) {
                        const rect = el.getBoundingClientRect();
                        if (rect.top <= 150 && rect.bottom >= 150) {
                            current = section;
                        }
                    }
                });

                document.querySelectorAll('.nav-link').forEach(link => {
                    link.classList.remove("text-primary", "dark:text-secondary", "font-bold");
                    const href = link.getAttribute("href");
                    if (href === `#${current}`) {
                        link.classList.add("text-primary", "dark:text-secondary", "font-bold");
                    }
                });

                // Header background on scroll
                const header = document.querySelector("header");
                if (window.scrollY > 50) {
                    header.classList.add("shadow-xl");
                } else {
                    header.classList.remove("shadow-xl");
                }
            });

            // Intersection Observer for animations
            const observerOptions = {
                threshold: 0.1,
                rootMargin: "0px 0px -100px 0px"
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add("animate-slide-up");
                    }
                });
            }, observerOptions);

            // Observe elements
            document.querySelectorAll("section > div").forEach(el => {
                observer.observe(el);
            });
        });
    </script>
</body>

</html>
