{{-- resources/views/vendor/pagination/tailwind.blade.php --}}
{{-- Custom Minimalist Pagination --}}

@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">
        
        {{-- Mobile View --}}
        <div class="flex justify-between flex-1 sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-400 bg-gray-100 dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 cursor-default leading-5 rounded-lg">
                    <span class="material-symbols-outlined text-lg">chevron_left</span>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-zinc-300 bg-white dark:bg-zinc-900 border border-gray-300 dark:border-zinc-700 leading-5 rounded-lg hover:bg-gray-50 dark:hover:bg-zinc-800 transition-colors">
                    <span class="material-symbols-outlined text-lg">chevron_left</span>
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 dark:text-zinc-300 bg-white dark:bg-zinc-900 border border-gray-300 dark:border-zinc-700 leading-5 rounded-lg hover:bg-gray-50 dark:hover:bg-zinc-800 transition-colors">
                    <span class="material-symbols-outlined text-lg">chevron_right</span>
                </a>
            @else
                <span class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-400 bg-gray-100 dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 cursor-default leading-5 rounded-lg">
                    <span class="material-symbols-outlined text-lg">chevron_right</span>
                </span>
            @endif
        </div>

        {{-- Desktop View --}}
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-600 dark:text-zinc-400 leading-5">
                    Menampilkan
                    <span class="font-semibold text-gray-900 dark:text-white">{{ $paginator->firstItem() }}</span>
                    -
                    <span class="font-semibold text-gray-900 dark:text-white">{{ $paginator->lastItem() }}</span>
                    dari
                    <span class="font-semibold text-gray-900 dark:text-white">{{ $paginator->total() }}</span>
                    produk
                </p>
            </div>

            <div>
                <span class="relative z-0 inline-flex rounded-lg shadow-sm gap-1">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="Previous">
                            <span class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-400 bg-gray-100 dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 cursor-default rounded-lg" aria-hidden="true">
                                <span class="material-symbols-outlined text-lg">chevron_left</span>
                            </span>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 dark:text-zinc-300 bg-white dark:bg-zinc-900 border border-gray-300 dark:border-zinc-700 rounded-lg hover:bg-gray-50 dark:hover:bg-zinc-800 transition-colors" aria-label="Previous">
                            <span class="material-symbols-outlined text-lg">chevron_left</span>
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span aria-disabled="true">
                                <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 dark:text-zinc-400 bg-white dark:bg-zinc-900 border border-gray-300 dark:border-zinc-700 cursor-default rounded-lg">{{ $element }}</span>
                            </span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page">
                                        <span class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-soft-green to-primary border border-soft-green rounded-lg cursor-default shadow-sm">{{ $page }}</span>
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-zinc-300 bg-white dark:bg-zinc-900 border border-gray-300 dark:border-zinc-700 rounded-lg hover:bg-gray-50 dark:hover:bg-zinc-800 transition-colors" aria-label="Go to page {{ $page }}">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 dark:text-zinc-300 bg-white dark:bg-zinc-900 border border-gray-300 dark:border-zinc-700 rounded-lg hover:bg-gray-50 dark:hover:bg-zinc-800 transition-colors" aria-label="Next">
                            <span class="material-symbols-outlined text-lg">chevron_right</span>
                        </a>
                    @else
                        <span aria-disabled="true" aria-label="Next">
                            <span class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-400 bg-gray-100 dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 cursor-default rounded-lg" aria-hidden="true">
                                <span class="material-symbols-oriented text-lg">chevron_right</span>
                            </span>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif