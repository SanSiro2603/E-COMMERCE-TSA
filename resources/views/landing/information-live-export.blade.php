@extends('landing.layout')

@section('content')
    <section class="relative isolate h-[430px] overflow-hidden bg-black sm:h-[470px]">
        <div
            class="absolute inset-0 bg-cover bg-center"
            style="background-position: {{ $settings['hero_position'] }}; background-image: linear-gradient(95deg, rgba(5,16,7,.82) 0%, rgba(5,16,7,.48) 48%, rgba(5,16,7,.22) 100%), url('{{ $assets['hero_image'] }}');">
        </div>

        <div class="relative mx-auto flex h-full w-[94%] max-w-[1240px] items-center">
            <div class="reveal-up max-w-2xl text-white" data-reveal>
                <p class="text-xl font-bold sm:text-2xl">{{ $settings['hero_eyebrow'] }}</p>
                <h1 class="line-mask mt-2 font-extrabold leading-[0.98] tracking-tight text-[46px] sm:text-[64px]" data-line-reveal>
                    <span class="line-mask-inner whitespace-pre-line">{{ $settings['hero_title'] }}</span>
                </h1>
                <div class="mt-4 h-1 w-20 rounded-full bg-tsa-green"></div>
                <p class="mt-5 max-w-xl text-xl leading-relaxed text-white/90 sm:text-2xl" data-word-stagger>
                    {{ $settings['hero_description'] }}
                </p>
            </div>
        </div>
    </section>

    <section class="bg-white py-10 sm:py-12">
        <div class="reveal-up mx-auto w-[94%] max-w-[1240px] rounded-xl border border-slate-200 bg-[#f8faf7] px-5 py-4" data-reveal>
            <p class="text-base leading-relaxed text-slate-700 sm:text-lg">
                {{ $settings['intro'] }}
            </p>
        </div>
    </section>

    <section class="bg-white pb-12 sm:pb-14" x-data="liveExportProcess()" x-init="init()">
        <div class="mx-auto w-[94%] max-w-[1240px]">
            <div class="relative">
                <div class="absolute bottom-0 left-1/2 top-0 hidden w-px -translate-x-1/2 bg-lime-200 lg:block"></div>

                <div class="space-y-5">
                    @foreach ($steps as $index => $step)
                        @php
                            $isRight = $index % 2 === 1;
                            $number = $index + 1;
                        @endphp
                        <article data-process-step="{{ $number }}" class="reveal-up delay-{{ ($loop->index % 8) + 1 }} grid items-center gap-4 lg:grid-cols-[1fr_auto_1fr] lg:gap-6" data-reveal>
                            @if (!$isRight)
                                <div class="zoom-soft overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                                    <img src="{{ $step->image_url }}" alt="{{ data_get($step->metadata, 'alt', $step->titleForLocale()) }}" class="h-52 w-full object-cover sm:h-64">
                                </div>
                                <div class="mx-auto flex h-11 w-11 items-center justify-center rounded-full border-2 text-base font-extrabold transition lg:h-12 lg:w-12"
                                     :class="activeStep === {{ $number }} ? 'border-tsa-green bg-tsa-green text-white shadow-md' : 'border-lime-300 bg-white text-tsa-greenDark'">
                                    {{ $number }}
                                </div>
                                <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
                                    <h3 class="text-xl font-extrabold text-tsa-greenDark sm:text-2xl">{{ $step->titleForLocale() }}</h3>
                                    <p class="mt-2 text-sm leading-relaxed text-slate-600 sm:text-base">{{ $step->descriptionForLocale() }}</p>
                                </div>
                            @else
                                <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
                                    <h3 class="text-xl font-extrabold text-tsa-greenDark sm:text-2xl">{{ $step->titleForLocale() }}</h3>
                                    <p class="mt-2 text-sm leading-relaxed text-slate-600 sm:text-base">{{ $step->descriptionForLocale() }}</p>
                                </div>
                                <div class="mx-auto flex h-11 w-11 items-center justify-center rounded-full border-2 text-base font-extrabold transition lg:h-12 lg:w-12"
                                     :class="activeStep === {{ $number }} ? 'border-tsa-green bg-tsa-green text-white shadow-md' : 'border-lime-300 bg-white text-tsa-greenDark'">
                                    {{ $number }}
                                </div>
                                <div class="zoom-soft overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                                    <img src="{{ $step->image_url }}" alt="{{ data_get($step->metadata, 'alt', $step->titleForLocale()) }}" class="h-52 w-full object-cover sm:h-64">
                                </div>
                            @endif
                        </article>
                    @endforeach
                </div>
            </div>

            <div class="reveal-up mt-8 rounded-xl border border-lime-200 bg-lime-50 p-4 sm:p-5" data-reveal>
                <div class="grid gap-4 md:grid-cols-[2fr,3fr] md:items-center">
                    <div>
                        <h3 class="text-2xl font-extrabold text-tsa-greenDark">{{ $settings['commitment_heading'] }}</h3>
                        <p class="mt-2 text-base leading-relaxed text-slate-700">{{ $settings['commitment_description'] }}</p>
                    </div>
                    <div class="grid gap-2 sm:grid-cols-2 lg:grid-cols-4">
                        @foreach ($highlights as $item)
                            <div class="reveal-up delay-{{ $loop->iteration }} rounded-lg border border-lime-200 bg-white px-3 py-2 text-center text-sm font-bold text-tsa-greenDark" data-reveal>
                                {{ $item->titleForLocale() }}
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        function liveExportProcess() {
            return {
                activeStep: 1,
                init() {
                    const stepElements = Array.from(this.$root.querySelectorAll('[data-process-step]'));
                    if (!stepElements.length || !('IntersectionObserver' in window)) {
                        return;
                    }

                    const visibility = new Map();
                    const observer = new IntersectionObserver((entries) => {
                        entries.forEach((entry) => {
                            const step = Number(entry.target.getAttribute('data-process-step'));
                            if (!step) return;

                            if (entry.isIntersecting) {
                                visibility.set(step, entry.intersectionRatio);
                            } else {
                                visibility.delete(step);
                            }
                        });

                        if (!visibility.size) return;

                        let bestStep = this.activeStep;
                        let bestRatio = -1;

                        visibility.forEach((ratio, step) => {
                            if (ratio > bestRatio) {
                                bestRatio = ratio;
                                bestStep = step;
                            }
                        });

                        this.activeStep = bestStep;
                    }, {
                        threshold: [0.2, 0.35, 0.5, 0.7],
                        rootMargin: '-8% 0px -35% 0px',
                    });

                    stepElements.forEach((element) => observer.observe(element));
                },
            };
        }
    </script>
@endpush
