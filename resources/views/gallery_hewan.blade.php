<!DOCTYPE html>

<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Lembah Hijau - Product Catalog</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&amp;display=swap" rel="stylesheet"/>
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
                        "text-light": "#121712",
                        "text-dark": "#f6f8f6",
                        "subtext-light": "#6c757d",
                        "subtext-dark": "#a9b1b9"
                    },
                    fontFamily: {
                        "display": ["Inter", "sans-serif"]
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
        .material-symbols-outlined {
            font-variation-settings:
            'FILL' 0,
            'wght' 400,
            'GRAD' 0,
            'opsz' 24
        }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark font-display text-text-light dark:text-text-dark">
<div class="relative flex h-auto min-h-screen w-full flex-col group/design-root overflow-x-hidden">
<div class="layout-container flex h-full grow flex-col">
<div class="px-4 md:px-10 lg:px-20 xl:px-40 flex flex-1 justify-center py-5 bg-gradient-to-r from-primary/10 to-secondary/10 dark:from-primary/20 dark:to-secondary/20">
<div class="layout-content-container flex flex-col max-w-7xl flex-1">
<header class="flex items-center justify-between whitespace-nowrap border-b border-solid border-b-primary/20 px-4 sm:px-6 md:px-10 py-3">
<div class="flex items-center gap-4 text-primary">
<div class="size-8">
<span class="material-symbols-outlined text-4xl">eco</span>
</div>
<a href="{{ route('home') }}" class="text-primary dark:text-white text-2xl font-bold leading-tight tracking-[-0.015em]">Lembah Hijau</a>
</div>
<div class="hidden md:flex flex-1 justify-center">
<label class="flex flex-col min-w-40 !h-10 max-w-lg w-full">
<div class="flex w-full flex-1 items-stretch rounded-xl h-full shadow-sm">
<div class="text-subtext-light dark:text-subtext-dark flex border-none bg-white dark:bg-background-dark items-center justify-center pl-4 rounded-l-xl border-r-0">
<span class="material-symbols-outlined">search</span>
</div>
<input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-r-xl text-text-light dark:text-text-dark focus:outline-0 focus:ring-0 border-none bg-white dark:bg-background-dark h-full placeholder:text-subtext-light dark:placeholder:text-subtext-dark px-4 text-base font-normal leading-normal" placeholder="Search for animals..." value=""/>
</div>
</label>
</div>
<div class="flex items-center gap-2">
<button class="hidden sm:flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-xl h-10 px-4 bg-secondary text-primary text-sm font-bold leading-normal tracking-[0.015em] hover:bg-secondary/90 transition-colors">
<span class="truncate">Login/Register</span>
</button>
<button class="flex max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-xl h-10 bg-white dark:bg-background-dark text-primary dark:text-white gap-2 text-sm font-bold leading-normal tracking-[0.015em] min-w-0 px-2.5 shadow-sm hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
<span class="material-symbols-outlined">shopping_cart</span>
</button>
</div>
</header>
<div class="px-4 sm:px-6 md:px-10 py-5">
<div class="flex flex-wrap gap-3 p-3 overflow-x-auto">
<button class="flex h-10 shrink-0 items-center justify-center gap-x-2 rounded-xl bg-white dark:bg-background-dark shadow-sm pl-4 pr-3 text-text-light dark:text-text-dark hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
<p class="text-sm font-medium leading-normal">Goats</p>
</button>
<button class="flex h-10 shrink-0 items-center justify-center gap-x-2 rounded-xl bg-white dark:bg-background-dark shadow-sm pl-4 pr-3 text-text-light dark:text-text-dark hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
<p class="text-sm font-medium leading-normal">Cows</p>
</button>
<button class="flex h-10 shrink-0 items-center justify-center gap-x-2 rounded-xl bg-white dark:bg-background-dark shadow-sm pl-4 pr-3 text-text-light dark:text-text-dark hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
<p class="text-sm font-medium leading-normal">Rabbits</p>
</button>
<button class="flex h-10 shrink-0 items-center justify-center gap-x-2 rounded-xl bg-white dark:bg-background-dark shadow-sm pl-4 pr-3 text-text-light dark:text-text-dark hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
<p class="text-sm font-medium leading-normal">Birds</p>
</button>
<button class="flex h-10 shrink-0 items-center justify-center gap-x-2 rounded-xl bg-primary/20 dark:bg-primary/30 pl-4 pr-2 text-primary dark:text-white">
<p class="text-sm font-medium leading-normal">Price Range</p>
<span class="material-symbols-outlined">arrow_drop_down</span>
</button>
<button class="flex h-10 shrink-0 items-center justify-center gap-x-2 rounded-xl bg-primary/20 dark:bg-primary/30 pl-4 pr-2 text-primary dark:text-white">
<p class="text-sm font-medium leading-normal">Availability</p>
<span class="material-symbols-outlined">arrow_drop_down</span>
</button>
<button class="flex h-10 shrink-0 items-center justify-center gap-x-2 rounded-xl bg-primary/20 dark:bg-primary/30 pl-4 pr-2 text-primary dark:text-white">
<p class="text-sm font-medium leading-normal">Newest</p>
<span class="material-symbols-outlined">arrow_drop_down</span>
</button>
</div>
</div>
<main class="flex-1 px-4 sm:px-6 md:px-10">
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
<div class="flex flex-col gap-4 p-4 bg-white dark:bg-background-dark rounded-xl shadow-lg hover:shadow-2xl transition-shadow duration-300 transform hover:-translate-y-1">
<div class="w-full bg-center bg-no-repeat aspect-square bg-cover rounded-lg" data-alt="A healthy brown goat in a green field" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuDFflB5EZlQc4QgGwoQZKZTi8DCn6LfajQ6fmknhnWOq_pPP0Oq0DmH0TkFa84DHB2AgzosI6zpYB_iO3UX2JTBD9SkrTamMWqZMsKeyisgzJe3ymVbTelzTATwF33ypRhPTK00CsmhGHM58q_uXZyyeip0lw2LGaBhgQgGlWKdWIQXQv9HnLlp3cUolDP0DJkgpQqzAIpx5YGpcSSTW-jCtPY5YfHyWU_XwojRg9J9OciworikUUNjBQMSK11trZD6gpw3IIHTpZ_d");'></div>
<div class="flex flex-col gap-2">
<h3 class="text-text-light dark:text-text-dark text-lg font-bold">Boer Goat</h3>
<p class="text-subtext-light dark:text-subtext-dark text-sm">Healthy and well-fed, perfect for breeding.</p>
<p class="text-primary dark:text-secondary text-xl font-bold">$150</p>
</div>
<div class="flex gap-2 mt-auto">
<button class="flex-1 flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary/20 dark:bg-primary/30 text-primary dark:text-white text-sm font-bold hover:bg-primary/30 dark:hover:bg-primary/40 transition-colors"><span class="truncate">View Details</span></button>
<button class="flex-1 flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-secondary text-primary text-sm font-bold hover:bg-secondary/90 transition-colors"><span class="truncate">Add to Cart</span></button>
</div>
</div>
<div class="flex flex-col gap-4 p-4 bg-white dark:bg-background-dark rounded-xl shadow-lg hover:shadow-2xl transition-shadow duration-300 transform hover:-translate-y-1">
<div class="w-full bg-center bg-no-repeat aspect-square bg-cover rounded-lg" data-alt="A black and white cow grazing in a pasture" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuC1cfRCsizZS7GvbI1y1vzvWUzXhSwch4RoozmhyWExzEXktX2W_dNeF9DhmexVYROSeHSpwEfRb-BhFTjoLt6TlDcxeIdCNleVw7tQC40t671o8J-D-f_ITyYxMLi3wGHA9Cevn_1xalw0l78AbVojFglvC--hNgifREL4KGqK-4K5wPHu4zfIAhMoyTDMmsCZ88rTqUtL-y2MFUGTtWjTiBsO2p5C6dMEkIgAeqvKi0TXWPkkc07aXlWysO26hTGUP-sIW8LfTNNs");'></div>
<div class="flex flex-col gap-2">
<h3 class="text-text-light dark:text-text-dark text-lg font-bold">Holstein Cow</h3>
<p class="text-subtext-light dark:text-subtext-dark text-sm">High milk yield, calm temperament.</p>
<p class="text-primary dark:text-secondary text-xl font-bold">$1200</p>
</div>
<div class="flex gap-2 mt-auto">
<button class="flex-1 flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary/20 dark:bg-primary/30 text-primary dark:text-white text-sm font-bold hover:bg-primary/30 dark:hover:bg-primary/40 transition-colors"><span class="truncate">View Details</span></button>
<button class="flex-1 flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-secondary text-primary text-sm font-bold hover:bg-secondary/90 transition-colors"><span class="truncate">Add to Cart</span></button>
</div>
</div>
<div class="flex flex-col gap-4 p-4 bg-white dark:bg-background-dark rounded-xl shadow-lg hover:shadow-2xl transition-shadow duration-300 transform hover:-translate-y-1">
<div class="w-full bg-center bg-no-repeat aspect-square bg-cover rounded-lg" data-alt="A fluffy white rabbit sitting on green grass" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCvfJnjVSfLhBiwgh0a6ZbXJF3JuCX3VG4VLCfe-RdTrOLunD40nOYAI4NIzzpGLy8016aTThDIzGSmOl2MhrzGODS1YZ9lFczwYaDN0ETeZ-LZWGyyyU0Ih9t7y-5HrCNZF60Nw8v9E7sPvehmc1PWDiAL327jVj3-_gRj9PQINA_MQSvumsuK3j8HbpeFArVvzFKRFjJXEVy5PE_HrnfCvalM5qrCy1JnMw3obm0vg0M9NoUtjjiAUrw5AJ030JF74fiDaDfeZGer");'></div>
<div class="flex flex-col gap-2">
<h3 class="text-text-light dark:text-text-dark text-lg font-bold">Angora Rabbit</h3>
<p class="text-subtext-light dark:text-subtext-dark text-sm">Friendly and fluffy, great for wool and as a pet.</p>
<p class="text-primary dark:text-secondary text-xl font-bold">$80</p>
</div>
<div class="flex gap-2 mt-auto">
<button class="flex-1 flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary/20 dark:bg-primary/30 text-primary dark:text-white text-sm font-bold hover:bg-primary/30 dark:hover:bg-primary/40 transition-colors"><span class="truncate">View Details</span></button>
<button class="flex-1 flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-secondary text-primary text-sm font-bold hover:bg-secondary/90 transition-colors"><span class="truncate">Add to Cart</span></button>
</div>
</div>
<div class="flex flex-col gap-4 p-4 bg-white dark:bg-background-dark rounded-xl shadow-lg hover:shadow-2xl transition-shadow duration-300 transform hover:-translate-y-1">
<div class="w-full bg-center bg-no-repeat aspect-square bg-cover rounded-lg" data-alt="A colorful parrot perched on a branch" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuDwNl3EsdTIFw3_oCdrbwjez_WKSgnz8573-SUrqcCMsWTr__Otxp55EBXGaeClG9hVI09EaxpaxwH5AAAYi8-K4H7nLMxsNz-zrSwf35DVg8JfOts84TzcrSQgyi13IfIAzTNZRYJ-Fd4YmospgpHTftfVflEASviD6EGmHhzhcKiL0ACiX-_k8bXEHdXy5ElLerAP3XTU-PemGX6FdU9eHd6gzvzZbJ3E0ltF2ya5pXf7vRkAwYCs0B_yJwztwH3rGQObNjJBtd6s");'></div>
<div class="flex flex-col gap-2">
<h3 class="text-text-light dark:text-text-dark text-lg font-bold">Macaw Parrot</h3>
<p class="text-subtext-light dark:text-subtext-dark text-sm">Vibrant and intelligent, a wonderful companion.</p>
<p class="text-primary dark:text-secondary text-xl font-bold">$2000</p>
</div>
<div class="flex gap-2 mt-auto">
<button class="flex-1 flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary/20 dark:bg-primary/30 text-primary dark:text-white text-sm font-bold hover:bg-primary/30 dark:hover:bg-primary/40 transition-colors"><span class="truncate">View Details</span></button>
<button class="flex-1 flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-secondary text-primary text-sm font-bold hover:bg-secondary/90 transition-colors"><span class="truncate">Add to Cart</span></button>
</div>
</div>
<!-- Repeat cards for demo -->
<div class="flex flex-col gap-4 p-4 bg-white dark:bg-background-dark rounded-xl shadow-lg hover:shadow-2xl transition-shadow duration-300 transform hover:-translate-y-1">
<div class="w-full bg-center bg-no-repeat aspect-square bg-cover rounded-lg" data-alt="Another goat" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuDm7A2QxV6iXvFRqYZM-ASCOhu6jjflG_uMoXync5v-gDvuc0Dhn5Y68izEC8AtSI7oS6J_UW1pzOiYIakxFwZoKJO8tpwIiu4GHQ9KyPr7B46NWNuPq3kakmYkAnXjlz4WNRdNLQM20phTSypcMnB70hbtobos30mJsVcm2bOzIVny34msLa2k7hRYOndj3go34huXEBRXtjI-b9Kib2tA0by6PxJo3Tn4qq8CV7IAUitbBQNNNv3D5sZ1bjDjfPlCo_zt8er4mQHL");'></div>
<div class="flex flex-col gap-2">
<h3 class="text-text-light dark:text-text-dark text-lg font-bold">Saanen Goat</h3>
<p class="text-subtext-light dark:text-subtext-dark text-sm">Known for high milk production.</p>
<p class="text-primary dark:text-secondary text-xl font-bold">$180</p>
</div>
<div class="flex gap-2 mt-auto">
<button class="flex-1 flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary/20 dark:bg-primary/30 text-primary dark:text-white text-sm font-bold hover:bg-primary/30 dark:hover:bg-primary/40 transition-colors"><span class="truncate">View Details</span></button>
<button class="flex-1 flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-secondary text-primary text-sm font-bold hover:bg-secondary/90 transition-colors"><span class="truncate">Add to Cart</span></button>
</div>
</div>
<div class="flex flex-col gap-4 p-4 bg-white dark:bg-background-dark rounded-xl shadow-lg hover:shadow-2xl transition-shadow duration-300 transform hover:-translate-y-1">
<div class="w-full bg-center bg-no-repeat aspect-square bg-cover rounded-lg" data-alt="A Jersey cow" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCG38J6NGcRX_R7e7vK8MalwEXqqeR4EWamcNY9seK_G9jNUiEoQzQoLRp2mEARNxxMSOvWE2Cw8EBcQPNnqnHDMlasW4znGwmjPcTbnOzXuhxBeEbdqEUwtlG5zdz2JOQW86cPZjQldCq--Y1SV2oduI5uZ4eKONqp8IpN6PiMiOqmSZALZmk1BWJovQ58fx-yEF3w0NzZOkonTYA5EhyXfEcfh6gap2d5ct5xsKA6mPGFpdw1Nb3ovywY4oCr9JmL1aUK6kfFJ9vc");'></div>
<div class="flex flex-col gap-2">
<h3 class="text-text-light dark:text-text-dark text-lg font-bold">Jersey Cow</h3>
<p class="text-subtext-light dark:text-subtext-dark text-sm">Rich, creamy milk and a smaller frame.</p>
<p class="text-primary dark:text-secondary text-xl font-bold">$1500</p>
</div>
<div class="flex gap-2 mt-auto">
<button class="flex-1 flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary/20 dark:bg-primary/30 text-primary dark:text-white text-sm font-bold hover:bg-primary/30 dark:hover:bg-primary/40 transition-colors"><span class="truncate">View Details</span></button>
<button class="flex-1 flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-secondary text-primary text-sm font-bold hover:bg-secondary/90 transition-colors"><span class="truncate">Add to Cart</span></button>
</div>
</div>
</div>
<div class="flex px-4 py-8 justify-center">
<button class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-xl h-12 px-6 bg-white dark:bg-background-dark shadow-md text-primary dark:text-white gap-2 text-sm font-bold tracking-[0.015em] hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
<span class="material-symbols-outlined">eco</span>
<span class="truncate">Load More</span>
</button>
</div>
</main>
<footer class="flex flex-col gap-6 px-5 py-10 text-center bg-primary text-white dark:bg-primary/90 rounded-t-xl mt-10">
<div class="flex flex-wrap items-center justify-center gap-6 sm:justify-around">
<a class="text-white/80 hover:text-white text-base font-normal leading-normal min-w-40" href="#">About Us</a>
<a class="text-white/80 hover:text-white text-base font-normal leading-normal min-w-40" href="#">FAQ</a>
<a class="text-white/80 hover:text-white text-base font-normal leading-normal min-w-40" href="#">Terms of Service</a>
</div>
<div class="flex flex-col items-center text-center gap-2">
<p>contact@lembahhijau.com | (123) 456-7890</p>
<p>123 Eco Farm Lane, Green Valley, 45678</p>
</div>
<div class="flex flex-wrap justify-center gap-6">
<a class="text-white/80 hover:text-white transition-colors" href="#">
<span class="material-symbols-outlined text-3xl">social_leaderboard</span>
</a>
<a class="text-white/80 hover:text-white transition-colors" href="#">
<svg aria-hidden="true" class="w-7 h-7" fill="currentColor" viewbox="0 0 24 24"><path clip-rule="evenodd" d="M12 2.25c-2.43 0-2.73.01-3.71.05-1.41.06-2.42.31-3.28.66a4.52 4.52 0 0 0-1.63 1.63c-.35.86-.59 1.87-.66 3.28-.04.98-.05 1.28-.05 3.71s.01 2.73.05 3.71c.07 1.41.31 2.42.66 3.28a4.52 4.52 0 0 0 1.63 1.63c.86.35 1.87.59 3.28.66.98.04 1.28.05 3.71.05s2.73-.01 3.71-.05c1.41-.07 2.42-.31 3.28-.66a4.52 4.52 0 0 0 1.63-1.63c.35-.86.59-1.87.66-3.28.04-.98.05-1.28.05-3.71s-.01-2.73-.05-3.71c-.07-1.41-.31-2.42-.66-3.28a4.52 4.52 0 0 0-1.63-1.63c-.86-.35-1.87-.59-3.28-.66A48.7 48.7 0 0 0 12 2.25Zm0 2.62c2.6 0 2.91.01 3.93.06 1.26.06 1.95.29 2.37.47.6.27.99.66 1.26 1.26.18.42.41 1.1.47 2.37.05 1.02.06 1.33.06 3.93s-.01 2.91-.06 3.93c-.06 1.26-.29 1.95-.47 2.37a2.88 2.88 0 0 1-1.26 1.26c-.42.18-1.1.41-2.37.47-1.02.05-1.33.06-3.93.06s-2.91-.01-3.93-.06c-1.26-.06-1.95-.29-2.37-.47a2.88 2.88 0 0 1-1.26-1.26c-.18-.42-.41-1.1-.47-2.37-.05-1.02-.06-1.33-.06-3.93s.01-2.91.06-3.93c.06-1.26.29-1.95.47-2.37.27-.6.66-.99 1.26-1.26.42-.18 1.1-.41 2.37-.47C9.09 4.88 9.4 4.87 12 4.87ZM12 8a4 4 0 1 0 0 8 4 4 0 0 0 0-8Zm0 6.5a2.5 2.5 0 1 1 0-5 2.5 2.5 0 0 1 0 5ZM16.75 6.5a1.25 1.25 0 1 0 0 2.5 1.25 1.25 0 0 0 0-2.5Z" fill-rule="evenodd"></path></svg>
</a>
<a class="text-white/80 hover:text-white transition-colors" href="#">
<svg aria-hidden="true" class="w-7 h-7" fill="currentColor" viewbox="0 0 24 24"><path d="M22.46 6c-.77.35-1.6.58-2.46.67.88-.53 1.56-1.37 1.88-2.38-.83.49-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98-3.56-.18-6.72-1.88-8.84-4.48-.37.63-.58 1.37-.58 2.15 0 1.49.76 2.81 1.91 3.58-.71-.02-1.37-.22-1.95-.54v.05c0 2.08 1.48 3.82 3.44 4.21a4.2 4.2 0 0 1-1.95.07 4.28 4.28 0 0 0 4 2.98 8.52 8.52 0 0 1-5.33 1.84c-.35 0-.69-.02-1.03-.06C3.44 20.29 5.7 21 8.12 21c7.34 0 11.35-6.08 11.35-11.35 0-.17 0-.34-.01-.51.78-.56 1.45-1.26 1.99-2.06Z"></path></svg>
</a>
</div>
<p class="text-white/60 text-sm font-normal leading-normal">© 2025 Lembah Hijau. All Rights Reserved.</p>
</footer>
</div>
</div>
</div>
</div>
</body></html>