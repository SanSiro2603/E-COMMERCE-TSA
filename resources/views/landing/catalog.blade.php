@extends('landing.layout')

@section('content')
    @php
        $categories = collect($mainCategories)->map(function ($category) {
            return [
                'key' => $category['key'],
                'name' => $category['name'],
                'desc' => $category['desc'],
                'image' => $category['image'],
                'families' => $category['families'],
            ];
        })->values();

        $productsPayload = collect($products)->map(function ($product) {
            return [
                'slug' => $product['slug'],
                'name' => $product['name'],
                'latin' => $product['latin'],
                'category' => $product['category'],
                'subcategory' => $product['subcategory'],
                'price' => $product['price'],
                'image' => $product['image'],
            ];
        })->shuffle()->values();
    @endphp

    <section class="relative isolate h-[360px] overflow-hidden bg-black sm:h-[420px]">
        <div class="absolute inset-0 bg-cover bg-top"
             style="background-image: linear-gradient(100deg, rgba(8,16,4,.86) 0%, rgba(8,16,4,.56) 46%, rgba(8,16,4,.18) 100%), url('{{ asset('images/catalog-banner.png') }}'); background-position: center -26px;">
        </div>
        <div class="relative mx-auto flex h-full w-[94%] max-w-[1240px] items-center">
            <div class="max-w-2xl text-white">
                <p class="text-base font-bold uppercase tracking-[0.1em] text-white sm:text-lg">Catalog</p>
                <h1 class="line-mask mt-3 text-5xl font-extrabold leading-tight sm:text-6xl" data-line-reveal>
                    <span class="line-mask-inner">Our Catalog</span>
                </h1>
                <div class="mt-4 h-1 w-20 rounded-full bg-tsa-green"></div>
                <p class="mt-5 max-w-xl text-xl leading-relaxed text-white/90 sm:text-2xl" data-word-stagger>
                    Explore our wide range of animals from trusted breeding and conservation programs
                </p>
            </div>
        </div>
    </section>

    <div x-data="catalogPage({ categories: @js($categories), products: @js($productsPayload) })" x-init="init()">
    <section class="bg-white py-12">
        <div class="mx-auto w-[94%] max-w-[1240px]">
            <div class="reveal-up text-center" data-reveal>
                <p class="text-3xl font-extrabold uppercase text-tsa-greenDark sm:text-4xl">
                    Explore by Main Category
                </p>
            </div>

            <div class="mt-8 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-5">
                <article class="reveal-up text-center delay-1" data-reveal>
                    <button type="button"
                            @click="selectCategory('all')"
                            class="group w-full">
                        <div class="relative mx-auto h-36 w-36 overflow-hidden rounded-full border-[4px] shadow-md transition sm:h-40 sm:w-40 lg:h-36 lg:w-36"
                             :class="selectedCategory === 'all' ? 'border-tsa-green ring-2 ring-tsa-green/25' : 'border-slate-300'">
                            <img src="{{ asset('images/semua.jpeg') }}" alt="Semua produk" class="h-full w-full scale-[1.22] object-cover [object-position:28%_30%] transition duration-300 group-hover:scale-[1.26]">
                        </div>
                        <h3 class="mt-3 text-[24px] font-extrabold uppercase leading-none sm:text-[26px]"
                            :class="selectedCategory === 'all' ? 'text-tsa-greenDark' : 'text-slate-800'">Semua</h3>
                    </button>
                </article>

                <template x-for="(category, idx) in categories" :key="category.key">
                    <article class="reveal-up text-center" data-reveal :class="'delay-' + (((idx + 1) % 8) + 1)">
                        <button type="button"
                                @click="selectCategory(category.key)"
                                class="group w-full">
                            <div class="mx-auto h-36 w-36 overflow-hidden rounded-full border-[4px] shadow-md transition sm:h-40 sm:w-40 lg:h-36 lg:w-36"
                                 :class="selectedCategory === category.key ? 'border-tsa-green ring-2 ring-tsa-green/25' : 'border-slate-300'">
                                <img :src="category.image" :alt="category.name" class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                            </div>
                            <h3 class="mt-3 text-[24px] font-extrabold uppercase leading-none sm:text-[26px]"
                                :class="selectedCategory === category.key ? 'text-tsa-greenDark' : 'text-slate-800'"
                                x-text="category.name"></h3>
                        </button>
                    </article>
                </template>
            </div>

        </div>
    </section>

    <section class="bg-white py-10">
        <div class="mx-auto w-[94%] max-w-[1240px]">
            <div class="reveal-up text-center" data-reveal>
                <p class="text-3xl font-extrabold uppercase text-tsa-greenDark sm:text-4xl">
                    Browse Our Animals
                </p>
            </div>

            <div class="reveal-up mt-7" data-reveal>
                <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:gap-5">
                    <div class="lg:w-7/12" x-show="selectedCategory !== 'all'">
                        <p class="text-lg font-extrabold uppercase text-tsa-greenDark">
                            <span x-text="activeCategoryLabel"></span> Sub Category (Family)
                        </p>
                        <div class="mt-3 overflow-x-auto pb-2">
                            <div class="inline-flex min-w-max gap-2.5 whitespace-nowrap pr-3">
                                <button type="button" @click="selectedSubcategory='all'" class="rounded-lg border px-4 py-1.5 text-sm font-semibold transition"
                                        :class="selectedSubcategory === 'all' ? 'border-tsa-green bg-tsa-green text-white' : 'border-slate-300 bg-white text-slate-700 hover:border-tsa-green hover:text-tsa-greenDark'">
                                    All
                                </button>
                                <template x-for="sub in availableSubcategories" :key="'chip-' + sub">
                                    <button type="button" @click="selectedSubcategory=sub" class="rounded-lg border px-4 py-1.5 text-sm font-semibold transition"
                                            :class="selectedSubcategory === sub ? 'border-tsa-green bg-tsa-green text-white' : 'border-slate-300 bg-white text-slate-700 hover:border-tsa-green hover:text-tsa-greenDark'"
                                            x-text="sub">
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>

                    <div class="lg:ml-auto lg:w-5/12 lg:flex lg:justify-end">
                        <label class="block w-full lg:max-w-[360px]">
                            <div class="relative">
                                <input type="text" x-model.trim="searchTerm" placeholder="Search animals.." class="h-11 w-full rounded-lg border border-slate-300 px-4 pr-10 text-sm font-medium text-slate-700 outline-none transition focus:border-tsa-green">
                                <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-slate-400">
                                    <svg viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5"><path fill-rule="evenodd" d="M8.5 3a5.5 5.5 0 014.38 8.83l3.65 3.64a.75.75 0 11-1.06 1.06l-3.64-3.65A5.5 5.5 0 118.5 3zm-4 5.5a4 4 0 118 0 4 4 0 01-8 0z" clip-rule="evenodd"/></svg>
                                </span>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <div class="mt-6 grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
                <template x-for="(animal, idx) in visibleProducts" :key="animal.slug">
                    <article class="flex h-full flex-col overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                        <div class="relative h-60 w-full overflow-hidden">
                            <img :src="animal.image" :alt="animal.name" class="h-full w-full object-cover transition duration-300 hover:scale-105">
                        </div>
                        <div class="flex flex-1 flex-col p-3.5">
                            <h3 class="min-h-[3rem] text-lg font-extrabold leading-tight text-slate-900 sm:text-xl" x-text="animal.name"></h3>
                            <p class="mt-0.5 min-h-[1.25rem] text-[13px] italic leading-tight text-slate-500" x-text="animal.latin"></p>
                            <p class="mt-1 min-h-[2rem] text-[15px] leading-tight text-slate-600">
                                Category:
                                <span x-text="categoryLabel(animal.category)"></span>
                                -
                                <span x-text="animal.subcategory"></span>
                            </p>
                            <p class="mt-1 text-[20px] font-bold leading-tight text-tsa-greenDark" x-text="animal.price"></p>
                            <a :href="detailUrl(animal.slug)" class="mt-2 inline-flex w-full items-center justify-center rounded-lg border border-tsa-green/40 px-4 py-2 text-sm font-bold text-tsa-greenDark transition hover:bg-tsa-soft">
                                View Detail &nbsp;<span aria-hidden="true">&rarr;</span>
                            </a>
                        </div>
                    </article>
                </template>
            </div>

            <p x-show="!visibleProducts.length" class="mt-8 text-center text-sm font-semibold text-slate-500">
                No animals found for the current filter.
            </p>

            <div class="reveal-up mt-8 flex justify-center" data-reveal x-show="canLoadMore">
                <button type="button" @click="loadMore()" class="inline-flex h-12 items-center gap-2 rounded-lg border border-tsa-green/50 px-10 text-base font-bold text-tsa-greenDark transition hover:bg-tsa-soft">
                    Load More
                    <svg viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/></svg>
                </button>
            </div>
        </div>
    </section>
    </div>
@endsection

@push('scripts')
    <script>
        function catalogPage(payload) {
            return {
                categories: payload.categories || [],
                products: payload.products || [],
                selectedCategory: 'all',
                selectedSubcategory: 'all',
                searchTerm: '',
                visibleCount: 8,
                init() {
                    const params = new URLSearchParams(window.location.search);
                    const categoryFromUrl = (params.get('category') || 'all').toLowerCase();
                    const validCategoryKeys = ['all', ...this.categories.map((item) => item.key)];

                    if (validCategoryKeys.includes(categoryFromUrl)) {
                        this.selectedCategory = categoryFromUrl;
                    }

                    this.selectedSubcategory = 'all';
                    if (this.selectedCategory === 'all') {
                        this.searchTerm = '';
                    }
                    this.visibleCount = 8;
                },
                selectCategory(categoryKey) {
                    this.selectedCategory = categoryKey;
                    this.selectedSubcategory = 'all';
                    if (categoryKey === 'all') {
                        this.searchTerm = '';
                    }
                    this.visibleCount = 8;
                },
                get availableSubcategories() {
                    if (this.selectedCategory === 'all') {
                        return [...new Set(this.categories.flatMap((item) => item.families || []))];
                    }
                    const active = this.categories.find((item) => item.key === this.selectedCategory);
                    return active ? active.families : [];
                },
                get filteredProducts() {
                    const keyword = this.searchTerm.toLowerCase();
                    return this.products.filter((item) => {
                        const passCategory = this.selectedCategory === 'all' || item.category === this.selectedCategory;
                        const passSub = this.selectedSubcategory === 'all' || item.subcategory === this.selectedSubcategory;
                        const passText = !keyword
                            || item.name.toLowerCase().includes(keyword)
                            || item.latin.toLowerCase().includes(keyword)
                            || item.subcategory.toLowerCase().includes(keyword);
                        return passCategory && passSub && passText;
                    });
                },
                get visibleProducts() {
                    return this.filteredProducts.slice(0, this.visibleCount);
                },
                get canLoadMore() {
                    return this.filteredProducts.length > this.visibleCount;
                },
                loadMore() {
                    this.visibleCount += 8;
                },
                onCategoryChange() {
                    this.selectedSubcategory = 'all';
                    this.visibleCount = 8;
                },
                resetFilters() {
                    this.selectedCategory = 'all';
                    this.selectedSubcategory = 'all';
                    this.searchTerm = '';
                    this.visibleCount = 8;
                },
                categoryLabel(key) {
                    const match = this.categories.find((item) => item.key === key);
                    return match ? match.name : key;
                },
                detailUrl(slug) {
                    return `{{ url('/home/catalog') }}/${slug}`;
                },
            }
        }
    </script>
@endpush
