@extends('landing.layout')

@section('content')
    <section x-data="heroSlider()" x-init="init()" @mouseenter="pause()" @mouseleave="resume()" class="relative isolate h-[640px] overflow-hidden bg-black sm:h-[680px]">
        <template x-for="(slide, index) in slides" :key="index">
            <article
                x-show="active === index"
                x-transition.opacity.duration.700ms
                class="absolute inset-0 bg-cover bg-center"
                :style="`background-image: linear-gradient(100deg, rgba(8,16,4,.82) 0%, rgba(8,16,4,.5) 45%, rgba(8,16,4,.2) 100%), url('${slide.image}')`">
                <div class="mx-auto flex h-full w-[94%] max-w-[1240px] items-center">
                    <div class="max-w-2xl text-white fade-up">
                        <h1 class="text-5xl font-extrabold leading-[1.05] sm:text-6xl" x-text="slide.title"></h1>
                        <p class="mt-5 max-w-xl text-2xl leading-relaxed text-white/90 sm:text-[38px]" x-text="slide.copy"></p>
                        <a href="{{ route('landing.about') }}" class="mt-8 inline-flex items-center rounded-full bg-tsa-green px-8 py-3 text-base font-extrabold text-white transition hover:bg-lime-400">Learn More</a>
                    </div>
                </div>
            </article>
        </template>

        <button @click="prev()" class="absolute left-5 top-1/2 z-20 hidden h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full border border-white/55 bg-black/25 text-2xl text-white transition hover:bg-black/40 md:inline-flex" aria-label="Previous slide">&lsaquo;</button>
        <button @click="next()" class="absolute right-5 top-1/2 z-20 hidden h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full border border-white/55 bg-black/25 text-2xl text-white transition hover:bg-black/40 md:inline-flex" aria-label="Next slide">&rsaquo;</button>

        <div class="absolute bottom-6 left-1/2 z-20 flex -translate-x-1/2 gap-2">
            <template x-for="(_, i) in slides" :key="`dot-${i}`">
                <button @click="go(i)" :class="active === i ? 'bg-tsa-green scale-110' : 'bg-white/70'" class="h-2.5 w-2.5 rounded-full transition duration-200" :aria-label="`Go to slide ${i + 1}`"></button>
            </template>
        </div>
    </section>

    <section class="bg-tsa-soft py-16 sm:py-20">
        <div class="mx-auto w-[94%] max-w-[1240px]">
            <div class="mx-auto max-w-3xl text-center">
                <p class="text-xs font-extrabold uppercase tracking-[0.14em] text-tsa-greenDark">Our Catalog</p>
                <h2 class="mt-2 text-4xl font-extrabold text-slate-900 sm:text-5xl">Explore Our Main Categories</h2>
            </div>

            @php
                $catalogCards = [
                    ['title' => 'Aves', 'desc' => 'Explore a wide variety of beautiful and exotic birds from around the world.', 'img' => asset('images/homeburung.png')],
                    ['title' => 'Mammals', 'desc' => 'High-quality mammals from trusted breeding and conservation programs.', 'img' => 'https://images.unsplash.com/photo-1549480017-d76466a4b7e8?auto=format&fit=crop&w=900&q=80'],
                    ['title' => 'Reptiles', 'desc' => 'Healthy and unique reptiles with excellent care and certification.', 'img' => 'https://images.unsplash.com/photo-1531386151447-fd76ad50012f?auto=format&fit=crop&w=900&q=80'],
                    ['title' => 'Hybrid & Mutation', 'desc' => 'Special hybrid and mutation animals with rare and unique characteristics.', 'img' => 'https://images.unsplash.com/photo-1520808663317-647b476a81b9?auto=format&fit=crop&w=900&q=80'],
                ];
            @endphp

            <div class="mt-10 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                @foreach ($catalogCards as $card)
                    <article class="rounded-2xl border border-slate-200 bg-white p-7 text-center shadow-sm transition duration-200 hover:-translate-y-1 hover:shadow-md">
                        <img src="{{ $card['img'] }}" alt="{{ $card['title'] }} category" class="mx-auto h-40 w-40 rounded-full object-cover">
                        <h3 class="mt-5 text-4xl font-extrabold uppercase text-tsa-greenDark">{{ $card['title'] }}</h3>
                        <p class="mt-4 text-2xl leading-relaxed text-slate-700">{{ $card['desc'] }}</p>
                        <a href="{{ route('landing.catalog') }}" class="mt-6 inline-flex text-2xl font-extrabold text-tsa-greenDark transition hover:text-tsa-green">View Catalog &rarr;</a>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="bg-tsa-soft pb-12">
        <div class="mx-auto w-[94%] max-w-[1240px]">
            <div class="mx-auto max-w-3xl text-center">
                <p class="text-xs font-extrabold uppercase tracking-[0.14em] text-tsa-greenDark">Our Partnership</p>
                <h2 class="mt-2 text-4xl font-extrabold text-slate-900 sm:text-5xl">Trusted Partners Worldwide</h2>
                <p class="mx-auto mt-4 max-w-2xl text-xl leading-relaxed text-slate-600">We collaborate with reputable organizations and institutions to support conservation and sustainable wildlife trade.</p>
            </div>

            <div class="mt-8 grid grid-cols-2 gap-3 md:grid-cols-3 lg:grid-cols-6">
                @foreach (['Groups-2.png', 'Groups-4.png', 'Groups.png', 'Groups-5.png', 'Groups-1.png', 'Groups-3.png'] as $partnerLogo)
                    <div class="flex min-h-[112px] items-center justify-center rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
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
                intervalMs: 6000,
                reduced,
                slides: [
                    {
                        image: '{{ asset('images/homeburung.png') }}',
                        title: 'Tunas Sejahtera Adhi Perkasa',
                        copy: 'We are an official import export company that has a private breeding center and is registered with the Indonesian Government'
                    },
                    {
                        image: '{{ asset('images/homeburung.png') }}',
                        title: 'Tunas Sejahtera Adhi Perkasa',
                        copy: 'We are an official import export company that has a private breeding center and is registered with the Indonesian Government'
                    },
                    {
                        image: '{{ asset('images/gajah.jpg') }}',
                        title: 'Tunas Sejahtera Adhi Perkasa',
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
