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

    <section class="bg-white py-12 sm:py-14">
        <div class="mx-auto grid w-[94%] max-w-[1240px] gap-8 lg:grid-cols-2 lg:items-center">
            <div class="reveal-left" data-reveal>
                <p class="text-xs font-extrabold uppercase tracking-[0.14em] text-tsa-greenDark">{{ $settings['commitment_label'] }}</p>
                <p class="mt-4 text-lg leading-relaxed text-slate-700 sm:text-xl">
                    {{ $settings['commitment_paragraph_1'] }}
                </p>
                <p class="mt-4 text-lg leading-relaxed text-slate-700 sm:text-xl">
                    {{ $settings['commitment_paragraph_2'] }}
                </p>
            </div>

            <div class="reveal-right zoom-soft overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm" data-reveal>
                <img src="{{ $assets['commitment_image'] }}" alt="{{ $settings['commitment_image_alt'] }}" class="h-full w-full object-cover">
            </div>
        </div>
    </section>

    <section class="bg-tsa-soft py-12 sm:py-14">
        <div class="mx-auto w-[94%] max-w-[1240px]">
            <div class="reveal-up text-center" data-reveal>
                <h2 class="line-mask text-4xl font-extrabold text-tsa-greenDark sm:text-5xl" data-line-reveal>
                    <span class="line-mask-inner">{{ $settings['air_heading'] }}</span>
                </h2>
            </div>

            <div class="mt-7 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                @foreach ($air_steps as $index => $step)
                    <article class="reveal-up delay-{{ $loop->iteration }} zoom-soft rounded-xl border border-slate-200 bg-white p-3 shadow-sm" data-reveal>
                        <img src="{{ $step->image_url }}" alt="{{ data_get($step->metadata, 'alt', $step->titleForLocale()) }}" class="h-40 w-full rounded-lg object-cover">
                        <div class="mt-3 inline-flex rounded-full bg-tsa-green px-3 py-1 text-sm font-extrabold text-white">
                            {{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}
                        </div>
                        <h3 class="mt-2 text-2xl font-extrabold text-slate-900">{{ $step->titleForLocale() }}</h3>
                        <p class="mt-2 text-base leading-relaxed text-slate-600">{{ $step->descriptionForLocale() }}</p>
                    </article>
                @endforeach
            </div>

            <div class="reveal-up mt-5 rounded-xl border border-slate-200 bg-white px-5 py-4" data-reveal>
                <p class="text-base leading-relaxed text-slate-700 sm:text-lg">
                    {{ $settings['air_note'] }}
                </p>
            </div>
        </div>
    </section>

    <section class="bg-tsa-soft pb-12 sm:pb-14">
        <div class="mx-auto w-[94%] max-w-[1240px]">
            <div class="reveal-up text-center" data-reveal>
                <h2 class="line-mask text-4xl font-extrabold text-tsa-greenDark sm:text-5xl" data-line-reveal>
                    <span class="line-mask-inner">{{ $settings['sea_heading'] }}</span>
                </h2>
            </div>

            <div class="mt-7 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                @foreach ($sea_steps as $index => $step)
                    <article class="reveal-up delay-{{ $loop->iteration }} zoom-soft rounded-xl border border-slate-200 bg-white p-3 shadow-sm" data-reveal>
                        <img src="{{ $step->image_url }}" alt="{{ data_get($step->metadata, 'alt', $step->titleForLocale()) }}" class="h-40 w-full rounded-lg object-cover">
                        <div class="mt-3 inline-flex rounded-full bg-tsa-green px-3 py-1 text-sm font-extrabold text-white">
                            {{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}
                        </div>
                        <h3 class="mt-2 text-2xl font-extrabold text-slate-900">{{ $step->titleForLocale() }}</h3>
                        <p class="mt-2 text-base leading-relaxed text-slate-600">{{ $step->descriptionForLocale() }}</p>
                    </article>
                @endforeach
            </div>

            <div class="reveal-up mt-5 rounded-xl border border-slate-200 bg-white px-5 py-4" data-reveal>
                <p class="text-base leading-relaxed text-slate-700 sm:text-lg">
                    {{ $settings['sea_note'] }}
                </p>
            </div>
        </div>
    </section>
@endsection
