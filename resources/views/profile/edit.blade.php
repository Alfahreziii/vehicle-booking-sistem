@extends('layouts.app')

@section('title', 'Profil Saya')
@section('page-title', 'Profil Saya')

@section('content')
    <div class="max-w-3xl mx-auto space-y-5">

        <div>
            <h2 class="text-xl font-semibold text-slate-800">Profil Saya</h2>
            <p class="text-sm text-slate-500 mt-0.5">Kelola informasi akun dan keamanan Anda</p>
        </div>

        {{-- Flash status --}}
        @if (session('status') === 'profile-updated')
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                class="flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                <svg class="h-5 w-5 shrink-0 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Profil berhasil diperbarui.
            </div>
        @endif

        @if (session('status') === 'password-updated')
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                class="flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                <svg class="h-5 w-5 shrink-0 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Password berhasil diperbarui.
            </div>
        @endif

        {{-- ── Info Akun ── --}}
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
            <div class="flex items-center gap-2 border-b border-slate-100 bg-slate-50 px-5 py-3.5">
                <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-blue-100">
                    <svg class="h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <h3 class="text-sm font-semibold text-slate-700">Informasi Profil</h3>
            </div>

            <div class="p-5">

                {{-- Avatar --}}
                <div class="flex items-center gap-4 mb-6 pb-6 border-b border-slate-100">
                    <div
                        class="flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-600 text-2xl font-bold text-white">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                    <div>
                        <p class="font-semibold text-slate-800">{{ $user->name }}</p>
                        <p class="text-sm text-slate-500">{{ $user->email }}</p>
                        <div class="flex items-center gap-2 mt-1.5">
                            @foreach ($user->roles as $role)
                                @php
                                    $c = match ($role->name) {
                                        'admin' => 'bg-blue-100 text-blue-700',
                                        'approver' => 'bg-amber-100 text-amber-700',
                                        'driver' => 'bg-emerald-100 text-emerald-700',
                                        default => 'bg-slate-100 text-slate-600',
                                    };
                                @endphp
                                <span
                                    class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $c }}">
                                    {{ ucfirst($role->name) }}
                                </span>
                            @endforeach

                            <span class="text-xs text-slate-400">
                                · {{ $user->region?->name ?? 'Semua Region' }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Info readonly --}}
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400 mb-1">ID Pegawai</p>
                        <p class="text-sm font-mono font-medium text-slate-700">{{ $user->employee_id ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400 mb-1">Departemen</p>
                        <p class="text-sm text-slate-700">{{ $user->department?->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400 mb-1">Telepon</p>
                        <p class="text-sm text-slate-700">{{ $user->phone ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400 mb-1">Bergabung sejak</p>
                        <p class="text-sm text-slate-700">{{ $user->created_at->format('d M Y') }}</p>
                    </div>
                </div>

                {{-- Form update profile --}}
                <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
                    @csrf
                    @method('patch')

                    <div>
                        <label for="name" class="block text-xs font-semibold text-slate-600 mb-1.5">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                            autocomplete="name"
                            class="w-full rounded-lg border px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-100
                                  {{ $errors->get('name') ? 'border-red-400 bg-red-50' : 'border-slate-200 focus:border-blue-400' }}">
                        @error('name')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-semibold text-slate-600 mb-1.5">
                            Alamat Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                            required autocomplete="username"
                            class="w-full rounded-lg border px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-100
                                  {{ $errors->get('email') ? 'border-red-400 bg-red-50' : 'border-slate-200 focus:border-blue-400' }}">
                        @error('email')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror

                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit"
                            class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700 transition-colors">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ── Ganti Password ── --}}
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
            <div class="flex items-center gap-2 border-b border-slate-100 bg-slate-50 px-5 py-3.5">
                <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-amber-100">
                    <svg class="h-4 w-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <h3 class="text-sm font-semibold text-slate-700">Ganti Password</h3>
            </div>

            <div class="p-5">
                <form method="POST" action="{{ route('password.update') }}" class="space-y-4" x-data="{ showCurrent: false, showNew: false, showConfirm: false }">
                    @csrf
                    @method('put')

                    {{-- Password saat ini --}}
                    <div>
                        <label for="current_password" class="block text-xs font-semibold text-slate-600 mb-1.5">
                            Password Saat Ini <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input :type="showCurrent ? 'text' : 'password'" id="current_password" name="current_password"
                                autocomplete="current-password" placeholder="••••••••"
                                class="w-full rounded-lg border pr-10 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-100
                                      {{ $errors->updatePassword->get('current_password') ? 'border-red-400 bg-red-50' : 'border-slate-200 focus:border-blue-400' }}">
                            <button type="button" @click="showCurrent = !showCurrent"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                        @error('current_password', 'updatePassword')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password baru --}}
                    <div>
                        <label for="password" class="block text-xs font-semibold text-slate-600 mb-1.5">
                            Password Baru <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input :type="showNew ? 'text' : 'password'" id="password" name="password"
                                autocomplete="new-password" placeholder="Minimal 8 karakter"
                                class="w-full rounded-lg border pr-10 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-100
                                      {{ $errors->updatePassword->get('password') ? 'border-red-400 bg-red-50' : 'border-slate-200 focus:border-blue-400' }}">
                            <button type="button" @click="showNew = !showNew"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                        @error('password', 'updatePassword')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Konfirmasi password --}}
                    <div>
                        <label for="password_confirmation" class="block text-xs font-semibold text-slate-600 mb-1.5">
                            Konfirmasi Password Baru <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input :type="showConfirm ? 'text' : 'password'" id="password_confirmation"
                                name="password_confirmation" autocomplete="new-password"
                                placeholder="Ulangi password baru"
                                class="w-full rounded-lg border border-slate-200 pr-10 px-3 py-2.5 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100">
                            <button type="button" @click="showConfirm = !showConfirm"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                        @error('password_confirmation', 'updatePassword')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-2">
                        <button type="submit"
                            class="inline-flex items-center gap-2 rounded-lg bg-amber-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-amber-700 transition-colors">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            Perbarui Password
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ── Hapus Akun ── --}}
        <div class="rounded-xl border border-red-200 bg-white shadow-sm overflow-hidden" x-data="{ confirmOpen: false }">
            <div class="flex items-center gap-2 border-b border-red-100 bg-red-50 px-5 py-3.5">
                <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-red-100">
                    <svg class="h-4 w-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </div>
                <h3 class="text-sm font-semibold text-red-700">Hapus Akun</h3>
            </div>

            <div class="p-5">
                <p class="text-sm text-slate-600 mb-4">
                    Setelah akun dihapus, semua data akan dihapus secara permanen.
                    Pastikan Anda sudah mengunduh data yang diperlukan sebelum melanjutkan.
                </p>

                <button type="button" @click="confirmOpen = true"
                    class="inline-flex items-center gap-2 rounded-lg border border-red-300 bg-red-50 px-4 py-2.5 text-sm font-semibold text-red-600 hover:bg-red-600 hover:text-white transition-colors">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Hapus Akun Saya
                </button>

                {{-- Confirm modal --}}
                <div x-show="confirmOpen" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4" style="display: none;">
                    <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl" @click.outside="confirmOpen = false">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-red-100">
                                <svg class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-slate-800">Konfirmasi Hapus Akun</h3>
                                <p class="text-xs text-slate-500 mt-0.5">Tindakan ini tidak dapat dibatalkan</p>
                            </div>
                        </div>

                        <p class="text-sm text-slate-600 mb-4">
                            Masukkan password Anda untuk mengkonfirmasi penghapusan akun.
                        </p>

                        <form method="POST" action="{{ route('profile.destroy') }}" class="space-y-3">
                            @csrf
                            @method('delete')

                            <div>
                                <label for="delete_password" class="block text-xs font-semibold text-slate-600 mb-1.5">
                                    Password <span class="text-red-500">*</span>
                                </label>
                                <input type="password" id="delete_password" name="password"
                                    placeholder="Masukkan password Anda"
                                    class="w-full rounded-lg border px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-100
                                          {{ $errors->userDeletion->get('password') ? 'border-red-400 bg-red-50' : 'border-slate-200 focus:border-red-400' }}">
                                @error('password', 'userDeletion')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex gap-2 justify-end pt-1">
                                <button type="button" @click="confirmOpen = false"
                                    class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50 transition-colors">
                                    Batal
                                </button>
                                <button type="submit"
                                    class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700 transition-colors">
                                    Ya, Hapus Akun
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
