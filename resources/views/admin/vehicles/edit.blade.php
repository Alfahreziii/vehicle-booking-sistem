@extends('layouts.app')

@section('title', 'Edit Kendaraan')
@section('page-title', 'Edit Kendaraan')

@section('content')
    <div class="max-w-4xl mx-auto">

        <div class="mb-5">
            <a href="{{ route('admin.vehicles.show', $vehicle) }}"
                class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-700">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali ke detail
            </a>
            <h2 class="mt-2 text-xl font-semibold text-slate-800">Edit Kendaraan</h2>
            <p class="text-sm text-slate-500 mt-0.5 font-mono">{{ $vehicle->plate_number }}</p>
        </div>

        @if ($errors->any())
            <div class="mb-5 rounded-xl border border-red-200 bg-red-50 p-4">
                <p class="text-sm font-semibold text-red-700 mb-2">Terdapat kesalahan:</p>
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li class="text-sm text-red-600">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.vehicles.update', $vehicle) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            {{-- Identitas --}}
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="flex items-center gap-2 border-b border-slate-100 bg-slate-50 px-5 py-3.5">
                    <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-blue-100">
                        <svg class="h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-slate-700">Identitas Kendaraan</h3>
                </div>
                <div class="p-5 grid grid-cols-2 gap-4">

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Plat Nomor <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="plate_number" value="{{ old('plate_number', $vehicle->plate_number) }}"
                            class="w-full rounded-lg border px-3 py-2.5 text-sm font-mono uppercase tracking-wide focus:outline-none focus:ring-2 focus:ring-blue-100
                                  {{ $errors->has('plate_number') ? 'border-red-400 bg-red-50' : 'border-slate-200 focus:border-blue-400' }}">
                        @error('plate_number')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Warna</label>
                        <input type="text" name="color" value="{{ old('color', $vehicle->color) }}"
                            class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Merk <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="brand" value="{{ old('brand', $vehicle->brand) }}"
                            class="w-full rounded-lg border px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-100
                                  {{ $errors->has('brand') ? 'border-red-400 bg-red-50' : 'border-slate-200 focus:border-blue-400' }}">
                        @error('brand')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Model <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="model" value="{{ old('model', $vehicle->model) }}"
                            class="w-full rounded-lg border px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-100
                                  {{ $errors->has('model') ? 'border-red-400 bg-red-50' : 'border-slate-200 focus:border-blue-400' }}">
                        @error('model')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Tahun <span
                                class="text-red-500">*</span></label>
                        <input type="number" name="year" value="{{ old('year', $vehicle->year) }}" min="2000"
                            max="{{ now()->year }}"
                            class="w-full rounded-lg border px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-100
                                  {{ $errors->has('year') ? 'border-red-400 bg-red-50' : 'border-slate-200 focus:border-blue-400' }}">
                        @error('year')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Jenis <span
                                class="text-red-500">*</span></label>
                        <select name="type"
                            class="w-full rounded-lg border px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-100
                                   {{ $errors->has('type') ? 'border-red-400 bg-red-50' : 'border-slate-200 focus:border-blue-400' }}">
                            <option value="passenger" @selected(old('type', $vehicle->type) === 'passenger')>Angkutan Orang</option>
                            <option value="cargo" @selected(old('type', $vehicle->type) === 'cargo')>Angkutan Barang</option>
                        </select>
                    </div>

                </div>
            </div>

            {{-- Status & Penempatan --}}
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="flex items-center gap-2 border-b border-slate-100 bg-slate-50 px-5 py-3.5">
                    <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-emerald-100">
                        <svg class="h-4 w-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-slate-700">Status & Penempatan</h3>
                </div>
                <div class="p-5 grid grid-cols-2 gap-4" x-data="{ ownership: '{{ old('ownership', $vehicle->ownership) }}' }">

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Status <span
                                class="text-red-500">*</span></label>
                        <select name="status"
                            class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100">
                            <option value="available" @selected(old('status', $vehicle->status) === 'available')>Tersedia</option>
                            <option value="in_use" @selected(old('status', $vehicle->status) === 'in_use')>Digunakan</option>
                            <option value="maintenance" @selected(old('status', $vehicle->status) === 'maintenance')>Perawatan</option>
                            <option value="inactive" @selected(old('status', $vehicle->status) === 'inactive')>Tidak Aktif</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Kepemilikan <span
                                class="text-red-500">*</span></label>
                        <select name="ownership" x-model="ownership"
                            class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100">
                            <option value="owned" @selected(old('ownership', $vehicle->ownership) === 'owned')>Milik Perusahaan</option>
                            <option value="rented" @selected(old('ownership', $vehicle->ownership) === 'rented')>Kendaraan Sewa</option>
                        </select>
                    </div>

                    <div x-show="ownership === 'rented'" x-transition>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Nama Perusahaan Sewaan</label>
                        <input type="text" name="rental_company"
                            value="{{ old('rental_company', $vehicle->rental_company) }}"
                            class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Region <span
                                class="text-red-500">*</span></label>
                        <select name="region_id"
                            class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100">
                            @foreach ($regions as $region)
                                <option value="{{ $region->id }}" @selected(old('region_id', $vehicle->region_id) == $region->id)>
                                    {{ $region->name }} ({{ $region->type_label }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>
            </div>

            {{-- Data Teknis --}}
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="flex items-center gap-2 border-b border-slate-100 bg-slate-50 px-5 py-3.5">
                    <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-amber-100">
                        <svg class="h-4 w-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-slate-700">Data Teknis</h3>
                </div>
                <div class="p-5 grid grid-cols-3 gap-4">

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Odometer Saat Ini (km)</label>
                        <input type="number" name="current_odometer"
                            value="{{ old('current_odometer', $vehicle->current_odometer) }}" min="0"
                            class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Konsumsi BBM (km/liter)</label>
                        <input type="number" name="fuel_consumption"
                            value="{{ old('fuel_consumption', $vehicle->fuel_consumption) }}" min="1"
                            step="0.1"
                            class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Interval Servis (km)</label>
                        <input type="number" name="service_interval_km"
                            value="{{ old('service_interval_km', $vehicle->service_interval_km) }}" min="1000"
                            class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100">
                    </div>

                </div>
            </div>

            {{-- Submit --}}
            <div class="flex gap-3 justify-end">
                <a href="{{ route('admin.vehicles.show', $vehicle) }}"
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
