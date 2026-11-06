@extends('layouts.guest')

@section('title', 'Masuk')

@section('content')
    <div class="animate-fade-in-up">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-brand-600 dark:text-brand-400">
                Selamat datang
            </p>
            <h1 class="mt-2 text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white sm:text-3xl">
                Masuk ke JobDesk
            </h1>
            <p class="mt-2 text-sm leading-relaxed text-slate-500 dark:text-slate-400">
                Gunakan username dan password akun kantor kamu untuk melanjutkan.
            </p>
        </div>

        @if (session('success'))
            <div class="mt-6 flex items-start gap-3 rounded-2xl border border-brand-200 bg-brand-50 p-4 dark:border-brand-900 dark:bg-brand-950/50">
                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-white text-brand-600 dark:bg-slate-900 dark:text-brand-400">
                    <x-icon name="check-circle" class="h-5 w-5" />
                </span>
                <p class="pt-1 text-sm font-medium text-brand-800 dark:text-brand-300">
                    {{ session('success') }}
                </p>
            </div>
        @endif

        @if ($errors->any())
            <div class="mt-6 flex items-start gap-3 rounded-2xl border border-rose-200 bg-rose-50 p-4 dark:border-rose-900 dark:bg-rose-950/40">
                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-white text-rose-600 dark:bg-slate-900 dark:text-rose-400">
                    <x-icon name="alert-triangle" class="h-5 w-5" />
                </span>
                <div class="pt-0.5">
                    <p class="text-sm font-bold text-rose-800 dark:text-rose-300">
                        Login belum berhasil
                    </p>
                    <ul class="mt-1 space-y-1 text-xs text-rose-700 dark:text-rose-400">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('login.store') }}" class="mt-7 space-y-5" data-submit-once>
            @csrf

            <div>
                <label for="username" class="mb-1.5 block text-sm font-semibold text-slate-700 dark:text-slate-300">
                    Username
                </label>

                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                        <x-icon name="at" class="h-5 w-5" />
                    </span>

                    <input type="text"
                           id="username"
                           name="username"
                           value="{{ old('username') }}"
                           required
                           autofocus
                           autocomplete="username"
                           placeholder="Masukkan username"
                           @class([
                               'w-full rounded-xl border bg-white py-3 pl-11 pr-4 text-sm text-slate-800 shadow-sm outline-none transition placeholder:text-slate-400 focus:ring-4 dark:bg-slate-950 dark:text-white dark:placeholder:text-slate-500',
                               'border-rose-300 focus:border-rose-400 focus:ring-rose-100 dark:border-rose-800 dark:focus:ring-rose-950/60' => $errors->has('username'),
                               'border-slate-200 focus:border-brand-400 focus:ring-brand-100 dark:border-slate-700 dark:focus:border-brand-600 dark:focus:ring-brand-950/60' => ! $errors->has('username'),
                           ])>
                </div>

                <x-input-error :name="'username'" />
            </div>

            <div>
                <label for="password" class="mb-1.5 block text-sm font-semibold text-slate-700 dark:text-slate-300">
                    Password
                </label>

                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                        <x-icon name="lock" class="h-5 w-5" />
                    </span>

                    <input type="password"
                           id="password"
                           name="password"
                           required
                           autocomplete="current-password"
                           placeholder="Masukkan password"
                           @class([
                               'w-full rounded-xl border bg-white py-3 pl-11 pr-12 text-sm text-slate-800 shadow-sm outline-none transition placeholder:text-slate-400 focus:ring-4 dark:bg-slate-950 dark:text-white dark:placeholder:text-slate-500',
                               'border-rose-300 focus:border-rose-400 focus:ring-rose-100 dark:border-rose-800 dark:focus:ring-rose-950/60' => $errors->has('password'),
                               'border-slate-200 focus:border-brand-400 focus:ring-brand-100 dark:border-slate-700 dark:focus:border-brand-600 dark:focus:ring-brand-950/60' => ! $errors->has('password'),
                           ])>

                    <button type="button"
                            data-password-toggle
                            data-target="password"
                            class="absolute inset-y-0 right-0 flex items-center px-3.5 text-slate-400 transition hover:text-slate-600 dark:hover:text-slate-200"
                            aria-label="Tampilkan password">
                        <span data-eye-open>
                            <x-icon name="eye" class="h-5 w-5" />
                        </span>
                        <span data-eye-closed class="hidden">
                            <x-icon name="eye-off" class="h-5 w-5" />
                        </span>
                    </button>
                </div>

                <x-input-error :name="'password'" />
            </div>

            <label for="remember" class="flex cursor-pointer items-center gap-2.5 text-sm text-slate-600 dark:text-slate-400">
                <input type="checkbox"
                       id="remember"
                       name="remember"
                       value="1"
                       @checked(old('remember'))
                       class="h-4 w-4 rounded border-slate-300 text-brand-600 focus:ring-brand-500 dark:border-slate-600 dark:bg-slate-900">
                Ingat saya di perangkat ini
            </label>

            <button type="submit"
                    data-loading-text="Memproses..."
                    class="flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-brand-600 to-teal-600 px-4 py-3 text-sm font-bold text-white shadow-soft transition hover:from-brand-700 hover:to-teal-700 focus:outline-none focus:ring-4 focus:ring-brand-200 dark:focus:ring-brand-900">
                <x-icon name="logout" class="h-4 w-4 rotate-180" />
                Masuk
            </button>
        </form>

        <p class="mt-8 text-center text-xs text-slate-400 dark:text-slate-500">
            Dengan masuk, kamu menyetujui penggunaan data untuk keperluan pekerjaan kantor.
        </p>
    </div>
@endsection
