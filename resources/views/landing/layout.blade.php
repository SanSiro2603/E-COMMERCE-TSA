<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $pageTitle ?? 'Tunas Sejahtera Adhi Perkasa' }}</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        tsa: {
                            green: '#6DC21E',
                            greenDark: '#3E8F13',
                            soft: '#F7F8F5'
                        }
                    },
                    fontFamily: {
                        sans: ['Manrope', 'ui-sans-serif', 'system-ui', 'Segoe UI', 'sans-serif']
                    }
                }
            }
        };
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        .reveal-up,
        .reveal-left,
        .reveal-right {
            opacity: 0;
            transition: opacity 620ms cubic-bezier(0.22, 1, 0.36, 1),
                        transform 620ms cubic-bezier(0.22, 1, 0.36, 1);
            will-change: opacity, transform;
        }

        .reveal-up {
            transform: translateY(20px);
        }

        .reveal-left {
            transform: translateX(-20px);
        }

        .reveal-right {
            transform: translateX(20px);
        }

        .reveal-up.is-visible,
        .reveal-left.is-visible,
        .reveal-right.is-visible {
            opacity: 1;
            transform: translate(0, 0);
        }

        .zoom-soft {
            transition: transform 360ms ease, box-shadow 360ms ease;
        }

        .zoom-soft:hover {
            transform: translateY(-2px) scale(1.01);
        }

        .line-mask {
            display: block;
            overflow: hidden;
        }

        .line-mask .line-mask-inner {
            display: block;
            opacity: 0;
            transform: translateY(108%);
            transition: opacity 680ms cubic-bezier(0.22, 1, 0.36, 1),
                        transform 680ms cubic-bezier(0.22, 1, 0.36, 1);
            will-change: opacity, transform;
        }

        .line-mask.is-visible .line-mask-inner {
            opacity: 1;
            transform: translateY(0);
        }

        .word-stagger .word-token {
            display: inline-block;
            opacity: 0;
            transform: translateY(12px);
            transition: opacity 520ms cubic-bezier(0.22, 1, 0.36, 1),
                        transform 520ms cubic-bezier(0.22, 1, 0.36, 1);
            transition-delay: calc(var(--word-delay, 0) * 40ms);
            will-change: opacity, transform;
        }

        .word-stagger.is-visible .word-token {
            opacity: 1;
            transform: translateY(0);
        }

        .delay-1 { transition-delay: 80ms; }
        .delay-2 { transition-delay: 160ms; }
        .delay-3 { transition-delay: 240ms; }
        .delay-4 { transition-delay: 320ms; }
        .delay-5 { transition-delay: 400ms; }
        .delay-6 { transition-delay: 480ms; }
        .delay-7 { transition-delay: 560ms; }
        .delay-8 { transition-delay: 640ms; }

        .landing-content {
            opacity: 1;
            transform: translateX(0);
        }

        .js .landing-content {
            will-change: opacity, transform;
        }

        .js .landing-content.page-enter-forward {
            opacity: 0;
            transform: translateX(32px);
        }

        .js .landing-content.page-enter-back {
            opacity: 0;
            transform: translateX(-32px);
        }

        .js .landing-content.page-leave-forward {
            opacity: 0;
            transform: translateX(-32px);
            pointer-events: none;
        }

        .js .landing-content.is-ready {
            transition: transform 320ms cubic-bezier(0.22, 1, 0.36, 1),
                        opacity 320ms cubic-bezier(0.22, 1, 0.36, 1);
        }

        @media (prefers-reduced-motion: reduce) {
            .reveal-up,
            .reveal-left,
            .reveal-right,
            .zoom-soft,
            .line-mask .line-mask-inner,
            .word-stagger .word-token,
            .landing-content {
                opacity: 1;
                transform: none;
                transition: none;
            }
        }
    </style>

    @stack('head')
</head>
<body class="m-0 bg-tsa-soft font-sans text-slate-900 antialiased">
    @php
        $isInformationRoute = request()->routeIs('landing.information.*');
        $locale = app()->getLocale() === 'id' ? 'id' : 'en';
    @endphp

    {{-- ─── Main header ─────────────────────────────────────────── --}}
    <header class="fixed inset-x-0 top-0 z-50 border-b border-slate-200/80 bg-white/95 backdrop-blur-md"
            x-data="{ mobileOpen: false, infoOpen: false, langOpen: false, mobileLangOpen: false }">
        <div class="mx-auto flex h-16 w-[94%] max-w-[1240px] items-center justify-between gap-6">

            {{-- Logo --}}
            <a href="{{ route('landing') }}" class="flex shrink-0 items-center gap-3" aria-label="Tunas Sejahtera Adhi Perkasa">
                <img src="{{ asset('images/logo header.png') }}"
                     alt="TSA logo"
                     class="h-12 w-12 object-contain">
                <span class="hidden text-[13px] font-extrabold leading-tight text-tsa-greenDark sm:block">
                    <span class="block">Tunas Sejahtera</span>
                    <span class="block">Adhi Perkasa</span>
                </span>
            </a>

            {{-- Desktop nav --}}
            <nav class="hidden items-center gap-7 lg:flex" aria-label="Main Navigation">

                {{-- Home --}}
                <a href="{{ route('landing') }}"
                   class="relative pb-0.5 text-[14px] font-bold transition-colors duration-150
                          {{ request()->routeIs('landing') ? 'text-tsa-greenDark' : 'text-slate-800 hover:text-tsa-greenDark' }}"
                   x-data @mouseenter="$el.querySelector('span').style.width='100%'" @mouseleave="$el.querySelector('span').style.width='{{ request()->routeIs('landing') ? '100%' : '0%' }}'">
                    Home
                    <span class="absolute bottom-0 left-0 h-0.5 rounded-full bg-tsa-green transition-all duration-200"
                          style="width: {{ request()->routeIs('landing') ? '100%' : '0%' }}"></span>
                </a>

                {{-- About --}}
                <a href="{{ route('landing.about') }}"
                   class="relative pb-0.5 text-[14px] font-bold transition-colors duration-150
                          {{ request()->routeIs('landing.about') ? 'text-tsa-greenDark' : 'text-slate-800 hover:text-tsa-greenDark' }}"
                   x-data @mouseenter="$el.querySelector('span').style.width='100%'" @mouseleave="$el.querySelector('span').style.width='{{ request()->routeIs('landing.about') ? '100%' : '0%' }}'">
                    About
                    <span class="absolute bottom-0 left-0 h-0.5 rounded-full bg-tsa-green transition-all duration-200"
                          style="width: {{ request()->routeIs('landing.about') ? '100%' : '0%' }}"></span>
                </a>

                {{-- Catalog --}}
                <a href="{{ route('landing.catalog') }}"
                   class="relative pb-0.5 text-[14px] font-bold transition-colors duration-150
                          {{ request()->routeIs('landing.catalog') || request()->routeIs('landing.catalog.*') ? 'text-tsa-greenDark' : 'text-slate-800 hover:text-tsa-greenDark' }}"
                   x-data @mouseenter="$el.querySelector('span').style.width='100%'" @mouseleave="$el.querySelector('span').style.width='{{ request()->routeIs('landing.catalog') || request()->routeIs('landing.catalog.*') ? '100%' : '0%' }}'">
                    Catalog
                    <span class="absolute bottom-0 left-0 h-0.5 rounded-full bg-tsa-green transition-all duration-200"
                          style="width: {{ request()->routeIs('landing.catalog') || request()->routeIs('landing.catalog.*') ? '100%' : '0%' }}"></span>
                </a>

                {{-- Information dropdown --}}
                <div class="relative" @click.outside="infoOpen = false">
                    <button type="button" @click="infoOpen = !infoOpen"
                            class="relative inline-flex cursor-pointer items-center gap-1 pb-0.5 text-[14px] font-bold transition-colors duration-150
                                   {{ $isInformationRoute ? 'text-tsa-greenDark' : 'text-slate-800 hover:text-tsa-greenDark' }}">
                        Information
                        <svg class="h-3.5 w-3.5 transition-transform duration-200"
                             :class="infoOpen ? 'rotate-180' : ''"
                             viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/>
                        </svg>
                        @if($isInformationRoute)
                            <span class="absolute bottom-0 left-0 h-0.5 w-full rounded-full bg-tsa-green"></span>
                        @endif
                    </button>

                    <div x-show="infoOpen"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-1"
                         class="absolute left-0 top-full mt-2 w-72 overflow-hidden rounded-xl border border-slate-100 bg-white shadow-xl shadow-slate-200/60">
                        <a href="{{ route('landing.information.logistic-delivery') }}"
                           class="flex items-center gap-3 px-4 py-3 text-[13px] font-semibold text-slate-700 transition-colors duration-150 hover:bg-tsa-soft hover:text-tsa-greenDark">
                            <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-tsa-green/10 text-tsa-green">
                                <svg viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4"><path d="M6 3a3 3 0 00-3 3v8a3 3 0 003 3h8a3 3 0 003-3V6a3 3 0 00-3-3H6zm1 7a1 1 0 100-2 1 1 0 000 2zm3 0a1 1 0 100-2 1 1 0 000 2zm3 0a1 1 0 100-2 1 1 0 000 2z"/></svg>
                            </span>
                            Logistic and Delivery
                        </a>
                        <div class="mx-4 h-px bg-slate-100"></div>
                        <a href="{{ route('landing.information.procurement-preparation') }}"
                           class="flex items-center gap-3 px-4 py-3 text-[13px] font-semibold text-slate-700 transition-colors duration-150 hover:bg-tsa-soft hover:text-tsa-greenDark">
                            <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-tsa-green/10 text-tsa-green">
                                <svg viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/></svg>
                            </span>
                            Procurement and Preparation
                        </a>
                        <div class="mx-4 h-px bg-slate-100"></div>
                        <a href="{{ route('landing.information.live-export-process') }}"
                           class="flex items-center gap-3 px-4 py-3 text-[13px] font-semibold text-slate-700 transition-colors duration-150 hover:bg-tsa-soft hover:text-tsa-greenDark">
                            <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-tsa-green/10 text-tsa-green">
                                <svg viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/></svg>
                            </span>
                            Live Export Process
                        </a>
                    </div>
                </div>

                {{-- Support --}}
                <a href="{{ route('landing.support') }}"
                   class="relative pb-0.5 text-[14px] font-bold transition-colors duration-150
                          {{ request()->routeIs('landing.support') ? 'text-tsa-greenDark' : 'text-slate-800 hover:text-tsa-greenDark' }}"
                   x-data @mouseenter="$el.querySelector('span').style.width='100%'" @mouseleave="$el.querySelector('span').style.width='{{ request()->routeIs('landing.support') ? '100%' : '0%' }}'">
                    Support
                    <span class="absolute bottom-0 left-0 h-0.5 rounded-full bg-tsa-green transition-all duration-200"
                          style="width: {{ request()->routeIs('landing.support') ? '100%' : '0%' }}"></span>
                </a>
            </nav>

            {{-- Lang switcher + Login + mobile toggle --}}
            <div class="flex items-center gap-3">

                {{-- Language switcher (desktop) --}}
                <div class="relative hidden lg:block" @click.outside="langOpen = false">
                    <button type="button"
                            @click="langOpen = !langOpen"
                            class="relative inline-flex cursor-pointer items-center gap-1 pb-0.5 text-[14px] font-bold text-slate-800 transition-colors duration-150 hover:text-tsa-greenDark">
                        <span class="inline-flex h-3.5 w-3.5 overflow-hidden rounded-full" aria-hidden="true">
                            @if($locale === 'id')
                                <svg viewBox="0 0 24 24" class="h-3.5 w-3.5"><rect width="24" height="12" fill="#d80027"/><rect y="12" width="24" height="12" fill="#ffffff"/></svg>
                            @else
                                <svg viewBox="0 0 24 24" class="h-3.5 w-3.5"><rect width="24" height="24" fill="#012169"/><path d="M0 0l24 24M24 0L0 24" stroke="#fff" stroke-width="4"/><path d="M0 0l24 24M24 0L0 24" stroke="#c8102e" stroke-width="2"/><path d="M12 0v24M0 12h24" stroke="#fff" stroke-width="6"/><path d="M12 0v24M0 12h24" stroke="#c8102e" stroke-width="3"/></svg>
                            @endif
                        </span>
                        <span>{{ $locale === 'id' ? 'Indonesian' : 'English' }}</span>
                        <svg class="h-3.5 w-3.5 transition-transform duration-150" :class="langOpen ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/>
                        </svg>
                    </button>

                    <div x-show="langOpen"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-1"
                         class="absolute right-0 top-full mt-2 w-44 translate-x-4 overflow-hidden rounded-lg border border-slate-100 bg-white shadow-xl shadow-slate-200/60">
                        <a href="{{ route('lang.switch', 'id') }}"
                           class="flex items-center gap-2 px-3 py-2 text-sm font-semibold transition-colors duration-150 {{ $locale === 'id' ? 'bg-tsa-soft text-tsa-greenDark' : 'text-slate-700 hover:bg-tsa-soft hover:text-tsa-greenDark' }}">
                            <span class="inline-flex h-4 w-4 overflow-hidden rounded-full" aria-hidden="true">
                                <svg viewBox="0 0 24 24" class="h-4 w-4"><rect width="24" height="12" fill="#d80027"/><rect y="12" width="24" height="12" fill="#ffffff"/></svg>
                            </span>
                            Indonesian
                        </a>
                        <a href="{{ route('lang.switch', 'en') }}"
                           class="flex items-center gap-2 px-3 py-2 text-sm font-semibold transition-colors duration-150 {{ $locale === 'en' ? 'bg-tsa-soft text-tsa-greenDark' : 'text-slate-700 hover:bg-tsa-soft hover:text-tsa-greenDark' }}">
                            <span class="inline-flex h-4 w-4 overflow-hidden rounded-full" aria-hidden="true">
                                <svg viewBox="0 0 24 24" class="h-4 w-4"><rect width="24" height="24" fill="#012169"/><path d="M0 0l24 24M24 0L0 24" stroke="#fff" stroke-width="4"/><path d="M0 0l24 24M24 0L0 24" stroke="#c8102e" stroke-width="2"/><path d="M12 0v24M0 12h24" stroke="#fff" stroke-width="6"/><path d="M12 0v24M0 12h24" stroke="#c8102e" stroke-width="3"/></svg>
                            </span>
                            English
                        </a>
                    </div>
                </div>

                {{-- Login button (desktop) --}}
                <a href="{{ route('login') }}"
                   class="hidden items-center gap-2 rounded-lg bg-tsa-green px-4 py-2 text-[13px] font-bold text-white shadow-sm transition-colors duration-150 hover:bg-tsa-greenDark lg:inline-flex">
                    <svg viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4"><path fill-rule="evenodd" d="M3 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L9.414 9H17a1 1 0 110 2H9.414l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    Login
                </a>

                <button type="button" @click="mobileOpen = !mobileOpen"
                        class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-700 transition-colors duration-150 hover:bg-slate-50 lg:hidden"
                        aria-label="Toggle navigation">
                    <svg x-show="!mobileOpen" viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg x-show="mobileOpen"  viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 6l12 12M18 6l-12 12"/></svg>
                </button>
            </div>
        </div>

        {{-- Mobile drawer --}}
        <div x-show="mobileOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="border-t border-slate-100 bg-white px-4 pb-5 pt-3 lg:hidden">
            <nav class="space-y-0.5">
                <a href="{{ route('landing') }}"         class="block rounded-lg px-3 py-2.5 text-sm font-bold text-slate-800 transition-colors hover:bg-tsa-soft hover:text-tsa-greenDark">Home</a>
                <a href="{{ route('landing.about') }}"   class="block rounded-lg px-3 py-2.5 text-sm font-bold text-slate-800 transition-colors hover:bg-tsa-soft hover:text-tsa-greenDark">About</a>
                <a href="{{ route('landing.catalog') }}" class="block rounded-lg px-3 py-2.5 text-sm font-bold text-slate-800 transition-colors hover:bg-tsa-soft hover:text-tsa-greenDark">Catalog</a>

                <div class="pb-0.5 pt-1">
                    <p class="px-3 pb-1 text-[11px] font-semibold uppercase tracking-widest text-slate-400">Information</p>
                    <a href="{{ route('landing.information.logistic-delivery') }}"       class="block rounded-lg px-3 py-2 text-sm font-semibold text-slate-700 transition-colors hover:bg-tsa-soft hover:text-tsa-greenDark">↳ Logistic and Delivery</a>
                    <a href="{{ route('landing.information.procurement-preparation') }}"  class="block rounded-lg px-3 py-2 text-sm font-semibold text-slate-700 transition-colors hover:bg-tsa-soft hover:text-tsa-greenDark">↳ Procurement and Preparation</a>
                    <a href="{{ route('landing.information.live-export-process') }}"     class="block rounded-lg px-3 py-2 text-sm font-semibold text-slate-700 transition-colors hover:bg-tsa-soft hover:text-tsa-greenDark">↳ Live Export Process</a>
                </div>

                <a href="{{ route('landing.support') }}" class="block rounded-lg px-3 py-2.5 text-sm font-bold text-slate-800 transition-colors hover:bg-tsa-soft hover:text-tsa-greenDark">Support</a>

                {{-- Language switcher (mobile) --}}
                <div class="relative pt-2" @click.outside="mobileLangOpen = false">
                    <button type="button"
                            @click="mobileLangOpen = !mobileLangOpen"
                            class="inline-flex w-full items-center justify-between rounded-lg px-3 py-2 text-sm font-semibold text-slate-700 transition-colors duration-150 hover:bg-tsa-soft">
                        <span class="inline-flex items-center gap-2">
                            <span class="inline-flex h-4 w-4 overflow-hidden rounded-full" aria-hidden="true">
                                @if($locale === 'id')
                                    <svg viewBox="0 0 24 24" class="h-4 w-4"><rect width="24" height="12" fill="#d80027"/><rect y="12" width="24" height="12" fill="#ffffff"/></svg>
                                @else
                                    <svg viewBox="0 0 24 24" class="h-4 w-4"><rect width="24" height="24" fill="#012169"/><path d="M0 0l24 24M24 0L0 24" stroke="#fff" stroke-width="4"/><path d="M0 0l24 24M24 0L0 24" stroke="#c8102e" stroke-width="2"/><path d="M12 0v24M0 12h24" stroke="#fff" stroke-width="6"/><path d="M12 0v24M0 12h24" stroke="#c8102e" stroke-width="3"/></svg>
                                @endif
                            </span>
                            <span>{{ $locale === 'id' ? 'Indonesian' : 'English' }}</span>
                        </span>
                        <svg class="h-4 w-4 transition-transform duration-150" :class="mobileLangOpen ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/>
                        </svg>
                    </button>

                    <div x-show="mobileLangOpen"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 -translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 -translate-y-1"
                         class="mt-2 overflow-hidden rounded-lg border border-slate-200 bg-white">
                        <a href="{{ route('lang.switch', 'id') }}"
                           class="flex items-center gap-2 px-3 py-2 text-sm font-semibold transition-colors duration-150 {{ $locale === 'id' ? 'bg-tsa-soft text-tsa-greenDark' : 'text-slate-700 hover:bg-tsa-soft hover:text-tsa-greenDark' }}">
                            <span class="inline-flex h-4 w-4 overflow-hidden rounded-full" aria-hidden="true">
                                <svg viewBox="0 0 24 24" class="h-4 w-4"><rect width="24" height="12" fill="#d80027"/><rect y="12" width="24" height="12" fill="#ffffff"/></svg>
                            </span>
                            Indonesian
                        </a>
                        <a href="{{ route('lang.switch', 'en') }}"
                           class="flex items-center gap-2 border-t border-slate-100 px-3 py-2 text-sm font-semibold transition-colors duration-150 {{ $locale === 'en' ? 'bg-tsa-soft text-tsa-greenDark' : 'text-slate-700 hover:bg-tsa-soft hover:text-tsa-greenDark' }}">
                            <span class="inline-flex h-4 w-4 overflow-hidden rounded-full" aria-hidden="true">
                                <svg viewBox="0 0 24 24" class="h-4 w-4"><rect width="24" height="24" fill="#012169"/><path d="M0 0l24 24M24 0L0 24" stroke="#fff" stroke-width="4"/><path d="M0 0l24 24M24 0L0 24" stroke="#c8102e" stroke-width="2"/><path d="M12 0v24M0 12h24" stroke="#fff" stroke-width="6"/><path d="M12 0v24M0 12h24" stroke="#c8102e" stroke-width="3"/></svg>
                            </span>
                            English
                        </a>
                    </div>
                </div>

                <div class="pt-2">
                    <a href="{{ route('login') }}" class="flex w-full items-center justify-center gap-2 rounded-lg bg-tsa-green py-2.5 text-sm font-bold text-white transition-colors hover:bg-tsa-greenDark">
                        <svg viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4"><path fill-rule="evenodd" d="M3 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L9.414 9H17a1 1 0 110 2H9.414l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        Login
                    </a>
                </div>
            </nav>
        </div>
    </header>

    <main class="pt-16">
        <div id="landing-content" class="landing-content">
            @yield('content')
        </div>
    </main>

    {{-- ─── Footer ──────────────────────────────────────────────── --}}
    <footer class="bg-tsa-greenDark text-white">
        {{-- Top accent line --}}
        <div class="h-1 w-full bg-gradient-to-r from-tsa-green/60 via-white/20 to-tsa-green/60"></div>

        <div class="mx-auto w-[94%] max-w-[1240px] py-12">

            {{-- Brand + socials row --}}
            <div class="mb-10 flex flex-col items-start gap-5 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logo header.png') }}" alt="TSA logo"
                         class="h-12 w-12 rounded-full object-cover ring-2 ring-white/20">
                    <div>
                        <p class="text-base font-extrabold leading-tight text-white">Tunas Sejahtera Adhi Perkasa</p>
                        <p class="mt-0.5 text-xs text-white/55">Live Export Specialist · Bandar Lampung, Indonesia</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <a href="#" class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-white/10 transition-colors hover:bg-white/20" aria-label="Facebook">
                        <svg viewBox="0 0 24 24" class="h-4 w-4" fill="currentColor"><path d="M13.5 21v-8h2.7l.4-3h-3.1V8.2c0-.9.3-1.5 1.6-1.5h1.7V4c-.3 0-1.4-.1-2.6-.1-2.6 0-4.4 1.6-4.4 4.5V10H7v3h2.7v8h3.8z"/></svg>
                    </a>
                    <a href="#" class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-white/10 transition-colors hover:bg-white/20" aria-label="Instagram">
                        <svg viewBox="0 0 24 24" class="h-4 w-4" fill="currentColor"><path d="M12 7a5 5 0 100 10 5 5 0 000-10zm0 8.3A3.3 3.3 0 1112 8.7a3.3 3.3 0 010 6.6zm6.3-8.5a1.2 1.2 0 11-2.4 0 1.2 1.2 0 012.4 0zM12 2.9c2.9 0 3.3 0 4.5.1 1 .1 1.6.2 2 .4.5.2.9.4 1.3.8.4.4.6.8.8 1.3.2.4.3 1 .4 2 .1 1.2.1 1.6.1 4.5s0 3.3-.1 4.5c-.1 1-.2 1.6-.4 2a3.8 3.8 0 01-2.1 2.1c-.4.2-1 .3-2 .4-1.2.1-1.6.1-4.5.1s-3.3 0-4.5-.1c-1-.1-1.6-.2-2-.4a3.8 3.8 0 01-2.1-2.1c-.2-.4-.3-1-.4-2C2.9 15.3 2.9 14.9 2.9 12s0-3.3.1-4.5c.1-1 .2-1.6.4-2 .2-.5.4-.9.8-1.3.4-.4.8-.6 1.3-.8.4-.2 1-.3 2-.4 1.2-.1 1.6-.1 4.5-.1zm0-1.7c-2.9 0-3.3 0-4.6.1-1.3.1-2.2.3-3 .6-.8.3-1.5.7-2.1 1.3A5.5 5.5 0 001.2 5.3c-.3.8-.5 1.7-.6 3C.5 9.6.5 10 .5 12c0 2 .1 2.4.1 3.7.1 1.3.3 2.2.6 3 .3.8.7 1.5 1.3 2.1.6.6 1.3 1 2.1 1.3.8.3 1.7.5 3 .6 1.3.1 1.7.1 3.7.1s2.4-.1 3.7-.1c1.3-.1 2.2-.3 3-.6.8-.3 1.5-.7 2.1-1.3.6-.6 1-1.3 1.3-2.1.3-.8.5-1.7.6-3 .1-1.3.1-1.7.1-3.7s-.1-2.4-.1-3.7c-.1-1.3-.3-2.2-.6-3-.3-.8-.7-1.5-1.3-2.1-.6-.6-1.3-1-2.1-1.3-.8-.3-1.7-.5-3-.6-1.3-.1-1.7-.1-3.7-.1z"/></svg>
                    </a>
                    <a href="#" class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-white/10 transition-colors hover:bg-white/20" aria-label="YouTube">
                        <svg viewBox="0 0 24 24" class="h-4 w-4" fill="currentColor"><path d="M23 7.5a3 3 0 00-2.1-2.1C19 5 12 5 12 5s-7 0-8.9.4A3 3 0 001 7.5 31.2 31.2 0 001 12a31.2 31.2 0 00.1 4.5 3 3 0 002.1 2.1C5 19 12 19 12 19s7 0 8.9-.4a3 3 0 002.1-2.1c.1-1.5.1-3 .1-4.5s0-3-.1-4.5zM9.8 15.1V8.9l5.4 3.1-5.4 3.1z"/></svg>
                    </a>
                    <a href="#" class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-white/10 transition-colors hover:bg-white/20" aria-label="WhatsApp">
                        <svg viewBox="0 0 24 24" class="h-4 w-4" fill="currentColor"><path d="M12 2a9.9 9.9 0 00-8.4 15.1L2 22l5-1.5A9.9 9.9 0 1012 2zm0 18a8.1 8.1 0 01-4.2-1.2l-.3-.2-3 .9.9-2.9-.2-.3A8.1 8.1 0 1112 20zm4.5-6.1c-.2-.1-1.3-.6-1.5-.7-.2-.1-.3-.1-.4.1-.1.2-.6.7-.7.8-.1.1-.2.1-.4 0-1.1-.5-1.8-1-2.5-2.2-.2-.2 0-.3.1-.5.1-.1.2-.2.3-.3.1-.1.1-.2.2-.3.1-.1 0-.3 0-.4 0-.1-.4-1.1-.6-1.6-.2-.4-.3-.4-.4-.4h-.4c-.1 0-.4.1-.6.3-.2.2-.8.7-.8 1.7s.8 2 1 2.3c.1.2 1.4 2.2 3.4 3.1 2 .9 2 .6 2.4.6.4 0 1.3-.5 1.4-1 .2-.5.2-1 .1-1 0-.1-.2-.2-.4-.3z"/></svg>
                    </a>
                </div>
            </div>

            {{-- 3-column grid --}}
            <div class="grid gap-8 border-t border-white/15 pt-10 md:grid-cols-3">
                <section>
                    <h2 class="mb-4 text-xs font-semibold uppercase tracking-widest text-tsa-green">Get In Touch</h2>
                    <ul class="space-y-2.5 text-sm leading-relaxed text-white/80">
                        <li class="flex items-center gap-2">
                            <svg class="h-3.5 w-3.5 shrink-0 text-tsa-green" viewBox="0 0 20 20" fill="currentColor"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/></svg>
                            +62721 8050354
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="h-3.5 w-3.5 shrink-0 text-tsa-green" viewBox="0 0 20 20" fill="currentColor"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/></svg>
                            +6282183948148
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="h-3.5 w-3.5 shrink-0 text-tsa-green" viewBox="0 0 20 20" fill="currentColor"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/></svg>
                            +6289695005000
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="h-3.5 w-3.5 shrink-0 text-tsa-green" viewBox="0 0 20 20" fill="currentColor"><path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/><path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/></svg>
                            pt.tsalampung@gmail.com
                        </li>
                    </ul>
                </section>

                <section>
                    <h2 class="mb-4 text-xs font-semibold uppercase tracking-widest text-tsa-green">Address</h2>
                    <p class="text-sm leading-relaxed text-white/80">
                        JL. Raden Imba Kusumaratu, NO: 22, RT: 005, Lk.I,<br>
                        Sukadana Ham, Tanjung Karang Barat,<br>
                        Bandar Lampung, Lampung, Indonesia.
                    </p>
                </section>

                <section>
                    <h2 class="mb-4 text-xs font-semibold uppercase tracking-widest text-tsa-green">Find Us</h2>
                    <div class="overflow-hidden rounded-xl border border-white/15">
                        <iframe
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            src="https://maps.google.com/maps?q=JL.%20Raden%20Imba%20Kusumaratu%20NO%2022%20Bandar%20Lampung&t=&z=13&ie=UTF8&iwloc=&output=embed"
                            title="Tunas Sejahtera Adhi Perkasa map"
                            class="block h-44 w-full border-0">
                        </iframe>
                    </div>
                </section>
            </div>

            {{-- Bottom bar --}}
            <div class="mt-8 flex flex-col items-center justify-between gap-2 border-t border-white/15 pt-6 text-xs text-white/50 sm:flex-row">
                <p>&copy; {{ now()->year }} Tunas Sejahtera Adhi Perkasa. All rights reserved.</p>
                <p>Live Export Specialist · Bandar Lampung</p>
            </div>
        </div>
    </footer>

    <script>
        document.documentElement.classList.add('js');

        document.addEventListener('DOMContentLoaded', () => {
            const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            const content = document.getElementById('landing-content');
            const resetContentState = () => {
                if (!content) return;
                content.classList.remove('page-leave-forward', 'page-enter-forward', 'page-enter-back');
                content.classList.add('is-ready');
                content.style.opacity = '1';
                content.style.transform = 'translateX(0)';
            };

            if (content) {
                if (reduceMotion) {
                    resetContentState();
                } else {
                    const navEntry = performance.getEntriesByType('navigation')[0];
                    const isBackForward = navEntry && navEntry.type === 'back_forward';
                    content.classList.remove('page-leave-forward', 'page-enter-forward', 'page-enter-back');
                    content.classList.add(isBackForward ? 'page-enter-back' : 'page-enter-forward');

                    requestAnimationFrame(() => {
                        content.classList.add('is-ready');
                        content.classList.remove('page-enter-forward', 'page-enter-back');
                    });
                }
            }

            window.addEventListener('pageshow', (event) => {
                if (!content) return;
                if (event.persisted) {
                    resetContentState();
                    return;
                }
                resetContentState();
            });

            window.addEventListener('pagehide', () => {
                if (!content) return;
                content.classList.remove('page-leave-forward');
            });

            document.addEventListener('visibilitychange', () => {
                if (!content) return;
                if (document.visibilityState === 'hidden') {
                    content.classList.remove('page-leave-forward');
                }
            });

            const revealItems = document.querySelectorAll('[data-reveal]');
            const lineItems = document.querySelectorAll('[data-line-reveal]');
            const wordItems = document.querySelectorAll('[data-word-stagger]');

            wordItems.forEach((item) => {
                if (item.dataset.wordPrepared === '1') return;
                const rawText = (item.textContent || '').trim();
                if (!rawText) return;

                item.dataset.wordPrepared = '1';
                item.classList.add('word-stagger');
                item.setAttribute('aria-label', rawText);

                const words = rawText.split(/\s+/);
                item.textContent = '';

                words.forEach((word, index) => {
                    const wordSpan = document.createElement('span');
                    wordSpan.className = 'word-token';
                    wordSpan.style.setProperty('--word-delay', String(index));
                    wordSpan.setAttribute('aria-hidden', 'true');
                    wordSpan.textContent = word;
                    item.appendChild(wordSpan);
                    if (index < words.length - 1) item.appendChild(document.createTextNode(' '));
                });
            });

            const activateVisible = (item) => {
                item.classList.add('is-visible');
            };

            const allObserved = [...revealItems, ...lineItems, ...wordItems];

            if (allObserved.length) {
                if (reduceMotion || typeof IntersectionObserver === 'undefined') {
                    allObserved.forEach(activateVisible);
                } else {
                    const revealObserver = new IntersectionObserver((entries, obs) => {
                        entries.forEach((entry) => {
                            if (!entry.isIntersecting) return;
                            activateVisible(entry.target);
                            obs.unobserve(entry.target);
                        });
                    }, {
                        threshold: 0.14,
                        rootMargin: '0px 0px -8% 0px'
                    });

                    allObserved.forEach((item) => revealObserver.observe(item));
                }
            }
            if (reduceMotion || !content) return;

            document.addEventListener('click', (event) => {
                const link = event.target.closest('a[href]');
                if (!link) return;

                if (event.defaultPrevented) return;
                if (event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return;
                if (link.target && link.target !== '_self') return;
                if (link.hasAttribute('download')) return;
                if (link.hasAttribute('data-no-transition')) return;

                const href = link.getAttribute('href');
                if (!href || href.startsWith('#') || href.startsWith('javascript:') || href.startsWith('mailto:') || href.startsWith('tel:')) return;

                const url = new URL(link.href, window.location.href);
                if (url.origin !== window.location.origin) return;
                if (url.href === window.location.href) return;

                event.preventDefault();
                content.classList.add('page-leave-forward');
                window.setTimeout(() => {
                    window.location.href = url.href;
                }, 220);
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
