@php
    $isEdit = isset($task) && $task;
    $inputClass = fn (string $field) => 'w-full rounded-xl border bg-white px-3.5 py-2.5 text-sm text-slate-800 shadow-sm outline-none transition placeholder:text-slate-400 focus:ring-4 dark:bg-slate-950 dark:text-white dark:placeholder:text-slate-500 ' .
        ($errors->has($field)
            ? 'border-rose-300 focus:border-rose-400 focus:ring-rose-100 dark:border-rose-800 dark:focus:ring-rose-950/60'
            : 'border-slate-200 focus:border-brand-400 focus:ring-brand-100 dark:border-slate-700 dark:focus:border-brand-600 dark:focus:ring-brand-950/60');
@endphp

<div class="grid gap-6 lg:grid-cols-3">
    <div class="space-y-6 lg:col-span-2">
        @if ($categories->isEmpty() || $penanggungJawabs->isEmpty())
            <div class="flex items-start gap-3 rounded-2xl border border-amber-200 bg-amber-50 p-4 text-sm dark:border-amber-900 dark:bg-amber-950/40">
                <x-icon name="alert-triangle" class="mt-0.5 h-5 w-5 shrink-0 text-amber-600 dark:text-amber-400" />
                <div class="text-amber-800 dark:text-amber-300">
                    <p class="font-bold">Data pendukung belum lengkap.</p>
                    <p class="mt-1 text-xs leading-relaxed">
                        @if ($categories->isEmpty())
                            Belum ada kategori.
                            <a href="{{ route('categories.create') }}" class="font-bold underline">Buat kategori</a> terlebih dahulu.
                        @endif

                        @if ($penanggungJawabs->isEmpty())
                            Belum ada pengguna aktif ber-role staff/superadmin.
                            <a href="{{ route('users.create') }}" class="font-bold underline">Tambah pengguna</a> terlebih dahulu.
                        @endif
                    </p>
                </div>
            </div>
        @endif

        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="border-b border-slate-100 px-5 py-4 dark:border-slate-800">
                <h2 class="text-base font-extrabold text-slate-900 dark:text-white">
                    Informasi tugas
                </h2>
                <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">
                    Isi detail pekerjaan yang akan dikerjakan tim.
                </p>
            </div>

            <div class="space-y-5 p-5">
                <div>
                    <label for="nama" class="mb-1.5 block text-sm font-semibold text-slate-700 dark:text-slate-300">
                        Nama tugas <span class="text-rose-500">*</span>
                    </label>

                    <input type="text"
                           id="nama"
                           name="nama"
                           value="{{ old('nama', $task->nama ?? '') }}"
                           required
                           maxlength="255"
                           placeholder="Contoh: Pembaruan sistem informasi"
                           class="{{ $inputClass('nama') }}">

                    <x-input-error :name="'nama'" />
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="category_id" class="mb-1.5 block text-sm font-semibold text-slate-700 dark:text-slate-300">
                            Kategori <span class="text-rose-500">*</span>
                        </label>

                        <select id="category_id"
                                name="category_id"
                                required
                                class="{{ $inputClass('category_id') }}">
                            <option value="">Pilih kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected((string) old('category_id', $task->category_id ?? '') === (string) $category->id)>
                                    {{ $category->nama }}
                                </option>
                            @endforeach
                        </select>

                        <x-input-error :name="'category_id'" />
                    </div>

                    <div>
                        <label for="penanggung_jawab" class="mb-1.5 block text-sm font-semibold text-slate-700 dark:text-slate-300">
                            Penanggung jawab <span class="text-rose-500">*</span>
                        </label>

                        <select id="penanggung_jawab"
                                name="penanggung_jawab"
                                required
                                class="{{ $inputClass('penanggung_jawab') }}">
                            <option value="">Pilih penanggung jawab</option>
                            @foreach ($penanggungJawabs as $penanggungJawab)
                                <option value="{{ $penanggungJawab->id }}" @selected((string) old('penanggung_jawab', $task->penanggung_jawab ?? '') === (string) $penanggungJawab->id)>
                                    {{ $penanggungJawab->nama }} — {{ ucfirst($penanggungJawab->role) }}
                                </option>
                            @endforeach
                        </select>

                        <x-input-error :name="'penanggung_jawab'" />
                    </div>
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="target_selesai" class="mb-1.5 block text-sm font-semibold text-slate-700 dark:text-slate-300">
                            Target selesai <span class="text-rose-500">*</span>
                        </label>

                        <input type="date"
                               id="target_selesai"
                               name="target_selesai"
                               value="{{ old('target_selesai', $isEdit ? $task->target_selesai->format('Y-m-d') : '') }}"
                               required
                               class="{{ $inputClass('target_selesai') }}">

                        <x-input-error :name="'target_selesai'" />
                    </div>

                    <div>
                        <label for="status" class="mb-1.5 block text-sm font-semibold text-slate-700 dark:text-slate-300">
                            Status @unless ($isEdit) <span class="text-slate-400">(opsional)</span> @endunless
                            @if ($isEdit) <span class="text-rose-500">*</span> @endif
                        </label>

                        <select id="status"
                                name="status"
                                @required($isEdit)
                                class="{{ $inputClass('status') }}">
                            <option value="belum_mulai" @selected(old('status', $task->status ?? 'belum_mulai') === 'belum_mulai')>Belum mulai</option>
                            <option value="berjalan" @selected(old('status', $task->status ?? 'belum_mulai') === 'berjalan')>Berjalan</option>
                            <option value="selesai" @selected(old('status', $task->status ?? 'belum_mulai') === 'selesai')>Selesai</option>
                            <option value="tertunda" @selected(old('status', $task->status ?? 'belum_mulai') === 'tertunda')>Tertunda</option>
                        </select>

                        <x-input-error :name="'status'" />
                    </div>
                </div>

                <div>
                    <label for="deskripsi" class="mb-1.5 block text-sm font-semibold text-slate-700 dark:text-slate-300">
                        Deskripsi <span class="text-rose-500">*</span>
                    </label>

                    <textarea id="deskripsi"
                              name="deskripsi"
                              rows="7"
                              required
                              placeholder="Jelaskan ruang lingkup, output, dan hal penting lainnya..."
                              class="{{ $inputClass('deskripsi') }} resize-y">{{ old('deskripsi', $task->deskripsi ?? '') }}</textarea>

                    <x-input-error :name="'deskripsi'" />
                </div>
            </div>

            <div class="flex flex-col-reverse gap-2 border-t border-slate-100 bg-slate-50/60 px-5 py-4 dark:border-slate-800 dark:bg-slate-800/30 sm:flex-row sm:items-center sm:justify-end">
                <a href="{{ $isEdit ? route('tasks.show', $task) : route('tasks.index') }}"
                   class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800">
                    Batal
                </a>

                <button type="submit"
                        data-loading-text="Menyimpan..."
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-brand-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-brand-700 focus:outline-none focus:ring-4 focus:ring-brand-200 dark:focus:ring-brand-900">
                    <x-icon name="save" class="h-4 w-4" />
                    {{ $isEdit ? 'Simpan perubahan' : 'Buat tugas' }}
                </button>
            </div>
        </div>
    </div>

    <aside class="space-y-6">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <h3 class="flex items-center gap-2 text-sm font-extrabold text-slate-900 dark:text-white">
                <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-brand-50 text-brand-600 dark:bg-brand-950/60 dark:text-brand-400">
                    <x-icon name="check-circle" class="h-4 w-4" />
                </span>
                Tips singkat
            </h3>

            <ul class="mt-4 space-y-3 text-xs leading-relaxed text-slate-500 dark:text-slate-400">
                <li class="flex gap-2">
                    <span class="mt-1 h-1.5 w-1.5 shrink-0 rounded-full bg-brand-400"></span>
                    Gunakan nama tugas yang spesifik dan mudah dicari.
                </li>
                <li class="flex gap-2">
                    <span class="mt-1 h-1.5 w-1.5 shrink-0 rounded-full bg-brand-400"></span>
                    Tetapkan target selesai yang realistis.
                </li>
                <li class="flex gap-2">
                    <span class="mt-1 h-1.5 w-1.5 shrink-0 rounded-full bg-brand-400"></span>
                    Tuliskan output yang diharapkan pada deskripsi.
                </li>
                <li class="flex gap-2">
                    <span class="mt-1 h-1.5 w-1.5 shrink-0 rounded-full bg-brand-400"></span>
                    Setelah dibuat, tugaskan staff dari halaman detail tugas.
                </li>
            </ul>
        </div>

        @if ($isEdit)
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h3 class="text-sm font-extrabold text-slate-900 dark:text-white">
                    Status saat ini
                </h3>

                <div class="mt-3">
                    <x-status-badge :status="$task->status" />
                </div>

                <dl class="mt-4 space-y-3 text-xs">
                    <div class="flex items-center justify-between gap-2">
                        <dt class="text-slate-400 dark:text-slate-500">Dibuat</dt>
                        <dd class="font-semibold text-slate-600 dark:text-slate-300">
                            {{ $task->created_at?->locale('id')->isoFormat('D MMM YYYY') }}
                        </dd>
                    </div>
                    <div class="flex items-center justify-between gap-2">
                        <dt class="text-slate-400 dark:text-slate-500">Target</dt>
                        <dd class="font-semibold text-slate-600 dark:text-slate-300">
                            {{ $task->target_selesai->locale('id')->isoFormat('D MMM YYYY') }}
                        </dd>
                    </div>
                </dl>

                <a href="{{ route('tasks.show', $task) }}"
                   class="mt-4 inline-flex w-full items-center justify-center gap-1.5 rounded-xl border border-slate-200 px-3 py-2 text-xs font-bold text-slate-600 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">
                    Lihat detail tugas
                    <x-icon name="chevron-right" class="h-3.5 w-3.5" />
                </a>
            </div>
        @endif
    </aside>
</div>
