<!DOCTYPE html>
<html class="light" lang="en">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title>Lembah Hijau - Premium Livestock Marketplace</title>
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
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
              '0%, 100%': { transform: 'translateY(0px)' },
              '50%': { transform: 'translateY(-20px)' },
            },
            slideUp: {
              '0%': { transform: 'translateY(100px)', opacity: '0' },
              '100%': { transform: 'translateY(0)', opacity: '1' },
            },
            fadeIn: {
              '0%': { opacity: '0' },
              '100%': { opacity: '1' },
            },
            scaleIn: {
              '0%': { transform: 'scale(0.9)', opacity: '0' },
              '100%': { transform: 'scale(1)', opacity: '1' },
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
    .hide-scrollbar::-webkit-scrollbar {
      display: none;
    }
    .hide-scrollbar {
      -ms-overflow-style: none;
      scrollbar-width: none;
    }
    @keyframes scroll {
      0% { transform: translateX(0); }
      100% { transform: translateX(-50%); }
    }
    .animate-scroll {
      animation: scroll 40s linear infinite;
    }
    .animate-scroll:hover {
      animation-play-state: paused;
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
      0%, 100% { border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%; }
      25% { border-radius: 58% 42% 75% 25% / 76% 46% 54% 24%; }
      50% { border-radius: 50% 50% 33% 67% / 55% 27% 73% 45%; }
      75% { border-radius: 33% 67% 58% 42% / 63% 68% 32% 37%; }
    }
    
  </style>
</head>
<body class="bg-white dark:bg-[#0a0f0a] font-display text-gray-900 dark:text-white overflow-x-hidden">

<div class="relative min-h-screen gradient-mesh">
  <!-- Decorative Blobs -->
  <div class="fixed top-20 left-10 w-96 h-96 bg-primary/20 dark:bg-primary/10 blur-3xl blob -z-10"></div>
  <div class="fixed bottom-20 right-10 w-96 h-96 bg-secondary/20 dark:bg-secondary/10 blur-3xl blob -z-10" style="animation-delay: 2s;"></div>
  <div class="fixed top-1/2 left-1/2 w-96 h-96 bg-accent/20 dark:bg-accent/10 blur-3xl blob -z-10" style="animation-delay: 4s;"></div>

  <!-- HEADER -->
  <header class="fixed top-0 left-0 right-0 z-50 glass shadow-lg">
    <div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between py-4">
        <!-- Logo -->
        <div class="flex items-center gap-3 group cursor-pointer">
          <div class="size-12 bg-gradient-to-br from-primary to-green-600 rounded-2xl flex items-center justify-center transform group-hover:rotate-12 transition-transform duration-300 shadow-lg">
            <span class="material-symbols-outlined text-3xl text-white">eco</span>
          </div>
          <div>
            <h2 class="text-xl font-bold text-gradient">Lembah Hijau</h2>
            <p class="text-xs text-gray-600 dark:text-gray-400">Premium Livestock</p>
          </div>
        </div>

        <!-- Desktop Nav -->
        <nav class="hidden md:flex items-center gap-8">
          <a href="#home" class="nav-link text-sm font-semibold text-gray-700 dark:text-gray-300 hover:text-primary dark:hover:text-secondary transition-colors">Home</a>
          <a href="#about" class="nav-link text-sm font-semibold text-gray-700 dark:text-gray-300 hover:text-primary dark:hover:text-secondary transition-colors">About</a>
          <a href="#shop" class="nav-link text-sm font-semibold text-gray-700 dark:text-gray-300 hover:text-primary dark:hover:text-secondary transition-colors">Animals</a>
          <a href="#why" class="nav-link text-sm font-semibold text-gray-700 dark:text-gray-300 hover:text-primary dark:hover:text-secondary transition-colors">Why Us</a>
          <a href="#contact" class="nav-link text-sm font-semibold text-gray-700 dark:text-gray-300 hover:text-primary dark:hover:text-secondary transition-colors">Contact</a>
        </nav>

        <!-- CTA Buttons -->
        <div class="hidden md:flex items-center gap-3">
          <button onclick="document.documentElement.classList.toggle('dark')" class="size-11 rounded-full glass-strong flex items-center justify-center hover:scale-110 transition-transform duration-300">
            <span class="material-symbols-outlined text-primary dark:text-secondary">dark_mode</span>
          </button>
          <a href="{{route("login")}}" class="px-6 h-11 rounded-full bg-gradient-to-r from-primary to-green-600 text-white font-bold shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-300 flex items-center gap-2">
            <span>Shop Now</span>
            <span class="material-symbols-outlined">arrow_forward</span>
          </a>
        </div>

        <!-- Mobile Menu Button -->
        <button id="mobile-menu-btn" class="md:hidden size-11 rounded-full glass-strong flex items-center justify-center">
          <span class="material-symbols-outlined text-primary dark:text-secondary">menu</span>
        </button>
      </div>

      <!-- Mobile Menu -->
      <div id="mobile-menu" class="hidden md:hidden pb-4">
        <nav class="flex flex-col gap-3 glass-strong rounded-2xl p-4 mt-2">
          <a href="#home" class="nav-link text-sm font-semibold text-gray-700 dark:text-gray-300 py-2 px-4 rounded-lg hover:bg-primary/10 transition-colors">Home</a>
          <a href="#about" class="nav-link text-sm font-semibold text-gray-700 dark:text-gray-300 py-2 px-4 rounded-lg hover:bg-primary/10 transition-colors">About</a>
          <a href="#shop" class="nav-link text-sm font-semibold text-gray-700 dark:text-gray-300 py-2 px-4 rounded-lg hover:bg-primary/10 transition-colors">Animals</a>
          <a href="#why" class="nav-link text-sm font-semibold text-gray-700 dark:text-gray-300 py-2 px-4 rounded-lg hover:bg-primary/10 transition-colors">Why Us</a>
          <a href="#contact" class="nav-link text-sm font-semibold text-gray-700 dark:text-gray-300 py-2 px-4 rounded-lg hover:bg-primary/10 transition-colors">Contact</a>
          <div class="flex gap-2 mt-2">
            <button onclick="document.documentElement.classList.toggle('dark')" class="flex-1 h-11 rounded-full glass-strong flex items-center justify-center gap-2">
              <span class="material-symbols-outlined text-primary dark:text-secondary">dark_mode</span>
            </button>
            <a href="#shop" class="flex-1 h-11 rounded-full bg-gradient-to-r from-primary to-green-600 text-white font-bold flex items-center justify-center gap-2">
              <span>Shop</span>
            </a>
          </div>
        </nav>
      </div>
    </div>
  </header>

<!-- HERO SECTION -->
<section 
  id="home" 
  class="relative min-h-screen flex items-center justify-center pt-20 bg-center bg-cover bg-no-repeat"
  style="background-image: url('images/gajah.jpg');"
>
  <!-- Overlay gradient hitam agar teks jelas -->
  <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/50 to-black/30"></div>

  <!-- Konten utama -->
  <div class="relative z-10 container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-20">
    <div class="text-center animate-fade-in">
      
      <!-- Label atas -->
      <div class="inline-block mb-6">
        <div class="glass-strong px-6 py-3 rounded-full">
          <p class="text-sm font-semibold text-primary dark:text-secondary flex items-center gap-2">
            <span class="size-2 bg-green-500 rounded-full animate-pulse"></span>
            Premium Quality Guaranteed
          </p>
        </div>
      </div>

      <!-- Judul utama -->
      <h1 class="text-5xl sm:text-6xl md:text-7xl lg:text-8xl font-black leading-tight mb-6 text-white">
        <span class="text-gradient">Fresh & Natural</span><br/>
        <span class="text-gray-100">Livestock Marketplace</span>
      </h1>

      <!-- Deskripsi -->
      <p class="text-lg sm:text-xl text-gray-200 max-w-3xl mx-auto mb-12">
        Welcome to Lembah Hijau, your trusted source for eco-farmed and naturally raised livestock with complete transparency and ethical standards.
      </p>

      <!-- Tombol -->
      <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
        <a href="{{route('gallery.hewan')}}" class="group px-8 h-14 rounded-full bg-gradient-to-r from-primary to-green-600 text-white font-bold shadow-xl hover:shadow-2xl hover:scale-105 transition-all duration-300 flex items-center gap-3">
          <span>Explore Animals</span>
          <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">arrow_forward</span>
        </a>
        <a href="#about" class="px-8 h-14 rounded-full glass-strong font-bold hover:scale-105 transition-all duration-300 flex items-center gap-2">
          <span class="material-symbols-outlined text-primary dark:text-secondary">play_circle</span>
          <span>Learn More</span>
        </a>
      </div>
    </div>
  </div>
</section>

<!-- STATS SECTION (di area putih, terpisah dari background) -->
<section class="bg-dark dark:bg-gray-900 py-24">
  <div class="container mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
      <div class="rounded-2xl p-8 bg-white dark:bg-gray-800 shadow-xl text-center card-hover">
        <p class="text-4xl font-black text-gradient mb-2">500+</p>
        <p class="text-sm text-gray-700 dark:text-gray-300 font-semibold">Happy Customers</p>
      </div>
      <div class="rounded-2xl p-8 bg-white dark:bg-gray-800 shadow-xl text-center card-hover">
        <p class="text-4xl font-black text-gradient mb-2">1000+</p>
        <p class="text-sm text-gray-700 dark:text-gray-300 font-semibold">Animals Sold</p>
      </div>
      <div class="rounded-2xl p-8 bg-white dark:bg-gray-800 shadow-xl text-center card-hover">
        <p class="text-4xl font-black text-gradient mb-2">50+</p>
        <p class="text-sm text-gray-700 dark:text-gray-300 font-semibold">Partner Farms</p>
      </div>
      <div class="rounded-2xl p-8 bg-white dark:bg-gray-800 shadow-xl text-center card-hover">
        <p class="text-4xl font-black text-gradient mb-2">100%</p>
        <p class="text-sm text-gray-700 dark:text-gray-300 font-semibold">Eco-Friendly</p>
      </div>
    </div>
  </div>
</section>




    <!-- Scroll Indicator -->
    <div class="absolute bottom-10 left-1/2 -translate-x-1/2 animate-bounce">
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
              <img src="images/gajah.jpg" alt="Farm" class="w-full h-full object-cover">
            </div>
          </div>
          <!-- Floating Badge -->
          <div class="absolute -top-6 -right-6 glass-strong rounded-2xl p-4 shadow-xl animate-float">
            <div class="flex items-center gap-3">
              <div class="size-12 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center">
                <span class="material-symbols-outlined text-2xl text-white">verified</span>
              </div>
              <div>
                <p class="text-sm font-bold text-gray-900 dark:text-white">Certified</p>
                <p class="text-xs text-gray-600 dark:text-gray-400">100% Organic</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Content -->
        <div class="animate-slide-up" style="animation-delay: 0.2s;">
          <div class="inline-block mb-6">
            <div class="glass-strong px-4 py-2 rounded-full">
              <p class="text-sm font-semibold text-primary dark:text-secondary">About Us</p>
            </div>
          </div>
          
          <h2 class="text-4xl md:text-5xl font-black text-gray-900 dark:text-white mb-6">
            Your Trusted Partner in <span class="text-gradient">Sustainable Farming</span>
          </h2>
          
          <p class="text-lg text-gray-600 dark:text-gray-400 mb-6 leading-relaxed">
            Lembah Hijau is dedicated to providing the freshest and most naturally raised livestock. Our commitment to eco-farming practices ensures that our animals are healthy, happy, and raised in a sustainable environment.
          </p>
          
          <p class="text-lg text-gray-600 dark:text-gray-400 mb-8 leading-relaxed">
            We believe in a transparent and ethical approach to livestock sales, connecting you directly with trusted farms that share our values.
          </p>

          <!-- Features -->
          <div class="grid grid-cols-2 gap-4 mb-8">
            <div class="flex items-center gap-3">
              <div class="size-10 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                <span class="material-symbols-outlined text-green-600 dark:text-green-400">check_circle</span>
              </div>
              <p class="font-semibold text-gray-900 dark:text-white">Eco-Friendly</p>
            </div>
            <div class="flex items-center gap-3">
              <div class="size-10 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                <span class="material-symbols-outlined text-green-600 dark:text-green-400">check_circle</span>
              </div>
              <p class="font-semibold text-gray-900 dark:text-white">100% Natural</p>
            </div>
            <div class="flex items-center gap-3">
              <div class="size-10 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                <span class="material-symbols-outlined text-green-600 dark:text-green-400">check_circle</span>
              </div>
              <p class="font-semibold text-gray-900 dark:text-white">Certified Farms</p>
            </div>
            <div class="flex items-center gap-3">
              <div class="size-10 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                <span class="material-symbols-outlined text-green-600 dark:text-green-400">check_circle</span>
              </div>
              <p class="font-semibold text-gray-900 dark:text-white">Ethical Trade</p>
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
            <p class="text-sm font-semibold text-primary dark:text-secondary">Featured Collection</p>
          </div>
        </div>
        <h2 class="text-4xl md:text-5xl font-black text-gray-900 dark:text-white mb-4">
          Premium <span class="text-gradient">Livestock</span>
        </h2>
        <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
          Explore our carefully selected collection of healthy, naturally raised animals
        </p>
      </div>

      <!-- Infinite Scroll -->
      <div class="relative">
        <div class="overflow-hidden">
          <div class="flex animate-scroll gap-6">
            <!-- Card 1 -->
            <div class="shrink-0 w-60">
              <div class="glass-strong rounded-3xl overflow-hidden shadow-xl card-hover">
                <div class="relative aspect-square overflow-hidden">
                  <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuDHqWW5BgZoA9HFpPNUrLR2y4tdyy0MKq6tz1_c4Tc2XuaYuEpNETCpDMcLFNp4cCzi5zwOdm-rYvW_E-_7NfSO_x7Zb3adGQ7wc4i2K_XF8Hx4gF9j7mQGu6AqpwSyn1YvWZyBzHtofn7e8zLB0veeVTa3nMuB0q9VvzdGMQWBrKpfpWny5GGpXBMqsNP0Hu9QKLR8ujPhFGC9tZ1TgQN6YrfedUwMYzulx9Smb-dqqzVjL6nLnrQMrlLewfzsgpcZG8U__JNweNKk" alt="Goat" class="w-full h-full object-cover">
                  <div class="absolute top-4 left-4">
                    <div class="glass-strong px-3 py-1 rounded-full">
                      <p class="text-xs font-bold text-green-600 dark:text-green-400">Available</p>
                    </div>
                  </div>
                </div>
                <div class="p-6">
                  <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Premium Goat</h3>
                  <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Healthy breed, perfect for farming</p>
                  <div class="flex items-center justify-between">
                    <p class="text-2xl font-black text-gradient">$250</p>
                    <button class="px-4 h-10 rounded-full bg-primary/10 dark:bg-primary/20 text-primary dark:text-secondary font-bold hover:bg-primary hover:text-white transition-all duration-300">
                      Details
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <div class="shrink-0 w-60">
              <div class="glass-strong rounded-3xl overflow-hidden shadow-xl card-hover">
                <div class="relative aspect-square overflow-hidden">
                  <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuDHqWW5BgZoA9HFpPNUrLR2y4tdyy0MKq6tz1_c4Tc2XuaYuEpNETCpDMcLFNp4cCzi5zwOdm-rYvW_E-_7NfSO_x7Zb3adGQ7wc4i2K_XF8Hx4gF9j7mQGu6AqpwSyn1YvWZyBzHtofn7e8zLB0veeVTa3nMuB0q9VvzdGMQWBrKpfpWny5GGpXBMqsNP0Hu9QKLR8ujPhFGC9tZ1TgQN6YrfedUwMYzulx9Smb-dqqzVjL6nLnrQMrlLewfzsgpcZG8U__JNweNKk" alt="Goat" class="w-full h-full object-cover">
                  <div class="absolute top-4 left-4">
                    <div class="glass-strong px-3 py-1 rounded-full">
                      <p class="text-xs font-bold text-green-600 dark:text-green-400">Available</p>
                    </div>
                  </div>
                </div>
                <div class="p-6">
                  <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Premium Goat</h3>
                  <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Healthy breed, perfect for farming</p>
                  <div class="flex items-center justify-between">
                    <p class="text-2xl font-black text-gradient">$250</p>
                    <button class="px-4 h-10 rounded-full bg-primary/10 dark:bg-primary/20 text-primary dark:text-secondary font-bold hover:bg-primary hover:text-white transition-all duration-300">
                      Details
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <div class="shrink-0 w-60">
              <div class="glass-strong rounded-3xl overflow-hidden shadow-xl card-hover">
                <div class="relative aspect-square overflow-hidden">
                  <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuDHqWW5BgZoA9HFpPNUrLR2y4tdyy0MKq6tz1_c4Tc2XuaYuEpNETCpDMcLFNp4cCzi5zwOdm-rYvW_E-_7NfSO_x7Zb3adGQ7wc4i2K_XF8Hx4gF9j7mQGu6AqpwSyn1YvWZyBzHtofn7e8zLB0veeVTa3nMuB0q9VvzdGMQWBrKpfpWny5GGpXBMqsNP0Hu9QKLR8ujPhFGC9tZ1TgQN6YrfedUwMYzulx9Smb-dqqzVjL6nLnrQMrlLewfzsgpcZG8U__JNweNKk" alt="Goat" class="w-full h-full object-cover">
                  <div class="absolute top-4 left-4">
                    <div class="glass-strong px-3 py-1 rounded-full">
                      <p class="text-xs font-bold text-green-600 dark:text-green-400">Available</p>
                    </div>
                  </div>
                </div>

                <div class="p-6">
                  <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Premium Goat</h3>
                  <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Healthy breed, perfect for farming</p>
                  <div class="flex items-center justify-between">
                    <p class="text-2xl font-black text-gradient">$250</p>
                    <button class="px-4 h-10 rounded-full bg-primary/10 dark:bg-primary/20 text-primary dark:text-secondary font-bold hover:bg-primary hover:text-white transition-all duration-300">
                      Details
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <div class="shrink-0 w-60">
              <div class="glass-strong rounded-3xl overflow-hidden shadow-xl card-hover">
                <div class="relative aspect-square overflow-hidden">
                  <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuDHqWW5BgZoA9HFpPNUrLR2y4tdyy0MKq6tz1_c4Tc2XuaYuEpNETCpDMcLFNp4cCzi5zwOdm-rYvW_E-_7NfSO_x7Zb3adGQ7wc4i2K_XF8Hx4gF9j7mQGu6AqpwSyn1YvWZyBzHtofn7e8zLB0veeVTa3nMuB0q9VvzdGMQWBrKpfpWny5GGpXBMqsNP0Hu9QKLR8ujPhFGC9tZ1TgQN6YrfedUwMYzulx9Smb-dqqzVjL6nLnrQMrlLewfzsgpcZG8U__JNweNKk" alt="Goat" class="w-full h-full object-cover">
                  <div class="absolute top-4 left-4">
                    <div class="glass-strong px-3 py-1 rounded-full">
                      <p class="text-xs font-bold text-green-600 dark:text-green-400">Available</p>
                    </div>
                  </div>
                </div>
                <div class="p-6">
                  <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Premium Goat</h3>
                  <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Healthy breed, perfect for farming</p>
                  <div class="flex items-center justify-between">
                    <p class="text-2xl font-black text-gradient">$250</p>
                    <button class="px-4 h-10 rounded-full bg-primary/10 dark:bg-primary/20 text-primary dark:text-secondary font-bold hover:bg-primary hover:text-white transition-all duration-300">
                      Details
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <div class="shrink-0 w-60">
              <div class="glass-strong rounded-3xl overflow-hidden shadow-xl card-hover">
                <div class="relative aspect-square overflow-hidden">
                  <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuDHqWW5BgZoA9HFpPNUrLR2y4tdyy0MKq6tz1_c4Tc2XuaYuEpNETCpDMcLFNp4cCzi5zwOdm-rYvW_E-_7NfSO_x7Zb3adGQ7wc4i2K_XF8Hx4gF9j7mQGu6AqpwSyn1YvWZyBzHtofn7e8zLB0veeVTa3nMuB0q9VvzdGMQWBrKpfpWny5GGpXBMqsNP0Hu9QKLR8ujPhFGC9tZ1TgQN6YrfedUwMYzulx9Smb-dqqzVjL6nLnrQMrlLewfzsgpcZG8U__JNweNKk" alt="Goat" class="w-full h-full object-cover">
                  <div class="absolute top-4 left-4">
                    <div class="glass-strong px-3 py-1 rounded-full">
                      <p class="text-xs font-bold text-green-600 dark:text-green-400">Available</p>
                    </div>
                  </div>
                </div>
                <div class="p-6">
                  <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Premium Goat</h3>
                  <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Healthy breed, perfect for farming</p>
                  <div class="flex items-center justify-between">
                    <p class="text-2xl font-black text-gradient">$250</p>
                    <button class="px-4 h-10 rounded-full bg-primary/10 dark:bg-primary/20 text-primary dark:text-secondary font-bold hover:bg-primary hover:text-white transition-all duration-300">
                      Details
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <div class="shrink-0 w-60">
              <div class="glass-strong rounded-3xl overflow-hidden shadow-xl card-hover">
                <div class="relative aspect-square overflow-hidden">
                  <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuDHqWW5BgZoA9HFpPNUrLR2y4tdyy0MKq6tz1_c4Tc2XuaYuEpNETCpDMcLFNp4cCzi5zwOdm-rYvW_E-_7NfSO_x7Zb3adGQ7wc4i2K_XF8Hx4gF9j7mQGu6AqpwSyn1YvWZyBzHtofn7e8zLB0veeVTa3nMuB0q9VvzdGMQWBrKpfpWny5GGpXBMqsNP0Hu9QKLR8ujPhFGC9tZ1TgQN6YrfedUwMYzulx9Smb-dqqzVjL6nLnrQMrlLewfzsgpcZG8U__JNweNKk" alt="Goat" class="w-full h-full object-cover">
                  <div class="absolute top-4 left-4">
                    <div class="glass-strong px-3 py-1 rounded-full">
                      <p class="text-xs font-bold text-green-600 dark:text-green-400">Available</p>
                    </div>
                  </div>
                </div>
                <div class="p-6">
                  <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Premium Goat</h3>
                  <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Healthy breed, perfect for farming</p>
                  <div class="flex items-center justify-between">
                    <p class="text-2xl font-black text-gradient">$250</p>
                    <button class="px-4 h-10 rounded-full bg-primary/10 dark:bg-primary/20 text-primary dark:text-secondary font-bold hover:bg-primary hover:text-white transition-all duration-300">
                      Details
                    </button>
                  </div>
                </div>
              </div>
            </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- View All Button -->
      <div class="text-center mt-12">
        <a href="{{route("gallery.hewan")}}" class="inline-flex items-center gap-2 px-8 h-14 rounded-full glass-strong font-bold hover:scale-105 transition-all duration-300">
          <span>View All Animals</span>
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
            <p class="text-sm font-semibold text-primary dark:text-secondary">Our Advantages</p>
          </div>
        </div>
        <h2 class="text-4xl md:text-5xl font-black text-gray-900 dark:text-white mb-4">
          Why Choose <span class="text-gradient">Lembah Hijau</span>
        </h2>
        <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
          We provide unmatched quality and service in the livestock marketplace
        </p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Feature 1 -->
        <div class="glass-strong rounded-3xl p-8 text-center card-hover">
          <div class="inline-flex items-center justify-center size-20 rounded-full bg-gradient-to-br from-green-400 to-green-600 mb-6 shadow-lg">
            <span class="material-symbols-outlined text-4xl text-white">health_and_safety</span>
          </div>
          <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Healthy Animals</h3>
          <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
            Our livestock are raised in natural, stress-free environments, ensuring their health and well-being with veterinary care.
          </p>
        </div>

        <!-- Feature 2 -->
        <div class="glass-strong rounded-3xl p-8 text-center card-hover">
          <div class="inline-flex items-center justify-center size-20 rounded-full bg-gradient-to-br from-yellow-400 to-yellow-600 mb-6 shadow-lg">
            <span class="material-symbols-outlined text-4xl text-white">verified</span>
          </div>
          <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Certified Farms</h3>
          <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
            We partner with verified farms that uphold the highest standards of ethical and sustainable farming practices.
          </p>
        </div>

        <!-- Feature 3 -->
        <div class="glass-strong rounded-3xl p-8 text-center card-hover">
          <div class="inline-flex items-center justify-center size-20 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 mb-6 shadow-lg">
            <span class="material-symbols-outlined text-4xl text-white">shopping_cart</span>
          </div>
          <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Easy Purchase</h3>
          <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
            Browse, select, and purchase your livestock with our simple and secure online platform with full support.
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
              <p class="text-sm font-semibold text-primary dark:text-secondary">Our Commitment</p>
            </div>
          </div>
          
          <h2 class="text-4xl md:text-5xl font-black text-gray-900 dark:text-white mb-6">
            Complete <span class="text-gradient">Transparency</span> & Ethics
          </h2>
          
          <p class="text-lg text-gray-600 dark:text-gray-400 mb-8 leading-relaxed">
            At Lembah Hijau, we believe in complete transparency and adherence to the highest legality standards. We are committed to ethical practices, ensuring every animal is sourced and sold responsibly.
          </p>

          <a href="#contact" class="inline-flex items-center gap-2 px-6 h-12 rounded-full bg-gradient-to-r from-primary to-green-600 text-white font-bold shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-300">
            <span>View Certifications</span>
            <span class="material-symbols-outlined">arrow_forward</span>
          </a>
        </div>

        <!-- Grid Cards -->
        <div class="grid grid-cols-2 gap-6">
          <div class="glass-strong rounded-3xl p-6 text-center card-hover">
            <div class="inline-flex items-center justify-center size-16 rounded-full bg-primary/10 dark:bg-primary/20 mb-4">
              <span class="material-symbols-outlined text-3xl text-primary dark:text-secondary">gavel</span>
            </div>
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Sales Regulations</h3>
            <a href="#" class="text-sm text-primary dark:text-secondary hover:underline font-semibold">Learn more →</a>
          </div>

          <div class="glass-strong rounded-3xl p-6 text-center card-hover">
            <div class="inline-flex items-center justify-center size-16 rounded-full bg-primary/10 dark:bg-primary/20 mb-4">
              <span class="material-symbols-outlined text-3xl text-primary dark:text-secondary">workspace_premium</span>
            </div>
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Quality Assurance</h3>
            <a href="#" class="text-sm text-primary dark:text-secondary hover:underline font-semibold">Learn more →</a>
          </div>

          <div class="glass-strong rounded-3xl p-6 text-center card-hover">
            <div class="inline-flex items-center justify-center size-16 rounded-full bg-primary/10 dark:bg-primary/20 mb-4">
              <span class="material-symbols-outlined text-3xl text-primary dark:text-secondary">shield</span>
            </div>
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Ethical Guidelines</h3>
            <a href="#" class="text-sm text-primary dark:text-secondary hover:underline font-semibold">Learn more →</a>
          </div>

          <div class="glass-strong rounded-3xl p-6 text-center card-hover">
            <div class="inline-flex items-center justify-center size-16 rounded-full bg-primary/10 dark:bg-primary/20 mb-4">
              <span class="material-symbols-outlined text-3xl text-primary dark:text-secondary">eco</span>
            </div>
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Eco-Farming</h3>
            <a href="#" class="text-sm text-primary dark:text-secondary hover:underline font-semibold">Learn more →</a>
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
            <p class="text-sm font-semibold text-primary dark:text-secondary">Testimonials</p>
          </div>
        </div>
        <h2 class="text-4xl md:text-5xl font-black text-gray-900 dark:text-white mb-4">
          What Our <span class="text-gradient">Customers Say</span>
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
            "The goat we purchased was incredibly healthy and well-cared for. The entire process was seamless and professional. Highly recommend Lembah Hijau!"
          </p>
          <div class="flex items-center gap-4">
            <div class="size-12 rounded-full bg-gradient-to-br from-primary to-green-600 flex items-center justify-center">
              <span class="text-white font-bold">SJ</span>
            </div>
            <div>
              <p class="font-bold text-gray-900 dark:text-white">Sarah Johnson</p>
              <p class="text-sm text-gray-600 dark:text-gray-400">Farm Owner</p>
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
            "Finally, a place that cares about animal welfare. The quality of their livestock is unmatched. Will definitely be a returning customer."
          </p>
          <div class="flex items-center gap-4">
            <div class="size-12 rounded-full bg-gradient-to-br from-secondary to-yellow-600 flex items-center justify-center">
              <span class="text-white font-bold">MB</span>
            </div>
            <div>
              <p class="font-bold text-gray-900 dark:text-white">Michael Brown</p>
              <p class="text-sm text-gray-600 dark:text-gray-400">Rancher</p>
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
            <p class="text-sm font-semibold text-primary dark:text-secondary">Get In Touch</p>
          </div>
        </div>
        <h2 class="text-4xl md:text-5xl font-black text-gray-900 dark:text-white mb-4">
          Contact <span class="text-gradient">Us</span>
        </h2>
        <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
          Have questions? We'd love to hear from you. Send us a message and we'll respond as soon as possible.
        </p>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
        <!-- Contact Form -->
        <div class="glass-strong rounded-3xl p-8">
          <form class="space-y-6">
            <div>
              <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Your Name</label>
              <input type="text" placeholder="Lembah Hijau" class="w-full px-4 h-12 rounded-xl bg-white/50 dark:bg-black/20 border-2 border-transparent focus:border-primary focus:ring-0 text-gray-900 dark:text-white placeholder:text-gray-500 dark:placeholder:text-gray-600 transition-all">
            </div>
            <div>
              <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Your Email</label>
              <input type="email" placeholder="lembahijau@gmail.com" class="w-full px-4 h-12 rounded-xl bg-white/50 dark:bg-black/20 border-2 border-transparent focus:border-primary focus:ring-0 text-gray-900 dark:text-white placeholder:text-gray-500 dark:placeholder:text-gray-600 transition-all">
            </div>
            <div>
              <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Your Message</label>
              <textarea rows="5" placeholder="Tell us what you're looking for..." class="w-full px-4 py-3 rounded-xl bg-white/50 dark:bg-black/20 border-2 border-transparent focus:border-primary focus:ring-0 text-gray-900 dark:text-white placeholder:text-gray-500 dark:placeholder:text-gray-600 transition-all"></textarea>
            </div>
            <button type="submit" class="w-full h-14 rounded-xl bg-gradient-to-r from-primary to-green-600 text-white font-bold shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-300">
              Send Message
            </button>
          </form>
        </div>

        <!-- Contact Info -->
        <div class="space-y-6">
          <div class="glass-strong rounded-3xl p-8 card-hover">
            <div class="flex items-start gap-4">
              <div class="size-12 rounded-full bg-primary/10 dark:bg-primary/20 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-primary dark:text-secondary">location_on</span>
              </div>
              <div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Visit Us</h3>
                <p class="text-gray-600 dark:text-gray-400">Lembah Hijau, Lampung, Indonesia</p>
              </div>
            </div>
          </div>

          <div class="glass-strong rounded-3xl p-8 card-hover">
            <div class="flex items-start gap-4">
              <div class="size-12 rounded-full bg-primary/10 dark:bg-primary/20 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-primary dark:text-secondary">mail</span>
              </div>
              <div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Email Us</h3>
                <p class="text-gray-600 dark:text-gray-400">contact@lembahhijau.com</p>
              </div>
            </div>
          </div>

          <div class="glass-strong rounded-3xl p-8 card-hover">
            <div class="flex items-start gap-4">
              <div class="size-12 rounded-full bg-primary/10 dark:bg-primary/20 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-primary dark:text-secondary">phone</span>
              </div>
              <div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Call Us</h3>
                <p class="text-gray-600 dark:text-gray-400">(123) 456-7890</p>
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
                            <li>123 Lembah Hijau Lampung</li>
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
                  <a href="#" class="text-gray-600 dark:text-gray-400 hover:text-primary dark:hover:text-secondary transition-colors">Cookies</a>
                </div>
              </div>
            </div>
          </div>
        </footer>
      </div>

<!-- SCRIPTS -->
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
        mobileBtn.innerHTML = isHidden 
          ? '<span class="material-symbols-outlined text-primary dark:text-secondary">close</span>'
          : '<span class="material-symbols-outlined text-primary dark:text-secondary">menu</span>';
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
          mobileBtn.innerHTML = '<span class="material-symbols-outlined text-primary dark:text-secondary">menu</span>';
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