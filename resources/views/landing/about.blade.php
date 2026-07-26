@extends('landing.layout')

@section('content')
    <section class="relative isolate h-[430px] overflow-hidden bg-black sm:h-[470px]">
        <div
            class="absolute inset-0 bg-cover bg-center"
            style="background-position: {{ $settings['hero_position'] }}; background-image: linear-gradient(100deg, rgba(8,16,4,.84) 0%, rgba(8,16,4,.54) 45%, rgba(8,16,4,.2) 100%), url('{{ $assets['hero_image'] }}');">
        </div>

        <div class="relative mx-auto flex h-full w-[94%] max-w-[1240px] items-center">
            <div class="reveal-up max-w-2xl text-white" data-reveal>
                <p class="text-2xl font-bold sm:text-3xl">{{ $settings['hero_eyebrow'] }}</p>
                <h1 class="mt-2 font-extrabold leading-[0.98] tracking-tight text-[46px] sm:text-[64px]">
                    <span class="line-mask block whitespace-pre-line text-white" data-line-reveal><span class="line-mask-inner">{{ $settings['hero_title'] }}</span></span>
                </h1>
                <div class="mt-4 h-1 w-20 rounded-full bg-tsa-green"></div>
                <p class="mt-5 max-w-xl text-xl leading-relaxed text-white/90 sm:text-2xl" data-word-stagger>
                    {{ $settings['hero_description'] }}
                </p>
            </div>
        </div>
    </section>

    <section class="bg-white py-14 sm:py-16">
        <div class="mx-auto grid w-[94%] max-w-[1240px] gap-8 lg:grid-cols-2 lg:items-center">
            <div class="reveal-left" data-reveal>
                <p class="text-xs font-extrabold uppercase tracking-[0.14em] text-tsa-greenDark">{{ $settings['about_label'] }}</p>
                <h2 class="line-mask mt-2 text-5xl font-extrabold text-slate-900 sm:text-6xl" data-line-reveal>
                    <span class="line-mask-inner">{{ $settings['about_heading'] }}</span>
                </h2>
                <p class="mt-5 text-lg leading-relaxed text-slate-700 sm:text-xl">
                    {{ $settings['about_paragraph_1'] }}
                </p>
                <p class="mt-4 text-lg leading-relaxed text-slate-700 sm:text-xl">
                    {{ $settings['about_paragraph_2'] }}
                </p>
            </div>

            <div class="reveal-right zoom-soft overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm" data-reveal>
                <img src="{{ $assets['about_image'] }}" alt="{{ $settings['about_image_alt'] }}" class="h-full w-full object-cover">
            </div>
        </div>
    </section>

    <section class="bg-tsa-soft py-14 sm:py-16">
        <div class="mx-auto w-[94%] max-w-[1240px]">
            <div class="reveal-up text-center" data-reveal>
                <p class="text-xs font-extrabold uppercase tracking-[0.14em] text-tsa-greenDark">{{ $settings['vision_mission_label'] }}</p>
                <h2 class="line-mask mt-2 text-5xl font-extrabold text-tsa-greenDark sm:text-6xl" data-line-reveal>
                    <span class="line-mask-inner">{{ $settings['vision_mission_heading'] }}</span>
                </h2>
            </div>

            <div class="mt-8 space-y-4">
                <div class="grid gap-4 lg:grid-cols-2">
                    <article class="reveal-left rounded-xl bg-[#eaf2e4] p-7" data-reveal>
                        <h3 class="text-4xl font-extrabold text-tsa-greenDark">{{ $settings['vision_title'] }}</h3>
                        <p class="mt-4 text-lg leading-relaxed text-slate-700 sm:text-xl">
                            {{ $settings['vision_description'] }}
                        </p>
                    </article>
                    <div class="reveal-right zoom-soft overflow-hidden rounded-xl border border-slate-200 bg-white" data-reveal>
                        <img src="{{ $assets['vision_image'] }}" alt="{{ $settings['vision_image_alt'] }}" class="h-full w-full object-cover">
                    </div>
                </div>

                <div class="grid gap-4 lg:grid-cols-2">
                    <div class="order-2 reveal-left zoom-soft overflow-hidden rounded-xl border border-slate-200 bg-white lg:order-1" data-reveal>
                        <img src="{{ $assets['mission_image'] }}" alt="{{ $settings['mission_image_alt'] }}" class="h-full w-full object-cover">
                    </div>
                    <article class="order-1 reveal-right rounded-xl bg-[#eaf2e4] p-7 lg:order-2" data-reveal>
                        <h3 class="text-4xl font-extrabold text-tsa-greenDark">{{ $settings['mission_title'] }}</h3>
                        <ol class="mt-4 list-decimal space-y-2 pl-6 text-base leading-relaxed text-slate-700 sm:text-lg">
                            @foreach($mission_items as $item)<li>{{ $item->descriptionForLocale() }}</li>@endforeach
                        </ol>
                    </article>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-white py-14 sm:py-16">
        <div class="mx-auto w-[94%] max-w-[1240px]">
            <div class="reveal-up text-center" data-reveal>
                <p class="text-xs font-extrabold uppercase tracking-[0.14em] text-tsa-greenDark">{{ $settings['leadership_label'] }}</p>
                <h2 class="line-mask mt-2 text-5xl font-extrabold text-slate-900 sm:text-6xl" data-line-reveal>
                    <span class="line-mask-inner">{{ $settings['leadership_heading'] }}</span>
                </h2>
            </div>

            <p class="reveal-up delay-1 mx-auto mt-6 max-w-6xl text-center text-base leading-relaxed text-slate-700 sm:text-lg" data-reveal>
                {{ $settings['leadership_paragraph_1'] }}
            </p>
            <p class="reveal-up delay-2 mx-auto mt-4 max-w-6xl text-center text-base leading-relaxed text-slate-700 sm:text-lg" data-reveal>
                {{ $settings['leadership_paragraph_2'] }}
            </p>

        </div>
    </section>
@endsection
