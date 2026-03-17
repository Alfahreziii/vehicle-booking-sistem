@extends('layouts.app')
@section('title', 'Kendaraan')
@section('page-title', 'Manajemen Kendaraan')
@section('content')
    <div class="max-w-7xl mx-auto">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-5">
            <div>
                <h2 class="text-xl font-semibold text-slate-800">Manajemen Kendaraan</h2>
                <p class="text-sm text-slate-500 mt-0.5">Kelola semua armada kendaraan perusahaan</p>
            </div>
            <a href="{{ route('admin.vehicles.create') }}"
                class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 transition-colors">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Kendaraan
            </a>
        </div>

        {{-- Status Cards --}}
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-4 mb-5">
            @foreach ([['label' => 'Tersedia', 'key' => 'available', 'color' => 'emerald'], ['label' => 'Digunakan', 'key' => 'in_use', 'color' => 'amber'], ['label' => 'Perawatan', 'key' => 'maintenance', 'color' => 'red'], ['label' => 'Tidak Aktif', 'key' => 'inactive', 'color' => 'slate']] as $card)
                @php
                    $colorMap = [
                        'emerald' => 'bg-emerald-50 border-emerald-200 text-emerald-700',
                        'amber' => 'bg-amber-50 border-amber-200 text-amber-700',
                        'red' => 'bg-red-50 border-red-200 text-red-700',
                        'slate' => 'bg-slate-50 border-slate-200 text-slate-600',
                    ];
                @endphp
                <div class="rounded-xl border {{ $colorMap[$card['color']] }} p-4 text-center shadow-sm">
                    <p class="text-2xl font-bold">{{ $statusCounts[$card['key']] ?? 0 }}</p>
                    <p class="text-xs font-medium mt-0.5">{{ $card['label'] }}</p>
                </div>
            @endforeach
        </div>

        {{-- Filter --}}
        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm mb-5">
            <form method="GET" class="flex flex-wrap gap-3 items-end">
                <div class="flex-1 min-w-40">
                    <label class="block text-xs font-medium text-slate-500 mb-1.5">Cari</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Plat, merk, model..."
                        class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-500 mb-1.5">Status</label>
                    <select name="status"
                        class="rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none">
                        <option value="">Semua</option>
                        <option value="available" @selected(request('status') === 'available')>Tersedia</option>
                        <option value="in_use" @selected(request('status') === 'in_use')>Digunakan</option>
                        <option value="maintenance" @selected(request('status') === 'maintenance')>Perawatan</option>
                        <option value="inactive" @selected(request('status') === 'inactive')>Tidak Aktif</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-500 mb-1.5">Jenis</label>
                    <select name="type"
                        class="rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none">
                        <option value="">Semua</option>
                        <option value="passenger" @selected(request('type') === 'passenger')>Angkutan Orang</option>
                        <option value="cargo" @selected(request('type') === 'cargo')>Angkutan Barang</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-500 mb-1.5">Region</label>
                    <select name="region_id"
                        class="rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none">
                        <option value="">Semua</option>
                        @foreach ($regions as $region)
                            <option value="{{ $region->id }}" @selected(request('region_id') == $region->id)>{{ $region->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit"
                        class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 transition-colors">Filter</button>
                    <a href="{{ route('admin.vehicles.index') }}"
                        class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50 transition-colors">Reset</a>
                </div>
            </form>
        </div>

        {{-- Table --}}
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50">
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-400">
                                Kendaraan</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-400">
                                Plat Nomor</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-400">
                                Jenis</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-400">
                                Kepemilikan</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-400">
                                Region</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-400">
                                Odometer</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-400">
                                Status</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide text-slate-400">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($vehicles as $vehicle)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-3">
                                    <p class="font-medium text-slate-700">{{ $vehicle->brand }} {{ $vehicle->model }}</p>
                                    <p class="text-xs text-slate-400">{{ $vehicle->year }} · {{ $vehicle->color }}</p>
                                </td>
                                <td class="px-4 py-3 font-mono text-sm font-semibold text-slate-600">
                                    {{ $vehicle->plate_number }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $vehicle->type_label }}</td>
                                <td class="px-4 py-3">
                                    <span
                                        class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold
                                {{ $vehicle->ownership === 'owned' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                                        {{ $vehicle->ownership_label }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-slate-600 text-xs">{{ $vehicle->region->name }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ number_format($vehicle->current_odometer) }} km
                                </td>
                                <td class="px-4 py-3">{!! $vehicle->status_badge !!}</td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <a href="{{ route('admin.vehicles.show', $vehicle) }}"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 text-slate-500 hover:bg-blue-50 hover:border-blue-200 hover:text-blue-600 transition-colors"
                                            title="Detail">
                                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        <a href="{{ route('admin.vehicles.edit', $vehicle) }}"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 text-slate-500 hover:bg-amber-50 hover:border-amber-200 hover:text-amber-600 transition-colors"
                                            title="Edit">
                                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-12 text-center text-sm text-slate-400">
                                    Belum ada data kendaraan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($vehicles->hasPages())
                <div class="border-t border-slate-100 px-4 py-3">
                    {{ $vehicles->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
