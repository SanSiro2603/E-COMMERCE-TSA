@extends('landing.layout')

@section('content')
    <section class="relative isolate h-[430px] overflow-hidden bg-black sm:h-[470px]">
        <div
            class="absolute inset-0 bg-cover bg-center"
            style="background-image: linear-gradient(95deg, rgba(5,16,7,.82) 0%, rgba(5,16,7,.48) 48%, rgba(5,16,7,.22) 100%), url('{{ asset('images/banner-logistic-delivery.png') }}');">
        </div>

        <div class="relative mx-auto flex h-full w-[94%] max-w-[1240px] items-center">
            <div class="reveal-up max-w-2xl text-white" data-reveal>
                <p class="text-xl font-bold sm:text-2xl">Information &nbsp;&rsaquo;&nbsp; Logistic & Delivery</p>
                <h1 class="line-mask mt-2 font-extrabold leading-[0.98] tracking-tight text-[46px] sm:text-[64px]" data-line-reveal>
                    <span class="line-mask-inner">Logistic & Delivery</span>
                </h1>
                <div class="mt-4 h-1 w-20 rounded-full bg-tsa-green"></div>
                <p class="mt-5 max-w-xl text-xl leading-relaxed text-white/90 sm:text-2xl" data-word-stagger>
                    Safe, professional and reliable logistics solutions for the live animal transportation worldwide
                </p>
            </div>
        </div>
    </section>

    <section class="bg-white py-12 sm:py-14">
        <div class="mx-auto grid w-[94%] max-w-[1240px] gap-8 lg:grid-cols-2 lg:items-center">
            <div class="reveal-left" data-reveal>
                <p class="text-xs font-extrabold uppercase tracking-[0.14em] text-tsa-greenDark">Our Commitment</p>
                <p class="mt-4 text-lg leading-relaxed text-slate-700 sm:text-xl">
                    We professionally oversee the loading and shipping of animals in optimal conditions to meet our customers needs.
                    As a token of our commitment to government certification, we can also provide technical support and guidance
                    to optimize animal health, welfare, and performance. Our experience and network enable us to efficiently
                    select quality animals for orders of varying sizes.
                </p>
                <p class="mt-4 text-lg leading-relaxed text-slate-700 sm:text-xl">
                    We are passionate about delivering quality, adaptable products that positively contribute to our customers goals
                    through careful preparation and a focus on the health and well-being of our customers and your animals.
                </p>
            </div>

            <div class="reveal-right zoom-soft overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm" data-reveal>
                <img src="{{ asset('images/ourcomitmen.png') }}" alt="Our commitment logistics process image" class="h-full w-full object-cover">
            </div>
        </div>
    </section>

    @php
        $airSteps = [
            [
                'title' => 'Animal Pickup',
                'desc' => 'Careful collection of animals from our breeding center with strict handling procedures.',
                'img' => 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'title' => 'Airport Handling',
                'desc' => 'Professional handling and transfer of animals to the airport cargo facility.',
                'img' => 'https://images.unsplash.com/photo-1601584115197-04ecc0da31d7?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'title' => 'Loading Process',
                'desc' => 'Animals are loaded into the aircraft under optimal conditions with temperature control.',
                'img' => 'https://images.unsplash.com/photo-1580833023191-83ec2c7e95d6?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'title' => 'Safe Delivery',
                'desc' => 'We ensure safe and timely delivery to the destination airport with full compliance.',
                'img' => 'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?auto=format&fit=crop&w=900&q=80',
            ],
        ];

        $seaSteps = [
            [
                'title' => 'Container Preparation',
                'desc' => 'Specialized containers are prepared with ventilation, temperature control, and appropriate flooring.',
                'img' => 'https://images.unsplash.com/photo-1586528116493-6f23a2f34c61?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'title' => 'Loading & Securing',
                'desc' => 'Animals are carefully loaded and secured to ensure safety during the voyage.',
                'img' => 'https://images.unsplash.com/photo-1578575437130-527eed3abbec?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'title' => 'Ocean Transport',
                'desc' => 'Shipment via reliable shipping lines with regular monitoring and care during transit.',
                'img' => 'https://images.unsplash.com/photo-1543459176-4426c2fc8be5?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'title' => 'Arrival & Customs Clearance',
                'desc' => 'Coordination with local agents for smooth customs clearance and final delivery.',
                'img' => 'https://images.unsplash.com/photo-1567789884554-0b844b597180?auto=format&fit=crop&w=900&q=80',
            ],
        ];
    @endphp

    <section class="bg-tsa-soft py-12 sm:py-14">
        <div class="mx-auto w-[94%] max-w-[1240px]">
            <div class="reveal-up text-center" data-reveal>
                <h2 class="line-mask text-4xl font-extrabold text-tsa-greenDark sm:text-5xl" data-line-reveal>
                    <span class="line-mask-inner">Air Freight Services</span>
                </h2>
            </div>

            <div class="mt-7 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                @foreach ($airSteps as $index => $step)
                    <article class="reveal-up delay-{{ $loop->iteration }} zoom-soft rounded-xl border border-slate-200 bg-white p-3 shadow-sm" data-reveal>
                        <img src="{{ $step['img'] }}" alt="{{ $step['title'] }}" class="h-40 w-full rounded-lg object-cover">
                        <div class="mt-3 inline-flex rounded-full bg-tsa-green px-3 py-1 text-sm font-extrabold text-white">
                            {{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}
                        </div>
                        <h3 class="mt-2 text-2xl font-extrabold text-slate-900">{{ $step['title'] }}</h3>
                        <p class="mt-2 text-base leading-relaxed text-slate-600">{{ $step['desc'] }}</p>
                    </article>
                @endforeach
            </div>

            <div class="reveal-up mt-5 rounded-xl border border-slate-200 bg-white px-5 py-4" data-reveal>
                <p class="text-base leading-relaxed text-slate-700 sm:text-lg">
                    Our air freight partners and routes are carefully selected to ensure the fastest, safest, and most comfortable travel for the animals.
                    All shipments are handled in accordance with IATA Live Animals Regulations.
                </p>
            </div>
        </div>
    </section>

    <section class="bg-tsa-soft pb-12 sm:pb-14">
        <div class="mx-auto w-[94%] max-w-[1240px]">
            <div class="reveal-up text-center" data-reveal>
                <h2 class="line-mask text-4xl font-extrabold text-tsa-greenDark sm:text-5xl" data-line-reveal>
                    <span class="line-mask-inner">Sea Freight Services</span>
                </h2>
            </div>

            <div class="mt-7 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                @foreach ($seaSteps as $index => $step)
                    <article class="reveal-up delay-{{ $loop->iteration }} zoom-soft rounded-xl border border-slate-200 bg-white p-3 shadow-sm" data-reveal>
                        <img src="{{ $step['img'] }}" alt="{{ $step['title'] }}" class="h-40 w-full rounded-lg object-cover">
                        <div class="mt-3 inline-flex rounded-full bg-tsa-green px-3 py-1 text-sm font-extrabold text-white">
                            {{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}
                        </div>
                        <h3 class="mt-2 text-2xl font-extrabold text-slate-900">{{ $step['title'] }}</h3>
                        <p class="mt-2 text-base leading-relaxed text-slate-600">{{ $step['desc'] }}</p>
                    </article>
                @endforeach
            </div>

            <div class="reveal-up mt-5 rounded-xl border border-slate-200 bg-white px-5 py-4" data-reveal>
                <p class="text-base leading-relaxed text-slate-700 sm:text-lg">
                    We work with trusted shipping lines and experienced logistics partners to ensure the welfare of the animals throughout the sea journey
                    and compliance with international regulations.
                </p>
            </div>
        </div>
    </section>
@endsection

