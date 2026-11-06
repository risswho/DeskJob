<div id="confirm-modal"
     class="fixed inset-0 z-[80] hidden items-center justify-center p-4"
     role="dialog"
     aria-modal="true"
     aria-labelledby="confirm-modal-title">
    <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm" data-confirm-cancel></div>

    <div class="relative w-full max-w-sm rounded-2xl border border-slate-200 bg-white p-6 shadow-2xl animate-fade-in-up dark:border-slate-700 dark:bg-slate-900">
        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-rose-50 text-rose-600 dark:bg-rose-950/60 dark:text-rose-400">
            <x-icon name="alert-triangle" class="h-6 w-6" />
        </span>

        <h2 id="confirm-modal-title" class="mt-4 text-lg font-extrabold text-slate-900 dark:text-white">
            Konfirmasi hapus
        </h2>

        <p data-confirm-message class="mt-1 text-sm leading-relaxed text-slate-500 dark:text-slate-400">
            Data yang dihapus tidak dapat dikembalikan.
        </p>

        <div class="mt-6 flex items-center justify-end gap-2">
            <button type="button"
                    data-confirm-cancel
                    class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700 dark:focus:ring-offset-slate-900">
                Batal
            </button>

            <button type="button"
                    data-confirm-accept
                    class="rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900">
                Ya, hapus
            </button>
        </div>
    </div>
</div>
