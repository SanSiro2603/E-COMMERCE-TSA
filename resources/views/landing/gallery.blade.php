@extends('landing.layout')

@section('content')
    <section class="relative isolate h-[430px] overflow-hidden bg-black sm:h-[470px]">
        <div
            class="absolute inset-0 bg-cover bg-center"
            style="background-image: linear-gradient(95deg, rgba(5,16,7,.82) 0%, rgba(5,16,7,.48) 48%, rgba(5,16,7,.22) 100%), url('{{ asset('images/catalog-banner.png') }}');">
        </div>

        <div class="relative mx-auto flex h-full w-[94%] max-w-[1240px] items-center">
            <div class="reveal-up max-w-2xl text-white" data-reveal>
                <p class="text-xl font-bold sm:text-2xl">Gallery</p>
                <h1 class="line-mask mt-2 font-extrabold leading-[0.98] tracking-tight text-[46px] sm:text-[64px]" data-line-reveal>
                    <span class="line-mask-inner">Our Gallery</span>
                </h1>
                <div class="mt-4 h-1 w-20 rounded-full bg-tsa-green"></div>
                <p class="mt-5 max-w-xl text-xl leading-relaxed text-white/90 sm:text-2xl" data-word-stagger>
                    A preview collection of our wildlife, breeding center, and export handling activities.
                </p>
            </div>
        </div>
    </section>

    @php
        $galleryItems = [
            ['title' => 'Aves Collection', 'category' => 'Wildlife', 'img' => asset('images/nicobar-pigeon.png')],
            ['title' => 'Mamalia Collection', 'category' => 'Wildlife', 'img' => asset('images/binturong.png')],
            ['title' => 'Reptile Collection', 'category' => 'Wildlife', 'img' => asset('images/reptil.jpeg')],
            ['title' => 'Hybrid & Mutation', 'category' => 'Wildlife', 'img' => asset('images/hybrid.jpeg')],
            ['title' => 'Breeding Center', 'category' => 'Facility', 'img' => asset('images/whoweare.png')],
            ['title' => 'Airport Handling', 'category' => 'Logistic', 'img' => asset('images/airport-handling.png')],
            ['title' => 'Procurement Process', 'category' => 'Preparation', 'img' => asset('images/procurement-commitment-1.png')],
            ['title' => 'Live Export Process', 'category' => 'Export', 'img' => asset('images/live-step-10.png')],
            ['title' => 'Sea Freight Handling', 'category' => 'Logistic', 'img' => asset('images/sea-freight-03.png')],
        ];
    @endphp

    <section class="bg-white py-14 sm:py-16">
        <div class="mx-auto w-[94%] max-w-[1240px]">
            <div class="reveal-up mx-auto max-w-3xl text-center" data-reveal>
                <p class="text-xs font-extrabold uppercase tracking-[0.14em] text-tsa-greenDark">Gallery</p>
                <h2 class="line-mask mt-2 text-4xl font-extrabold text-slate-900 sm:text-5xl" data-line-reveal>
                    <span class="line-mask-inner">Photo Collection</span>
                </h2>
            </div>

            <div class="mt-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($galleryItems as $item)
                    <article class="reveal-up delay-{{ ($loop->index % 8) + 1 }} zoom-soft group overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm" data-reveal>
                        <div class="relative aspect-[4/3] overflow-hidden bg-slate-100">
                            <img src="{{ $item['img'] }}" alt="{{ $item['title'] }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                            <div class="absolute left-3 top-3 rounded-full bg-white/90 px-3 py-1 text-xs font-extrabold uppercase tracking-[0.08em] text-tsa-greenDark">
                                {{ $item['category'] }}
                            </div>
                        </div>
                        <div class="p-5">
                            <h3 class="text-2xl font-extrabold text-tsa-greenDark">{{ $item['title'] }}</h3>
                            <p class="mt-2 text-base leading-relaxed text-slate-600">Dummy gallery image for preview and layout preparation.</p>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
@endsection
