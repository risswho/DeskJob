@extends('layouts.app')

@section('title', 'Buat Kategori')

@section('content')
    <div class="space-y-6">
        <div>
            <nav class="mb-3 flex items-center gap-1.5 text-xs font-semibold text-slate-400 dark:text-slate-500" aria-label="Breadcrumb">
                <a href="{{ route('dashboard') }}" class="transition hover:text-brand-600 dark:hover:text-brand-400">Dashboard</a>
                <x-icon name="chevron-right" class="h-3 w-3" />
                <a href="{{ route('categories.index') }}" class="transition hover:text-brand-600 dark:hover:text-brand-400">Kategori</a>
                <x-icon name="chevron-right" class="h-3 w-3" />
                <span class="text-slate-600 dark:text-slate-300">Buat baru</span>
            </nav>

            <x-page-header title="Buat kategori baru" subtitle="Tambahkan kelompok pekerjaan yang akan digunakan pada tugas." />
        </div>

        <form method="POST" action="{{ route('categories.store') }}" data-submit-once>
            @csrf
            @include('categories._form', ['category' => null])
        </form>
    </div>
@endsection
