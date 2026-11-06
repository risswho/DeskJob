<!DOCTYPE html>
<html lang="id" class="h-full scroll-smooth">
<head>
    @include('partials.head')
</head>
<body class="min-h-full bg-slate-50 font-sans text-slate-800 antialiased dark:bg-slate-950 dark:text-slate-100">
    <a href="#main-content"
       class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-[100] focus:rounded-xl focus:bg-brand-600 focus:px-4 focus:py-2 focus:text-sm focus:font-semibold focus:text-white">
        Lewati ke konten
    </a>

    @php
        $user = auth()->user();
        $isSuperadmin = $user?->role === 'superadmin';

        $navItems = [
            [
                'label' => 'Dashboard',
                'route' => 'dashboard',
                'active' => request()->routeIs('dashboard'),
                'icon' => 'dashboard',
            ],
            [
                'label' => 'Tugas',
                'route' => 'tasks.index',
                'active' => request()->routeIs('tasks.*'),
                'icon' => 'tasks',
            ],
        ];

        if ($isSuperadmin) {
            $navItems[] = [
                'label' => 'Kategori',
                'route' => 'categories.index',
                'active' => request()->routeIs('categories.*'),
                'icon' => 'category',
            ];
            $navItems[] = [
                'label' => 'Pengguna',
                'route' => 'users.index',
                'active' => request()->routeIs('users.*'),
                'icon' => 'users',
            ];
        }
    @endphp

    <div class="min-h-screen lg:flex">
        <aside id="app-sidebar"
               class="fixed inset-y-0 left-0 z-50 flex w-72 -translate-x-full flex-col border-r border-slate-200 bg-white transition-transform duration-300 ease-out dark:border-slate-800 dark:bg-slate-900 lg:sticky lg:top-0 lg:h-screen lg:translate-x-0">
            <div class="flex items-center justify-between gap-3 px-5 py-5">
                <a href="{{ route('dashboard') }}" class="group flex items-center gap-3">
                    <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-brand-500 to-teal-600 text-white shadow-soft transition group-hover:scale-105">
                        <x-icon name="check" class="h-6 w-6" />
                    </span>

                    <span class="min-w-0">
                        <span class="block text-lg font-extrabold tracking-tight text-slate-900 dark:text-white">
                            JobDesk
                        </span>
                        <span class="block text-[11px] font-semibold uppercase tracking-wider text-brand-600 dark:text-brand-400">
                            Manajemen Tugas
                        </span>
                    </span>
                </a>

                <button type="button"
                        data-sidebar-toggle
                        class="rounded-xl p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700 dark:hover:bg-slate-800 dark:hover:text-slate-200 lg:hidden"
                        aria-label="Tutup menu">
                    <x-icon name="x" class="h-5 w-5" />
                </button>
            </div>

            <nav class="flex-1 space-y-1 overflow-y-auto px-3 pb-4">
                <p class="px-3 pb-2 pt-2 text-[10px] font-bold uppercase tracking-[0.18em] text-slate-400 dark:text-slate-500">
                    Menu utama
                </p>

                @foreach ($navItems as $item)
                    <a href="{{ route($item['route']) }}"
                       @class([
                           'group flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-semibold transition',
                           'bg-brand-50 text-brand-700 shadow-sm ring-1 ring-brand-100 dark:bg-brand-950/60 dark:text-brand-300 dark:ring-brand-900' => $item['active'],
                           'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white' => ! $item['active'],
                       ])
                       @if ($item['active']) aria-current="page" @endif>
                        <span @class([
                            'flex h-8 w-8 items-center justify-center rounded-lg transition',
                            'bg-brand-600 text-white shadow-sm' => $item['active'],
                            'bg-slate-100 text-slate-500 group-hover:bg-white group-hover:text-brand-600 dark:bg-slate-800 dark:text-slate-400 dark:group-hover:bg-slate-700 dark:group-hover:text-brand-300' => ! $item['active'],
                        ])>
                            <x-icon :name="$item['icon']" class="h-4 w-4" />
                        </span>

                        {{ $item['label'] }}

                        @if ($item['active'])
                            <span class="ml-auto h-1.5 w-1.5 rounded-full bg-brand-500"></span>
                        @endif
                    </a>
                @endforeach

                @unless ($isSuperadmin)
                    <div class="mt-6 rounded-2xl border border-brand-100 bg-brand-50/70 p-4 dark:border-brand-900 dark:bg-brand-950/40">
                        <p class="flex items-center gap-2 text-xs font-bold text-brand-800 dark:text-brand-300">
                            <x-icon name="check-circle" class="h-4 w-4" />
                            Akses {{ $user?->role === 'staff' ? 'Staff' : 'Viewer' }}
                        </p>
                        <p class="mt-1.5 text-xs leading-relaxed text-brand-700/80 dark:text-brand-400/80">
                            @if ($user?->role === 'staff')
                                Kamu bisa melihat tugas dan menambahkan progress pada tugas yang ditugaskan.
                            @else
                                Kamu bisa melihat seluruh tugas dan progress. Mode hanya baca.
                            @endif
                        </p>
                    </div>
                @endunless
            </nav>

            <div class="border-t border-slate-100 p-4 dark:border-slate-800">
                <div class="flex items-center gap-3 rounded-2xl bg-slate-50 p-3 dark:bg-slate-800/60">
                    <x-avatar :name="$user?->nama ?? 'User'" class="h-10 w-10 text-sm" />

                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-bold text-slate-800 dark:text-slate-100">
                            {{ $user?->nama }}
                        </p>
                        <p class="truncate text-xs text-slate-500 dark:text-slate-400">
                            {{ $user?->jabatan ?: '@' . $user?->username }}
                        </p>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="rounded-xl p-2 text-slate-400 transition hover:bg-white hover:text-rose-600 dark:hover:bg-slate-700 dark:hover:text-rose-400"
                                title="Keluar"
                                aria-label="Keluar">
                            <x-icon name="logout" class="h-5 w-5" />
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <div id="sidebar-overlay"
             class="fixed inset-0 z-40 hidden bg-slate-950/50 backdrop-blur-sm lg:hidden"
             data-sidebar-close></div>

        <div class="flex min-w-0 flex-1 flex-col">
            <header class="sticky top-0 z-30 border-b border-slate-200/80 bg-white/85 backdrop-blur-lg dark:border-slate-800 dark:bg-slate-900/85">
                <div class="mx-auto flex h-16 w-full max-w-7xl items-center gap-3 px-4 sm:px-6 lg:px-8">
                    <button type="button"
                            data-sidebar-toggle
                            class="rounded-xl p-2 text-slate-500 transition hover:bg-slate-100 hover:text-slate-800 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white lg:hidden"
                            aria-label="Buka menu">
                        <x-icon name="menu" class="h-5 w-5" />
                    </button>

                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-bold text-slate-800 dark:text-slate-100">
                            @yield('title', 'Dashboard')
                        </p>
                        <p class="hidden text-xs text-slate-500 dark:text-slate-400 sm:block">
                            {{ now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                        </p>
                    </div>

                    <button type="button"
                            data-theme-toggle
                            class="rounded-xl border border-slate-200 bg-white p-2.5 text-slate-500 transition hover:border-brand-300 hover:text-brand-600 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300 dark:hover:border-brand-800 dark:hover:text-brand-400"
                            aria-label="Ganti tema">
                        <x-icon name="moon" class="h-5 w-5 dark:hidden" />
                        <x-icon name="sun" class="hidden h-5 w-5 dark:block" />
                    </button>

                    <div class="relative">
                        <button type="button"
                                data-user-menu-toggle
                                class="flex items-center gap-2 rounded-xl border border-transparent p-1.5 pr-2 transition hover:border-slate-200 hover:bg-slate-50 dark:hover:border-slate-700 dark:hover:bg-slate-800"
                                aria-haspopup="true"
                                aria-expanded="false">
                            <x-avatar :name="$user?->nama ?? 'User'" class="h-8 w-8 text-[11px]" />
                            <span class="hidden text-left sm:block">
                                <span class="block max-w-[10rem] truncate text-xs font-bold text-slate-800 dark:text-slate-100">
                                    {{ $user?->nama }}
                                </span>
                                <span class="block text-[10px] font-semibold uppercase tracking-wide text-brand-600 dark:text-brand-400">
                                    {{ $user?->role }}
                                </span>
                            </span>
                            <x-icon name="chevron-down" class="hidden h-4 w-4 text-slate-400 sm:block" />
                        </button>

                        <div id="user-menu"
                             class="absolute right-0 mt-2 hidden w-64 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xl dark:border-slate-700 dark:bg-slate-900">
                            <div class="border-b border-slate-100 p-4 dark:border-slate-800">
                                <div class="flex items-center gap-3">
                                    <x-avatar :name="$user?->nama ?? 'User'" class="h-10 w-10 text-sm" />
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-bold text-slate-800 dark:text-slate-100">
                                            {{ $user?->nama }}
                                        </p>
                                        <p class="truncate text-xs text-slate-500 dark:text-slate-400">
                                            &#64;{{ $user?->username }}
                                        </p>
                                    </div>
                                </div>

                                <div class="mt-3 flex items-center gap-2">
                                    <x-role-badge :role="$user?->role" />
                                    <span class="text-xs text-slate-400 dark:text-slate-500">
                                        NIP {{ $user?->nip }}
                                    </span>
                                </div>
                            </div>

                            <form method="POST" action="{{ route('logout') }}" class="p-2">
                                @csrf
                                <button type="submit"
                                        class="flex w-full items-center gap-2 rounded-xl px-3 py-2.5 text-sm font-semibold text-rose-600 transition hover:bg-rose-50 dark:text-rose-400 dark:hover:bg-rose-950/40">
                                    <x-icon name="logout" class="h-4 w-4" />
                                    Keluar dari akun
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <main id="main-content" class="mx-auto w-full max-w-7xl flex-1 px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
                @yield('content')
            </main>

            <footer class="border-t border-slate-200/80 bg-white/60 py-4 dark:border-slate-800 dark:bg-slate-900/40">
                <div class="mx-auto flex w-full max-w-7xl flex-col gap-1 px-4 text-xs text-slate-500 dark:text-slate-400 sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">
                    <p>&copy; {{ now()->year }} JobDesk. Manajemen tugas yang lebih rapi.</p>
                    <p class="flex items-center gap-1.5">
                        <span class="h-1.5 w-1.5 rounded-full bg-brand-500"></span>
                        {{ $user?->status === 'aktif' ? 'Akun aktif' : 'Akun nonaktif' }}
                    </p>
                </div>
            </footer>
        </div>
    </div>

    @include('partials.loader')
    @include('partials.confirm-modal')
    @include('partials.flash')

    <script src="{{ asset('js/jobdesk.js') }}" defer></script>
    @stack('scripts')
</body>
</html>
