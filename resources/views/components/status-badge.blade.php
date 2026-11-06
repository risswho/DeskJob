@props(['status'])

@php
    $badges = [
        'belum_mulai' => [
            'label' => 'Belum mulai',
            'class' => 'bg-slate-100 text-slate-700 ring-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:ring-slate-700',
            'dot' => 'bg-slate-400',
        ],
        'berjalan' => [
            'label' => 'Berjalan',
            'class' => 'bg-sky-50 text-sky-700 ring-sky-200 dark:bg-sky-950/60 dark:text-sky-300 dark:ring-sky-900',
            'dot' => 'bg-sky-500',
        ],
        'selesai' => [
            'label' => 'Selesai',
            'class' => 'bg-brand-50 text-brand-700 ring-brand-200 dark:bg-brand-950/60 dark:text-brand-300 dark:ring-brand-900',
            'dot' => 'bg-brand-500',
        ],
        'tertunda' => [
            'label' => 'Tertunda',
            'class' => 'bg-amber-50 text-amber-700 ring-amber-200 dark:bg-amber-950/60 dark:text-amber-300 dark:ring-amber-900',
            'dot' => 'bg-amber-500',
        ],
    ];

    $badge = $badges[$status] ?? [
        'label' => ucfirst(str_replace('_', ' ', (string) $status)),
        'class' => 'bg-slate-100 text-slate-700 ring-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:ring-slate-700',
        'dot' => 'bg-slate-400',
    ];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset ' . $badge['class']]) }}>
    <span class="h-1.5 w-1.5 rounded-full {{ $badge['dot'] }}"></span>
    {{ $badge['label'] }}
</span>
