@extends('layouts.app')

@section('title', 'Tugas')

@section('content')
    @php
        $hasFilters = request()->filled('q') || request()->filled('status') || request()->filled('category');
    @endphp

    <div class="space-y-6">
        <x-page-header title="Daftar tugas" subtitle="Pantau seluruh pekerjaan, penanggung jawab, dan progresnya.">
            @can('create', \App\Models\Task::class)
                <a href="{{ route('tasks.create') }}"
                   class="inline-flex items-center gap-2 rounded-xl bg-brand-600 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-brand-700 focus:outline-none focus:ring-4 focus:ring-brand-200 dark:focus:ring-brand-900">
                    <x-icon name="plus" class="h-4 w-4" />
                    Tugas baru
                </a>
            @endcan
        </x-page-header>

        <form method="GET" action="{{ route('tasks.index') }}"
              class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-[minmax(0,1fr)_180px_220px_auto]">
                <div>
                    <label for="q" class="sr-only">Cari tugas</label>
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                            <x-icon name="search" class="h-4 w-4" />
                        </span>

                        <input type="search"
                               id="q"
                               name="q"
                               value="{{ $filters['q'] ?? '' }}"
                               placeholder="Cari nama tugas atau kategori..."
                               class="w-full rounded-xl border border-slate-200 bg-white py-2.5 pl-10 pr-3.5 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-brand-400 focus:ring-4 focus:ring-brand-100 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:placeholder:text-slate-500 dark:focus:border-brand-600 dark:focus:ring-brand-950/60">
                    </div>
                </div>

                <div>
                    <label for="status" class="sr-only">Status</label>
                    <select id="status"
                            name="status"
                            class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm font-medium text-slate-700 outline-none transition focus:border-brand-400 focus:ring-4 focus:ring-brand-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200 dark:focus:border-brand-600 dark:focus:ring-brand-950/60">
                        <option value="">Semua status</option>
                        <option value="belum_mulai" @selected(($filters['status'] ?? '') === 'belum_mulai')>Belum mulai</option>
                        <option value="berjalan" @selected(($filters['status'] ?? '') === 'berjalan')>Berjalan</option>
                        <option value="selesai" @selected(($filters['status'] ?? '') === 'selesai')>Selesai</option>
                        <option value="tertunda" @selected(($filters['status'] ?? '') === 'tertunda')>Tertunda</option>
                    </select>
                </div>

                <div>
                    <label for="category" class="sr-only">Kategori</label>
                    <select id="category"
                            name="category"
                            class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm font-medium text-slate-700 outline-none transition focus:border-brand-400 focus:ring-4 focus:ring-brand-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200 dark:focus:border-brand-600 dark:focus:ring-brand-950/60">
                        <option value="">Semua kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected((string) ($filters['category'] ?? '') === (string) $category->id)>
                                {{ $category->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit"
                            class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl bg-brand-600 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-brand-700 focus:outline-none focus:ring-4 focus:ring-brand-200 dark:focus:ring-brand-900 sm:flex-none">
                        <x-icon name="filter" class="h-4 w-4" />
                        Terapkan
                    </button>

                    @if ($hasFilters)
                        <a href="{{ route('tasks.index') }}"
                           class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm font-semibold text-slate-600 transition hover:border-slate-300 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800"
                           title="Reset filter">
                            <x-icon name="refresh" class="h-4 w-4" />
                            <span class="sm:sr-only">Reset</span>
                        </a>
                    @endif
                </div>
            </div>

            @if ($hasFilters)
                <div class="mt-3 flex flex-wrap items-center gap-2 border-t border-slate-100 pt-3 text-xs dark:border-slate-800">
                    <span class="font-semibold text-slate-500 dark:text-slate-400">Filter aktif:</span>

                    @if (request()->filled('q'))
                        <span class="inline-flex items-center gap-1 rounded-lg bg-brand-50 px-2 py-1 font-semibold text-brand-700 dark:bg-brand-950/60 dark:text-brand-300">
                            Pencarian: {{ $filters['q'] }}
                        </span>
                    @endif

                    @if (request()->filled('status'))
                        <span class="inline-flex items-center gap-1 rounded-lg bg-brand-50 px-2 py-1 font-semibold text-brand-700 dark:bg-brand-950/60 dark:text-brand-300">
                            Status: {{ str_replace('_', ' ', $filters['status']) }}
                        </span>
                    @endif

                    @if (request()->filled('category'))
                        <span class="inline-flex items-center gap-1 rounded-lg bg-brand-50 px-2 py-1 font-semibold text-brand-700 dark:bg-brand-950/60 dark:text-brand-300">
                            Kategori: {{ $categories->firstWhere('id', (int) $filters['category'])?->nama ?? '-' }}
                        </span>
                    @endif
                </div>
            @endif
        </form>

        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm text-slate-500 dark:text-slate-400">
                Menampilkan
                <span class="font-bold text-slate-700 dark:text-slate-200">{{ $tasks->firstItem() ?? 0 }}</span>
                –
                <span class="font-bold text-slate-700 dark:text-slate-200">{{ $tasks->lastItem() ?? 0 }}</span>
                dari
                <span class="font-bold text-slate-700 dark:text-slate-200">{{ $tasks->total() }}</span>
                tugas
            </p>

            @if ($hasFilters)
                <a href="{{ route('tasks.index') }}"
                   class="inline-flex items-center gap-1 text-xs font-bold text-brand-600 hover:text-brand-800 dark:text-brand-400 dark:hover:text-brand-300">
                    Tampilkan semua tugas
                    <x-icon name="chevron-right" class="h-3.5 w-3.5" />
                </a>
            @endif
        </div>

        @if ($tasks->isEmpty())
            <x-empty-state
                :title="$hasFilters ? 'Tidak ada tugas yang cocok' : 'Belum ada tugas'"
                :message="$hasFilters ? 'Coba ubah kata kunci atau reset filter untuk melihat tugas lainnya.' : 'Mulai dengan membuat tugas pertama untuk tim.'"
                icon="tasks">
                @if ($hasFilters)
                    <a href="{{ route('tasks.index') }}"
                       class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200">
                        <x-icon name="refresh" class="h-4 w-4" />
                        Reset filter
                    </a>
                @else
                    @can('create', \App\Models\Task::class)
                        <a href="{{ route('tasks.create') }}"
                           class="inline-flex items-center gap-2 rounded-xl bg-brand-600 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-brand-700">
                            <x-icon name="plus" class="h-4 w-4" />
                            Buat tugas pertama
                        </a>
                    @endcan
                @endif
            </x-empty-state>
        @else
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="hidden overflow-x-auto xl:block">
                    <table class="min-w-full divide-y divide-slate-200 text-left dark:divide-slate-800">
                        <thead class="bg-slate-50/80 dark:bg-slate-800/50">
                            <tr class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                <th scope="col" class="w-[34%] px-5 py-3.5">Tugas</th>
                                <th scope="col" class="px-4 py-3.5">Penanggung jawab</th>
                                <th scope="col" class="px-4 py-3.5">Target</th>
                                <th scope="col" class="px-4 py-3.5">Status</th>
                                <th scope="col" class="px-5 py-3.5 text-right">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @foreach ($tasks as $task)
                                @php $isOverdue = $task->target_selesai->lt(today()) && $task->status !== 'selesai'; @endphp

                                <tr class="transition hover:bg-slate-50/80 dark:hover:bg-slate-800/40">
                                    <td class="px-5 py-4">
                                        <div class="flex min-w-[13rem] items-center gap-3">
                                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-brand-50 text-brand-600 dark:bg-brand-950/60 dark:text-brand-400">
                                                <x-icon name="tasks" class="h-4 w-4" />
                                            </span>
                                            <div class="min-w-0">
                                                <a href="{{ route('tasks.show', $task) }}"
                                                   class="line-clamp-1 text-sm font-bold text-slate-800 transition hover:text-brand-700 dark:text-slate-100 dark:hover:text-brand-300">
                                                    {{ $task->nama }}
                                                </a>
                                                <p class="mt-0.5 truncate text-xs text-slate-500 dark:text-slate-400">
                                                    {{ $task->category?->nama ?? 'Tanpa kategori' }}
                                                    <span class="mx-1 text-slate-300 dark:text-slate-600">·</span>
                                                    {{ $task->assigned_users_count }} staff
                                                    <span class="mx-1 text-slate-300 dark:text-slate-600">·</span>
                                                    {{ $task->progress_count }} progress
                                                </p>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-4 py-4">
                                        <div class="flex items-center gap-2">
                                            <x-avatar :name="$task->penanggungJawab?->nama ?? 'User'" class="h-7 w-7 text-[10px]" />
                                            <span class="max-w-[10rem] truncate text-sm text-slate-600 dark:text-slate-300">
                                                {{ $task->penanggungJawab?->nama ?? '-' }}
                                            </span>
                                        </div>
                                    </td>

                                    <td class="px-4 py-4">
                                        <span class="whitespace-nowrap text-sm font-semibold {{ $isOverdue ? 'text-rose-600 dark:text-rose-400' : 'text-slate-600 dark:text-slate-300' }}">
                                            {{ $task->target_selesai->locale('id')->isoFormat('D MMM YYYY') }}
                                        </span>
                                        @if ($isOverdue)
                                            <p class="mt-0.5 text-[10px] font-bold uppercase tracking-wide text-rose-500">
                                                Lewat target
                                            </p>
                                        @endif
                                    </td>

                                    <td class="px-4 py-4">
                                        <x-status-badge :status="$task->status" class="whitespace-nowrap" />
                                    </td>

                                    <td class="px-5 py-4">
                                        <div class="flex items-center justify-end gap-1">
                                            <a href="{{ route('tasks.show', $task) }}"
                                               class="rounded-lg p-2 text-slate-400 transition hover:bg-slate-100 hover:text-brand-600 dark:hover:bg-slate-800 dark:hover:text-brand-400"
                                               title="Lihat detail">
                                                <x-icon name="eye" class="h-4 w-4" />
                                            </a>

                                            @can('update', $task)
                                                <a href="{{ route('tasks.edit', $task) }}"
                                                   class="rounded-lg p-2 text-slate-400 transition hover:bg-slate-100 hover:text-sky-600 dark:hover:bg-slate-800 dark:hover:text-sky-400"
                                                   title="Edit tugas">
                                                    <x-icon name="edit" class="h-4 w-4" />
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
                                                            class="rounded-lg p-2 text-slate-400 transition hover:bg-rose-50 hover:text-rose-600 dark:hover:bg-rose-950/50 dark:hover:text-rose-400"
                                                            title="Hapus tugas">
                                                        <x-icon name="trash" class="h-4 w-4" />
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="grid gap-3 p-3 sm:grid-cols-2 xl:hidden">
                    @foreach ($tasks as $task)
                        @php $isOverdue = $task->target_selesai->lt(today()) && $task->status !== 'selesai'; @endphp

                        <div class="rounded-2xl border border-slate-200 p-4 dark:border-slate-800">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <a href="{{ route('tasks.show', $task) }}"
                                       class="line-clamp-2 text-sm font-bold text-slate-800 dark:text-slate-100">
                                        {{ $task->nama }}
                                    </a>
                                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                                        {{ $task->category?->nama ?? 'Tanpa kategori' }}
                                    </p>
                                </div>

                                <x-status-badge :status="$task->status" class="whitespace-nowrap" />
                            </div>

                            <div class="mt-3 grid grid-cols-2 gap-2 text-xs">
                                <div class="flex items-center gap-1.5 text-slate-500 dark:text-slate-400">
                                    <x-icon name="user" class="h-3.5 w-3.5 shrink-0" />
                                    <span class="truncate">{{ $task->penanggungJawab?->nama ?? '-' }}</span>
                                </div>

                                <div class="flex items-center gap-1.5 {{ $isOverdue ? 'text-rose-600 dark:text-rose-400' : 'text-slate-500 dark:text-slate-400' }}">
                                    <x-icon name="calendar" class="h-3.5 w-3.5 shrink-0" />
                                    <span>{{ $task->target_selesai->locale('id')->isoFormat('D MMM YYYY') }}</span>
                                </div>

                                <div class="flex items-center gap-1.5 text-slate-500 dark:text-slate-400">
                                    <x-icon name="users" class="h-3.5 w-3.5 shrink-0" />
                                    <span>{{ $task->assigned_users_count }} staff</span>
                                </div>

                                <div class="flex items-center gap-1.5 text-slate-500 dark:text-slate-400">
                                    <x-icon name="clock" class="h-3.5 w-3.5 shrink-0" />
                                    <span>{{ $task->progress_count }} progress</span>
                                </div>
                            </div>

                            <div class="mt-3 flex items-center justify-end gap-1 border-t border-slate-100 pt-2 dark:border-slate-800">
                                <a href="{{ route('tasks.show', $task) }}"
                                   class="rounded-lg p-2 text-slate-400 transition hover:bg-slate-100 hover:text-brand-600 dark:hover:bg-slate-800">
                                    <x-icon name="eye" class="h-4 w-4" />
                                </a>

                                @can('update', $task)
                                    <a href="{{ route('tasks.edit', $task) }}"
                                       class="rounded-lg p-2 text-slate-400 transition hover:bg-slate-100 hover:text-sky-600 dark:hover:bg-slate-800">
                                        <x-icon name="edit" class="h-4 w-4" />
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
                                                class="rounded-lg p-2 text-slate-400 transition hover:bg-rose-50 hover:text-rose-600 dark:hover:bg-rose-950/50">
                                            <x-icon name="trash" class="h-4 w-4" />
                                        </button>
                                    </form>
                                @endcan
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{ $tasks->links('partials.pagination') }}
        @endif
    </div>
@endsection
