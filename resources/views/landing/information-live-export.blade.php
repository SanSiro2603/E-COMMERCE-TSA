@extends('landing.layout')

@section('content')
    <section class="relative isolate h-[430px] overflow-hidden bg-black sm:h-[470px]">
        <div
            class="absolute inset-0 bg-cover bg-center"
            style="background-image: linear-gradient(95deg, rgba(5,16,7,.82) 0%, rgba(5,16,7,.48) 48%, rgba(5,16,7,.22) 100%), url('{{ asset('images/live-export-banner-new.png') }}');">
        </div>

        <div class="relative mx-auto flex h-full w-[94%] max-w-[1240px] items-center">
            <div class="reveal-up max-w-2xl text-white" data-reveal>
                <p class="text-xl font-bold sm:text-2xl">Information &nbsp;&rsaquo;&nbsp; Live Export Process</p>
                <h1 class="line-mask mt-2 font-extrabold leading-[0.98] tracking-tight text-[46px] sm:text-[64px]" data-line-reveal>
                    <span class="line-mask-inner">Live Export Process</span>
                </h1>
                <div class="mt-4 h-1 w-20 rounded-full bg-tsa-green"></div>
                <p class="mt-5 max-w-xl text-xl leading-relaxed text-white/90 sm:text-2xl" data-word-stagger>
                    A clear and transparent process to ensure safe, legal and ethical live animal export
                </p>
            </div>
        </div>
    </section>

    <section class="bg-white py-10 sm:py-12">
        <div class="reveal-up mx-auto w-[94%] max-w-[1240px] rounded-xl border border-slate-200 bg-[#f8faf7] px-5 py-4" data-reveal>
            <p class="text-base leading-relaxed text-slate-700 sm:text-lg">
                We are committed to conducting live animal exports in full compliance with national and international regulations, including CITES,
                IATA Live Animals Regulations (LAR), and the animal welfare standards of importing countries. Below is our step-by-step export process
                from start to finish.
            </p>
        </div>
    </section>

    @php
        $steps = [
            [
                'title' => 'Initial Consultation',
                'desc' => 'We discuss your needs, specifications, quantity, and logistics requirements. A detailed proposal will be sent within 14 days.',
                'img' => asset('images/live-step-01.png'),
            ],
            [
                'title' => 'Contract Agreement',
                'desc' => 'A comprehensive contract is reviewed and signed by both parties before any work begins.',
                'img' => asset('images/live-step-02.png'),
            ],
            [
                'title' => 'Pro Forma Invoice',
                'desc' => 'The Pro Forma Invoice will be issued after the contract is signed. An initial payment is required to secure your order.',
                'img' => asset('images/live-step-03.png'),
            ],
            [
                'title' => 'Import Permits',
                'desc' => 'We handle the import permit process in the destination country. Requirements may vary depending on each country\'s regulations.',
                'img' => asset('images/live-step-04.png'),
            ],
            [
                'title' => 'Livestock Procurement Begins',
                'desc' => 'Selection and preparation of animals begin in accordance with the agreed specifications.',
                'img' => asset('images/live-step-05.png'),
            ],
            [
                'title' => 'Selection and On Farm Testing',
                'desc' => 'Genetic, physical, health, and temperament evaluations are conducted for approximately 2 weeks.',
                'img' => asset('images/live-step-06.png'),
            ],
            [
                'title' => 'Animal Quarantine',
                'desc' => 'Animals are transferred to a government-licensed quarantine facility.',
                'img' => asset('images/live-step-07.png'),
            ],
            [
                'title' => 'Vet Checks, Testing and Vaccination',
                'desc' => 'Veterinary examinations, laboratory testing, and vaccinations are carried out during the quarantine period.',
                'img' => asset('images/live-step-08.png'),
            ],
            [
                'title' => 'Final Payment',
                'desc' => 'Final payment is made to confirm air shipment and government export booking. All shipments are CIP (Carriage and Insurance Paid).',
                'img' => asset('images/live-step-09.png'),
            ],
            [
                'title' => 'Final Government Vet Inspection and Delivery',
                'desc' => 'Final inspection by government veterinarian, delivery to destination port/airport, coordination with import agent, and all documents are sent 48 hours before shipment.',
                'img' => asset('images/live-step-10.png'),
            ],
        ];

        $highlights = ['Legal Compliance', 'Animal Welfare', 'Quality Assurance', 'Safe Delivery'];
    @endphp

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
                                    <img src="{{ $step['img'] }}" alt="{{ $step['title'] }}" class="h-52 w-full object-cover sm:h-64">
                                </div>
                                <div class="mx-auto flex h-11 w-11 items-center justify-center rounded-full border-2 text-base font-extrabold transition lg:h-12 lg:w-12"
                                     :class="activeStep === {{ $number }} ? 'border-tsa-green bg-tsa-green text-white shadow-md' : 'border-lime-300 bg-white text-tsa-greenDark'">
                                    {{ $number }}
                                </div>
                                <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
                                    <h3 class="text-xl font-extrabold text-tsa-greenDark sm:text-2xl">{{ $step['title'] }}</h3>
                                    <p class="mt-2 text-sm leading-relaxed text-slate-600 sm:text-base">{{ $step['desc'] }}</p>
                                </div>
                            @else
                                <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
                                    <h3 class="text-xl font-extrabold text-tsa-greenDark sm:text-2xl">{{ $step['title'] }}</h3>
                                    <p class="mt-2 text-sm leading-relaxed text-slate-600 sm:text-base">{{ $step['desc'] }}</p>
                                </div>
                                <div class="mx-auto flex h-11 w-11 items-center justify-center rounded-full border-2 text-base font-extrabold transition lg:h-12 lg:w-12"
                                     :class="activeStep === {{ $number }} ? 'border-tsa-green bg-tsa-green text-white shadow-md' : 'border-lime-300 bg-white text-tsa-greenDark'">
                                    {{ $number }}
                                </div>
                                <div class="zoom-soft overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                                    <img src="{{ $step['img'] }}" alt="{{ $step['title'] }}" class="h-52 w-full object-cover sm:h-64">
                                </div>
                            @endif
                        </article>
                    @endforeach
                </div>
            </div>

            <div class="reveal-up mt-8 rounded-xl border border-lime-200 bg-lime-50 p-4 sm:p-5" data-reveal>
                <div class="grid gap-4 md:grid-cols-[2fr,3fr] md:items-center">
                    <div>
                        <h3 class="text-2xl font-extrabold text-tsa-greenDark">Our Commitment</h3>
                        <p class="mt-2 text-base leading-relaxed text-slate-700">We ensure every process is conducted with the highest standards of animal welfare, safety, and full compliance with all applicable laws and regulations.</p>
                    </div>
                    <div class="grid gap-2 sm:grid-cols-2 lg:grid-cols-4">
                        @foreach ($highlights as $item)
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

