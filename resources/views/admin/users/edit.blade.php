@extends('layouts.app')
@section('title', 'Edit User')
@section('page-title', 'Edit User')

@section('content')
    <div class="max-w-2xl mx-auto">

        <div class="mb-5">
            <a href="{{ route('admin.users.index') }}"
                class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-700">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali
            </a>
            <h2 class="mt-2 text-xl font-semibold text-slate-800">Edit Pengguna</h2>
            <p class="text-sm text-slate-500 mt-0.5">{{ $user->name }}</p>
        </div>

        @if ($errors->any())
            <div class="mb-5 rounded-xl border border-red-200 bg-red-50 p-4">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li class="text-sm text-red-600">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.users.update', $user) }}" method="POST"
            class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-2 gap-4">

                <div class="col-span-2">
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Nama Lengkap <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                        class="w-full rounded-lg border px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-100
                              {{ $errors->has('name') ? 'border-red-400 bg-red-50' : 'border-slate-200 focus:border-blue-400' }}">
                    @error('name')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Email <span
                            class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                        class="w-full rounded-lg border px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-100
                              {{ $errors->has('email') ? 'border-red-400 bg-red-50' : 'border-slate-200 focus:border-blue-400' }}">
                    @error('email')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">ID Pegawai</label>
                    <input type="text" name="employee_id" value="{{ old('employee_id', $user->employee_id) }}"
                        class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm font-mono focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                        Password Baru
                        <span class="font-normal text-slate-400">(kosongkan jika tidak diubah)</span>
                    </label>
                    <input type="password" name="password"
                        class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100">
                    @error('password')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation"
                        class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">No. Telepon</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                        class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100">
                </div>

                <div class="col-span-2">
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Role <span
                            class="text-red-500">*</span></label>
                    <div class="flex flex-wrap gap-3">
                        @foreach (\Spatie\Permission\Models\Role::orderBy('name')->get() as $role)
                            @php
                                $roleColor = match ($role->name) {
                                    'admin'
                                        => 'peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-700',
                                    'approver'
                                        => 'peer-checked:border-amber-500 peer-checked:bg-amber-50 peer-checked:text-amber-700',
                                    'driver'
                                        => 'peer-checked:border-emerald-500 peer-checked:bg-emerald-50 peer-checked:text-emerald-700',
                                    default
                                        => 'peer-checked:border-slate-500 peer-checked:bg-slate-50 peer-checked:text-slate-700',
                                };
                            @endphp
                            <label class="cursor-pointer">
                                <input type="radio" name="role" value="{{ $role->name }}" class="peer sr-only"
                                    @checked(old('role', $user->getRoleNames()->first()) === $role->name)>
                                <div
                                    class="rounded-lg border-2 border-slate-200 px-4 py-2 text-sm font-medium text-slate-600 transition-all {{ $roleColor }}">
                                    {{ ucfirst($role->name) }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Region</label>
                    <select name="region_id"
                        class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100">
                        <option value="">-- Pilih Region --</option>
                        @foreach ($regions as $region)
                            <option value="{{ $region->id }}" @selected(old('region_id', $user->region_id) == $region->id)>
                                {{ $region->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Departemen</label>
                    <select name="department_id"
                        class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100">
                        <option value="">-- Pilih Departemen --</option>
                        @foreach ($departments as $dept)
                            <option value="{{ $dept->id }}" @selected(old('department_id', $user->department_id) == $dept->id)>
                                {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-span-2">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $user->is_active))
                            class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                        <span class="text-sm font-medium text-slate-700">User Aktif</span>
                    </label>
                </div>

            </div>

            <div class="flex gap-3 justify-end pt-2">
                <a href="{{ route('admin.users.index') }}"
                    class="rounded-lg border border-slate-200 px-5 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50 transition-colors">
                    Batal
                </a>
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700 transition-colors">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
@endsection
