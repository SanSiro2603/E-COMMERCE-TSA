<!DOCTYPE html>
<html class="light" lang="en">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title>Lembah Hijau - Fresh & Natural Livestock Marketplace</title>
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;700;800&display=swap" rel="stylesheet"/>
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
            "background-light": "#FFF8E1",
            "background-dark": "#131f13",
          },
          fontFamily: {
            "display": ["Plus Jakarta Sans", "sans-serif"]
          },
          borderRadius: {
            "DEFAULT": "0.5rem",
            "lg": "1rem",
            "xl": "1.5rem",
            "full": "9999px"
          },
        },
      },
    }
  </script>
  <style>
    .gradient-green-yellow {
      background-image: linear-gradient(to right, #2E7D32, #FDD835);
    }
    .hide-scrollbar::-webkit-scrollbar {
      display: none;
    }
    .hide-scrollbar {
      -ms-overflow-style: none;
      scrollbar-width: none;
    }
    @keyframes scroll {
      0% {
        transform: translateX(0);
      }
      100% {
        transform: translateX(-50%);
      }
    }
    .animate-scroll {
      animation: scroll 30s linear infinite;
    }
    .animate-scroll:hover {
      animation-play-state: paused;
    }
  </style>
</head>
<body class="bg-white dark:bg-background-dark font-display">

<div class="relative flex h-auto min-h-screen w-full flex-col group/design-root overflow-x-hidden">
  <div class="layout-container flex h-full grow flex-col">
    <div class="flex flex-1 justify-center">
      <div class="layout-content-container flex flex-col w-full">

        <!-- HEADER -->
        <header id="header" class="bg-white/80 dark:bg-background-dark/80 backdrop-blur-sm sticky top-0 z-50 px-4 sm:px-6 lg:px-8 transition-all duration-300">
          <div class="container mx-auto max-w-7xl">
            <div class="flex items-center justify-between whitespace-nowrap border-b border-gray-200 dark:border-gray-700 py-4">
              <div class="flex items-center gap-4 text-primary">
                <div class="size-8 text-primary">
                  <svg fill="currentColor" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 38c-2.21 0-4-1.79-4-4V14c0-2.21 1.79-4 4-4h24c2.21 0 4 1.79 4 4v20c0 2.21-1.79 4-4 4H12Zm0-2h24V14H12v22Zm-6 6c-1.1 0-2-.9-2-2V8c0-1.1.9-2 2-2s2 .9 2 2v32c0 1.1-.9 2-2 2Z M24 28c3.31 0 6-2.69 6-6s-2.69-6-6-6-6 2.69-6 6 2.69 6 6 6Zm0-2c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4Z"></path>
                  </svg>
                </div>
                <h2 class="text-primary text-xl font-bold leading-tight tracking-[-0.015em]">Lembah Hijau</h2>
              </div>

              <!-- Desktop Nav -->
              <nav class="hidden md:flex items-center gap-9">
                <a href="#home" class="nav-link text-gray-800 dark:text-gray-200 text-sm font-medium leading-normal hover:text-primary dark:hover:text-secondary transition-colors">Home</a>
                <a href="#shop" class="nav-link text-gray-800 dark:text-gray-200 text-sm font-medium leading-normal hover:text-primary dark:hover:text-secondary transition-colors">Shop</a>
                <a href="#about" class="nav-link text-gray-800 dark:text-gray-200 text-sm font-medium leading-normal hover:text-primary dark:hover:text-secondary transition-colors">About</a>
                <a href="#shop" class="nav-link text-gray-800 dark:text-gray-200 text-sm font-medium leading-normal hover:text-primary dark:hover:text-secondary transition-colors">View Gallery</a>
                <a href="#contact" class="nav-link text-gray-800 dark:text-gray-200 text-sm font-medium leading-normal hover:text-primary dark:hover:text-secondary transition-colors">Contact</a>
              </nav>

              <!-- Shop Now Button -->
              <a href="#shop" class="hidden md:flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-xl h-10 px-4 gradient-green-yellow text-white text-sm font-bold leading-normal tracking-[0.015em] hover:opacity-90 transition-opacity">
                <span class="truncate">Shop Now</span>
              </a>

              <!-- Mobile Menu Button -->
              <button id="mobile-menu-btn" class="md:hidden flex items-center justify-center size-10 rounded-full bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200">
                <span class="material-symbols-outlined">menu</span>
              </button>
            </div>

            <!-- Mobile Menu (Hidden by Default) -->
            <div id="mobile-menu" class="md:hidden hidden flex-col gap-4 py-4 border-t border-gray-200 dark:border-gray-700">
              <a href="#home" class="nav-link block text-gray-800 dark:text-gray-200 text-sm font-medium py-2 hover:text-primary dark:hover:text-secondary">Home</a>
              <a href="#shop" class="nav-link block text-gray-800 dark:text-gray-200 text-sm font-medium py-2 hover:text-primary dark:hover:text-secondary">Shop</a>
              <a href="#about" class="nav-link block text-gray-800 dark:text-gray-200 text-sm font-medium py-2 hover:text-primary dark:hover:text-secondary">About</a>
              <a href="#shop" class="nav-link block text-gray-800 dark:text-gray-200 text-sm font-medium py-2 hover:text-primary dark:hover:text-secondary">View Gallery</a>
              <a href="#contact" class="nav-link block text-gray-800 dark:text-gray-200 text-sm font-medium py-2 hover:text-primary dark:hover:text-secondary">Contact</a>
              <a href="#shop" class="block min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-xl h-10 px-4 gradient-green-yellow text-white text-sm font-bold leading-normal tracking-[0.015em] hover:opacity-90 transition-opacity text-center leading-10">
                <span class="truncate">Shop Now</span>
              </a>
            </div>
          </div>
        </header>

        <!-- HERO SECTION -->
        <div id="home" class="relative">
          <div class="absolute inset-0 bg-cover bg-center" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuAXdAle6NiNJNQ_094kZf_JIwErR0DK_3frHCk4iWYMbbSqtQ1AlRar-8zHe3MZv02tDADAyniQtdSFMN0yyEBD2GwBj8P3VLv3xlVJY4pVUrSD9P15fylomEFVW0HaUXNbWd9868Z3rqOYB7peVgst189mr6N_gd3IN6xXfkcp8IM7woevWSM4NdsxCXtqH9C92j-wtcgZRXC-cksQw5p_W1q5j0yopXDsOjc2FgI0e9WSrxT8xkKdI8Ak2BWwAlowhbBWrXwpwu4");'></div>
          <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/30 to-black/10"></div>
          <div class="relative container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex min-h-[calc(100vh-80px)] max-h-[720px] flex-col items-center justify-center text-center py-20">
              <div class="flex flex-col gap-4 text-center">
                <h1 class="text-white text-4xl sm:text-5xl md:text-6xl font-black leading-tight tracking-[-0.033em]"> Fresh & Natural Livestock Marketplace </h1>
                <h2 class="text-gray-200 text-base sm:text-lg md:text-xl font-normal leading-normal max-w-3xl mx-auto"> Welcome to Lembah Hijau, your trusted source for eco-farmed and naturally raised livestock. </h2>
              </div>                
            </div>
          </div>
        </div>

        <!-- ABOUT SECTION -->
        <section id="about" class="py-16 sm:py-24 bg-background-light dark:bg-background-dark">
          <div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
              <div class="w-full aspect-[4/3] rounded-xl overflow-hidden shadow-lg">
                <div class="w-full h-full bg-center bg-no-repeat bg-cover" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCqOLVo3G5tUeWSBevZanFjNVEayKBSKem-M9zRS79iKzbeVe_76J040Y7h23X00mx3BSeOKIGHFQj2nqUHSBDMuwKIHal7BbBivUQKwejBDq5g0ylbVg2Q99oGbaIBFAZlNiWu53xcecV6YMJGe4LgtGzOo3K0ODR--FKimeAfGm18aEsBOSqftE2uw93WLG-CAmpa15ChbaBclX-gdGePtQlkZCTSJP9PBLiz_79bpq48UgJt06kO4llMGsMmSesbXpYIbxOyLpl8");'></div>
              </div>
              <div class="flex flex-col gap-4">
                <h2 class="text-primary dark:text-secondary text-3xl font-bold leading-tight tracking-[-0.015em]">About Us</h2>
                <p class="text-gray-700 dark:text-gray-300 text-base font-normal leading-relaxed"> Lembah Hijau is dedicated to providing the freshest and most naturally raised livestock. Our commitment to eco-farming practices ensures that our animals are healthy, happy, and raised in a sustainable environment. We believe in a transparent and ethical approach to livestock sales, connecting you directly with trusted farms. </p>
              </div>
            </div>
          </div>
        </section>

        <div class="w-full h-1 bg-gradient-to-r from-primary/20 via-secondary/20 to-accent/20"></div>

        <!-- SHOP SECTION -->
        <section id="shop" class="py-16 sm:py-24 bg-white dark:bg-background-dark">
          <div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <h2 class="text-primary dark:text-secondary text-3xl font-bold leading-tight tracking-[-0.015em] text-center mb-12">Featured Animals</h2>

            <!-- Horizontal Infinite Scroll Container -->
            <div class="relative overflow-hidden">
              <div id="infinite-scroll" class="flex overflow-x-auto scroll-smooth snap-x snap-mandatory hide-scrollbar gap-6 pb-4">
                <!-- Animal Cards (Duplikat untuk infinite loop) -->
                <div class="flex animate-scroll gap-6">
                  <!-- Card 1 -->
                  <div class="snap-center shrink-0 w-80">
                    <div class="bg-background-light dark:bg-gray-800 rounded-xl overflow-hidden shadow-md hover:shadow-xl transition-shadow duration-300">
                      <div class="w-full h-48 bg-center bg-no-repeat bg-cover" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuDHqWW5BgZoA9HFpPNUrLR2y4tdyy0MKq6tz1_c4Tc2XuaYuEpNETCpDMcLFNp4cCzi5zwOdm-rYvW_E-_7NfSO_x7Zb3adGQ7wc4i2K_XF8Hx4gF9j7mQGu6AqpwSyn1YvWZyBzHtofn7e8zLB0veeVTa3nMuB0q9VvzdGMQWBrKpfpWny5GGpXBMqsNP0Hu9QKLR8ujPhFGC9tZ1TgQN6YrfedUwMYzulx9Smb-dqqzVjL6nLnrQMrlLewfzsgpcZG8U__JNweNKk");'></div>
                      <div class="p-6">
                        <h3 class="text-gray-900 dark:text-white text-xl font-bold">Goat</h3>
                        <p class="text-primary dark:text-secondary text-lg font-semibold mt-2">$250.00</p>
                        <button class="mt-4 w-full rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold hover:bg-primary/90 transition-colors">
                          View Details
                        </button>
                      </div>
                    </div>
                  </div>

                  <!-- Card 2 -->
                  <div class="snap-center shrink-0 w-80">
                    <div class="bg-background-light dark:bg-gray-800 rounded-xl overflow-hidden shadow-md hover:shadow-xl transition-shadow duration-300">
                      <div class="w-full h-48 bg-center bg-no-repeat bg-cover" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCG9YUBqYZVlVcOs3a32f_SFAUR9kCOLuRc9XRhqYM6Hbybbqf8dsdEADIkf8iidbr6zjZJ5qcYUsTclEnhgQd7isMxb6KaeaDlYzG8qfozVjmlJZ17J9U9IvV5FH8NrLajIMvct92HbFQT_QBV_73EQtqACM7ATDt2rN3_NuhFMyv6JBT2GJfGm_jhLX5GJp9zO4GsuSzrlp-_-qgrPU-Bj_sIHWTxmwuj-OsLnLL5qgV8v8lEH45YQixIM41FYj-T4A0BadkqeXIj");'></div>
                      <div class="p-6">
                        <h3 class="text-gray-900 dark:text-white text-xl font-bold">Cow</h3>
                        <p class="text-primary dark:text-secondary text-lg font-semibold mt-2">$1,200.00</p>
                        <button class="mt-4 w-full rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold hover:bg-primary/90 transition-colors">
                          View Details
                        </button>
                      </div>
                    </div>
                  </div>

                  <!-- Card 3 -->
                  <div class="snap-center shrink-0 w-80">
                    <div class="bg-background-light dark:bg-gray-800 rounded-xl overflow-hidden shadow-md hover:shadow-xl transition-shadow duration-300">
                      <div class="w-full h-48 bg-center bg-no-repeat bg-cover" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuAKXPsiPP-vJVuDvC0cjfdrZANtZKbkXI8aPh2LJFMFo5CiqHwlgbZG255r1t_4dVd9lemwIaDz1K0YoKRqyfDWZnfA5rMIBMyeySIGcGd3L1RHP4AIJqgyrTqdX3bhguXpTvv-hGyn0itCFEXXiaYBdrQyVJj3Cn30vvYVqLUm-6tNDzlIC955s-bUWZVnFxTwl2-P4j1L09k753s9zOTNl-65AucjRA_WLMrnD_lhpLUFI8IOu5J3qbmUcrDHGlYWlZF8JlL4Px4x");'></div>
                      <div class="p-6">
                        <h3 class="text-gray-900 dark:text-white text-xl font-bold">Chicken</h3>
                        <p class="text-primary dark:text-secondary text-lg font-semibold mt-2">$25.00</p>
                        <button class="mt-4 w-full rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold hover:bg-primary/90 transition-colors">
                          View Details
                        </button>
                      </div>
                    </div>
                  </div>

                  <!-- Card 4 (Tambahan untuk variasi) -->
                  <div class="snap-center shrink-0 w-80">
                    <div class="bg-background-light dark:bg-gray-800 rounded-xl overflow-hidden shadow-md hover:shadow-xl transition-shadow duration-300">
                      <div class="w-full h-48 bg-center bg-no-repeat bg-cover" style='background-image: url("https://images.unsplash.com/photo-1584464491033-06628f3a6b7b?w=400");'></div>
                      <div class="p-6">
                        <h3 class="text-gray-900 dark:text-white text-xl font-bold">Sheep</h3>
                        <p class="text-primary dark:text-secondary text-lg font-semibold mt-2">$180.00</p>
                        <button class="mt-4 w-full rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold hover:bg-primary/90 transition-colors">
                          View Details
                        </button>
                      </div>
                    </div>
                  </div>

                  <!-- Duplicate cards for infinite loop -->
                  <div class="snap-center shrink-0 w-80">
                    <div class="bg-background-light dark:bg-gray-800 rounded-xl overflow-hidden shadow-md hover:shadow-xl transition-shadow duration-300">
                      <div class="w-full h-48 bg-center bg-no-repeat bg-cover" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuDHqWW5BgZoA9HFpPNUrLR2y4tdyy0MKq6tz1_c4Tc2XuaYuEpNETCpDMcLFNp4cCzi5zwOdm-rYvW_E-_7NfSO_x7Zb3adGQ7wc4i2K_XF8Hx4gF9j7mQGu6AqpwSyn1YvWZyBzHtofn7e8zLB0veeVTa3nMuB0q9VvzdGMQWBrKpfpWny5GGpXBMqsNP0Hu9QKLR8ujPhFGC9tZ1TgQN6YrfedUwMYzulx9Smb-dqqzVjL6nLnrQMrlLewfzsgpcZG8U__JNweNKk");'></div>
                      <div class="p-6">
                        <h3 class="text-gray-900 dark:text-white text-xl font-bold">Goat</h3>
                        <p class="text-primary dark:text-secondary text-lg font-semibold mt-2">$250.00</p>
                        <button class="mt-4 w-full rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold hover:bg-primary/90 transition-colors">
                          View Details
                        </button>
                      </div>
                    </div>
                  </div>

                  <div class="snap-center shrink-0 w-80">
                    <div class="bg-background-light dark:bg-gray-800 rounded-xl overflow-hidden shadow-md hover:shadow-xl transition-shadow duration-300">
                      <div class="w-full h-48 bg-center bg-no-repeat bg-cover" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCG9YUBqYZVlVcOs3a32f_SFAUR9kCOLuRc9XRhqYM6Hbybbqf8dsdEADIkf8iidbr6zjZJ5qcYUsTclEnhgQd7isMxb6KaeaDlYzG8qfozVjmlJZ17J9U9IvV5FH8NrLajIMvct92HbFQT_QBV_73EQtqACM7ATDt2rN3_NuhFMyv6JBT2GJfGm_jhLX5GJp9zO4GsuSzrlp-_-qgrPU-Bj_sIHWTxmwuj-OsLnLL5qgV8v8lEH45YQixIM41FYj-T4A0BadkqeXIj");'></div>
                      <div class="p-6">
                        <h3 class="text-gray-900 dark:text-white text-xl font-bold">Cow</h3>
                        <p class="text-primary dark:text-secondary text-lg font-semibold mt-2">$1,200.00</p>
                        <button class="mt-4 w-full rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold hover:bg-primary/90 transition-colors">
                          View Details
                        </button>
                      </div>
                    </div>
                  </div>

                  <div class="snap-center shrink-0 w-80">
                    <div class="bg-background-light dark:bg-gray-800 rounded-xl overflow-hidden shadow-md hover:shadow-xl transition-shadow duration-300">
                      <div class="w-full h-48 bg-center bg-no-repeat bg-cover" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuAKXPsiPP-vJVuDvC0cjfdrZANtZKbkXI8aPh2LJFMFo5CiqHwlgbZG255r1t_4dVd9lemwIaDz1K0YoKRqyfDWZnfA5rMIBMyeySIGcGd3L1RHP4AIJqgyrTqdX3bhguXpTvv-hGyn0itCFEXXiaYBdrQyVJj3Cn30vvYVqLUm-6tNDzlIC955s-bUWZVnFxTwl2-P4j1L09k753s9zOTNl-65AucjRA_WLMrnD_lhpLUFI8IOu5J3qbmUcrDHGlYWlZF8JlL4Px4x");'></div>
                      <div class="p-6">
                        <h3 class="text-gray-900 dark:text-white text-xl font-bold">Chicken</h3>
                        <p class="text-primary dark:text-secondary text-lg font-semibold mt-2">$25.00</p>
                        <button class="mt-4 w-full rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold hover:bg-primary/90 transition-colors">
                          View Details
                        </button>
                      </div>
                    </div>
                  </div>

                  <div class="snap-center shrink-0 w-80">
                    <div class="bg-background-light dark:bg-gray-800 rounded-xl overflow-hidden shadow-md hover:shadow-xl transition-shadow duration-300">
                      <div class="w-full h-48 bg-center bg-no-repeat bg-cover" style='background-image: url("https://images.unsplash.com/photo-1584464491033-06628f3a6b7b?w=400");'></div>
                      <div class="p-6">
                        <h3 class="text-gray-900 dark:text-white text-xl font-bold">Sheep</h3>
                        <p class="text-primary dark:text-secondary text-lg font-semibold mt-2">$180.00</p>
                        <button class="mt-4 w-full rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold hover:bg-primary/90 transition-colors">
                          View Details
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>

        <!-- WHY CHOOSE US -->
        <section class="py-16 sm:py-24 bg-background-light dark:bg-background-dark">
          <div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <h2 class="text-primary dark:text-secondary text-3xl font-bold leading-tight tracking-[-0.015em] text-center mb-12">Why Choose Us</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10 text-center">
              <div class="flex flex-col items-center gap-4">
                <div class="flex items-center justify-center size-16 rounded-full bg-primary/10 text-primary">
                  <span class="material-symbols-outlined text-3xl">health_and_safety</span>
                </div>
                <h3 class="text-gray-900 dark:text-white text-xl font-bold">Healthy Animals</h3>
                <p class="text-gray-600 dark:text-gray-400 text-sm">Our livestock are raised in natural, stress-free environments, ensuring their health and well-being.</p>
              </div>
              <div class="flex flex-col items-center gap-4">
                <div class="flex items-center justify-center size-16 rounded-full bg-primary/10 text-primary">
                  <span class="material-symbols-outlined text-3xl">verified</span>
                </div>
                <h3 class="text-gray-900 dark:text-white text-xl font-bold">Trusted Farm</h3>
                <p class="text-gray-600 dark:text-gray-400 text-sm">We partner with verified farms that uphold the highest standards of ethical and sustainable farming.</p>
              </div>
              <div class="flex flex-col items-center gap-4">
                <div class="flex items-center justify-center size-16 rounded-full bg-primary/10 text-primary">
                  <span class="material-symbols-outlined text-3xl">shopping_cart</span>
                </div>
                <h3 class="text-gray-900 dark:text-white text-xl font-bold">Easy Online Purchase</h3>
                <p class="text-gray-600 dark:text-gray-400 text-sm">Browse, select, and purchase your livestock with our simple and secure online platform.</p>
              </div>
            </div>
          </div>
        </section>

        <div class="w-full h-1 bg-gradient-to-r from-primary/20 via-secondary/20 to-accent/20"></div>

        <!-- TRANSPARENCY -->
        <section class="py-16 sm:py-24 bg-white dark:bg-background-dark">
          <div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
              <div class="flex flex-col gap-6">
                <h2 class="text-primary dark:text-secondary text-3xl font-bold leading-tight tracking-[-0.015em]">Our Commitment to Transparency</h2>
                <p class="text-gray-700 dark:text-gray-300 text-base font-normal leading-relaxed"> At Lembah Hijau, we believe in complete transparency and adherence to the highest legality standards. We are committed to ethical practices, ensuring every animal is sourced and sold responsibly. Our certifications and regulations are a testament to our dedication to building trust with our customers. </p>
                <div class="flex-wrap gap-4 mt-2 flex">
                  <button class="flex min-w-[120px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-xl h-11 px-5 gradient-green-yellow text-white text-sm font-bold leading-normal tracking-[0.015em] hover:opacity-90 transition-opacity">
                    <span class="truncate">View Certifications</span>
                  </button>
                </div>
              </div>
              <div class="grid grid-cols-2 gap-6">
                <div class="bg-background-light dark:bg-gray-800 p-6 rounded-xl shadow-sm text-center flex flex-col items-center justify-center gap-3">
                  <span class="material-symbols-outlined text-4xl text-primary dark:text-secondary">gavel</span>
                  <h3 class="text-gray-900 dark:text-white font-semibold">Sales Regulations</h3>
                  <a class="text-sm text-primary dark:text-secondary hover:underline" href="#">Learn more</a>
                </div>
                <div class="bg-background-light dark:bg-gray-800 p-6 rounded-xl shadow-sm text-center flex flex-col items-center justify-center gap-3">
                  <span class="material-symbols-outlined text-4xl text-primary dark:text-secondary">workspace_premium</span>
                  <h3 class="text-gray-900 dark:text-white font-semibold">Quality Assurance</h3>
                  <a class="text-sm text-primary dark:text-secondary hover:underline" href="#">Learn more</a>
                </div>
                <div class="bg-background-light dark:bg-gray-800 p-6 rounded-xl shadow-sm text-center flex flex-col items-center justify-center gap-3">
                  <span class="material-symbols-outlined text-4xl text-primary dark:text-secondary">shield</span>
                  <h3 class="text-gray-900 dark:text-white font-semibold">Ethical Guidelines</h3>
                  <a class="text-sm text-primary dark:text-secondary hover:underline" href="#">Learn more</a>
                </div>
                <div class="bg-background-light dark:bg-gray-800 p-6 rounded-xl shadow-sm text-center flex flex-col items-center justify-center gap-3">
                  <span class="material-symbols-outlined text-4xl text-primary dark:text-secondary">eco</span>
                  <h3 class="text-gray-900 dark:text-white font-semibold">Eco-Farming</h3>
                  <a class="text-sm text-primary dark:text-secondary hover:underline" href="#">Learn more</a>
                </div>
              </div>
            </div>
          </div>
        </section>

        <!-- TESTIMONIALS -->
        <section class="py-16 sm:py-24 bg-background-light dark:bg-background-dark">
          <div class="container mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            <h2 class="text-primary dark:text-secondary text-3xl font-bold leading-tight tracking-[-0.015em] text-center mb-12">What Our Customers Say</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
              <div class="bg-white dark:bg-gray-800 p-8 rounded-xl shadow-sm">
                <p class="text-gray-700 dark:text-gray-300 italic">"The goat we purchased was incredibly healthy and well-cared for. The entire process was seamless. Highly recommend Lembah Hijau!"</p>
                <p class="text-gray-900 dark:text-white font-bold mt-4">- Sarah J.</p>
              </div>
              <div class="bg-white dark:bg-gray-800 p-8 rounded-xl shadow-sm">
                <p class="text-gray-700 dark:text-gray-300 italic">"Finally, a place that cares about animal welfare. The quality of their livestock is unmatched. Will definitely be a returning customer."</p>
                <p class="text-gray-900 dark:text-white font-bold mt-4">- Michael B.</p>
              </div>
            </div>
          </div>
        </section>

        <!-- CONTACT SECTION -->
        <section id="contact" class="py-16 sm:py-24 bg-white dark:bg-background-dark">
          <div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <h2 class="text-primary dark:text-secondary text-3xl font-bold leading-tight tracking-[-0.015em] text-center mb-12">Contact Us</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
              <div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Get in Touch</h3>
                <p class="text-gray-700 dark:text-gray-300 mb-6">Have questions? We'd love to hear from you. Send us a message and we'll respond as soon as possible.</p>
                <form class="space-y-4">
                  <input type="text" placeholder="Your Name" class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent">
                  <input type="email" placeholder="Your Email" class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent">
                  <textarea placeholder="Your Message" rows="4" class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent"></textarea>
                  <button type="submit" class="w-full gradient-green-yellow text-white font-bold py-3 rounded-lg hover:opacity-90 transition-opacity">Send Message</button>
                </form>
              </div>
              <div class="space-y-6">
                <div>
                  <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Visit Us</h3>
                  <p class="text-gray-700 dark:text-gray-300">123 Green Valley, Farmville</p>
                </div>
                <div>
                  <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Email Us</h3>
                  <p class="text-gray-700 dark:text-gray-300">contact@lembahhijau.com</p>
                </div>
                <div>
                  <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Call Us</h3>
                  <p class="text-gray-700 dark:text-gray-300">(123) 456-7890</p>
                </div>
              </div>
            </div>
          </div>
        </section>

        <!-- FOOTER -->
        <footer class="bg-gray-800 dark:bg-black text-white">
          <div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
              <div class="col-span-1 md:col-span-2">
                <h3 class="text-xl font-bold text-secondary">Lembah Hijau</h3>
                <p class="mt-2 text-gray-400 text-sm">Your trusted partner in eco-friendly livestock.</p>
              </div>
              <div>
                <h4 class="font-semibold text-gray-200">Contact Us</h4>
                <ul class="mt-4 space-y-2 text-sm text-gray-400">
                  <li>123 Green Valley, Farmville</li>
                  <li>contact@lembahhijau.com</li>
                  <li>(123) 456-7890</li>
                </ul>
              </div>
              <div>
                <h4 class="font-semibold text-gray-200">Follow Us</h4>
                <div class="flex mt-4 space-x-4">
                  <a class="text-gray-400 hover:text-secondary" href="#"><svg class="size-6" fill="currentColor" viewBox="0 0 24 24"><path d="M22.46,6C21.69,6.35 20.86,6.58 20,6.69C20.88,6.16 21.56,5.32 21.88,4.31C21.05,4.81 20.13,5.16 19.16,5.36C18.37,4.5 17.26,4 16,4C13.65,4 11.73,5.92 11.73,8.29C11.73,8.63 11.77,8.96 11.84,9.27C8.28,9.09 5.11,7.38 3,4.79C2.63,5.42 2.42,6.16 2.42,6.94C2.42,8.43 3.17,9.75 4.33,10.5C3.62,10.5 2.96,10.3 2.38,10C2.38,10 2.38,10 2.38,10.03C2.38,12.11 3.86,13.85 5.82,14.24C5.46,14.34 5.08,14.39 4.69,14.39C4.42,14.39 4.15,14.36 3.89,14.31C4.43,16 6,17.26 7.89,17.29C6.43,18.45 4.58,19.13 2.56,19.13C2.22,19.13 1.88,19.11 1.54,19.07C3.44,20.29 5.7,21 8.12,21C16,21 20.33,14.46 20.33,8.79C20.33,8.6 20.33,8.42 20.32,8.23C21.16,7.63 21.88,6.87 22.46,6Z"></path></svg></a>
                  <a class="text-gray-400 hover:text-secondary" href="#"><svg class="size-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12,2A10,10 0 0,0 2,12C2,16.42 5.58,20 10,20C10.74,20 11.47,19.92 12.18,19.77C12.05,19.54 11.95,19.28 11.87,19C11.39,19 10.92,19 10.5,19C6.91,19 4,16.09 4,12.5C4,9.44 6.25,6.92 9.13,6.58C9.25,5.81 9.5,5.08 9.87,4.4C8.75,4.5 7.7,4.81 6.75,5.32C6.96,4.56 7.3,3.87 7.75,3.26C8.89,4.21 10.33,4.81 11.91,4.96C11.86,4.64 11.83,4.32 11.83,4A4.17,4.17 0 0,1 16,0A4.17,4.17 0 0,1 20.17,4.17C20.17,4.54 20.1,4.9 20,5.25C21,4.72 21.89,4 22.67,3.14C22.21,4.12 21.45,4.91 20.5,5.41C21.27,5.33 22,5.1 22.67,4.75C22.67,4.83 22.67,4.91 22.67,5C22.67,6.33 21.9,7.5 20.83,8.25C20.83,11.39 18.2,14 15,14C14.2,14 13.43,13.88 12.72,13.65C13.5,14.56 14.63,15.17 15.83,15.17C17.67,15.17 19.17,13.67 19.17,11.83C19.17,11.5 19.13,11.17 19.05,10.87C19.6,10.63 20.1,10.29 20.5,9.86C19.89,9.94 19.28,10 18.67,10C18.4,10.75 17.89,11.38 17.2,11.75C17.06,12.5 16.5,13.13 15.75,13.5C15.75,13.5 15.75,13.5 15.75,13.5C15.68,13.34 15.63,13.17 15.58,13C16.22,12.78 16.79,12.28 17.08,11.56C16.5,11.19 16.08,10.62 15.83,10C16.42,9.92 17,9.69 17.5,9.33C16.89,8.82 16.33,8.19 16,7.5C15.2,8.08 14.28,8.42 13.33,8.42C11.5,8.42 10,6.92 10,5.08C10,4.28 10.23,3.53 10.63,2.92C10.5,3.54 10.33,4.2 10.33,4.89C10.33,6.22 11.23,7.34 12.42,7.77C12.2,7.24 12,6.67 12,6.08C12,4.39 13.39,3 15.08,3C15.89,3 16.63,3.33 17.17,3.87C17.5,2.78 18.25,1.83 19.25,1.19C18.5,0.47 17.33,0 16,0A4.17,4.17 0 0,0 11.83,4.17C11.83,4.21 11.83,4.21 11.83,4.21C11.2,2.83 9.75,1.83 8.08,1.83C6.31,1.83 4.8,2.92 4.29,4.45C4.2,4.3 4.1,4.17 4,4C4,2.9 4.9,2 6,2C6.7,2 7.34,2.28 7.83,2.75C6.75,1.85 5.42,1.33 4,1.33C2.83,1.33 1.75,1.67 0.83,2.25C1.42,2.1 2,2 2.58,2C3.58,2 4.5,2.33 5.25,2.92C4.5,3.61 4,4.5 4,5.5C4,6.92 5.08,8 6.5,8C6.92,8 7.33,7.92 7.69,7.77C7.45,8.8 6.78,9.69 5.86,10.24C6.55,11.21 7.5,11.95 8.63,12.37C8.2,13.34 8,14.4 8,15.5C8,18.53 10.47,21 13.5,21C14.91,21 16.2,20.5 17.22,19.67C18.25,20.56 19.5,21.17 20.83,21.42C21.17,21.82 21.56,22.17 22,22.45C21.33,22.81 20.5,23.08 19.67,23.22C19.67,23.39 19.67,23.56 19.67,23.75C19.67,23.83 19.67,23.92 19.67,24H12C5.38,24 0,18.62 0,12S5.38,0 12,0C12.75,0 13.5,0.08 14.22,0.25C13.5,0.85 12.83,1.54 12.28,2.33C12.19,2.22 12.1,2.11 12,2Z"></path></svg></a>
                  <a class="text-gray-400 hover:text-secondary" href="#"><svg class="size-6" fill="currentColor" viewBox="0 0 24 24"><path d="M7.8,2H16.2C19.4,2 22,4.6 22,7.8V16.2A5.8,5.8 0 0,1 16.2,22H7.8C4.6,22 2,19.4 2,16.2V7.8A5.8,5.8 0 0,1 7.8,2M7.6,4A3.6,3.6 0 0,0 4,7.6V16.4C4,18.39 5.61,20 7.6,20H16.4A3.6,3.6 0 0,0 20,16.4V7.6C20,5.61 18.39,4 16.4,4H7.6M17.25,5.5A1.25,1.25 0 0,1 18.5,6.75A1.25,1.25 0 0,1 17.25,8A1.25,1.25 0 0,1 16,6.75A1.25,1.25 0 0,1 17.25,5.5M12,7A5,5 0 0,1 17,12A5,5 0 0,1 12,17A5,5 0 0,1 7,12A5,5 0 0,1 12,7M12,9A3,3 0 0,0 9,12A3,3 0 0,0 12,15A3,3 0 0,0 15,12A3,3 0 0,0 12,9Z"></path></svg></a>
                </div>
              </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-sm text-gray-500">
              <p>© 2025 Lembah Hijau TSA. All rights reserved.</p>
            </div>
          </div>
        </footer>
      </div>
    </div>
  </div>

  <!-- SMOOTH SCROLL & MOBILE MENU SCRIPT -->
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const headerHeight = 80;

      // Smooth Scroll
      const scrollTriggers = document.querySelectorAll('a[href^="#"], button[data-scroll-to]');
      scrollTriggers.forEach(trigger => {
        trigger.addEventListener("click", function (e) {
          e.preventDefault();
          let targetId = this.getAttribute("href");
          if (targetId && targetId.startsWith("#")) {
            targetId = targetId.substring(1);
          } else {
            targetId = this.getAttribute("data-scroll-to");
          }
          if (!targetId) return;

          const target = document.getElementById(targetId);
          if (!target) return;

          const elementPosition = target.getBoundingClientRect().top;
          const offsetPosition = elementPosition + window.pageYOffset - headerHeight;

          window.scrollTo({
            top: offsetPosition,
            behavior: "smooth"
          });

          const mobileMenu = document.getElementById("mobile-menu");
          if (mobileMenu && !mobileMenu.classList.contains("hidden")) {
            mobileMenu.classList.add("hidden");
            document.getElementById("mobile-menu-btn").innerHTML = '<span class="material-symbols-outlined">menu</span>';
          }
        });
      });

      // Mobile Menu Toggle
      const mobileBtn = document.getElementById("mobile-menu-btn");
      const mobileMenu = document.getElementById("mobile-menu");
      if (mobileBtn && mobileMenu) {
        mobileBtn.addEventListener("click", () => {
          const isHidden = mobileMenu.classList.contains("hidden");
          mobileMenu.classList.toggle("hidden", !isHidden);
          mobileBtn.innerHTML = isHidden 
            ? '<span class="material-symbols-outlined">close</span>' 
            : '<span class="material-symbols-outlined">menu</span>';
        });
      }

      // Active Link on Scroll
      window.addEventListener("scroll", () => {
        let current = "";
        const sections = ["home", "about", "shop", "contact"];
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
          if (link.getAttribute("href") === `#${current}`) {
            link.classList.add("text-primary", "dark:text-secondary", "font-bold");
          }
        });
      });
    });
  </script>
</body>
</html>