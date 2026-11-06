@extends('layouts.guest')

@section('title', 'Halaman Tidak Ditemukan')

@section('content')
    <div class="text-center animate-fade-in-up">
        <span class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-brand-50 text-brand-600 dark:bg-brand-950/60 dark:text-brand-400">
            <x-icon name="search" class="h-8 w-8" />
        </span>

        <p class="mt-6 text-6xl font-extrabold tracking-tight text-brand-500">
            404
        </p>

        <h1 class="mt-3 text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">
            Halaman tidak ditemukan
        </h1>

        <p class="mx-auto mt-2 max-w-sm text-sm leading-relaxed text-slate-500 dark:text-slate-400">
            Halaman yang kamu cari tidak tersedia atau sudah dipindahkan. Periksa kembali alamatnya.
        </p>

        <div class="mt-7 flex flex-col items-center justify-center gap-2 sm:flex-row">
            <a href="{{ auth()->check() ? route('dashboard') : route('login') }}"
               class="inline-flex items-center gap-2 rounded-xl bg-brand-600 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-brand-700">
                <x-icon name="arrow-left" class="h-4 w-4" />
                {{ auth()->check() ? 'Kembali ke dashboard' : 'Kembali ke login' }}
            </a>

            @auth
                <a href="{{ route('tasks.index') }}"
                   class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800">
                    Lihat tugas
                </a>
            @endauth
        </div>
    </div>
@endsection
