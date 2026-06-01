@extends('landing.layout')

@section('content')
    <section class="relative isolate h-[430px] overflow-hidden bg-black sm:h-[470px]">
        <div
            class="absolute inset-0 bg-cover bg-center"
            style="background-image: linear-gradient(95deg, rgba(5,16,7,.82) 0%, rgba(5,16,7,.48) 48%, rgba(5,16,7,.22) 100%), url('{{ asset('images/support-banner.jpeg') }}');">
        </div>

        <div class="relative mx-auto flex h-full w-[94%] max-w-[1240px] items-center">
            <div class="reveal-up max-w-2xl text-white" data-reveal>
                <p class="text-xl font-bold sm:text-2xl">Support</p>
                <h1 class="line-mask mt-2 font-extrabold leading-[0.98] tracking-tight text-[46px] sm:text-[64px]" data-line-reveal><span class="line-mask-inner">Our Support & Partners</span></h1>
                <div class="mt-4 h-1 w-20 rounded-full bg-tsa-green"></div>
                <p class="mt-5 max-w-xl text-xl leading-relaxed text-white/90 sm:text-2xl" data-word-stagger>
                    Strong partnerships and reliable support are the foundation of our commitment to responsible wildlife trade and conservation.
                </p>
            </div>
        </div>
    </section>

    @php
        $partners = [
            [
                'name' => 'Kementerian Lingkungan Hidup dan Kehutanan',
                'desc' => 'Government partner in wildlife conservation, biodiversity protection, and sustainable natural resource management.',
                'logo' => null,
            ],
            [
                'name' => 'CITES',
                'desc' => 'Ensuring all international trade of wildlife is legal, sustainable, and not a threat to survival in the wild.',
                'logo' => asset('images/Groups.png'),
            ],
            [
                'name' => 'IATA',
                'desc' => 'Working in accordance with IATA Live Animals Regulations (LAR) to ensure safe and humane transportation.',
                'logo' => null,
            ],
            [
                'name' => 'BKSDA',
                'desc' => 'Supporting conservation programs, habitat protection, and the management of protected species.',
                'logo' => null,
            ],
            [
                'name' => 'Indonesia Quarantine',
                'desc' => 'Ensuring animal health standards and compliance with national and international biosecurity regulations.',
                'logo' => null,
            ],
            [
                'name' => 'Zoos Victoria',
                'desc' => 'Collaborating on conservation breeding programs and knowledge exchange for threatened species.',
                'logo' => null,
            ],
            [
                'name' => 'WAZA',
                'desc' => 'Member of the World Association of Zoos and Aquariums, supporting global conservation efforts.',
                'logo' => asset('images/Groups-5.png'),
            ],
            [
                'name' => 'Breeders Association',
                'desc' => 'Partnering with certified breeders to ensure ethical breeding practices and animal welfare.',
                'logo' => null,
            ],
            [
                'name' => 'FedEx Live Animals',
                'desc' => 'Reliable logistics partner for the safe, fast, and professional delivery of live animals worldwide.',
                'logo' => null,
            ],
            [
                'name' => 'Maersk Line',
                'desc' => 'Shipping partner for secure and efficient sea freight services with temperature control and safety.',
                'logo' => null,
            ],
        ];

        $highlightItems = ['Responsible Trade', 'Conservation Focused', 'Ethical Standards', 'Strong Partnerships'];
    @endphp

    <section class="bg-white py-12 sm:py-14">
        <div class="mx-auto w-[94%] max-w-[1240px]">
            <div class="reveal-up mx-auto max-w-4xl text-center" data-reveal>
                <h2 class="line-mask text-4xl font-extrabold text-tsa-greenDark sm:text-5xl" data-line-reveal><span class="line-mask-inner">Working Together for a Sustainable Future</span></h2>
                <div class="mx-auto mt-3 h-1 w-20 rounded-full bg-tsa-green"></div>
                <p class="mt-5 text-lg leading-relaxed text-slate-700 sm:text-xl" data-word-stagger>
                    We collaborate with government agencies, conservation organizations, breeders, and industry experts to ensure the highest standards of animal welfare, legal compliance, and environmental sustainability.
                </p>
            </div>

            <div class="mt-8 grid gap-4 md:grid-cols-2 xl:grid-cols-5">
                @foreach ($partners as $partner)
                    <article class="reveal-up delay-{{ ($loop->index % 8) + 1 }} zoom-soft rounded-xl border border-slate-200 bg-white p-5 text-center shadow-sm" data-reveal>
                        <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-lime-50">
                            @if ($partner['logo'])
                                <img src="{{ $partner['logo'] }}" alt="{{ $partner['name'] }} logo" class="max-h-16 w-auto object-contain">
                            @else
                                <span class="text-xs font-extrabold uppercase leading-tight text-tsa-greenDark">{{ \Illuminate\Support\Str::of($partner['name'])->explode(' ')->take(2)->implode(' ') }}</span>
                            @endif
                        </div>
                        <h3 class="mt-4 text-xl font-extrabold uppercase text-tsa-greenDark">{{ $partner['name'] }}</h3>
                        <p class="mt-3 text-base leading-relaxed text-slate-600">{{ $partner['desc'] }}</p>
                    </article>
                @endforeach
            </div>

            <div class="reveal-up mt-8 rounded-xl border border-lime-200 bg-lime-50 p-4 sm:p-5" data-reveal>
                <div class="grid gap-4 md:grid-cols-[2fr,3fr] md:items-center">
                    <div>
                        <h3 class="text-2xl font-extrabold text-tsa-greenDark">Our Commitment</h3>
                        <p class="mt-2 text-base leading-relaxed text-slate-700">We are committed to building long-term partnerships based on trust, transparency, and shared values to protect wildlife and support a healthier planet.</p>
                    </div>
                    <div class="grid gap-2 sm:grid-cols-2 lg:grid-cols-4">
                        @foreach ($highlightItems as $item)
                            <div class="reveal-up delay-{{ $loop->iteration }} rounded-lg border border-lime-200 bg-white px-3 py-2 text-center text-sm font-bold text-tsa-greenDark" data-reveal>
                                {{ $item }}
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
