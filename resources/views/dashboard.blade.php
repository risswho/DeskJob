@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    @php
        $displayName = \Illuminate\Support\Str::of(auth()->user()->nama)->limit(28);
        $hour = (int) now()->format('H');

        $greeting = match (true) {
            $hour < 11 => 'Selamat pagi',
            $hour < 15 => 'Selamat siang',
            $hour < 18 => 'Selamat sore',
            default => 'Selamat malam',
        };

        $total = max(1, $stats['total']);

        $statusRows = [
            [
                'key' => 'belum_mulai',
                'label' => 'Belum mulai',
                'bar' => 'bg-slate-400',
                'count' => $stats['belum_mulai'],
            ],
            [
                'key' => 'berjalan',
                'label' => 'Berjalan',
                'bar' => 'bg-sky-500',
                'count' => $stats['berjalan'],
            ],
            [
                'key' => 'selesai',
                'label' => 'Selesai',
                'bar' => 'bg-brand-500',
                'count' => $stats['selesai'],
            ],
            [
                'key' => 'tertunda',
                'label' => 'Tertunda',
                'bar' => 'bg-amber-500',
                'count' => $stats['tertunda'],
            ],
        ];

        $cards = [
            [
                'label' => 'Total tugas',
                'value' => $stats['total'],
                'hint' => 'Seluruh tugas terdaftar',
                'icon' => 'tasks',
                'iconClass' => 'bg-brand-50 text-brand-600 dark:bg-brand-950/60 dark:text-brand-400',
            ],
            [
                'label' => 'Sedang berjalan',
                'value' => $stats['berjalan'],
                'hint' => 'Tugas yang sedang dikerjakan',
                'icon' => 'clock',
                'iconClass' => 'bg-sky-50 text-sky-600 dark:bg-sky-950/60 dark:text-sky-400',
            ],
            [
                'label' => 'Selesai',
                'value' => $stats['selesai'],
                'hint' => 'Tugas yang sudah tuntas',
                'icon' => 'check-circle',
                'iconClass' => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/60 dark:text-emerald-400',
            ],
            [
                'label' => 'Terlambat',
                'value' => $stats['terlambat'],
                'hint' => 'Melewati target selesai',
                'icon' => 'alert-triangle',
                'iconClass' => 'bg-rose-50 text-rose-600 dark:bg-rose-950/60 dark:text-rose-400',
            ],
        ];
    @endphp

    <div class="space-y-6">
        <section class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-brand-600 via-emerald-600 to-teal-700 p-6 text-white shadow-soft sm:p-8">
            <div class="pointer-events-none absolute -right-16 -top-16 h-56 w-56 rounded-full border border-white/10"></div>
            <div class="pointer-events-none absolute -bottom-24 right-24 h-48 w-48 rounded-full bg-white/5"></div>

            <div class="relative flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                <div class="max-w-2xl">
                    <p class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-xs font-semibold text-brand-50 ring-1 ring-inset ring-white/15">
                        <span class="h-1.5 w-1.5 rounded-full bg-brand-200"></span>
                        {{ now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                    </p>

                    <h1 class="mt-4 text-2xl font-extrabold leading-tight tracking-tight sm:text-3xl">
                        {{ $greeting }}, {{ $displayName }}.
                    </h1>

                    <p class="mt-2 max-w-xl text-sm leading-relaxed text-brand-50/85">
                        Berikut ringkasan pekerjaan tim hari ini. Pantau deadline, progres, dan penugasan dalam satu tampilan.
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    @can('create', \App\Models\Task::class)
                        <a href="{{ route('tasks.create') }}"
                           class="inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2.5 text-sm font-bold text-brand-700 shadow-sm transition hover:bg-brand-50">
                            <x-icon name="plus" class="h-4 w-4" />
                            Buat tugas
                        </a>
                    @endcan

                    <a href="{{ route('tasks.index') }}"
                       class="inline-flex items-center gap-2 rounded-xl bg-white/10 px-4 py-2.5 text-sm font-semibold text-white ring-1 ring-inset ring-white/20 backdrop-blur transition hover:bg-white/15">
                        <x-icon name="tasks" class="h-4 w-4" />
                        Lihat semua tugas
                    </a>
                </div>
            </div>
        </section>

        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ($cards as $card)
                <article class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-brand-200 hover:shadow-soft dark:border-slate-800 dark:bg-slate-900 dark:hover:border-brand-900">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                {{ $card['label'] }}
                            </p>
                            <p class="mt-2 text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white">
                                {{ $card['value'] }}
                            </p>
                        </div>

                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl {{ $card['iconClass'] }}">
                            <x-icon :name="$card['icon']" class="h-5 w-5" />
                        </span>
                    </div>

                    <p class="mt-3 text-xs text-slate-500 dark:text-slate-400">
                        {{ $card['hint'] }}
                    </p>
                </article>
            @endforeach
        </section>

        <section class="grid gap-6 xl:grid-cols-3">
            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <h2 class="text-base font-extrabold text-slate-900 dark:text-white">
                            Distribusi status
                        </h2>
                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                            Komposisi seluruh tugas
                        </p>
                    </div>

                    <span class="rounded-xl bg-brand-50 px-2.5 py-1 text-xs font-bold text-brand-700 dark:bg-brand-950/60 dark:text-brand-300">
                        {{ $stats['total'] }} tugas
                    </span>
                </div>

                <div class="mt-5 space-y-4">
                    @foreach ($statusRows as $row)
                        @php $percent = $stats['total'] > 0 ? round(($row['count'] / $total) * 100) : 0; @endphp

                        <div>
                            <div class="flex items-center justify-between gap-2 text-xs">
                                <span class="flex items-center gap-2 font-semibold text-slate-600 dark:text-slate-300">
                                    <span class="h-2 w-2 rounded-full {{ $row['bar'] }}"></span>
                                    {{ $row['label'] }}
                                </span>
                                <span class="font-bold text-slate-700 dark:text-slate-200">
                                    {{ $row['count'] }} <span class="font-medium text-slate-400">· {{ $percent }}%</span>
                                </span>
                            </div>

                            <div class="mt-1.5 h-2 overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800">
                                <div class="h-full rounded-full {{ $row['bar'] }} transition-all" style="width: {{ $percent }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6 grid grid-cols-2 gap-3 border-t border-slate-100 pt-4 dark:border-slate-800">
                    <div class="rounded-xl bg-slate-50 p-3 dark:bg-slate-800/60">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Kategori</p>
                        <p class="mt-1 text-lg font-extrabold text-slate-800 dark:text-slate-100">{{ $stats['kategori'] }}</p>
                    </div>
                    <div class="rounded-xl bg-slate-50 p-3 dark:bg-slate-800/60">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Pengguna aktif</p>
                        <p class="mt-1 text-lg font-extrabold text-slate-800 dark:text-slate-100">{{ $stats['pengguna'] }}</p>
                    </div>
                </div>
            </article>

            <article class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900 xl:col-span-2">
                <div class="flex items-center justify-between gap-3 border-b border-slate-100 px-5 py-4 dark:border-slate-800">
                    <div>
                        <h2 class="text-base font-extrabold text-slate-900 dark:text-white">
                            Tugas terbaru
                        </h2>
                        <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">
                            Lima tugas yang terakhir dibuat
                        </p>
                    </div>

                    <a href="{{ route('tasks.index') }}"
                       class="inline-flex items-center gap-1 text-xs font-bold text-brand-600 transition hover:text-brand-800 dark:text-brand-400 dark:hover:text-brand-300">
                        Lihat semua
                        <x-icon name="chevron-right" class="h-3.5 w-3.5" />
                    </a>
                </div>

                @if ($recentTasks->isEmpty())
                    <div class="p-5">
                        <x-empty-state title="Belum ada tugas" message="Tugas yang dibuat akan muncul di sini." icon="tasks" />
                    </div>
                @else
                    <div class="divide-y divide-slate-100 dark:divide-slate-800">
                        @foreach ($recentTasks as $task)
                            <a href="{{ route('tasks.show', $task) }}"
                               class="group flex items-center gap-3 px-5 py-3.5 transition hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-brand-50 text-brand-600 dark:bg-brand-950/60 dark:text-brand-400">
                                    <x-icon name="tasks" class="h-4 w-4" />
                                </span>

                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-sm font-bold text-slate-800 transition group-hover:text-brand-700 dark:text-slate-100 dark:group-hover:text-brand-300">
                                        {{ $task->nama }}
                                    </p>
                                    <p class="mt-0.5 flex flex-wrap items-center gap-x-2 gap-y-0.5 text-xs text-slate-500 dark:text-slate-400">
                                        <span>{{ $task->category?->nama ?? 'Tanpa kategori' }}</span>
                                        <span class="text-slate-300 dark:text-slate-600">·</span>
                                        <span>{{ $task->progress_count }} progress</span>
                                    </p>
                                </div>

                                <div class="hidden shrink-0 text-right sm:block">
                                    <p class="text-xs font-semibold {{ $task->target_selesai->lt(today()) && $task->status !== 'selesai' ? 'text-rose-600 dark:text-rose-400' : 'text-slate-500 dark:text-slate-400' }}">
                                        {{ $task->target_selesai->locale('id')->isoFormat('D MMM YYYY') }}
                                    </p>
                                    <div class="mt-1 flex justify-end">
                                        <x-status-badge :status="$task->status" />
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </article>
        </section>

        <section class="grid gap-6 lg:grid-cols-2">
            <article class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-center justify-between gap-3 border-b border-slate-100 px-5 py-4 dark:border-slate-800">
                    <div>
                        <h2 class="text-base font-extrabold text-slate-900 dark:text-white">
                            Deadline mendatang
                        </h2>
                        <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">
                            Tugas aktif yang paling dekat tenggatnya
                        </p>
                    </div>

                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-amber-50 text-amber-600 dark:bg-amber-950/60 dark:text-amber-400">
                        <x-icon name="calendar" class="h-4 w-4" />
                    </span>
                </div>

                @if ($upcomingTasks->isEmpty())
                    <div class="p-5">
                        <x-empty-state title="Tidak ada deadline mendatang" message="Semua tugas aktif belum memiliki tenggat terdekat." icon="calendar" />
                    </div>
                @else
                    <div class="divide-y divide-slate-100 dark:divide-slate-800">
                        @foreach ($upcomingTasks as $task)
                            @php $daysLeft = (int) today()->diffInDays($task->target_selesai->copy()->startOfDay()); @endphp

                            <a href="{{ route('tasks.show', $task) }}"
                               class="flex items-center gap-3 px-5 py-3.5 transition hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                <div class="flex h-11 w-11 shrink-0 flex-col items-center justify-center rounded-xl bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-400">
                                    <span class="text-sm font-extrabold leading-none">{{ $task->target_selesai->format('d') }}</span>
                                    <span class="mt-0.5 text-[9px] font-bold uppercase leading-none">{{ $task->target_selesai->locale('id')->isoFormat('MMM') }}</span>
                                </div>

                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-sm font-bold text-slate-800 dark:text-slate-100">
                                        {{ $task->nama }}
                                    </p>
                                    <p class="mt-0.5 truncate text-xs text-slate-500 dark:text-slate-400">
                                        {{ $task->penanggungJawab?->nama ?? 'Tanpa penanggung jawab' }}
                                    </p>
                                </div>

                                <div class="shrink-0 text-right">
                                    <x-status-badge :status="$task->status" />
                                    <p class="mt-1 text-[10px] font-semibold text-slate-400 dark:text-slate-500">
                                        {{ $daysLeft <= 0 ? 'Hari ini' : $daysLeft . ' hari lagi' }}
                                    </p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </article>

            <article class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-center justify-between gap-3 border-b border-slate-100 px-5 py-4 dark:border-slate-800">
                    <div>
                        <h2 class="text-base font-extrabold text-slate-900 dark:text-white">
                            Progress terbaru
                        </h2>
                        <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">
                            Aktivitas terakhir dari tim
                        </p>
                    </div>

                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-sky-50 text-sky-600 dark:bg-sky-950/60 dark:text-sky-400">
                        <x-icon name="clock" class="h-4 w-4" />
                    </span>
                </div>

                @if ($recentProgress->isEmpty())
                    <div class="p-5">
                        <x-empty-state title="Belum ada progress" message="Progress yang dikirim tim akan muncul di sini." icon="clock" />
                    </div>
                @else
                    <div class="divide-y divide-slate-100 dark:divide-slate-800">
                        @foreach ($recentProgress as $progress)
                            <div class="flex items-start gap-3 px-5 py-3.5">
                                <x-avatar :name="$progress->user?->nama ?? 'User'" class="h-9 w-9 text-[10px]" />

                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-sm font-bold text-slate-800 dark:text-slate-100">
                                        {{ $progress->nama }}
                                    </p>
                                    <p class="mt-0.5 truncate text-xs text-slate-500 dark:text-slate-400">
                                        {{ $progress->user?->nama ?? 'Pengguna' }}
                                        @if ($progress->task)
                                            · <a href="{{ route('tasks.show', $progress->task) }}" class="font-semibold text-brand-600 hover:underline dark:text-brand-400">{{ $progress->task->nama }}</a>
                                        @endif
                                    </p>
                                </div>

                                <span class="shrink-0 text-[10px] font-semibold text-slate-400 dark:text-slate-500">
                                    {{ $progress->tanggal?->locale('id')->isoFormat('D MMM') }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </article>
        </section>
    </div>
@endsection
