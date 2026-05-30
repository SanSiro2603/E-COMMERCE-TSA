@extends('landing.layout')

@section('content')
    <section class="relative isolate h-[380px] overflow-hidden bg-black sm:h-[420px]">
        <div
            class="absolute inset-0 bg-cover bg-center"
            style="background-image: linear-gradient(95deg, rgba(5,16,7,.82) 0%, rgba(5,16,7,.48) 48%, rgba(5,16,7,.22) 100%), url('{{ asset('images/procurement-banner.jpeg') }}');">
        </div>

        <div class="relative mx-auto flex h-full w-[94%] max-w-[1240px] items-center">
            <div class="max-w-2xl text-white">
                <p class="text-sm font-semibold text-white/85">Home &nbsp;›&nbsp; Information &nbsp;›&nbsp; Procurement & Preparation</p>
                <h1 class="mt-4 text-5xl font-extrabold leading-tight sm:text-6xl">Procurement & Preparation</h1>
                <div class="mt-4 h-1 w-20 rounded-full bg-tsa-green"></div>
            </div>
        </div>
    </section>

    <section class="bg-white py-12 sm:py-14">
        <div class="mx-auto w-[94%] max-w-[1240px] rounded-2xl bg-[#f7faf5] p-6 sm:p-8">
            <div class="grid gap-6 lg:grid-cols-2">
                <div>
                    <p class="text-4xl font-extrabold text-tsa-greenDark sm:text-5xl">Our Commitment</p>
                    <p class="mt-4 text-lg leading-relaxed text-slate-700 sm:text-xl">
                        After careful selection of animals, we tailor our approach to preparation, continually reviewing and refining our stringent quality
                        assurance procedures to ensure livestock are prepared not only in accordance with the regulatory and animal welfare requirements
                        of the exporting and importing countries, but also to ensure safe and secure transportation and optimal animal performance for
                        our customers production systems.
                    </p>
                    <p class="mt-4 text-lg leading-relaxed text-slate-700 sm:text-xl">
                        All animals are identified with a closed ring or individual electronic tag (microchip) connected to a database to record their ancestry,
                        breed, health and welfare data.
                    </p>
                </div>

                <div class="grid gap-3 sm:grid-cols-2">
                    <img src="https://images.unsplash.com/photo-1552728089-57bdde30beb3?auto=format&fit=crop&w=900&q=80" alt="Parrot inspection close-up" class="h-52 w-full rounded-xl object-cover sm:h-full">
                    <img src="https://images.unsplash.com/photo-1612444530582-fc66183b16f7?auto=format&fit=crop&w=900&q=80" alt="Digital animal identification scanner" class="h-52 w-full rounded-xl object-cover sm:h-full">
                </div>
            </div>

            @php
                $qualityItems = [
                    ['title' => 'Quality Assurance', 'desc' => 'Strict quality control at every stage.'],
                    ['title' => 'Animal Welfare', 'desc' => 'We prioritize the health and well-being.'],
                    ['title' => 'Individual Identification', 'desc' => 'Microchip tracking for complete traceability.'],
                    ['title' => 'Regulatory Compliance', 'desc' => 'Meet international regulations & standards.'],
                ];
            @endphp

            <div class="mt-6 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($qualityItems as $item)
                    <article class="rounded-xl border border-slate-200 bg-white p-4 text-center">
                        <div class="mx-auto inline-flex h-10 w-10 items-center justify-center rounded-full bg-lime-100 text-tsa-greenDark">
                            <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 2l8 4v6c0 5-3.4 9.7-8 11-4.6-1.3-8-6-8-11V6l8-4z"></path>
                                <path d="M9 12l2 2 4-4"></path>
                            </svg>
                        </div>
                        <h3 class="mt-3 text-xl font-extrabold text-tsa-greenDark">{{ $item['title'] }}</h3>
                        <p class="mt-2 text-base leading-relaxed text-slate-600">{{ $item['desc'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="bg-white pb-12 sm:pb-14">
        <div class="mx-auto w-[94%] max-w-[1240px] rounded-2xl bg-[#f7faf5] p-6 sm:p-8">
            <div class="grid gap-6 lg:grid-cols-2 lg:items-center">
                <div>
                    <p class="text-4xl font-extrabold text-tsa-greenDark sm:text-5xl">Sources of Livestock</p>
                    <p class="mt-4 text-lg leading-relaxed text-slate-700 sm:text-xl">
                        We work closely with accredited breeding facilities, conservation organizations, and certified breeders to obtain healthy,
                        ethically bred, and high-quality animals.
                    </p>
                </div>
                <img src="https://images.unsplash.com/photo-1617957743098-33b1f3f77f6a?auto=format&fit=crop&w=1300&q=80" alt="Breeding center aerial view" class="h-56 w-full rounded-xl object-cover sm:h-64">
            </div>

            <p class="mt-6 text-lg leading-relaxed text-slate-700 sm:text-xl">
                All breeding partners are carefully selected based on their reputation, facilities, animal welfare practices, and compliance with national and international regulations.
            </p>

            @php
                $sourceCards = [
                    ['title' => 'Accredited Breeding Partners', 'img' => 'https://images.unsplash.com/photo-1452570053594-1b985d6ea890?auto=format&fit=crop&w=900&q=80'],
                    ['title' => 'Ethical Breeding Practices', 'img' => 'https://images.unsplash.com/photo-1501700493788-fa1a4fc9fe62?auto=format&fit=crop&w=900&q=80'],
                    ['title' => 'Health & Genetic Screening', 'img' => 'https://images.unsplash.com/photo-1552728089-57bdde30beb3?auto=format&fit=crop&w=900&q=80'],
                    ['title' => 'Hygiene & Biosecurity', 'img' => 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=900&q=80'],
                    ['title' => 'Continuous Monitoring', 'img' => 'https://images.unsplash.com/photo-1526336024174-e58f5cdd8e13?auto=format&fit=crop&w=900&q=80'],
                ];
            @endphp

            <div class="mt-6 grid gap-3 sm:grid-cols-2 xl:grid-cols-5">
                @foreach ($sourceCards as $card)
                    <article class="rounded-xl border border-slate-200 bg-white p-3">
                        <img src="{{ $card['img'] }}" alt="{{ $card['title'] }}" class="h-36 w-full rounded-lg object-cover">
                        <h3 class="mt-3 text-base font-extrabold text-slate-900">{{ $card['title'] }}</h3>
                    </article>
                @endforeach
            </div>

            <div class="mt-6 rounded-xl border border-lime-200 bg-lime-50 px-5 py-4">
                <p class="text-base leading-relaxed text-slate-700 sm:text-lg">
                    Our procurement and preparation processes are designed to ensure the highest standards of animal health, welfare, and safety before export.
                </p>
            </div>
        </div>
    </section>

    @php
        $standards = [
            ['title' => 'Health Check', 'desc' => 'Comprehensive veterinary examination and health certification.'],
            ['title' => 'Vaccination', 'desc' => 'Appropriate vaccinations according to destination country requirements.'],
            ['title' => 'Nutrition', 'desc' => 'Balanced diet to ensure optimal condition and stamina.'],
            ['title' => 'Quarantine', 'desc' => 'Isolation and monitoring to prevent disease transmission.'],
            ['title' => 'Export Ready', 'desc' => 'Fully prepared and certified for safe international transportation.'],
        ];
    @endphp

    <section class="bg-white pb-12 sm:pb-14">
        <div class="mx-auto w-[94%] max-w-[1240px] rounded-2xl border border-slate-200 bg-white p-5 sm:p-6">
            <div class="mx-auto mb-5 inline-flex rounded-lg bg-tsa-greenDark px-8 py-2 text-lg font-extrabold text-white">
                Our Preparation Standards
            </div>

            <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-5">
                @foreach ($standards as $standard)
                    <article class="rounded-xl border border-slate-200 p-4 text-center">
                        <div class="mx-auto inline-flex h-11 w-11 items-center justify-center rounded-full bg-lime-100 text-tsa-greenDark">
                            <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 12l2 2 4-4"></path>
                                <path d="M21 12c0 5-4 9-9 9s-9-4-9-9 4-9 9-9 9 4 9 9z"></path>
                            </svg>
                        </div>
                        <h3 class="mt-3 text-xl font-extrabold text-tsa-greenDark">{{ $standard['title'] }}</h3>
                        <p class="mt-2 text-base leading-relaxed text-slate-600">{{ $standard['desc'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
@endsection
