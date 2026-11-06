@props(['name' => '', 'class' => 'h-9 w-9 text-xs'])

@php
    $words = \Illuminate\Support\Str::of((string) $name)
        ->trim()
        ->explode(' ')
        ->filter()
        ->take(2);

    $initials = $words->map(fn ($word) => mb_strtoupper(mb_substr($word, 0, 1)))->implode('');

    if ($initials === '') {
        $initials = '?';
    }

    $palettes = [
        'bg-brand-100 text-brand-800 dark:bg-brand-900/60 dark:text-brand-200',
        'bg-sky-100 text-sky-800 dark:bg-sky-900/60 dark:text-sky-200',
        'bg-amber-100 text-amber-800 dark:bg-amber-900/60 dark:text-amber-200',
        'bg-violet-100 text-violet-800 dark:bg-violet-900/60 dark:text-violet-200',
        'bg-rose-100 text-rose-800 dark:bg-rose-900/60 dark:text-rose-200',
        'bg-teal-100 text-teal-800 dark:bg-teal-900/60 dark:text-teal-200',
    ];

    $palette = $palettes[abs(crc32((string) $name)) % count($palettes)];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex shrink-0 items-center justify-center rounded-full font-bold ' . $class . ' ' . $palette]) }}
      title="{{ $name }}">
    {{ $initials }}
</span>
