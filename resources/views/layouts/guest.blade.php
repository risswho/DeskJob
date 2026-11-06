<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    @include('partials.head')
</head>
<body class="min-h-full bg-slate-50 font-sans text-slate-800 antialiased dark:bg-slate-950 dark:text-slate-100">
    <div class="relative flex min-h-screen items-center justify-center overflow-hidden px-4 py-10 sm:px-6">
        <div class="pointer-events-none absolute -left-24 -top-24 h-80 w-80 rounded-full bg-brand-200/50 blur-3xl dark:bg-brand-900/30"></div>
        <div class="pointer-events-none absolute -bottom-24 -right-24 h-80 w-80 rounded-full bg-teal-200/40 blur-3xl dark:bg-teal-900/20"></div>

        <button type="button"
                data-theme-toggle
                class="absolute right-4 top-4 z-20 rounded-xl border border-slate-200 bg-white/90 p-2.5 text-slate-500 shadow-sm backdrop-blur transition hover:border-brand-300 hover:text-brand-600 dark:border-slate-700 dark:bg-slate-900/90 dark:text-slate-300 dark:hover:border-brand-800 dark:hover:text-brand-400"
                aria-label="Ganti tema">
            <x-icon name="moon" class="h-5 w-5 dark:hidden" />
            <x-icon name="sun" class="hidden h-5 w-5 dark:block" />
        </button>

        <div class="relative w-full max-w-5xl overflow-hidden rounded-3xl border border-white/60 bg-white shadow-2xl shadow-brand-900/10 dark:border-slate-800 dark:bg-slate-900 lg:grid lg:grid-cols-[1.05fr_1fr]">
            <aside class="relative hidden overflow-hidden bg-gradient-to-br from-brand-600 via-emerald-700 to-teal-900 p-10 text-white lg:flex lg:flex-col lg:justify-between">
                <div class="pointer-events-none absolute -right-16 top-16 h-56 w-56 rounded-full border border-white/10"></div>
                <div class="pointer-events-none absolute -bottom-20 -left-10 h-64 w-64 rounded-full bg-white/5"></div>

                <div class="relative">
                    <div class="flex items-center gap-3">
                        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/15 ring-1 ring-white/20 backdrop-blur">
                            <x-icon name="check" class="h-7 w-7" />
                        </span>
                        <div>
                            <p class="text-xl font-extrabold tracking-tight">JobDesk</p>
                            <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-brand-100/80">
                                Manajemen Tugas
                            </p>
                        </div>
                    </div>

                    <h1 class="mt-14 max-w-sm text-3xl font-extrabold leading-tight tracking-tight">
                        Kelola pekerjaan tim dalam satu tempat.
                    </h1>
                    <p class="mt-3 max-w-sm text-sm leading-relaxed text-brand-50/80">
                        Pantau tugas, penugasan staff, progres, kendala, dan lampiran tanpa berpindah-pindah aplikasi.
                    </p>

                    <ul class="mt-8 space-y-3 text-sm text-brand-50/90">
                        <li class="flex items-center gap-3">
                            <span class="flex h-6 w-6 items-center justify-center rounded-full bg-white/15">
                                <x-icon name="check" class="h-3.5 w-3.5" />
                            </span>
                            Dashboard statistik dan deadline
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="flex h-6 w-6 items-center justify-center rounded-full bg-white/15">
                                <x-icon name="check" class="h-3.5 w-3.5" />
                            </span>
                            Riwayat progress dengan lampiran
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="flex h-6 w-6 items-center justify-center rounded-full bg-white/15">
                                <x-icon name="check" class="h-3.5 w-3.5" />
                            </span>
                            Hak akses superadmin, staff, dan viewer
                        </li>
                    </ul>
                </div>

                <p class="relative text-xs text-brand-100/60">
                    &copy; {{ now()->year }} JobDesk. Semua data pekerjaan tersimpan rapi.
                </p>
            </aside>

            <section class="relative p-6 sm:p-10">
                <div class="mb-8 flex items-center gap-3 lg:hidden">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-brand-500 to-teal-600 text-white shadow-soft">
                        <x-icon name="check" class="h-5 w-5" />
                    </span>
                    <div>
                        <p class="text-base font-extrabold text-slate-900 dark:text-white">JobDesk</p>
                        <p class="text-[10px] font-semibold uppercase tracking-wider text-brand-600 dark:text-brand-400">
                            Manajemen Tugas
                        </p>
                    </div>
                </div>

                @yield('content')

                <p class="mt-8 border-t border-slate-100 pt-5 text-center text-xs text-slate-400 dark:border-slate-800 dark:text-slate-500">
                    Butuh bantuan? Hubungi administrator sistem.
                </p>
            </section>
        </div>
    </div>

    @include('partials.loader')

    <script src="{{ asset('js/jobdesk.js') }}" defer></script>
    @stack('scripts')
</body>
</html>
