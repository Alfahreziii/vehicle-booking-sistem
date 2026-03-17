@extends('layouts.app')
@section('title', 'Edit Driver')
@section('page-title', 'Edit Driver')

@section('content')
    <div class="max-w-2xl mx-auto">

        <div class="mb-5">
            <a href="{{ route('admin.drivers.show', $driver) }}"
                class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-700">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali ke detail
            </a>
            <h2 class="mt-2 text-xl font-semibold text-slate-800">Edit Driver</h2>
            <p class="text-sm text-slate-500 mt-0.5">{{ $driver->user->name }}</p>
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

        <form action="{{ route('admin.drivers.update', $driver) }}" method="POST"
            class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm space-y-4">
            @csrf
            @method('PUT')

            {{-- Info user (read only) --}}
            <div class="rounded-lg bg-slate-50 border border-slate-100 p-4">
                <div class="flex items-center gap-3">
                    <div
                        class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 text-sm font-bold text-blue-700">
                        {{ strtoupper(substr($driver->user->name, 0, 2)) }}
                    </div>
                    <div>
                        <p class="font-semibold text-slate-800">{{ $driver->user->name }}</p>
                        <p class="text-xs text-slate-500">{{ $driver->user->employee_id }} ·
                            {{ $driver->user->region?->name }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                        Nomor SIM <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="license_number" value="{{ old('license_number', $driver->license_number) }}"
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
                        class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100">
                        @foreach (['A', 'B1', 'B2', 'C'] as $type)
                            <option value="{{ $type }}" @selected(old('license_type', $driver->license_type) === $type)>
                                SIM {{ $type }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                        Tanggal Kedaluwarsa SIM <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="license_expiry"
                        value="{{ old('license_expiry', $driver->license_expiry->format('Y-m-d')) }}"
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
                        @foreach (['available' => 'Tersedia', 'on_duty' => 'Bertugas', 'off' => 'Cuti/Off', 'inactive' => 'Nonaktif'] as $val => $label)
                            <option value="{{ $val }}" @selected(old('status', $driver->status) === $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex gap-3 justify-end pt-2">
                <a href="{{ route('admin.drivers.show', $driver) }}"
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
