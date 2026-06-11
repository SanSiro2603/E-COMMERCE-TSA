@extends('landing.layout')

@section('content')
    <section class="relative isolate h-[430px] overflow-hidden bg-black sm:h-[470px]">
        <div
            class="absolute inset-0 bg-cover bg-center"
            style="background-position: 78% top; background-image: linear-gradient(100deg, rgba(8,16,4,.84) 0%, rgba(8,16,4,.54) 45%, rgba(8,16,4,.2) 100%), url('{{ asset('images/about-banner-nicobar-pigeon.png') }}');">
        </div>

        <div class="relative mx-auto flex h-full w-[94%] max-w-[1240px] items-center">
            <div class="reveal-up max-w-2xl text-white" data-reveal>
                <p class="text-2xl font-bold sm:text-3xl">About</p>
                <h1 class="mt-2 font-extrabold leading-[0.98] tracking-tight text-[46px] sm:text-[64px]">
                    <span class="line-mask block text-white" data-line-reveal>
                        <span class="line-mask-inner">Tunas Sejahtera</span>
                    </span>
                    <span class="line-mask block text-white" data-line-reveal>
                        <span class="line-mask-inner">Adhi Perkasa</span>
                    </span>
                </h1>
                <div class="mt-4 h-1 w-20 rounded-full bg-tsa-green"></div>
                <p class="mt-5 max-w-xl text-xl leading-relaxed text-white/90 sm:text-2xl" data-word-stagger>
                    Committed to wildlife conservation, sustainable breeding, and responsible international trade for a better future
                </p>
            </div>
        </div>
    </section>

    <section class="bg-white py-14 sm:py-16">
        <div class="mx-auto grid w-[94%] max-w-[1240px] gap-8 lg:grid-cols-2 lg:items-center">
            <div class="reveal-left" data-reveal>
                <p class="text-xs font-extrabold uppercase tracking-[0.14em] text-tsa-greenDark">About Us</p>
                <h2 class="line-mask mt-2 text-5xl font-extrabold text-slate-900 sm:text-6xl" data-line-reveal>
                    <span class="line-mask-inner">Who We Are</span>
                </h2>
                <p class="mt-5 text-lg leading-relaxed text-slate-700 sm:text-xl">
                    PT. Tunas Sejahtera Adhiperkasa adalah salah satu perusahaan yang mendukung program Pemerintah di bidang Peternakan
                    yang telah resmi terdaftar di Pemerintah Indonesia dan berkomitmen untuk memberikan edukasi kepada masyarakat
                    tentang pentingnya memahami lingkungan kita dalam rangka melestarikan habitat satwa liar kita.
                </p>
                <p class="mt-4 text-lg leading-relaxed text-slate-700 sm:text-xl">
                    Kami membiakkan hewan yang dilindungi dan tidak dilindungi serta membuka peluang kerjasama dan kemitraan
                    dengan perusahaan atau lembaga lain yang mendukung misi utama kami untuk melestarikan hewan di seluruh dunia.
                </p>
            </div>

            <div class="reveal-right zoom-soft overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm" data-reveal>
                <img src="{{ asset('images/whoweare.png') }}" alt="Tunas Sejahtera Adhi Perkasa breeding center" class="h-full w-full object-cover">
            </div>
        </div>
    </section>

    <section class="bg-tsa-soft py-14 sm:py-16">
        <div class="mx-auto w-[94%] max-w-[1240px]">
            <div class="reveal-up text-center" data-reveal>
                <p class="text-xs font-extrabold uppercase tracking-[0.14em] text-tsa-greenDark">Vision & Mission</p>
                <h2 class="line-mask mt-2 text-5xl font-extrabold text-tsa-greenDark sm:text-6xl" data-line-reveal>
                    <span class="line-mask-inner">Vision & Mission</span>
                </h2>
            </div>

            <div class="mt-8 space-y-4">
                <div class="grid gap-4 lg:grid-cols-2">
                    <article class="reveal-left rounded-xl bg-[#eaf2e4] p-7" data-reveal>
                        <h3 class="text-4xl font-extrabold text-tsa-greenDark">Our Vision</h3>
                        <p class="mt-4 text-lg leading-relaxed text-slate-700 sm:text-xl">
                            "To become a leading and trusted global wildlife breeding center company in the legal wildlife trade and contribute
                            to the sustainable conservation of biodiversity, especially animals".
                        </p>
                    </article>
                    <div class="reveal-right zoom-soft overflow-hidden rounded-xl border border-slate-200 bg-white" data-reveal>
                        <img src="{{ asset('images/about-vision-sumatran-rabbit.png') }}" alt="Sumatran striped rabbit in rainforest" class="h-full w-full object-cover">
                    </div>
                </div>

                <div class="grid gap-4 lg:grid-cols-2">
                    <div class="order-2 reveal-left zoom-soft overflow-hidden rounded-xl border border-slate-200 bg-white lg:order-1" data-reveal>
                        <img src="{{ asset('images/about-mission-linsang.png') }}" alt="Banded linsang in rainforest" class="h-full w-full object-cover">
                    </div>
                    <article class="order-1 reveal-right rounded-xl bg-[#eaf2e4] p-7 lg:order-2" data-reveal>
                        <h3 class="text-4xl font-extrabold text-tsa-greenDark">Our Mission</h3>
                        <ol class="mt-4 list-decimal space-y-2 pl-6 text-base leading-relaxed text-slate-700 sm:text-lg">
                            <li>Membangun kemitraan strategis dengan Pemerintah, lembaga konservasi, peternak bersertifikat dan Organisasi resmi di seluruh dunia.</li>
                            <li>Mendukung konservasi keanekaragaman hayati global melalui praktik perdagangan satwa liar yang bertanggung jawab dan berkelanjutan.</li>
                            <li>Memberikan layanan profesional, andal, dan berkualitas tinggi serta memberikan jaminan keamanan kepada klien di seluruh dunia.</li>
                        </ol>
                    </article>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-white py-14 sm:py-16">
        <div class="mx-auto w-[94%] max-w-[1240px]">
            <div class="reveal-up text-center" data-reveal>
                <p class="text-xs font-extrabold uppercase tracking-[0.14em] text-tsa-greenDark">Company Founder</p>
                <h2 class="line-mask mt-2 text-5xl font-extrabold text-slate-900 sm:text-6xl" data-line-reveal>
                    <span class="line-mask-inner">Our Leadership</span>
                </h2>
            </div>

            <p class="reveal-up delay-1 mx-auto mt-6 max-w-6xl text-center text-base leading-relaxed text-slate-700 sm:text-lg" data-reveal>
                The founder of PT. Tunas Sejahtera Adhiperkasa started from the love of Mrs. Rina Fitriani (President Commissioner) for cattle which over time her livestock increased, because of her love for animals she added several exotic animal collections, so she had many exotic animals that were traditionally farmed and over time her livestock population increased from day to day, based on her success in raising animals on January 13, 2020 she registered the animal breeding company with a notary so that it has a legal basis and official legality from the government and the authorities.
            </p>
            <p class="reveal-up delay-2 mx-auto mt-4 max-w-6xl text-center text-base leading-relaxed text-slate-700 sm:text-lg" data-reveal>
                PT Tunas Sejahtera Adhiperkasa is a company engaged in the Breeding Center and international wildlife trade (export and import) of various types of animals including Birds, Mammals, and Reptiles on a global scale, as an officially certified business actor in the wildlife trade industry, we carry out all activities in accordance with applicable national and international regulations, including those stipulated by CITES (Convention on International Trade in Endangered Species of Wild Fauna and Flora).
            </p>

        </div>
    </section>
@endsection
