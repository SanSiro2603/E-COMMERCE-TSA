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
                @if(filled($settings['hero_description']))
                    <p class="mt-5 max-w-xl text-xl leading-relaxed text-white/90 sm:text-2xl" data-word-stagger>{{ $settings['hero_description'] }}</p>
                @endif
            </div>
        </div>
    </section>

    <section class="bg-white py-12 sm:py-14">
        <div class="mx-auto w-[94%] max-w-[1240px] rounded-2xl bg-[#f7faf5] p-6 sm:p-8">
            <div class="grid gap-6 lg:grid-cols-2">
                <div class="reveal-left" data-reveal>
                    <h2 class="line-mask text-4xl font-extrabold text-tsa-greenDark sm:text-5xl" data-line-reveal>
                        <span class="line-mask-inner">{{ $settings['commitment_heading'] }}</span>
                    </h2>
                    <p class="mt-4 text-lg leading-relaxed text-slate-700 sm:text-xl">
                        {{ $settings['commitment_paragraph_1'] }}
                    </p>
                    <p class="mt-4 text-lg leading-relaxed text-slate-700 sm:text-xl">
                        {{ $settings['commitment_paragraph_2'] }}
                    </p>
                </div>

                <div class="reveal-right grid gap-3 sm:grid-cols-2" data-reveal>
                    <img src="{{ $assets['commitment_image_1'] }}" alt="{{ $settings['commitment_image_1_alt'] }}" class="h-52 w-full rounded-xl object-cover sm:h-full">
                    <img src="{{ $assets['commitment_image_2'] }}" alt="{{ $settings['commitment_image_2_alt'] }}" class="h-52 w-full rounded-xl object-cover sm:h-full">
                </div>
            </div>

            <div class="mt-6 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($quality_items as $item)
                    <article class="reveal-up delay-{{ $loop->iteration }} zoom-soft rounded-xl border border-slate-200 bg-white p-4 text-center" data-reveal>
                        <div class="mx-auto inline-flex h-10 w-10 items-center justify-center rounded-full bg-lime-100 text-tsa-greenDark">
                            <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 2l8 4v6c0 5-3.4 9.7-8 11-4.6-1.3-8-6-8-11V6l8-4z"></path>
                                <path d="M9 12l2 2 4-4"></path>
                            </svg>
                        </div>
                        <h3 class="mt-3 text-xl font-extrabold text-tsa-greenDark">{{ $item->titleForLocale() }}</h3>
                        <p class="mt-2 text-base leading-relaxed text-slate-600">{{ $item->descriptionForLocale() }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="bg-white pb-12 sm:pb-14">
        <div class="mx-auto w-[94%] max-w-[1240px] rounded-2xl bg-[#f7faf5] p-6 sm:p-8">
            <div class="grid gap-6 lg:grid-cols-2 lg:items-center">
                <div class="reveal-left" data-reveal>
                    <h2 class="line-mask text-4xl font-extrabold text-tsa-greenDark sm:text-5xl" data-line-reveal>
                        <span class="line-mask-inner">{{ $settings['sources_heading'] }}</span>
                    </h2>
                    <p class="mt-4 text-lg leading-relaxed text-slate-700 sm:text-xl">
                        {{ $settings['sources_paragraph_1'] }}
                    </p>
                </div>
                <img src="{{ $assets['sources_image'] }}" alt="{{ $settings['sources_image_alt'] }}" class="reveal-right zoom-soft h-56 w-full rounded-xl object-cover sm:h-64" data-reveal>
            </div>

            <p class="reveal-up mt-6 text-lg leading-relaxed text-slate-700 sm:text-xl" data-reveal>
                {{ $settings['sources_paragraph_2'] }}
            </p>

            <div class="mt-6 grid gap-3 sm:grid-cols-2 xl:grid-cols-5">
                @foreach ($source_cards as $card)
                    <article class="reveal-up delay-{{ ($loop->index % 8) + 1 }} zoom-soft rounded-xl border border-slate-200 bg-white p-3" data-reveal>
                        <img src="{{ $card->image_url }}" alt="{{ data_get($card->metadata, 'alt', $card->titleForLocale()) }}" class="h-36 w-full rounded-lg object-cover">
                        <h3 class="mt-3 text-base font-extrabold text-slate-900">{{ $card->titleForLocale() }}</h3>
                    </article>
                @endforeach
            </div>

            <div class="reveal-up mt-6 rounded-xl border border-lime-200 bg-lime-50 px-5 py-4" data-reveal>
                <p class="text-base leading-relaxed text-slate-700 sm:text-lg">
                    {{ $settings['sources_note'] }}
                </p>
            </div>
        </div>
    </section>

    <section class="bg-white pb-12 sm:pb-14">
        <div class="mx-auto w-[94%] max-w-[1240px] rounded-2xl border border-slate-200 bg-white p-5 sm:p-6">
            <div class="reveal-up mx-auto mb-5 inline-flex rounded-lg bg-tsa-greenDark px-8 py-2 text-lg font-extrabold text-white" data-reveal>
                {{ $settings['standards_heading'] }}
            </div>

            <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-5">
                @foreach ($standards as $standard)
                    <article class="reveal-up delay-{{ ($loop->index % 8) + 1 }} zoom-soft rounded-xl border border-slate-200 p-4 text-center" data-reveal>
                        <div class="mx-auto inline-flex h-11 w-11 items-center justify-center rounded-full bg-lime-100 text-tsa-greenDark">
                            <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 12l2 2 4-4"></path>
                                <path d="M21 12c0 5-4 9-9 9s-9-4-9-9 4-9 9-9 9 4 9 9z"></path>
                            </svg>
                        </div>
                        <h3 class="mt-3 text-xl font-extrabold text-tsa-greenDark">{{ $standard->titleForLocale() }}</h3>
                        <p class="mt-2 text-base leading-relaxed text-slate-600">{{ $standard->descriptionForLocale() }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
@endsection
