@props([
    'title' => 'Belum ada data',
    'message' => null,
    'icon' => 'inbox',
])

<div {{ $attributes->merge(['class' => 'flex flex-col items-center justify-center rounded-2xl border border-dashed border-slate-300 bg-white/60 px-6 py-14 text-center dark:border-slate-700 dark:bg-slate-900/40']) }}>
    <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-brand-50 text-brand-600 dark:bg-brand-950/60 dark:text-brand-400">
        <x-icon :name="$icon" class="h-7 w-7" />
    </span>

    <h3 class="mt-4 text-base font-bold text-slate-800 dark:text-slate-100">
        {{ $title }}
    </h3>

    @if ($message)
        <p class="mt-1 max-w-md text-sm text-slate-500 dark:text-slate-400">
            {{ $message }}
        </p>
    @endif

    @if (isset($slot) && ! $slot->isEmpty())
        <div class="mt-5">
            {{ $slot }}
        </div>
    @endif
</div>
