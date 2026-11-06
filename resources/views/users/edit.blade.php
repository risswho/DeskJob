@extends('layouts.app')

@section('title', 'Edit Pengguna')

@section('content')
    <div class="space-y-6">
        <div>
            <nav class="mb-3 flex items-center gap-1.5 text-xs font-semibold text-slate-400 dark:text-slate-500" aria-label="Breadcrumb">
                <a href="{{ route('dashboard') }}" class="transition hover:text-brand-600 dark:hover:text-brand-400">Dashboard</a>
                <x-icon name="chevron-right" class="h-3 w-3" />
                <a href="{{ route('users.index') }}" class="transition hover:text-brand-600 dark:hover:text-brand-400">Pengguna</a>
                <x-icon name="chevron-right" class="h-3 w-3" />
                <span class="text-slate-600 dark:text-slate-300">Edit</span>
            </nav>

            <x-page-header title="Edit pengguna" :subtitle="$user->nama" />
        </div>

        <form method="POST" action="{{ route('users.update', $user) }}" data-submit-once>
            @csrf
            @method('PUT')
            @include('users._form', ['user' => $user])
        </form>
    </div>
@endsection
