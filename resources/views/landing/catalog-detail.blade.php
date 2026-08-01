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

    @php
        $galleryImages = array_values($product['gallery']);
        $galleryCount = count($galleryImages);
    @endphp

    <section class="bg-white py-8 sm:py-10"
             x-data="{
                 images: @js($galleryImages),
                 activeIndex: 0,
                 previewOpen: false,
                 touchStartX: null,
                 get currentImage() {
                     return this.images[this.activeIndex] ?? {
                         url: @js($product['image']),
                         alt: @js($product['image_alt'])
                     };
                 },
                 changeImage(index) {
                     if (this.images.length < 2) return;
                     this.activeIndex = (index + this.images.length) % this.images.length;
                     this.scrollActiveThumbnail();
                 },
                 previous() {
                     this.changeImage(this.activeIndex - 1);
                 },
                 next() {
                     this.changeImage(this.activeIndex + 1);
                 },
                 selectImage(index) {
                     this.activeIndex = index;
                     this.scrollActiveThumbnail();
                 },
                 scrollActiveThumbnail() {
                     this.$nextTick(() => {
                         const thumbnail = this.$refs.thumbs?.querySelector(`[data-gallery-index='${this.activeIndex}']`);
                         thumbnail?.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
                     });
                 },
                 endSwipe(event) {
                     if (this.touchStartX === null || this.images.length < 2) return;
                     const touchEndX = event.changedTouches.length ? event.changedTouches[0].clientX : this.touchStartX;
                     const distance = touchEndX - this.touchStartX;
                     this.touchStartX = null;
                     if (Math.abs(distance) < 50) return;
                     distance > 0 ? this.previous() : this.next();
                 },
                 openPreview() {
                     this.previewOpen = true;
                     this.$nextTick(() => this.$refs.previewClose?.focus());
                 },
                 closePreview() {
                     this.previewOpen = false;
                     this.$nextTick(() => this.$refs.expandButton?.focus());
                 }
             }"
             @keydown.escape.window="previewOpen && closePreview()">
        <div class="mx-auto grid w-[94%] max-w-[1240px] gap-6 lg:grid-cols-[1.08fr,0.92fr]">
            <div class="reveal-left min-w-0"
                 data-reveal
                 role="region"
                 aria-label="Photo gallery for {{ $product['name'] }}"
                 aria-describedby="gallery-status"
                 tabindex="0"
                 @keydown.arrow-left.prevent="previous()"
                 @keydown.arrow-right.prevent="next()">
                <div class="relative aspect-[4/3] overflow-hidden rounded-2xl border border-slate-200 bg-slate-100 sm:aspect-[16/10]"
                     @touchstart.passive="touchStartX = $event.changedTouches.length ? $event.changedTouches[0].clientX : null"
                     @touchend.passive="endSwipe($event)"
                     @touchcancel.passive="touchStartX = null">
                    <img :src="currentImage.url"
                         alt=""
                         aria-hidden="true"
                         class="absolute inset-0 h-full w-full scale-110 object-cover opacity-25 blur-2xl">
                    <div class="absolute inset-0 bg-white/55" aria-hidden="true"></div>
                    <img :src="currentImage.url"
                         :alt="currentImage.alt || @js($product['name'])"
                         class="relative z-10 h-full w-full object-contain p-2 sm:p-3">

                    @if ($galleryCount > 1)
                        <button type="button"
                                @click.stop="previous()"
                                aria-label="Show previous image"
                                class="absolute left-3 top-1/2 z-20 inline-flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full bg-white/95 text-slate-800 shadow-lg shadow-slate-900/15 transition hover:bg-white hover:text-tsa-greenDark focus:outline-none focus:ring-2 focus:ring-tsa-green focus:ring-offset-2 sm:left-4 sm:h-12 sm:w-12">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" class="h-5 w-5" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6" />
                            </svg>
                        </button>
                        <button type="button"
                                @click.stop="next()"
                                aria-label="Show next image"
                                class="absolute right-3 top-1/2 z-20 inline-flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full bg-white/95 text-slate-800 shadow-lg shadow-slate-900/15 transition hover:bg-white hover:text-tsa-greenDark focus:outline-none focus:ring-2 focus:ring-tsa-green focus:ring-offset-2 sm:right-4 sm:h-12 sm:w-12">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" class="h-5 w-5" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 6l6 6-6 6" />
                            </svg>
                        </button>

                        <span class="absolute bottom-3 right-3 z-20 rounded-full bg-slate-950/70 px-3 py-1.5 text-xs font-bold text-white shadow sm:bottom-4 sm:right-4"
                              aria-hidden="true"
                              x-text="`${activeIndex + 1} / ${images.length}`"></span>
                    @endif

                    <button type="button"
                            x-ref="expandButton"
                            @click="openPreview()"
                            aria-label="Open current image in fullscreen"
                            class="absolute right-3 top-3 z-20 inline-flex h-11 w-11 items-center justify-center rounded-full bg-white/95 text-tsa-greenDark shadow-lg shadow-slate-900/15 transition hover:bg-white hover:text-tsa-greenDark focus:outline-none focus:ring-2 focus:ring-tsa-green focus:ring-offset-2 sm:right-4 sm:top-4">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 10l7-7" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 3h5v5" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l-7 7" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 21H3v-5" />
                        </svg>
                    </button>
                </div>

                @if ($galleryCount > 1)
                    <div class="mt-4 overflow-x-auto pb-2 [scrollbar-width:thin] [scrollbar-color:#94a3b8_transparent]"
                         x-ref="thumbs"
                         aria-label="Choose an image">
                        <div class="flex min-w-max snap-x snap-mandatory gap-2.5 px-0.5">
                            @foreach ($galleryImages as $index => $image)
                                <button type="button"
                                        data-gallery-index="{{ $index }}"
                                        @click="selectImage({{ $index }})"
                                        aria-label="Show image {{ $index + 1 }} of {{ $galleryCount }}"
                                        :aria-current="activeIndex === {{ $index }} ? 'true' : 'false'"
                                        class="h-20 w-20 shrink-0 snap-center overflow-hidden rounded-xl border-2 bg-white p-0.5 transition hover:border-tsa-green focus:outline-none focus:ring-2 focus:ring-tsa-green focus:ring-offset-2 sm:h-24 sm:w-24"
                                        :class="activeIndex === {{ $index }} ? 'border-tsa-green shadow-md shadow-tsa-green/15' : 'border-slate-200'">
                                    <img src="{{ $image['url'] }}"
                                         alt=""
                                         loading="lazy"
                                         class="h-full w-full rounded-lg object-cover">
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                <p id="gallery-status"
                   class="sr-only"
                   aria-live="polite"
                   x-text="`Image ${activeIndex + 1} of ${images.length}: ${currentImage.alt || @js($product['name'])}`"></p>
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
                    'care' => ['title' => 'Care & Maintenance', 'content' => $product['care'] ?? '-'],
                    'legal' => ['title' => 'Legal & Documents', 'content' => $product['legal'] ?? '-'],
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
                 @click.self="closePreview()"
                 @keydown.arrow-left.prevent.stop="previous()"
                 @keydown.arrow-right.prevent.stop="next()"
                 @touchstart.passive="touchStartX = $event.changedTouches.length ? $event.changedTouches[0].clientX : null"
                 @touchend.passive="endSwipe($event)"
                 @touchcancel.passive="touchStartX = null"
                 role="dialog"
                 aria-modal="true"
                 aria-label="Fullscreen image preview for {{ $product['name'] }}"
                 class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/80 p-4 backdrop-blur-md sm:p-7">
                <button type="button"
                        x-ref="previewClose"
                        @click="closePreview()"
                        aria-label="Close fullscreen preview"
                        class="absolute right-4 top-4 z-30 inline-flex h-12 w-12 items-center justify-center rounded-full bg-white/15 text-4xl font-light text-white shadow-lg shadow-black/25 transition hover:bg-white/25 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-black sm:right-6 sm:top-6">
                    &times;
                </button>

                <img :src="currentImage.url"
                     :alt="currentImage.alt || @js($product['name'])"
                     class="max-h-[86vh] w-auto max-w-[92vw] object-contain shadow-2xl sm:max-h-[90vh]">

                @if ($galleryCount > 1)
                    <button type="button"
                            @click.stop="previous()"
                            aria-label="Show previous image"
                            class="absolute left-3 top-1/2 z-20 inline-flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full bg-white/15 text-white shadow-lg shadow-black/25 transition hover:bg-white/25 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-black sm:left-6 sm:h-12 sm:w-12">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" class="h-5 w-5" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6" />
                        </svg>
                    </button>
                    <button type="button"
                            @click.stop="next()"
                            aria-label="Show next image"
                            class="absolute right-3 top-1/2 z-20 inline-flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full bg-white/15 text-white shadow-lg shadow-black/25 transition hover:bg-white/25 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-black sm:right-6 sm:h-12 sm:w-12">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" class="h-5 w-5" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 6l6 6-6 6" />
                        </svg>
                    </button>
                    <span class="absolute bottom-5 left-1/2 z-20 -translate-x-1/2 rounded-full bg-black/55 px-3 py-1.5 text-sm font-bold text-white shadow-lg shadow-black/20 sm:bottom-7"
                          aria-hidden="true"
                          x-text="`${activeIndex + 1} / ${images.length}`"></span>
                @endif
            </div>
        </template>
    </section>
@endsection
