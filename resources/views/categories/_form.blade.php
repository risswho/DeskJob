@php
    $isEdit = isset($category) && $category;
    $inputClass = fn (string $field) => 'w-full rounded-xl border bg-white px-3.5 py-2.5 text-sm text-slate-800 shadow-sm outline-none transition placeholder:text-slate-400 focus:ring-4 dark:bg-slate-950 dark:text-white dark:placeholder:text-slate-500 ' .
        ($errors->has($field)
            ? 'border-rose-300 focus:border-rose-400 focus:ring-rose-100 dark:border-rose-800 dark:focus:ring-rose-950/60'
            : 'border-slate-200 focus:border-brand-400 focus:ring-brand-100 dark:border-slate-700 dark:focus:border-brand-600 dark:focus:ring-brand-950/60');
@endphp

<div class="grid gap-6 lg:grid-cols-3">
    <div class="lg:col-span-2">
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="border-b border-slate-100 px-5 py-4 dark:border-slate-800">
                <h2 class="text-base font-extrabold text-slate-900 dark:text-white">
                    Informasi kategori
                </h2>
                <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">
                    Nama kategori harus unik agar tidak membingungkan saat memilih tugas.
                </p>
            </div>

            <div class="space-y-5 p-5">
                <div>
                    <label for="nama" class="mb-1.5 block text-sm font-semibold text-slate-700 dark:text-slate-300">
                        Nama kategori <span class="text-rose-500">*</span>
                    </label>

                    <input type="text"
                           id="nama"
                           name="nama"
                           value="{{ old('nama', $category->nama ?? '') }}"
                           required
                           maxlength="100"
                           placeholder="Contoh: Pengembangan Sistem"
                           class="{{ $inputClass('nama') }}">

                    <x-input-error :name="'nama'" />
                </div>

                <div>
                    <label for="deskripsi" class="mb-1.5 block text-sm font-semibold text-slate-700 dark:text-slate-300">
                        Deskripsi <span class="font-medium text-slate-400">(opsional)</span>
                    </label>

                    <textarea id="deskripsi"
                              name="deskripsi"
                              rows="5"
                              placeholder="Jelaskan jenis pekerjaan dalam kategori ini..."
                              class="{{ $inputClass('deskripsi') }} resize-y">{{ old('deskripsi', $category->deskripsi ?? '') }}</textarea>

                    <x-input-error :name="'deskripsi'" />
                </div>
            </div>

            <div class="flex flex-col-reverse gap-2 border-t border-slate-100 bg-slate-50/60 px-5 py-4 dark:border-slate-800 dark:bg-slate-800/30 sm:flex-row sm:items-center sm:justify-end">
                <a href="{{ route('categories.index') }}"
                   class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800">
                    Batal
                </a>

                <button type="submit"
                        data-loading-text="Menyimpan..."
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-brand-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-brand-700 focus:outline-none focus:ring-4 focus:ring-brand-200 dark:focus:ring-brand-900">
                    <x-icon name="save" class="h-4 w-4" />
                    {{ $isEdit ? 'Simpan perubahan' : 'Buat kategori' }}
                </button>
            </div>
        </div>
    </div>

    <aside>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <h3 class="flex items-center gap-2 text-sm font-extrabold text-slate-900 dark:text-white">
                <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-brand-50 text-brand-600 dark:bg-brand-950/60 dark:text-brand-400">
                    <x-icon name="category" class="h-4 w-4" />
                </span>
                Catatan
            </h3>

            <ul class="mt-4 space-y-3 text-xs leading-relaxed text-slate-500 dark:text-slate-400">
                <li class="flex gap-2">
                    <span class="mt-1 h-1.5 w-1.5 shrink-0 rounded-full bg-brand-400"></span>
                    Kategori yang masih digunakan tugas tidak dapat dihapus.
                </li>
                <li class="flex gap-2">
                    <span class="mt-1 h-1.5 w-1.5 shrink-0 rounded-full bg-brand-400"></span>
                    Gunakan nama singkat tetapi tetap jelas.
                </li>
                <li class="flex gap-2">
                    <span class="mt-1 h-1.5 w-1.5 shrink-0 rounded-full bg-brand-400"></span>
                    Deskripsi membantu tim memahami cakupan kategori.
                </li>
            </ul>
        </div>
    </aside>
</div>
