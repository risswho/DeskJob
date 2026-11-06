@if (session('success') || (isset($errors) && $errors->any()))
    <div class="pointer-events-none fixed inset-x-0 top-20 z-[70] flex flex-col items-center gap-3 px-4 sm:items-end sm:px-6">
        @if (session('success'))
            <div data-flash
                 class="pointer-events-auto flex w-full max-w-sm items-start gap-3 rounded-2xl border border-brand-200 bg-white p-4 shadow-soft transition-all duration-300 animate-toast-in dark:border-brand-900 dark:bg-slate-900"
                 role="status">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-brand-50 text-brand-600 dark:bg-brand-950/60 dark:text-brand-400">
                    <x-icon name="check-circle" class="h-5 w-5" />
                </span>

                <p class="flex-1 pt-1.5 text-sm font-medium text-slate-700 dark:text-slate-200">
                    {{ session('success') }}
                </p>

                <button type="button"
                        data-dismiss-flash
                        class="rounded-lg p-1 text-slate-400 transition hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-slate-800 dark:hover:text-slate-200"
                        aria-label="Tutup notifikasi">
                    <x-icon name="x" class="h-4 w-4" />
                </button>
            </div>
        @endif

        @if (isset($errors) && $errors->any())
            <div data-flash
                 class="pointer-events-auto flex w-full max-w-sm items-start gap-3 rounded-2xl border border-rose-200 bg-white p-4 shadow-soft transition-all duration-300 animate-toast-in dark:border-rose-900 dark:bg-slate-900"
                 role="alert">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-rose-50 text-rose-600 dark:bg-rose-950/60 dark:text-rose-400">
                    <x-icon name="alert-triangle" class="h-5 w-5" />
                </span>

                <div class="flex-1 pt-1">
                    <p class="text-sm font-bold text-slate-800 dark:text-slate-100">
                        Terjadi kesalahan
                    </p>
                    <ul class="mt-1 max-h-40 space-y-1 overflow-y-auto text-xs text-rose-600 dark:text-rose-400">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>

                <button type="button"
                        data-dismiss-flash
                        class="rounded-lg p-1 text-slate-400 transition hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-slate-800 dark:hover:text-slate-200"
                        aria-label="Tutup notifikasi">
                    <x-icon name="x" class="h-4 w-4" />
                </button>
            </div>
        @endif
    </div>
@endif
