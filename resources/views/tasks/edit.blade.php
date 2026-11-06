@extends('layouts.app')

@section('title', 'Edit Tugas')

@section('content')
    <div class="space-y-6">
        <div>
            <nav class="mb-3 flex items-center gap-1.5 text-xs font-semibold text-slate-400 dark:text-slate-500" aria-label="Breadcrumb">
                <a href="{{ route('dashboard') }}" class="transition hover:text-brand-600 dark:hover:text-brand-400">Dashboard</a>
                <x-icon name="chevron-right" class="h-3 w-3" />
                <a href="{{ route('tasks.index') }}" class="transition hover:text-brand-600 dark:hover:text-brand-400">Tugas</a>
                <x-icon name="chevron-right" class="h-3 w-3" />
                <a href="{{ route('tasks.show', $task) }}" class="max-w-[10rem] truncate transition hover:text-brand-600 dark:hover:text-brand-400">
                    {{ $task->nama }}
                </a>
                <x-icon name="chevron-right" class="h-3 w-3" />
                <span class="text-slate-600 dark:text-slate-300">Edit</span>
            </nav>

            <x-page-header title="Edit tugas" :subtitle="$task->nama" />
        </div>

        <form method="POST" action="{{ route('tasks.update', $task) }}" data-submit-once>
            @csrf
            @method('PUT')
            @include('tasks._form')
        </form>
    </div>
@endsection
