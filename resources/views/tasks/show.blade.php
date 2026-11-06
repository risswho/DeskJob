@extends('layouts.app')

@section('title', $task->nama)

@section('content')
    @php
        $isOverdue = $task->target_selesai->lt(today()) && $task->status !== 'selesai';
        $daysLeft = (int) today()->diffInDays($task->target_selesai->copy()->startOfDay());
        $attachmentCount = $task->progress->sum(fn ($progress) => $progress->attachments->count());
        $sortedProgress = $task->progress->sortByDesc(fn ($progress) => $progress->tanggal?->timestamp ?? 0);
        $assignedIds = $task->assignedUsers->pluck('id')->all();
        $selectedStaff = array_map('intval', old('staff', $assignedIds));
    @endphp

    <div class="space-y-6">
        <nav class="flex items-center gap-1.5 text-xs font-semibold text-slate-400 dark:text-slate-500" aria-label="Breadcrumb">
            <a href="{{ route('dashboard') }}" class="transition hover:text-brand-600 dark:hover:text-brand-400">Dashboard</a>
            <x-icon name="chevron-right" class="h-3 w-3" />
            <a href="{{ route('tasks.index') }}" class="transition hover:text-brand-600 dark:hover:text-brand-400">Tugas</a>
            <x-icon name="chevron-right" class="h-3 w-3" />
            <span class="max-w-[12rem] truncate text-slate-600 dark:text-slate-300">{{ $task->nama }}</span>
        </nav>

        <section class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:p-6">
            <div class="pointer-events-none absolute -right-10 -top-10 h-32 w-32 rounded-full bg-brand-50 dark:bg-brand-950/40"></div>

            <div class="relative">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 rounded-lg bg-slate-100 px-2.5 py-1 text-xs font-bold text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                        <x-icon name="tag" class="h-3.5 w-3.5" />
                        {{ $task->category?->nama ?? 'Tanpa kategori' }}
                    </span>

                    <x-status-badge :status="$task->status" />

                    @if ($isOverdue)
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-rose-50 px-2.5 py-1 text-xs font-bold text-rose-700 ring-1 ring-inset ring-rose-200 dark:bg-rose-950/60 dark:text-rose-300 dark:ring-rose-900">
                            <x-icon name="alert-triangle" class="h-3.5 w-3.5" />
                            Lewat target
                        </span>
                    @endif
                </div>

                <div class="mt-4 flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
                    <div class="min-w-0">
                        <h1 class="text-2xl font-extrabold leading-tight tracking-tight text-slate-900 dark:text-white sm:text-3xl">
                            {{ $task->nama }}
                        </h1>

                        <div class="mt-4 flex flex-wrap items-center gap-x-5 gap-y-2 text-xs text-slate-500 dark:text-slate-400">
                            <span class="flex items-center gap-2">
                                <x-avatar :name="$task->creator?->nama ?? 'User'" class="h-6 w-6 text-[9px]" />
                                Dibuat oleh <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $task->creator?->nama ?? '-' }}</span>
                            </span>

                            <span class="flex items-center gap-2">
                                <x-avatar :name="$task->penanggungJawab?->nama ?? 'User'" class="h-6 w-6 text-[9px]" />
                                PJ <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $task->penanggungJawab?->nama ?? '-' }}</span>
                            </span>

                            <span class="flex items-center gap-1.5">
                                <x-icon name="calendar" class="h-3.5 w-3.5" />
                                Target <span class="font-semibold {{ $isOverdue ? 'text-rose-600 dark:text-rose-400' : 'text-slate-700 dark:text-slate-200' }}">{{ $task->target_selesai->locale('id')->isoFormat('D MMMM YYYY') }}</span>
                            </span>
                        </div>
                    </div>

                    <div class="flex shrink-0 flex-wrap items-center gap-2">
                        @can('update', $task)
                            <a href="{{ route('tasks.edit', $task) }}"
                               class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-sky-300 hover:text-sky-700 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:border-sky-800 dark:hover:text-sky-400">
                                <x-icon name="edit" class="h-4 w-4" />
                                Edit
                            </a>
                        @endcan

                        @can('delete', $task)
                            <form method="POST"
                                  action="{{ route('tasks.destroy', $task) }}"
                                  data-confirm="Hapus tugas &quot;{{ $task->nama }}&quot;? Semua progress dan lampiran terkait juga akan dihapus."
                                  data-confirm-title="Hapus tugas">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="inline-flex items-center gap-2 rounded-xl border border-rose-200 bg-white px-3.5 py-2.5 text-sm font-semibold text-rose-600 transition hover:bg-rose-50 dark:border-rose-900 dark:bg-slate-900 dark:text-rose-400 dark:hover:bg-rose-950/40">
                                    <x-icon name="trash" class="h-4 w-4" />
                                    Hapus
                                </button>
                            </form>
                        @endcan
                    </div>
                </div>
            </div>
        </section>

        <div class="grid gap-6 lg:grid-cols-3">
            <div class="space-y-6 lg:col-span-2">
                <section class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="border-b border-slate-100 px-5 py-4 dark:border-slate-800">
                        <h2 class="flex items-center gap-2 text-base font-extrabold text-slate-900 dark:text-white">
                            <x-icon name="document" class="h-4 w-4 text-brand-600 dark:text-brand-400" />
                            Deskripsi pekerjaan
                        </h2>
                    </div>

                    <p class="whitespace-pre-line p-5 text-sm leading-relaxed text-slate-600 dark:text-slate-300">{{ $task->deskripsi }}</p>
                </section>

                <section class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 px-5 py-4 dark:border-slate-800">
                        <div>
                            <h2 class="flex items-center gap-2 text-base font-extrabold text-slate-900 dark:text-white">
                                <x-icon name="clock" class="h-4 w-4 text-brand-600 dark:text-brand-400" />
                                Progress pekerjaan
                            </h2>
                            <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">
                                {{ $task->progress->count() }} catatan progress · {{ $attachmentCount }} lampiran
                            </p>
                        </div>
                    </div>

                    @can('createProgress', $task)
                        <div class="border-b border-slate-100 bg-brand-50/40 p-5 dark:border-slate-800 dark:bg-brand-950/20">
                            <h3 class="flex items-center gap-2 text-sm font-extrabold text-brand-900 dark:text-brand-200">
                                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-brand-600 text-white">
                                    <x-icon name="plus" class="h-4 w-4" />
                                </span>
                                Tambah progress
                            </h3>

                            <form method="POST"
                                  action="{{ route('tasks.progress.store', $task) }}"
                                  enctype="multipart/form-data"
                                  class="mt-4 space-y-4"
                                  data-submit-once>
                                @csrf

                                <div class="grid gap-4 sm:grid-cols-3">
                                    <div class="sm:col-span-2">
                                        <label for="progress_nama" class="mb-1.5 block text-xs font-bold text-slate-600 dark:text-slate-300">
                                            Judul progress <span class="text-rose-500">*</span>
                                        </label>
                                        <input type="text"
                                               id="progress_nama"
                                               name="nama"
                                               value="{{ old('nama') }}"
                                               required
                                               maxlength="255"
                                               placeholder="Contoh: Menyelesaikan modul laporan"
                                               class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-brand-400 focus:ring-4 focus:ring-brand-100 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:placeholder:text-slate-500 dark:focus:border-brand-600 dark:focus:ring-brand-950/60">
                                        <x-input-error :name="'nama'" />
                                    </div>

                                    <div>
                                        <label for="progress_tanggal" class="mb-1.5 block text-xs font-bold text-slate-600 dark:text-slate-300">
                                            Tanggal <span class="text-rose-500">*</span>
                                        </label>
                                        <input type="date"
                                               id="progress_tanggal"
                                               name="tanggal"
                                               value="{{ old('tanggal', now()->toDateString()) }}"
                                               required
                                               class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-800 outline-none transition focus:border-brand-400 focus:ring-4 focus:ring-brand-100 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:focus:border-brand-600 dark:focus:ring-brand-950/60">
                                        <x-input-error :name="'tanggal'" />
                                    </div>
                                </div>

                                <div>
                                    <label for="progress_kendala" class="mb-1.5 block text-xs font-bold text-slate-600 dark:text-slate-300">
                                        Kendala <span class="font-medium text-slate-400">(opsional)</span>
                                    </label>
                                    <textarea id="progress_kendala"
                                              name="kendala"
                                              rows="3"
                                              placeholder="Tuliskan kendala yang dihadapi, jika ada..."
                                              class="w-full resize-y rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-brand-400 focus:ring-4 focus:ring-brand-100 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:placeholder:text-slate-500 dark:focus:border-brand-600 dark:focus:ring-brand-950/60">{{ old('kendala') }}</textarea>
                                    <x-input-error :name="'kendala'" />
                                </div>

                                <div>
                                    <label for="progress_files" class="mb-1.5 block text-xs font-bold text-slate-600 dark:text-slate-300">
                                        Lampiran <span class="font-medium text-slate-400">(opsional)</span>
                                    </label>

                                    <input type="file"
                                           id="progress_files"
                                           name="attachments[]"
                                           multiple
                                           accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.xls,.xlsx"
                                           data-file-input
                                           data-file-list="progress-file-list"
                                           data-max-files="5"
                                           data-max-size="5"
                                           class="block w-full cursor-pointer rounded-xl border border-dashed border-slate-300 bg-white px-3.5 py-2.5 text-xs text-slate-500 file:mr-3 file:rounded-lg file:border-0 file:bg-brand-50 file:px-3 file:py-2 file:text-xs file:font-bold file:text-brand-700 hover:border-brand-300 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-400 dark:file:bg-brand-950/60 dark:file:text-brand-300">

                                    <p class="mt-1.5 text-[10px] text-slate-400 dark:text-slate-500">
                                        Maksimal 5 file, masing-masing 5 MB. Format: JPG, PNG, PDF, DOC, DOCX, XLS, XLSX.
                                    </p>

                                    <div id="progress-file-list" class="mt-2 hidden flex flex-wrap gap-2"></div>
                                    <p data-file-error="progress-file-list" class="mt-1.5 hidden text-xs font-semibold text-rose-600 dark:text-rose-400"></p>
                                    <x-input-error :name="'attachments'" />
                                </div>

                                <div class="flex justify-end">
                                    <button type="submit"
                                            data-loading-text="Menyimpan..."
                                            class="inline-flex items-center gap-2 rounded-xl bg-brand-600 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-brand-700 focus:outline-none focus:ring-4 focus:ring-brand-200 dark:focus:ring-brand-900">
                                        <x-icon name="save" class="h-4 w-4" />
                                        Simpan progress
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endcan

                    <div class="p-5">
                        @if ($sortedProgress->isEmpty())
                            <x-empty-state title="Belum ada progress" message="Progress yang dikirim akan tercatat di sini sebagai riwayat pekerjaan." icon="clock" />
                        @else
                            <div class="space-y-4">
                                @foreach ($sortedProgress as $progress)
                                    <article class="rounded-2xl border border-slate-200 bg-slate-50/50 p-4 dark:border-slate-800 dark:bg-slate-800/30">
                                        <div class="flex flex-wrap items-start justify-between gap-3">
                                            <div class="flex min-w-0 items-center gap-2.5">
                                                <x-avatar :name="$progress->user?->nama ?? 'User'" class="h-9 w-9 text-[10px]" />

                                                <div class="min-w-0">
                                                    <p class="truncate text-xs font-bold text-slate-700 dark:text-slate-200">
                                                        {{ $progress->user?->nama ?? 'Pengguna' }}
                                                    </p>
                                                    <p class="text-[10px] text-slate-400 dark:text-slate-500">
                                                        {{ $progress->tanggal?->locale('id')->isoFormat('D MMMM YYYY') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="flex items-center gap-1">
                                                @can('update', $progress)
                                                    <button type="button"
                                                            data-toggle-target="progress-edit-{{ $progress->id }}"
                                                            aria-expanded="false"
                                                            class="rounded-lg p-2 text-slate-400 transition hover:bg-white hover:text-sky-600 dark:hover:bg-slate-800 dark:hover:text-sky-400"
                                                            title="Edit progress">
                                                        <x-icon name="edit" class="h-4 w-4" />
                                                    </button>
                                                @endcan

                                                @can('delete', $progress)
                                                    <form method="POST"
                                                          action="{{ route('progress.destroy', $progress) }}"
                                                          data-confirm="Hapus progress &quot;{{ $progress->nama }}&quot; beserta lampirannya?"
                                                          data-confirm-title="Hapus progress">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="rounded-lg p-2 text-slate-400 transition hover:bg-white hover:text-rose-600 dark:hover:bg-slate-800 dark:hover:text-rose-400"
                                                                title="Hapus progress">
                                                            <x-icon name="trash" class="h-4 w-4" />
                                                        </button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </div>

                                        <h4 class="mt-3 text-sm font-bold text-slate-800 dark:text-slate-100">
                                            {{ $progress->nama }}
                                        </h4>

                                        @if ($progress->kendala)
                                            <div class="mt-2 rounded-xl border border-amber-200 bg-amber-50 p-3 dark:border-amber-900 dark:bg-amber-950/30">
                                                <p class="flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-wide text-amber-700 dark:text-amber-400">
                                                    <x-icon name="alert-triangle" class="h-3.5 w-3.5" />
                                                    Kendala
                                                </p>
                                                <p class="mt-1 whitespace-pre-line text-xs leading-relaxed text-amber-800 dark:text-amber-300">
                                                    {{ $progress->kendala }}
                                                </p>
                                            </div>
                                        @endif

                                        @if ($progress->attachments->isNotEmpty())
                                            <div class="mt-3">
                                                <p class="mb-1.5 flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-wide text-slate-400 dark:text-slate-500">
                                                    <x-icon name="paperclip" class="h-3.5 w-3.5" />
                                                    Lampiran ({{ $progress->attachments->count() }})
                                                </p>

                                                <div class="flex flex-wrap gap-2">
                                                    @foreach ($progress->attachments as $attachment)
                                                        @php $attachmentUrl = \Illuminate\Support\Facades\Storage::disk('public')->url($attachment->file_path); @endphp

                                                        <div class="flex max-w-full items-center gap-1 rounded-xl border border-slate-200 bg-white py-1.5 pl-2.5 pr-1.5 dark:border-slate-700 dark:bg-slate-900">
                                                            <x-icon name="document" class="h-3.5 w-3.5 shrink-0 text-slate-400" />

                                                            <a href="{{ $attachmentUrl }}"
                                                               target="_blank"
                                                               rel="noopener"
                                                               class="max-w-[12rem] truncate text-xs font-semibold text-slate-600 transition hover:text-brand-600 dark:text-slate-300 dark:hover:text-brand-400">
                                                                {{ $attachment->file_name }}
                                                            </a>

                                                            <a href="{{ $attachmentUrl }}"
                                                               target="_blank"
                                                               rel="noopener"
                                                               download
                                                               class="rounded-lg p-1.5 text-slate-400 transition hover:bg-slate-100 hover:text-brand-600 dark:hover:bg-slate-800 dark:hover:text-brand-400"
                                                               title="Unduh lampiran">
                                                                <x-icon name="download" class="h-3.5 w-3.5" />
                                                            </a>

                                                            @can('delete', $progress)
                                                                <form method="POST"
                                                                      action="{{ route('progress.attachments.destroy', $attachment) }}"
                                                                      data-confirm="Hapus lampiran &quot;{{ $attachment->file_name }}&quot;?"
                                                                      data-confirm-title="Hapus lampiran">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                            class="rounded-lg p-1.5 text-slate-400 transition hover:bg-rose-50 hover:text-rose-600 dark:hover:bg-rose-950/40 dark:hover:text-rose-400"
                                                                            title="Hapus lampiran">
                                                                        <x-icon name="trash" class="h-3.5 w-3.5" />
                                                                    </button>
                                                                </form>
                                                            @endcan
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif

                                        @can('update', $progress)
                                            <div id="progress-edit-{{ $progress->id }}" class="mt-4 hidden rounded-xl border border-sky-200 bg-sky-50/60 p-4 dark:border-sky-900 dark:bg-sky-950/20">
                                                <form method="POST"
                                                      action="{{ route('progress.update', $progress) }}"
                                                      class="space-y-3"
                                                      data-submit-once>
                                                    @csrf
                                                    @method('PUT')

                                                    <div class="grid gap-3 sm:grid-cols-3">
                                                        <div class="sm:col-span-2">
                                                            <label for="edit_progress_nama_{{ $progress->id }}" class="mb-1 block text-[10px] font-bold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                                                                Judul progress
                                                            </label>
                                                            <input type="text"
                                                                   id="edit_progress_nama_{{ $progress->id }}"
                                                                   name="nama"
                                                                   value="{{ $progress->nama }}"
                                                                   required
                                                                   maxlength="255"
                                                                   class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 outline-none focus:border-sky-400 focus:ring-4 focus:ring-sky-100 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:focus:ring-sky-950/60">
                                                        </div>

                                                        <div>
                                                            <label for="edit_progress_tanggal_{{ $progress->id }}" class="mb-1 block text-[10px] font-bold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                                                                Tanggal
                                                            </label>
                                                            <input type="date"
                                                                   id="edit_progress_tanggal_{{ $progress->id }}"
                                                                   name="tanggal"
                                                                   value="{{ $progress->tanggal?->format('Y-m-d') }}"
                                                                   required
                                                                   class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 outline-none focus:border-sky-400 focus:ring-4 focus:ring-sky-100 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:focus:ring-sky-950/60">
                                                        </div>
                                                    </div>

                                                    <div>
                                                        <label for="edit_progress_kendala_{{ $progress->id }}" class="mb-1 block text-[10px] font-bold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                                                            Kendala
                                                        </label>
                                                        <textarea id="edit_progress_kendala_{{ $progress->id }}"
                                                                  name="kendala"
                                                                  rows="2"
                                                                  class="w-full resize-y rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 outline-none focus:border-sky-400 focus:ring-4 focus:ring-sky-100 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:focus:ring-sky-950/60">{{ $progress->kendala }}</textarea>
                                                    </div>

                                                    <div class="flex items-center justify-end gap-2">
                                                        <button type="button"
                                                                data-toggle-target="progress-edit-{{ $progress->id }}"
                                                                aria-expanded="true"
                                                                class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-600 transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300">
                                                            Batal
                                                        </button>

                                                        <button type="submit"
                                                                data-loading-text="Menyimpan..."
                                                                class="inline-flex items-center gap-1.5 rounded-xl bg-sky-600 px-3.5 py-2 text-xs font-bold text-white transition hover:bg-sky-700">
                                                            <x-icon name="save" class="h-3.5 w-3.5" />
                                                            Simpan
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>

                                            <details class="group mt-3">
                                                <summary class="inline-flex cursor-pointer items-center gap-1.5 rounded-lg px-2 py-1.5 text-[10px] font-bold uppercase tracking-wide text-slate-400 transition hover:bg-white hover:text-brand-600 dark:text-slate-500 dark:hover:bg-slate-800 dark:hover:text-brand-400">
                                                    <x-icon name="paperclip" class="h-3.5 w-3.5" />
                                                    Tambah lampiran
                                                </summary>

                                                <form method="POST"
                                                      action="{{ route('progress.attachments.store', $progress) }}"
                                                      enctype="multipart/form-data"
                                                      class="mt-2 flex flex-col gap-2 rounded-xl border border-slate-200 bg-white p-3 dark:border-slate-700 dark:bg-slate-900 sm:flex-row sm:items-center"
                                                      data-submit-once>
                                                    @csrf

                                                    <input type="file"
                                                           id="attachment_files_{{ $progress->id }}"
                                                           name="attachments[]"
                                                           multiple
                                                           required
                                                           accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.xls,.xlsx"
                                                           data-file-input
                                                           data-file-list="attachment-file-list-{{ $progress->id }}"
                                                           data-max-files="5"
                                                           data-max-size="5"
                                                           class="block w-full flex-1 cursor-pointer rounded-lg border border-dashed border-slate-300 bg-white px-3 py-2 text-xs text-slate-500 file:mr-2 file:rounded-md file:border-0 file:bg-brand-50 file:px-2.5 file:py-1.5 file:text-[10px] file:font-bold file:text-brand-700 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-400 dark:file:bg-brand-950/60 dark:file:text-brand-300">

                                                    <button type="submit"
                                                            data-loading-text="Mengunggah..."
                                                            class="inline-flex shrink-0 items-center justify-center gap-1.5 rounded-lg bg-brand-600 px-3 py-2 text-xs font-bold text-white transition hover:bg-brand-700">
                                                        <x-icon name="upload" class="h-3.5 w-3.5" />
                                                        Unggah
                                                    </button>

                                                    <div id="attachment-file-list-{{ $progress->id }}" class="hidden flex flex-wrap gap-1.5 sm:basis-full"></div>
                                                    <p data-file-error="attachment-file-list-{{ $progress->id }}" class="hidden text-[10px] font-semibold text-rose-600 sm:basis-full dark:text-rose-400"></p>
                                                </form>
                                            </details>
                                        @endcan
                                    </article>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </section>
            </div>

            <aside class="space-y-6">
                <section class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="flex items-center justify-between gap-2 border-b border-slate-100 px-5 py-4 dark:border-slate-800">
                        <h2 class="flex items-center gap-2 text-sm font-extrabold text-slate-900 dark:text-white">
                            <x-icon name="users" class="h-4 w-4 text-brand-600 dark:text-brand-400" />
                            Penugasan staff
                        </h2>

                        <span class="rounded-lg bg-slate-100 px-2 py-0.5 text-[10px] font-bold text-slate-500 dark:bg-slate-800 dark:text-slate-400">
                            {{ $task->assignedUsers->count() }} orang
                        </span>
                    </div>

                    @can('assign', $task)
                        <form method="POST"
                              action="{{ route('tasks.assign', $task) }}"
                              class="space-y-4 border-b border-slate-100 p-5 dark:border-slate-800"
                              data-submit-once>
                            @csrf
                            @method('PUT')

                            <div>
                                <p class="mb-2 text-[10px] font-bold uppercase tracking-wide text-slate-400 dark:text-slate-500">
                                    Pilih staff
                                </p>

                                @if ($staffs->isEmpty())
                                    <p class="rounded-xl bg-amber-50 p-3 text-xs text-amber-700 dark:bg-amber-950/30 dark:text-amber-400">
                                        Belum ada staff aktif.
                                    </p>
                                @else
                                    <div class="max-h-64 space-y-1 overflow-y-auto rounded-xl border border-slate-200 p-1.5 dark:border-slate-700">
                                        @foreach ($staffs as $staff)
                                            <label class="flex cursor-pointer items-center gap-2.5 rounded-lg px-2 py-1.5 transition hover:bg-slate-50 dark:hover:bg-slate-800">
                                                <input type="checkbox"
                                                       name="staff[]"
                                                       value="{{ $staff->id }}"
                                                       @checked(in_array($staff->id, $selectedStaff))
                                                       class="h-4 w-4 rounded border-slate-300 text-brand-600 focus:ring-brand-500 dark:border-slate-600 dark:bg-slate-900">

                                                <x-avatar :name="$staff->nama" class="h-7 w-7 text-[9px]" />

                                                <span class="min-w-0 flex-1">
                                                    <span class="block truncate text-xs font-semibold text-slate-700 dark:text-slate-200">{{ $staff->nama }}</span>
                                                    <span class="block truncate text-[10px] text-slate-400 dark:text-slate-500">{{ $staff->jabatan ?: 'Staff' }}</span>
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>
                                @endif

                                <x-input-error :name="'staff'" />
                            </div>

                            <div>
                                <label for="tanggal_penugasan" class="mb-1.5 block text-[10px] font-bold uppercase tracking-wide text-slate-400 dark:text-slate-500">
                                    Tanggal penugasan
                                </label>
                                <input type="date"
                                       id="tanggal_penugasan"
                                       name="tanggal_penugasan"
                                       value="{{ old('tanggal_penugasan', now()->toDateString()) }}"
                                       required
                                       class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 outline-none focus:border-brand-400 focus:ring-4 focus:ring-brand-100 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:focus:ring-brand-950/60">
                                <x-input-error :name="'tanggal_penugasan'" />
                            </div>

                            @unless ($staffs->isEmpty())
                                <button type="submit"
                                        data-loading-text="Menyimpan..."
                                        class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-brand-600 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-brand-700">
                                    <x-icon name="save" class="h-4 w-4" />
                                    Simpan penugasan
                                </button>
                            @endunless
                        </form>
                    @endcan

                    <div class="p-5">
                        <p class="mb-2 text-[10px] font-bold uppercase tracking-wide text-slate-400 dark:text-slate-500">
                            Staff ditugaskan
                        </p>

                        @forelse ($task->assignedUsers as $assignment)
                            <div class="flex items-center gap-2.5 py-2">
                                <x-avatar :name="$assignment->nama" class="h-8 w-8 text-[10px]" />

                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-xs font-bold text-slate-700 dark:text-slate-200">{{ $assignment->nama }}</p>
                                    <p class="text-[10px] text-slate-400 dark:text-slate-500">
                                        Ditugaskan {{ \Illuminate\Support\Carbon::parse($assignment->pivot->tanggal_penugasan)->locale('id')->isoFormat('D MMM YYYY') }}
                                    </p>
                                </div>
                            </div>
                        @empty
                            <p class="rounded-xl border border-dashed border-slate-200 p-3 text-center text-xs text-slate-400 dark:border-slate-700 dark:text-slate-500">
                                Belum ada staff yang ditugaskan.
                            </p>
                        @endforelse
                    </div>
                </section>

                <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <h2 class="flex items-center gap-2 text-sm font-extrabold text-slate-900 dark:text-white">
                        <x-icon name="chart" class="h-4 w-4 text-brand-600 dark:text-brand-400" />
                        Ringkasan
                    </h2>

                    <dl class="mt-4 space-y-3 text-xs">
                        <div class="flex items-center justify-between gap-2">
                            <dt class="flex items-center gap-1.5 text-slate-400 dark:text-slate-500">
                                <x-icon name="calendar" class="h-3.5 w-3.5" />
                                Target selesai
                            </dt>
                            <dd class="font-bold {{ $isOverdue ? 'text-rose-600 dark:text-rose-400' : 'text-slate-700 dark:text-slate-200' }}">
                                {{ $task->target_selesai->locale('id')->isoFormat('D MMM YYYY') }}
                            </dd>
                        </div>

                        <div class="flex items-center justify-between gap-2">
                            <dt class="flex items-center gap-1.5 text-slate-400 dark:text-slate-500">
                                <x-icon name="clock" class="h-3.5 w-3.5" />
                                Sisa waktu
                            </dt>
                            <dd class="font-bold {{ $isOverdue ? 'text-rose-600 dark:text-rose-400' : 'text-slate-700 dark:text-slate-200' }}">
                                @if ($task->status === 'selesai')
                                    Selesai
                                @elseif ($daysLeft < 0)
                                    {{ abs($daysLeft) }} hari lewat
                                @elseif ($daysLeft === 0)
                                    Hari ini
                                @else
                                    {{ $daysLeft }} hari
                                @endif
                            </dd>
                        </div>

                        <div class="flex items-center justify-between gap-2">
                            <dt class="flex items-center gap-1.5 text-slate-400 dark:text-slate-500">
                                <x-icon name="clock" class="h-3.5 w-3.5" />
                                Progress
                            </dt>
                            <dd class="font-bold text-slate-700 dark:text-slate-200">{{ $task->progress->count() }} catatan</dd>
                        </div>

                        <div class="flex items-center justify-between gap-2">
                            <dt class="flex items-center gap-1.5 text-slate-400 dark:text-slate-500">
                                <x-icon name="paperclip" class="h-3.5 w-3.5" />
                                Lampiran
                            </dt>
                            <dd class="font-bold text-slate-700 dark:text-slate-200">{{ $attachmentCount }} file</dd>
                        </div>

                        <div class="flex items-center justify-between gap-2">
                            <dt class="flex items-center gap-1.5 text-slate-400 dark:text-slate-500">
                                <x-icon name="users" class="h-3.5 w-3.5" />
                                Staff
                            </dt>
                            <dd class="font-bold text-slate-700 dark:text-slate-200">{{ $task->assignedUsers->count() }} orang</dd>
                        </div>
                    </dl>

                    <div class="mt-4 border-t border-slate-100 pt-4 dark:border-slate-800">
                        <p class="text-[10px] font-bold uppercase tracking-wide text-slate-400 dark:text-slate-500">Dibuat</p>
                        <p class="mt-1 text-xs font-semibold text-slate-600 dark:text-slate-300">
                            {{ $task->creator?->nama ?? '-' }}
                        </p>
                        <p class="text-[10px] text-slate-400 dark:text-slate-500">
                            {{ $task->created_at?->locale('id')->isoFormat('D MMMM YYYY, HH:mm') }}
                        </p>
                    </div>
                </section>
            </aside>
        </div>
    </div>
@endsection
