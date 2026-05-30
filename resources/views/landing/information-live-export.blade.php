@extends('landing.layout')

@section('content')
    <section class="relative isolate h-[380px] overflow-hidden bg-black sm:h-[420px]">
        <div
            class="absolute inset-0 bg-cover bg-center"
            style="background-image: linear-gradient(95deg, rgba(5,16,7,.82) 0%, rgba(5,16,7,.48) 48%, rgba(5,16,7,.22) 100%), url('{{ asset('images/live-export-banner.jpeg') }}');">
        </div>

        <div class="relative mx-auto flex h-full w-[94%] max-w-[1240px] items-center">
            <div class="max-w-2xl text-white">
                <p class="text-sm font-semibold text-white/85">Home &nbsp;›&nbsp; Information &nbsp;›&nbsp; Live Export Process</p>
                <h1 class="mt-4 text-5xl font-extrabold leading-tight sm:text-6xl">Live Export Process</h1>
                <div class="mt-4 h-1 w-20 rounded-full bg-tsa-green"></div>
                <p class="mt-5 text-lg leading-relaxed text-white/90 sm:text-2xl">
                    A clear and transparent process to ensure safe, legal and ethical live animal export.
                </p>
            </div>
        </div>
    </section>

    <section class="bg-white py-10 sm:py-12">
        <div class="mx-auto w-[94%] max-w-[1240px] rounded-xl border border-slate-200 bg-[#f8faf7] px-5 py-4">
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
                'img' => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?auto=format&fit=crop&w=1000&q=80',
            ],
            [
                'title' => 'Contract Agreement',
                'desc' => 'A comprehensive contract is reviewed and signed by both parties before any work begins.',
                'img' => 'https://images.unsplash.com/photo-1556740749-887f6717d7e4?auto=format&fit=crop&w=1000&q=80',
            ],
            [
                'title' => 'Pro Forma Invoice',
                'desc' => 'The Pro Forma Invoice will be issued after the contract is signed. An initial payment is required to secure your order.',
                'img' => 'https://images.unsplash.com/photo-1554224154-26032fced8bd?auto=format&fit=crop&w=1000&q=80',
            ],
            [
                'title' => 'Import Permits',
                'desc' => 'We handle the import permit process in the destination country. Requirements may vary depending on each country\'s regulations.',
                'img' => 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?auto=format&fit=crop&w=1000&q=80',
            ],
            [
                'title' => 'Livestock Procurement Begins',
                'desc' => 'Selection and preparation of animals begin in accordance with the agreed specifications.',
                'img' => 'https://images.unsplash.com/photo-1516467508483-a7212febe31a?auto=format&fit=crop&w=1000&q=80',
            ],
            [
                'title' => 'Selection and On Farm Testing',
                'desc' => 'Genetic, physical, health, and temperament evaluations are conducted for approximately 2 weeks.',
                'img' => 'https://images.unsplash.com/photo-1552728089-57bdde30beb3?auto=format&fit=crop&w=1000&q=80',
            ],
            [
                'title' => 'Animal Quarantine',
                'desc' => 'Animals are transferred to a government-licensed quarantine facility.',
                'img' => 'https://images.unsplash.com/photo-1527482797697-8795b05a13fe?auto=format&fit=crop&w=1000&q=80',
            ],
            [
                'title' => 'Vet Checks, Testing and Vaccination',
                'desc' => 'Veterinary examinations, laboratory testing, and vaccinations are carried out during the quarantine period.',
                'img' => 'https://images.unsplash.com/photo-1584556812952-905ffd0c611a?auto=format&fit=crop&w=1000&q=80',
            ],
            [
                'title' => 'Final Payment',
                'desc' => 'Final payment is made to confirm air shipment and government export booking. All shipments are CIP (Carriage and Insurance Paid).',
                'img' => 'https://images.unsplash.com/photo-1556742208-999815fca738?auto=format&fit=crop&w=1000&q=80',
            ],
            [
                'title' => 'Final Government Vet Inspection and Delivery',
                'desc' => 'Final inspection by government veterinarian, delivery to destination port/airport, coordination with import agent, and all documents are sent 48 hours before shipment.',
                'img' => 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?auto=format&fit=crop&w=1000&q=80',
            ],
        ];

        $highlights = ['Legal Compliance', 'Animal Welfare', 'Quality Assurance', 'Safe Delivery'];
    @endphp

    <section class="bg-white pb-12 sm:pb-14">
        <div class="mx-auto w-[94%] max-w-[1240px]">
            <div class="relative">
                <div class="absolute left-1/2 top-0 hidden h-full w-px -translate-x-1/2 bg-lime-200 lg:block"></div>

                <div class="space-y-4">
                    @foreach ($steps as $index => $step)
                        @php
                            $isRight = $index % 2 === 1;
                            $number = $index + 1;
                        @endphp
                        <article class="relative grid items-center gap-4 lg:grid-cols-2">
                            @if (!$isRight)
                                <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                                    <div class="grid gap-4 md:grid-cols-[220px,1fr] md:items-center">
                                        <img src="{{ $step['img'] }}" alt="{{ $step['title'] }}" class="h-36 w-full rounded-lg object-cover md:h-32">
                                        <div>
                                            <h3 class="text-2xl font-extrabold text-tsa-greenDark">{{ $step['title'] }}</h3>
                                            <p class="mt-2 text-base leading-relaxed text-slate-600">{{ $step['desc'] }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="hidden lg:block"></div>
                            @else
                                <div class="hidden lg:block"></div>
                                <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                                    <div class="grid gap-4 md:grid-cols-[1fr,220px] md:items-center">
                                        <div>
                                            <h3 class="text-2xl font-extrabold text-tsa-greenDark">{{ $step['title'] }}</h3>
                                            <p class="mt-2 text-base leading-relaxed text-slate-600">{{ $step['desc'] }}</p>
                                        </div>
                                        <img src="{{ $step['img'] }}" alt="{{ $step['title'] }}" class="h-36 w-full rounded-lg object-cover md:h-32">
                                    </div>
                                </div>
                            @endif

                            <div class="absolute left-1/2 top-1/2 hidden h-12 w-12 -translate-x-1/2 -translate-y-1/2 items-center justify-center rounded-full border-2 border-lime-300 bg-white text-lg font-extrabold text-tsa-greenDark lg:flex">
                                {{ $number }}
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>

            <div class="mt-8 rounded-xl border border-lime-200 bg-lime-50 p-4 sm:p-5">
                <div class="grid gap-4 md:grid-cols-[2fr,3fr] md:items-center">
                    <div>
                        <h3 class="text-2xl font-extrabold text-tsa-greenDark">Our Commitment</h3>
                        <p class="mt-2 text-base leading-relaxed text-slate-700">We ensure every process is conducted with the highest standards of animal welfare, safety, and full compliance with all applicable laws and regulations.</p>
                    </div>
                    <div class="grid gap-2 sm:grid-cols-2 lg:grid-cols-4">
                        @foreach ($highlights as $item)
                            <div class="rounded-lg border border-lime-200 bg-white px-3 py-2 text-center text-sm font-bold text-tsa-greenDark">
                                {{ $item }}
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
