@extends('landing.layout')

@section('content')
    <section x-data="heroSlider()" x-init="init()" class="-mt-16 relative isolate h-[640px] overflow-hidden bg-black sm:h-[680px]">
        <template x-for="(slide, index) in slides" :key="index">
            <article
                x-show="active === index"
                x-transition:enter="transition ease-out duration-1000"
                x-transition:enter-start="opacity-0 scale-[1.03]"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-700"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-[1.015]"
                class="absolute inset-0 bg-cover bg-center"
                :style="`background-image: linear-gradient(100deg, rgba(8,16,4,.82) 0%, rgba(8,16,4,.5) 45%, rgba(8,16,4,.2) 100%), url('${slide.image}')`">
            </article>
        </template>

        <div class="relative z-10 mx-auto flex h-full w-[94%] max-w-[1240px] items-center">
            <div class="max-w-[620px] text-white">
                <h1 class="font-extrabold leading-[0.98] tracking-tight text-[46px] sm:text-[64px]">
                    <span class="block text-white" x-text="currentSlide.titleTop"></span>
                    <span class="block text-tsa-green" x-text="currentSlide.titleBottom"></span>
                </h1>
                <p class="mt-4 max-w-[560px] text-base font-semibold leading-relaxed text-white/90 sm:text-[18px]" x-text="currentSlide.copy"></p>
                <a href="{{ route('landing.about') }}" class="mt-8 inline-flex items-center rounded-full bg-tsa-green px-8 py-3 text-[24px] font-extrabold text-white transition hover:bg-tsa-greenDark">Learn More</a>
            </div>
        </div>

        <button @click="prev()" class="absolute left-5 top-1/2 z-20 hidden h-12 w-12 -translate-y-1/2 items-center justify-center rounded-full bg-white/85 text-3xl font-bold text-slate-700 transition hover:bg-white md:inline-flex" aria-label="Previous slide">&lsaquo;</button>
        <button @click="next()" class="absolute right-5 top-1/2 z-20 hidden h-12 w-12 -translate-y-1/2 items-center justify-center rounded-full bg-white/85 text-3xl font-bold text-slate-700 transition hover:bg-white md:inline-flex" aria-label="Next slide">&rsaquo;</button>

        <div class="absolute bottom-6 left-1/2 z-20 flex -translate-x-1/2 gap-3">
            <template x-for="(_, i) in slides" :key="`dot-${i}`">
                <button @click="go(i)" :class="active === i ? 'bg-tsa-green scale-110' : 'bg-white/80'" class="h-3.5 w-3.5 rounded-full transition duration-200" :aria-label="`Go to slide ${i + 1}`"></button>
            </template>
        </div>
    </section>

    <section class="bg-tsa-soft py-16 sm:py-20">
        <div class="mx-auto w-[94%] max-w-[1240px]">
            <div class="mx-auto max-w-3xl text-center reveal-up" data-reveal>
                <p class="text-xs font-extrabold uppercase tracking-[0.14em] text-tsa-greenDark">Our Catalog</p>
                <h2 class="line-mask mt-2 text-4xl font-extrabold text-slate-900 sm:text-5xl" data-line-reveal>
                    <span class="line-mask-inner">Explore Our Main Categories</span>
                </h2>
            </div>

            @php
                $catalogCards = [
                    ['key' => 'aves', 'title' => 'Aves', 'desc' => 'Explore a wide variety of beautiful and exotic birds from around the world.', 'img' => asset('images/aves.jpeg')],
                    ['key' => 'mammals', 'title' => 'Mammals', 'desc' => 'High-quality mammals from trusted breeding and conservation programs.', 'img' => asset('images/mammals.jpeg')],
                    ['key' => 'reptiles', 'title' => 'Reptiles', 'desc' => 'Healthy and unique reptiles with excellent care and certification.', 'img' => asset('images/reptil.jpeg')],
                    ['key' => 'hybrid', 'title' => 'Hybrid & Mutation', 'desc' => 'Special hybrid and mutation animals with rare and unique characteristics.', 'img' => asset('images/hybrid.jpeg')],
                ];
            @endphp

            <div class="mt-10 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                @foreach ($catalogCards as $card)
                    <article class="reveal-up delay-{{ $loop->iteration }} zoom-soft flex h-full flex-col overflow-hidden rounded-md border border-slate-200 bg-white text-center shadow-sm transition duration-200 hover:-translate-y-1 hover:shadow-md" data-reveal>
                        <img src="{{ $card['img'] }}" alt="{{ $card['title'] }} category" class="h-80 w-full object-cover">
                        <div class="flex flex-1 flex-col px-6 pb-7 pt-6">
                            <h3 class="text-3xl font-extrabold uppercase text-tsa-greenDark">{{ $card['title'] }}</h3>
                            <p class="mt-4 text-xl leading-relaxed text-slate-700">{{ $card['desc'] }}</p>
                            <a href="{{ route('landing.catalog', ['category' => $card['key']]) }}" class="mt-auto pt-6 inline-flex justify-center text-xl font-extrabold text-tsa-greenDark transition hover:text-tsa-green">View Catalog &rarr;</a>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="bg-tsa-soft pb-12">
        <div class="mx-auto w-[94%] max-w-[1240px]">
            <div class="mx-auto max-w-3xl text-center reveal-up" data-reveal>
                <p class="text-xs font-extrabold uppercase tracking-[0.14em] text-tsa-greenDark">Our Partnership</p>
                <h2 class="line-mask mt-2 text-4xl font-extrabold text-slate-900 sm:text-5xl" data-line-reveal>
                    <span class="line-mask-inner">Trusted Partners Worldwide</span>
                </h2>
                <p class="mx-auto mt-4 max-w-2xl text-xl leading-relaxed text-slate-600" data-word-stagger>We collaborate with reputable organizations and institutions to support conservation and sustainable wildlife trade.</p>
            </div>

            <div class="mt-8 grid grid-cols-2 gap-3 md:grid-cols-3 lg:grid-cols-6">
                @foreach (['Groups-2.png', 'Groups-4.png', 'Groups.png', 'Groups-5.png', 'Groups-1.png', 'Groups-3.png'] as $partnerLogo)
                    <div class="reveal-up delay-{{ $loop->iteration }} zoom-soft flex min-h-[112px] items-center justify-center rounded-xl border border-slate-200 bg-white p-4 shadow-sm" data-reveal>
                        <img src="{{ asset('images/' . $partnerLogo) }}" alt="Partner logo {{ $loop->iteration }}" class="max-h-16 w-auto object-contain">
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        function heroSlider() {
            const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

            return {
                active: 0,
                timer: null,
                intervalMs: 4500,
                reduced,
                slides: [
                    {
                        image: '{{ asset('images/hero-tiger.jpeg') }}',
                        titleTop: 'Tunas Sejahtera',
                        titleBottom: 'Adhi Perkasa',
                        copy: 'We are an official import export company that has a private breeding center and is registered with the Indonesian Government'
                    },
                    {
                        image: '{{ asset('images/hero-iguana.jpeg') }}',
                        titleTop: 'Tunas Sejahtera',
                        titleBottom: 'Adhi Perkasa',
                        copy: 'We are an official import export company that has a private breeding center and is registered with the Indonesian Government'
                    },
                    {
                        image: '{{ asset('images/hero-macaw.jpeg') }}',
                        titleTop: 'Tunas Sejahtera',
                        titleBottom: 'Adhi Perkasa',
                        copy: 'We are an official import export company that has a private breeding center and is registered with the Indonesian Government'
                    }
                ],
                init() {
                    if (!this.reduced) this.resume();
                },
                next() {
                    this.active = (this.active + 1) % this.slides.length;
                },
                prev() {
                    this.active = (this.active - 1 + this.slides.length) % this.slides.length;
                },
                go(index) {
                    this.active = index;
                    if (!this.reduced) this.resume();
                },
                get currentSlide() {
                    return this.slides[this.active] || this.slides[0];
                },
                pause() {
                    if (this.timer) {
                        clearInterval(this.timer);
                        this.timer = null;
                    }
                },
                resume() {
                    this.pause();
                    this.timer = setInterval(() => this.next(), this.intervalMs);
                }
            }
        }

    </script>
@endpush
