@extends('layouts.app')
@section('title', 'Tambah Driver')
@section('page-title', 'Tambah Driver')

@section('content')
    <div class="max-w-2xl mx-auto">

        <div class="mb-5">
            <a href="{{ route('admin.drivers.index') }}"
                class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-700">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali
            </a>
            <h2 class="mt-2 text-xl font-semibold text-slate-800">Tambah Driver Baru</h2>
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

        <form action="{{ route('admin.drivers.store') }}" method="POST"
            class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                    Pilih User <span class="text-red-500">*</span>
                </label>
                <select name="user_id"
                    class="w-full rounded-lg border px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-100
                           {{ $errors->has('user_id') ? 'border-red-400 bg-red-50' : 'border-slate-200 focus:border-blue-400' }}">
                    <option value="">-- Pilih User --</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" @selected(old('user_id') == $user->id)>
                            {{ $user->name }} ({{ $user->employee_id }}) — {{ $user->region?->name }}
                        </option>
                    @endforeach
                </select>
                @error('user_id')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
                @if ($users->isEmpty())
                    <p class="mt-1 text-xs text-amber-600">Semua user dengan role driver sudah terdaftar sebagai driver.</p>
                @endif
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                        Nomor SIM <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="license_number" value="{{ old('license_number') }}"
                        class="w-full rounded-lg border px-3 py-2.5 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-blue-100
                              {{ $errors->has('license_number') ? 'border-red-400 bg-red-50' : 'border-slate-200 focus:border-blue-400' }}">
                    @error('license_number')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                        Tipe SIM <span class="text-red-500">*</span>
                    </label>
                    <select name="license_type"
                        class="w-full rounded-lg border px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-100
                               {{ $errors->has('license_type') ? 'border-red-400 bg-red-50' : 'border-slate-200 focus:border-blue-400' }}">
                        <option value="">-- Pilih --</option>
                        @foreach (['A', 'B1', 'B2', 'C'] as $type)
                            <option value="{{ $type }}" @selected(old('license_type') === $type)>SIM {{ $type }}
                            </option>
                        @endforeach
                    </select>
                    @error('license_type')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                        Tanggal Kedaluwarsa SIM <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="license_expiry" value="{{ old('license_expiry') }}"
                        min="{{ now()->addDay()->format('Y-m-d') }}"
                        class="w-full rounded-lg border px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-100
                              {{ $errors->has('license_expiry') ? 'border-red-400 bg-red-50' : 'border-slate-200 focus:border-blue-400' }}">
                    @error('license_expiry')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select name="status"
                        class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100">
                        <option value="available" @selected(old('status') === 'available')>Tersedia</option>
                        <option value="on_duty" @selected(old('status') === 'on_duty')>Bertugas</option>
                        <option value="off" @selected(old('status') === 'off')>Cuti/Off</option>
                        <option value="inactive" @selected(old('status') === 'inactive')>Nonaktif</option>
                    </select>
                </div>
            </div>

            <div class="flex gap-3 justify-end pt-2">
                <a href="{{ route('admin.drivers.index') }}"
                    class="rounded-lg border border-slate-200 px-5 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50 transition-colors">
                    Batal
                </a>
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700 transition-colors">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    Simpan Driver
                </button>
            </div>
        </form>
    </div>
@endsection
