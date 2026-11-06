@props(['title', 'subtitle' => null])

<div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div class="min-w-0">
        <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white sm:text-3xl">
            {{ $title }}
        </h1>

        @if ($subtitle)
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                {{ $subtitle }}
            </p>
        @endif
    </div>

    @if (isset($slot) && ! $slot->isEmpty())
        <div class="flex shrink-0 flex-wrap items-center gap-2">
            {{ $slot }}
        </div>
    @endif
</div>
