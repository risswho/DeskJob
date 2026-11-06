@props(['role'])

@php
    $badges = [
        'superadmin' => [
            'label' => 'Superadmin',
            'class' => 'bg-brand-50 text-brand-700 ring-brand-200 dark:bg-brand-950/60 dark:text-brand-300 dark:ring-brand-900',
        ],
        'staff' => [
            'label' => 'Staff',
            'class' => 'bg-sky-50 text-sky-700 ring-sky-200 dark:bg-sky-950/60 dark:text-sky-300 dark:ring-sky-900',
        ],
        'viewer' => [
            'label' => 'Viewer',
            'class' => 'bg-slate-100 text-slate-700 ring-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:ring-slate-700',
        ],
    ];

    $badge = $badges[$role] ?? [
        'label' => ucfirst((string) $role),
        'class' => 'bg-slate-100 text-slate-700 ring-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:ring-slate-700',
    ];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset ' . $badge['class']]) }}>
    {{ $badge['label'] }}
</span>
