@extends('landing.layout')

@section('content')
    <section class="relative isolate h-[300px] overflow-hidden bg-black sm:h-[340px]">
        <div class="absolute inset-0 bg-cover bg-center"
             style="background-image: linear-gradient(100deg, rgba(8,16,4,.86) 0%, rgba(8,16,4,.56) 46%, rgba(8,16,4,.18) 100%), url('{{ $product['image'] }}');">
        </div>
        <div class="relative mx-auto flex h-full w-[94%] max-w-[1240px] items-center">
            <div class="max-w-3xl text-white">
                <p class="text-sm font-semibold text-tsa-green">
                    Home &nbsp;&rsaquo;&nbsp; Catalog &nbsp;&rsaquo;&nbsp; {{ $categoryName }} &nbsp;&rsaquo;&nbsp; {{ $product['subcategory'] }} &nbsp;&rsaquo;&nbsp; {{ $product['name'] }}
                </p>
                <h1 class="line-mask mt-3 text-4xl font-extrabold leading-tight sm:text-6xl" data-line-reveal>
                    <span class="line-mask-inner">{{ $product['name'] }}</span>
                </h1>
                <p class="mt-3 text-xl italic text-white/85">{{ $product['latin'] }}</p>
            </div>
        </div>
    </section>

    <section class="bg-white py-10"
             x-data="{ activeImage: '{{ $product['gallery'][0] ?? $product['image'] }}', previewOpen: false }"
             @keydown.escape.window="previewOpen = false">
        <div class="mx-auto grid w-[94%] max-w-[1240px] gap-6 lg:grid-cols-[1.08fr,0.92fr]">
            <div class="reveal-left" data-reveal>
                <div class="relative overflow-hidden rounded-xl border border-slate-200 bg-white">
                    <img :src="activeImage" alt="{{ $product['name'] }}" class="h-[620px] w-full object-cover sm:h-[700px] lg:h-[760px]">
                    <button type="button" @click="previewOpen = true" class="absolute right-4 top-4 inline-flex h-10 w-10 items-center justify-center rounded-full bg-white/95 text-tsa-greenDark shadow">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 10l7-7" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 3h5v5" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l-7 7" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 21H3v-5" />
                        </svg>
                    </button>
                </div>

                <div class="mt-4 flex items-center gap-2">
                    <button type="button"
                            @click="$refs.thumbs.scrollBy({ left: -220, behavior: 'smooth' })"
                            class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-slate-300 text-slate-600 transition hover:border-tsa-green hover:text-tsa-greenDark">
                        &lsaquo;
                    </button>
                    <div class="min-w-0 flex-1 overflow-x-auto" x-ref="thumbs">
                        <div class="inline-flex gap-2.5 pr-1">
                            @foreach ($product['gallery'] as $image)
                                <button type="button"
                                        @click="activeImage = '{{ $image }}'"
                                        class="overflow-hidden rounded-lg border border-slate-300 bg-white transition hover:border-tsa-green"
                                        :class="activeImage === '{{ $image }}' ? 'ring-2 ring-tsa-green border-tsa-green' : ''">
                                    <img src="{{ $image }}" alt="{{ $product['name'] }} image {{ $loop->iteration }}" class="h-24 w-24 object-cover">
                                </button>
                            @endforeach
                        </div>
                    </div>
                    <button type="button"
                            @click="$refs.thumbs.scrollBy({ left: 220, behavior: 'smooth' })"
                            class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-slate-300 text-slate-600 transition hover:border-tsa-green hover:text-tsa-greenDark">
                        &rsaquo;
                    </button>
                </div>
            </div>

            <div class="reveal-right" data-reveal>
                <div class="rounded-xl border border-slate-200 bg-white p-5 sm:p-6">
                    <p class="text-sm">
                        <span class="font-semibold text-slate-500">Category</span>
                        <span class="ml-2 font-bold text-tsa-greenDark">{{ $categoryName }}</span>
                        <span class="mx-1 text-slate-300">•</span>
                        <span class="font-semibold text-tsa-greenDark">{{ $product['subcategory'] }}</span>
                    </p>
                    <h2 class="mt-2 text-4xl font-extrabold text-slate-900">{{ $product['name'] }}</h2>
                    <p class="mt-1 text-xl italic text-slate-500">{{ $product['latin'] }}</p>

                    <div class="mt-5">
                        <p class="text-sm font-semibold text-slate-500">Price</p>
                        <p class="text-5xl font-extrabold text-tsa-greenDark">{{ $product['price'] }}</p>
                        <p class="mt-2 text-base font-semibold text-tsa-greenDark">
                            <span class="mr-1 text-tsa-green">&#10003;</span> Availability: In Stock
                        </p>
                    </div>

                    <div class="mt-5 border-t border-slate-200 pt-5">
                        <h3 class="text-2xl font-extrabold text-slate-900">Interested in this animal?</h3>
                        <p class="mt-1 text-sm text-slate-600">Please fill out the form below and we will get back to you.</p>

                        <form class="mt-4 grid gap-3">
                            <div class="grid gap-3 sm:grid-cols-2">
                                <label class="block">
                                    <span class="mb-1.5 block text-xs font-bold uppercase text-slate-500">Full Name *</span>
                                    <input type="text" placeholder="Your full name" class="h-11 w-full rounded-lg border border-slate-300 px-3 text-sm outline-none transition focus:border-tsa-green">
                                </label>
                                <label class="block">
                                    <span class="mb-1.5 block text-xs font-bold uppercase text-slate-500">Email Address *</span>
                                    <input type="email" placeholder="Your email address" class="h-11 w-full rounded-lg border border-slate-300 px-3 text-sm outline-none transition focus:border-tsa-green">
                                </label>
                            </div>
                            <div class="grid gap-3 sm:grid-cols-2">
                                <label class="block">
                                    <span class="mb-1.5 block text-xs font-bold uppercase text-slate-500">Phone Number *</span>
                                    <input type="tel" placeholder="Your phone number" class="h-11 w-full rounded-lg border border-slate-300 px-3 text-sm outline-none transition focus:border-tsa-green">
                                </label>
                                <label class="block">
                                    <span class="mb-1.5 block text-xs font-bold uppercase text-slate-500">Country *</span>
                                    <select class="h-11 w-full rounded-lg border border-slate-300 px-3 text-sm outline-none transition focus:border-tsa-green">
                                        <option value="">Select your country</option>
                                        @foreach ($countries as $country)
                                            <option value="{{ $country }}">{{ $country }}</option>
                                        @endforeach
                                    </select>
                                </label>
                            </div>
                            <label class="block">
                                <span class="mb-1.5 block text-xs font-bold uppercase text-slate-500">Message *</span>
                                <textarea rows="4" placeholder="Tell us your interest or any specific request..." class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-tsa-green"></textarea>
                            </label>
                            <button type="button" class="inline-flex h-12 items-center justify-center rounded-lg bg-tsa-green px-5 text-base font-bold text-white transition hover:bg-tsa-greenDark">
                                Send Inquiry
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="mx-auto mt-7 w-[94%] max-w-[1240px] reveal-up" data-reveal x-data="{ open: 'description' }">
            @php
                $accordion = [
                    'description' => ['title' => 'Description', 'content' => $product['description'] ?? '-'],
                    'details' => ['title' => 'Details', 'content' => $product['details'] ?? '-'],
                    'shipping' => ['title' => 'Shipping Information', 'content' => $product['shipping'] ?? '-'],
                    'care' => ['title' => 'Care & Maintenance', 'content' => $product['care'] ?? ($product['other'] ?? '-')],
                    'legal' => ['title' => 'Legal & Documents', 'content' => $product['legal'] ?? ($product['other'] ?? '-')],
                ];
            @endphp

            <div class="space-y-3">
                @foreach ($accordion as $key => $item)
                    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">
                        <button type="button"
                                @click="open = open === '{{ $key }}' ? '' : '{{ $key }}'"
                                class="flex w-full items-center justify-between px-5 py-4 text-left">
                            <span class="flex items-center gap-3 text-xl font-extrabold text-tsa-greenDark">
                                <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-tsa-green text-white">
                                    @if($loop->first) i @else + @endif
                                </span>
                                {{ $item['title'] }}
                            </span>
                            <span class="text-2xl font-bold text-tsa-greenDark" x-text="open === '{{ $key }}' ? '-' : '+'"></span>
                        </button>
                        <div x-show="open === '{{ $key }}'" x-transition class="px-5 pb-5 text-base leading-relaxed text-slate-700">
                            {{ $item['content'] }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <template x-teleport="body">
            <div x-show="previewOpen"
                 x-transition.opacity
                 @click.self="previewOpen = false"
                 class="fixed inset-0 z-[9999] bg-black/60 backdrop-blur-md p-4 sm:p-7">
                <button type="button"
                        @click="previewOpen = false"
                        class="absolute right-4 top-4 inline-flex h-12 w-12 items-center justify-center rounded-full bg-black/35 text-4xl font-light text-white transition hover:bg-black/55 sm:right-6 sm:top-6">
                    &times;
                </button>
                <div class="flex h-full w-full items-center justify-center">
                    <img :src="activeImage"
                         alt="{{ $product['name'] }} preview"
                         class="max-h-[92vh] w-auto max-w-[94vw] object-contain shadow-2xl">
                </div>
            </div>
        </template>
    </section>
@endsection
