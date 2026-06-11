@extends('landing.layout')

@section('content')
    <section class="relative isolate h-[430px] overflow-hidden bg-black sm:h-[470px]">
        <div
            class="absolute inset-0 bg-cover bg-center"
            style="background-image: linear-gradient(95deg, rgba(5,16,7,.82) 0%, rgba(5,16,7,.48) 48%, rgba(5,16,7,.22) 100%), url('{{ asset('images/procurement-banner-new.png') }}');">
        </div>

        <div class="relative mx-auto flex h-full w-[94%] max-w-[1240px] items-center">
            <div class="reveal-up max-w-2xl text-white" data-reveal>
                <p class="text-xl font-bold sm:text-2xl">Future Projects</p>
                <h1 class="line-mask mt-2 font-extrabold leading-[0.98] tracking-tight text-[46px] sm:text-[64px]" data-line-reveal>
                    <span class="line-mask-inner">Future Projects</span>
                </h1>
                <div class="mt-4 h-1 w-20 rounded-full bg-tsa-green"></div>
                <p class="mt-5 max-w-xl text-xl leading-relaxed text-white/90 sm:text-2xl" data-word-stagger>
                    Strategic development plans to strengthen our legal, certified, and sustainable wildlife breeding operations.
                </p>
            </div>
        </div>
    </section>

    @php
        $futureProjects = [
            'In the process of registering Appendix I permits at the Cites International organization',
            'In the licensing process for private animal quarantine facilities at the Indonesian Quarantine Agency.',
        ];
    @endphp

    <section class="bg-white py-14 sm:py-16">
        <div class="mx-auto w-[94%] max-w-[1240px]">
            <div class="reveal-up mx-auto max-w-3xl text-center" data-reveal>
                <p class="text-xs font-extrabold uppercase tracking-[0.14em] text-tsa-greenDark">Future Projects</p>
                <h2 class="line-mask mt-2 text-4xl font-extrabold text-slate-900 sm:text-5xl" data-line-reveal>
                    <span class="line-mask-inner">Development Roadmap</span>
                </h2>
            </div>

            <div class="mx-auto mt-10 grid max-w-5xl gap-4 md:grid-cols-2">
                @foreach ($futureProjects as $project)
                    <article class="reveal-up delay-{{ $loop->iteration }} zoom-soft rounded-xl border border-lime-200 bg-[#f7faf5] p-6 shadow-sm" data-reveal>
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-tsa-green text-xl font-extrabold text-white">
                            {{ $loop->iteration }}
                        </div>
                        <p class="mt-5 text-xl font-semibold leading-relaxed text-slate-700">
                            {{ $project }}
                        </p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
@endsection
