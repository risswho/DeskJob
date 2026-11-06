@extends('layouts.app')

@section('title', 'Kategori')

@section('content')
    <div class="space-y-6">
        <x-page-header title="Kategori tugas" subtitle="Kelompokkan pekerjaan agar lebih mudah dicari dan dilaporkan.">
            <a href="{{ route('categories.create') }}"
               class="inline-flex items-center gap-2 rounded-xl bg-brand-600 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-brand-700 focus:outline-none focus:ring-4 focus:ring-brand-200 dark:focus:ring-brand-900">
                <x-icon name="plus" class="h-4 w-4" />
                Kategori baru
            </a>
        </x-page-header>

        @if ($categories->isEmpty())
            <x-empty-state title="Belum ada kategori" message="Buat kategori pertama sebelum menambahkan tugas." icon="category">
                <a href="{{ route('categories.create') }}"
                   class="inline-flex items-center gap-2 rounded-xl bg-brand-600 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-brand-700">
                    <x-icon name="plus" class="h-4 w-4" />
                    Buat kategori
                </a>
            </x-empty-state>
        @else
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-left dark:divide-slate-800">
                        <thead class="bg-slate-50/80 dark:bg-slate-800/50">
                            <tr class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                <th scope="col" class="px-5 py-3.5">Kategori</th>
                                <th scope="col" class="px-4 py-3.5">Deskripsi</th>
                                <th scope="col" class="px-4 py-3.5">Jumlah tugas</th>
                                <th scope="col" class="px-5 py-3.5 text-right">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @foreach ($categories as $category)
                                <tr class="transition hover:bg-slate-50/80 dark:hover:bg-slate-800/40">
                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-3">
                                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-brand-50 text-brand-600 dark:bg-brand-950/60 dark:text-brand-400">
                                                <x-icon name="category" class="h-4 w-4" />
                                            </span>
                                            <span class="text-sm font-bold text-slate-800 dark:text-slate-100">
                                                {{ $category->nama }}
                                            </span>
                                        </div>
                                    </td>

                                    <td class="max-w-md px-4 py-4">
                                        <p class="line-clamp-2 text-sm text-slate-500 dark:text-slate-400">
                                            {{ $category->deskripsi ?: '—' }}
                                        </p>
                                    </td>

                                    <td class="px-4 py-4">
                                        <span class="inline-flex items-center gap-1.5 rounded-lg bg-slate-100 px-2.5 py-1 text-xs font-bold text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                                            <x-icon name="tasks" class="h-3.5 w-3.5" />
                                            {{ $category->tasks_count }} tugas
                                        </span>
                                    </td>

                                    <td class="px-5 py-4">
                                        <div class="flex items-center justify-end gap-1">
                                            <a href="{{ route('categories.edit', $category) }}"
                                               class="rounded-lg p-2 text-slate-400 transition hover:bg-slate-100 hover:text-sky-600 dark:hover:bg-slate-800 dark:hover:text-sky-400"
                                               title="Edit kategori">
                                                <x-icon name="edit" class="h-4 w-4" />
                                            </a>

                                            <form method="POST"
                                                  action="{{ route('categories.destroy', $category) }}"
                                                  data-confirm="Hapus kategori &quot;{{ $category->nama }}&quot;? Kategori yang masih dipakai tugas tidak dapat dihapus."
                                                  data-confirm-title="Hapus kategori">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="rounded-lg p-2 text-slate-400 transition hover:bg-rose-50 hover:text-rose-600 dark:hover:bg-rose-950/50 dark:hover:text-rose-400"
                                                        title="Hapus kategori">
                                                    <x-icon name="trash" class="h-4 w-4" />
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
@endsection
