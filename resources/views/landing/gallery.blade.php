@extends('landing.layout')

@section('content')
    <section class="relative isolate h-[430px] overflow-hidden bg-black sm:h-[470px]">
        <div
            class="absolute inset-0 bg-cover bg-center"
            style="background-image: linear-gradient(95deg, rgba(5,16,7,.82) 0%, rgba(5,16,7,.48) 48%, rgba(5,16,7,.22) 100%), url('{{ asset('images/catalog-banner.png') }}');">
        </div>

        <div class="relative mx-auto flex h-full w-[94%] max-w-[1240px] items-center">
            <div class="reveal-up max-w-2xl text-white" data-reveal>
                <p class="text-xl font-bold sm:text-2xl">{{ __('Gallery') }}</p>
                <h1 class="line-mask mt-2 font-extrabold leading-[0.98] tracking-tight text-[46px] sm:text-[64px]" data-line-reveal>
                    <span class="line-mask-inner">{{ __('Our Gallery') }}</span>
                </h1>
                <div class="mt-4 h-1 w-20 rounded-full bg-tsa-green"></div>
                <p class="mt-5 max-w-xl text-xl leading-relaxed text-white/90 sm:text-2xl" data-word-stagger>
                    {{ __('A preview collection of our wildlife, breeding center, and export handling activities.') }}
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

    <section class="bg-white py-14 sm:py-16" x-data="{ lightboxOpen: false, lightboxImg: '' }">
        <div class="mx-auto w-[94%] max-w-[1240px]">
            <div class="reveal-up mx-auto max-w-3xl text-center" data-reveal>
                <p class="text-xs font-extrabold uppercase tracking-[0.14em] text-tsa-greenDark">{{ __('Gallery') }}</p>
                <h2 class="line-mask mt-2 text-4xl font-extrabold text-slate-900 sm:text-5xl" data-line-reveal>
                    <span class="line-mask-inner">{{ __('Photo Collection') }}</span>
                </h2>
            </div>

            <div class="mt-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($galleryItems as $item)
                    <article class="reveal-up delay-{{ ($loop->index % 8) + 1 }} zoom-soft group overflow-hidden rounded-xl border border-slate-200 bg-slate-100 shadow-sm cursor-pointer" data-reveal @click="lightboxOpen = true; lightboxImg = '{{ $item['img'] }}'">
                        <div class="relative aspect-[4/3] overflow-hidden">
                            <img src="{{ $item['img'] }}" alt="Gallery Image" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                            
                            {{-- Hover dark overlay --}}
                            <div class="absolute inset-0 bg-slate-900/0 transition-colors duration-300 group-hover:bg-slate-900/20"></div>
                            
                            {{-- Hover search icon --}}
                            <div class="absolute inset-0 flex items-center justify-center opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                                <span class="flex h-12 w-12 items-center justify-center rounded-full bg-white/90 shadow-lg text-tsa-greenDark transition-transform duration-300 scale-90 group-hover:scale-100">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>

        {{-- Lightbox Overlay --}}
        <template x-teleport="body">
            <div x-show="lightboxOpen"
                 class="fixed inset-0 z-[9999] flex items-center justify-center bg-slate-900/80 backdrop-blur-sm p-4 sm:p-6"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @keydown.escape.window="lightboxOpen = false"
                 style="display: none;">
                 
                 {{-- Background click to close --}}
                 <div class="absolute inset-0 cursor-pointer" @click="lightboxOpen = false"></div>
                 
                 {{-- Close button --}}
                 <button @click="lightboxOpen = false" class="absolute top-4 right-4 sm:top-6 sm:right-6 z-[10010] rounded-full bg-white/10 p-2 text-white/80 transition-all hover:bg-white/20 hover:text-white" aria-label="Close lightbox">
                     <svg class="h-6 w-6 sm:h-8 sm:w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                     </svg>
                 </button>

                 {{-- Image container --}}
                 <div class="relative z-[10005] max-h-full max-w-full flex items-center justify-center h-full"
                      x-show="lightboxOpen"
                      x-transition:enter="transition ease-out duration-300 delay-100"
                      x-transition:enter-start="opacity-0 scale-95"
                      x-transition:enter-end="opacity-100 scale-100"
                      x-transition:leave="transition ease-in duration-200"
                      x-transition:leave-start="opacity-100 scale-100"
                      x-transition:leave-end="opacity-0 scale-95">
                     <img :src="lightboxImg" class="max-h-[95vh] w-auto max-w-[95vw] rounded-xl object-contain shadow-2xl ring-1 ring-white/10" alt="Lightbox Image">
                 </div>
            </div>
        </template>
    </section>
@endsection
