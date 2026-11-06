@if ($paginator->hasPages())
    <div class="mt-6 flex flex-col gap-4 border-t border-slate-200 pt-5 dark:border-slate-800 sm:flex-row sm:items-center sm:justify-between">
        <p class="text-sm text-slate-500 dark:text-slate-400">
            Menampilkan
            <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $paginator->firstItem() }}</span>
            –
            <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $paginator->lastItem() }}</span>
            dari
            <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $paginator->total() }}</span>
            data
        </p>

        <nav class="flex items-center gap-1.5" aria-label="Navigasi halaman">
            @if ($paginator->onFirstPage())
                <span class="flex h-9 w-9 cursor-not-allowed items-center justify-center rounded-xl border border-slate-200 text-slate-300 dark:border-slate-800 dark:text-slate-600">
                    <x-icon name="chevron-right" class="h-4 w-4 rotate-180" />
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                   rel="prev"
                   class="flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 transition hover:border-brand-300 hover:bg-brand-50 hover:text-brand-700 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300 dark:hover:border-brand-800 dark:hover:bg-brand-950/50 dark:hover:text-brand-300"
                   aria-label="Halaman sebelumnya">
                    <x-icon name="chevron-right" class="h-4 w-4 rotate-180" />
                </a>
            @endif

            <span class="px-2 text-sm font-semibold text-slate-700 dark:text-slate-200 sm:hidden">
                {{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}
            </span>

            <div class="hidden items-center gap-1.5 sm:flex">
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span class="flex h-9 min-w-[2.25rem] items-center justify-center px-1 text-sm text-slate-400">
                            {{ $element }}
                        </span>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span aria-current="page"
                                      class="flex h-9 min-w-[2.25rem] items-center justify-center rounded-xl bg-brand-600 px-3 text-sm font-bold text-white shadow-sm">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}"
                                   class="flex h-9 min-w-[2.25rem] items-center justify-center rounded-xl border border-slate-200 bg-white px-3 text-sm font-semibold text-slate-600 transition hover:border-brand-300 hover:bg-brand-50 hover:text-brand-700 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300 dark:hover:border-brand-800 dark:hover:bg-brand-950/50 dark:hover:text-brand-300">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </div>

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                   rel="next"
                   class="flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 transition hover:border-brand-300 hover:bg-brand-50 hover:text-brand-700 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300 dark:hover:border-brand-800 dark:hover:bg-brand-950/50 dark:hover:text-brand-300"
                   aria-label="Halaman berikutnya">
                    <x-icon name="chevron-right" class="h-4 w-4" />
                </a>
            @else
                <span class="flex h-9 w-9 cursor-not-allowed items-center justify-center rounded-xl border border-slate-200 text-slate-300 dark:border-slate-800 dark:text-slate-600">
                    <x-icon name="chevron-right" class="h-4 w-4" />
                </span>
            @endif
        </nav>
    </div>
@endif
