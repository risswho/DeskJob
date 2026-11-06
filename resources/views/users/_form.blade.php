@php
    $isEdit = isset($user) && $user;
    $isSelf = $isEdit && auth()->id() === $user->id;

    $inputClass = fn (string $field) => 'w-full rounded-xl border bg-white px-3.5 py-2.5 text-sm text-slate-800 shadow-sm outline-none transition placeholder:text-slate-400 focus:ring-4 dark:bg-slate-950 dark:text-white dark:placeholder:text-slate-500 ' .
        ($errors->has($field)
            ? 'border-rose-300 focus:border-rose-400 focus:ring-rose-100 dark:border-rose-800 dark:focus:ring-rose-950/60'
            : 'border-slate-200 focus:border-brand-400 focus:ring-brand-100 dark:border-slate-700 dark:focus:border-brand-600 dark:focus:ring-brand-950/60');
@endphp

<div class="grid gap-6 lg:grid-cols-3">
    <div class="space-y-6 lg:col-span-2">
        @if ($isSelf)
            <div class="flex items-start gap-3 rounded-2xl border border-amber-200 bg-amber-50 p-4 dark:border-amber-900 dark:bg-amber-950/40">
                <x-icon name="alert-triangle" class="mt-0.5 h-5 w-5 shrink-0 text-amber-600 dark:text-amber-400" />
                <div class="text-xs text-amber-800 dark:text-amber-300">
                    <p class="font-bold">Ini akun kamu sendiri.</p>
                    <p class="mt-1 leading-relaxed">
                        Role dan status akun sendiri tidak dapat diturunkan atau dinonaktifkan. Pastikan tetap ada superadmin aktif lain sebelum mengubah akses.
                    </p>
                </div>
            </div>
        @endif

        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="border-b border-slate-100 px-5 py-4 dark:border-slate-800">
                <h2 class="text-base font-extrabold text-slate-900 dark:text-white">
                    Identitas pengguna
                </h2>
                <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">
                    Data ini digunakan untuk login dan identitas pada tugas.
                </p>
            </div>

            <div class="space-y-5 p-5">
                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="nama" class="mb-1.5 block text-sm font-semibold text-slate-700 dark:text-slate-300">
                            Nama lengkap <span class="text-rose-500">*</span>
                        </label>
                        <input type="text"
                               id="nama"
                               name="nama"
                               value="{{ old('nama', $user->nama ?? '') }}"
                               required
                               maxlength="255"
                               autocomplete="name"
                               placeholder="Nama lengkap"
                               class="{{ $inputClass('nama') }}">
                        <x-input-error :name="'nama'" />
                    </div>

                    <div>
                        <label for="nip" class="mb-1.5 block text-sm font-semibold text-slate-700 dark:text-slate-300">
                            NIP <span class="text-rose-500">*</span>
                        </label>
                        <input type="text"
                               id="nip"
                               name="nip"
                               value="{{ old('nip', $user->nip ?? '') }}"
                               required
                               maxlength="255"
                               inputmode="numeric"
                               placeholder="Nomor induk pegawai"
                               class="{{ $inputClass('nip') }}">
                        <x-input-error :name="'nip'" />
                    </div>
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="username" class="mb-1.5 block text-sm font-semibold text-slate-700 dark:text-slate-300">
                            Username <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                                <x-icon name="at" class="h-4 w-4" />
                            </span>
                            <input type="text"
                                   id="username"
                                   name="username"
                                   value="{{ old('username', $user->username ?? '') }}"
                                   required
                                   maxlength="255"
                                   autocomplete="username"
                                   placeholder="username"
                                   class="pl-10 {{ $inputClass('username') }}">
                        </div>
                        <x-input-error :name="'username'" />
                    </div>

                    <div>
                        <label for="jabatan" class="mb-1.5 block text-sm font-semibold text-slate-700 dark:text-slate-300">
                            Jabatan <span class="font-medium text-slate-400">(opsional)</span>
                        </label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                                <x-icon name="briefcase" class="h-4 w-4" />
                            </span>
                            <input type="text"
                                   id="jabatan"
                                   name="jabatan"
                                   value="{{ old('jabatan', $user->jabatan ?? '') }}"
                                   maxlength="255"
                                   placeholder="Contoh: Staff Pelaksana"
                                   class="pl-10 {{ $inputClass('jabatan') }}">
                        </div>
                        <x-input-error :name="'jabatan'" />
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="border-b border-slate-100 px-5 py-4 dark:border-slate-800">
                <h2 class="text-base font-extrabold text-slate-900 dark:text-white">
                    Keamanan akun
                </h2>
                <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">
                    @if ($isEdit)
                        Kosongkan password jika tidak ingin mengubahnya.
                    @else
                        Gunakan minimal 8 karakter yang sulit ditebak.
                    @endif
                </p>
            </div>

            <div class="grid gap-5 p-5 sm:grid-cols-2">
                <div>
                    <label for="password" class="mb-1.5 block text-sm font-semibold text-slate-700 dark:text-slate-300">
                        Password @unless ($isEdit) <span class="text-rose-500">*</span> @endunless
                    </label>

                    <div class="relative">
                        <input type="password"
                               id="password"
                               name="password"
                               @required(! $isEdit)
                               minlength="8"
                               autocomplete="new-password"
                               placeholder="{{ $isEdit ? 'Kosongkan jika tidak diubah' : 'Minimal 8 karakter' }}"
                               class="pr-12 {{ $inputClass('password') }}">

                        <button type="button"
                                data-password-toggle
                                data-target="password"
                                class="absolute inset-y-0 right-0 flex items-center px-3.5 text-slate-400 transition hover:text-slate-600 dark:hover:text-slate-200"
                                aria-label="Tampilkan password">
                            <span data-eye-open><x-icon name="eye" class="h-4 w-4" /></span>
                            <span data-eye-closed class="hidden"><x-icon name="eye-off" class="h-4 w-4" /></span>
                        </button>
                    </div>

                    <x-input-error :name="'password'" />
                </div>

                <div>
                    <label for="password_confirmation" class="mb-1.5 block text-sm font-semibold text-slate-700 dark:text-slate-300">
                        Konfirmasi password @unless ($isEdit) <span class="text-rose-500">*</span> @endunless
                    </label>

                    <div class="relative">
                        <input type="password"
                               id="password_confirmation"
                               name="password_confirmation"
                               @required(! $isEdit)
                               minlength="8"
                               autocomplete="new-password"
                               placeholder="Ulangi password"
                               class="pr-12 {{ $inputClass('password_confirmation') }}">

                        <button type="button"
                                data-password-toggle
                                data-target="password_confirmation"
                                class="absolute inset-y-0 right-0 flex items-center px-3.5 text-slate-400 transition hover:text-slate-600 dark:hover:text-slate-200"
                                aria-label="Tampilkan password">
                            <span data-eye-open><x-icon name="eye" class="h-4 w-4" /></span>
                            <span data-eye-closed class="hidden"><x-icon name="eye-off" class="h-4 w-4" /></span>
                        </button>
                    </div>

                    <x-input-error :name="'password_confirmation'" />
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="border-b border-slate-100 px-5 py-4 dark:border-slate-800">
                <h2 class="text-base font-extrabold text-slate-900 dark:text-white">
                    Akses pengguna
                </h2>
                <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">
                    Atur peran dan apakah akun dapat digunakan untuk login.
                </p>
            </div>

            <div class="grid gap-5 p-5 sm:grid-cols-2">
                <div>
                    <label for="role" class="mb-1.5 block text-sm font-semibold text-slate-700 dark:text-slate-300">
                        Role <span class="text-rose-500">*</span>
                    </label>
                    <select id="role" name="role" required class="{{ $inputClass('role') }}">
                        <option value="viewer" @selected(old('role', $user->role ?? 'viewer') === 'viewer')>Viewer — hanya melihat</option>
                        <option value="staff" @selected(old('role', $user->role ?? 'viewer') === 'staff')>Staff — mengerjakan tugas</option>
                        <option value="superadmin" @selected(old('role', $user->role ?? 'viewer') === 'superadmin')>Superadmin — kelola semua</option>
                    </select>
                    <x-input-error :name="'role'" />
                </div>

                <div>
                    <label for="status" class="mb-1.5 block text-sm font-semibold text-slate-700 dark:text-slate-300">
                        Status <span class="text-rose-500">*</span>
                    </label>
                    <select id="status" name="status" required class="{{ $inputClass('status') }}">
                        <option value="aktif" @selected(old('status', $user->status ?? 'aktif') === 'aktif')>Aktif — dapat login</option>
                        <option value="nonaktif" @selected(old('status', $user->status ?? 'aktif') === 'nonaktif')>Nonaktif — tidak dapat login</option>
                    </select>
                    <x-input-error :name="'status'" />
                </div>
            </div>

            <div class="flex flex-col-reverse gap-2 border-t border-slate-100 bg-slate-50/60 px-5 py-4 dark:border-slate-800 dark:bg-slate-800/30 sm:flex-row sm:items-center sm:justify-end">
                <a href="{{ route('users.index') }}"
                   class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800">
                    Batal
                </a>

                <button type="submit"
                        data-loading-text="Menyimpan..."
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-brand-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-brand-700 focus:outline-none focus:ring-4 focus:ring-brand-200 dark:focus:ring-brand-900">
                    <x-icon name="save" class="h-4 w-4" />
                    {{ $isEdit ? 'Simpan perubahan' : 'Buat pengguna' }}
                </button>
            </div>
        </div>
    </div>

    <aside class="space-y-6">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <h3 class="text-sm font-extrabold text-slate-900 dark:text-white">
                Panduan role
            </h3>

            <div class="mt-4 space-y-3">
                <div class="rounded-xl border border-slate-100 p-3 dark:border-slate-800">
                    <x-role-badge role="superadmin" />
                    <p class="mt-1.5 text-xs leading-relaxed text-slate-500 dark:text-slate-400">
                        Mengelola tugas, kategori, pengguna, penugasan, dan progress.
                    </p>
                </div>

                <div class="rounded-xl border border-slate-100 p-3 dark:border-slate-800">
                    <x-role-badge role="staff" />
                    <p class="mt-1.5 text-xs leading-relaxed text-slate-500 dark:text-slate-400">
                        Melihat tugas dan mengirim progress pada tugas yang ditugaskan.
                    </p>
                </div>

                <div class="rounded-xl border border-slate-100 p-3 dark:border-slate-800">
                    <x-role-badge role="viewer" />
                    <p class="mt-1.5 text-xs leading-relaxed text-slate-500 dark:text-slate-400">
                        Melihat seluruh tugas dan progress tanpa mengubah data.
                    </p>
                </div>
            </div>
        </div>

        @if ($isEdit)
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h3 class="text-sm font-extrabold text-slate-900 dark:text-white">
                    Informasi akun
                </h3>

                <dl class="mt-4 space-y-3 text-xs">
                    <div class="flex items-center justify-between gap-2">
                        <dt class="text-slate-400 dark:text-slate-500">Dibuat</dt>
                        <dd class="font-semibold text-slate-600 dark:text-slate-300">
                            {{ $user->created_at?->locale('id')->isoFormat('D MMM YYYY') }}
                        </dd>
                    </div>
                    <div class="flex items-center justify-between gap-2">
                        <dt class="text-slate-400 dark:text-slate-500">Terakhir diubah</dt>
                        <dd class="font-semibold text-slate-600 dark:text-slate-300">
                            {{ $user->updated_at?->locale('id')->isoFormat('D MMM YYYY') }}
                        </dd>
                    </div>
                    <div class="flex items-center justify-between gap-2">
                        <dt class="text-slate-400 dark:text-slate-500">Status</dt>
                        <dd>
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
                        </dd>
                    </div>
                </dl>
            </div>
        @endif
    </aside>
</div>
