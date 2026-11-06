@extends('layouts.app')

@section('title', 'Pengguna')

@section('content')
    <div class="space-y-6">
        <x-page-header title="Pengguna" subtitle="Kelola akun, role, dan status pengguna aplikasi.">
            <a href="{{ route('users.create') }}"
               class="inline-flex items-center gap-2 rounded-xl bg-brand-600 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-brand-700 focus:outline-none focus:ring-4 focus:ring-brand-200 dark:focus:ring-brand-900">
                <x-icon name="plus" class="h-4 w-4" />
                Pengguna baru
            </a>
        </x-page-header>

        @if ($users->isEmpty())
            <x-empty-state title="Belum ada pengguna" message="Tambahkan akun untuk staff atau viewer yang akan menggunakan aplikasi." icon="users">
                <a href="{{ route('users.create') }}"
                   class="inline-flex items-center gap-2 rounded-xl bg-brand-600 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-brand-700">
                    <x-icon name="plus" class="h-4 w-4" />
                    Tambah pengguna
                </a>
            </x-empty-state>
        @else
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-left dark:divide-slate-800">
                        <thead class="bg-slate-50/80 dark:bg-slate-800/50">
                            <tr class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                <th scope="col" class="px-5 py-3.5">Pengguna</th>
                                <th scope="col" class="px-4 py-3.5">NIP</th>
                                <th scope="col" class="hidden px-4 py-3.5 lg:table-cell">Jabatan</th>
                                <th scope="col" class="px-4 py-3.5">Role</th>
                                <th scope="col" class="px-4 py-3.5">Status</th>
                                <th scope="col" class="px-5 py-3.5 text-right">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @foreach ($users as $user)
                                <tr class="transition hover:bg-slate-50/80 dark:hover:bg-slate-800/40">
                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-3">
                                            <x-avatar :name="$user->nama" class="h-9 w-9 text-xs" />

                                            <div class="min-w-0">
                                                <div class="flex items-center gap-2">
                                                    <span class="truncate text-sm font-bold text-slate-800 dark:text-slate-100">
                                                        {{ $user->nama }}
                                                    </span>
                                                    @if (auth()->id() === $user->id)
                                                        <span class="rounded-md bg-brand-50 px-1.5 py-0.5 text-[9px] font-bold uppercase tracking-wide text-brand-700 dark:bg-brand-950/60 dark:text-brand-300">
                                                            Anda
                                                        </span>
                                                    @endif
                                                </div>
                                                <p class="mt-0.5 truncate text-xs text-slate-400 dark:text-slate-500">
                                                    &#64;{{ $user->username }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-4 py-4">
                                        <span class="text-xs font-semibold text-slate-600 dark:text-slate-300">{{ $user->nip }}</span>
                                    </td>

                                    <td class="hidden max-w-[12rem] px-4 py-4 lg:table-cell">
                                        <span class="line-clamp-1 text-xs text-slate-500 dark:text-slate-400">
                                            {{ $user->jabatan ?: '—' }}
                                        </span>
                                    </td>

                                    <td class="px-4 py-4">
                                        <x-role-badge :role="$user->role" />
                                    </td>

                                    <td class="px-4 py-4">
                                        @if ($user->status === 'aktif')
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-brand-50 px-2.5 py-1 text-xs font-semibold text-brand-700 ring-1 ring-inset ring-brand-200 dark:bg-brand-950/60 dark:text-brand-300 dark:ring-brand-900">
                                                <span class="h-1.5 w-1.5 rounded-full bg-brand-500"></span>
                                                Aktif
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600 ring-1 ring-inset ring-slate-200 dark:bg-slate-800 dark:text-slate-400 dark:ring-slate-700">
                                                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                                                Nonaktif
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-5 py-4">
                                        <div class="flex items-center justify-end gap-1">
                                            @can('update', $user)
                                                <a href="{{ route('users.edit', $user) }}"
                                                   class="rounded-lg p-2 text-slate-400 transition hover:bg-slate-100 hover:text-sky-600 dark:hover:bg-slate-800 dark:hover:text-sky-400"
                                                   title="Edit pengguna">
                                                    <x-icon name="edit" class="h-4 w-4" />
                                                </a>
                                            @endcan

                                            @can('delete', $user)
                                                <form method="POST"
                                                      action="{{ route('users.destroy', $user) }}"
                                                      data-confirm="Hapus pengguna &quot;{{ $user->nama }}&quot;? Pengguna yang memiliki riwayat tugas tidak dapat dihapus, nonaktifkan saja akunnya."
                                                      data-confirm-title="Hapus pengguna">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="rounded-lg p-2 text-slate-400 transition hover:bg-rose-50 hover:text-rose-600 dark:hover:bg-rose-950/50 dark:hover:text-rose-400"
                                                            title="Hapus pengguna">
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
            </div>
        @endif
    </div>
@endsection
