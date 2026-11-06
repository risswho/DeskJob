@extends('layouts.guest')

@section('title', 'Sesi Kedaluwarsa')

@section('content')
    <div class="text-center animate-fade-in-up">
        <span class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-amber-50 text-amber-600 dark:bg-amber-950/60 dark:text-amber-400">
            <x-icon name="clock" class="h-8 w-8" />
        </span>

        <p class="mt-6 text-6xl font-extrabold tracking-tight text-amber-500">
            419
        </p>

        <h1 class="mt-3 text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">
            Sesi sudah kedaluwarsa
        </h1>

        <p class="mx-auto mt-2 max-w-sm text-sm leading-relaxed text-slate-500 dark:text-slate-400">
            Sesi kamu telah berakhir karena halaman terlalu lama terbuka. Silakan muat ulang dan coba lagi.
        </p>

        <div class="mt-7 flex flex-col items-center justify-center gap-2 sm:flex-row">
            <a href="{{ auth()->check() ? route('dashboard') : route('login') }}"
               class="inline-flex items-center gap-2 rounded-xl bg-brand-600 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-brand-700">
                <x-icon name="refresh" class="h-4 w-4" />
                Muat ulang halaman
            </a>
        </div>
    </div>
@endsection
