@extends('layouts.app')
@section('title', 'Manajemen Driver')
@section('page-title', 'Manajemen Driver')

@section('content')
    <div class="max-w-7xl mx-auto space-y-5">

        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-slate-800">Manajemen Driver</h2>
                <p class="text-sm text-slate-500 mt-0.5">Kelola data pengemudi kendaraan</p>
            </div>
            <a href="{{ route('admin.drivers.create') }}"
                class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 transition-colors">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Driver
            </a>
        </div>

        {{-- Status Cards --}}
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
            @foreach ([['label' => 'Tersedia', 'key' => 'available', 'bg' => 'bg-emerald-50', 'border' => 'border-emerald-200', 'text' => 'text-emerald-700'], ['label' => 'Bertugas', 'key' => 'on_duty', 'bg' => 'bg-amber-50', 'border' => 'border-amber-200', 'text' => 'text-amber-700'], ['label' => 'Cuti/Off', 'key' => 'off', 'bg' => 'bg-blue-50', 'border' => 'border-blue-200', 'text' => 'text-blue-700'], ['label' => 'Nonaktif', 'key' => 'inactive', 'bg' => 'bg-slate-50', 'border' => 'border-slate-200', 'text' => 'text-slate-600']] as $card)
                <div class="rounded-xl border {{ $card['border'] }} {{ $card['bg'] }} p-4 text-center shadow-sm">
                    <p class="text-2xl font-bold {{ $card['text'] }}">{{ $statusCounts[$card['key']] ?? 0 }}</p>
                    <p class="text-xs font-medium mt-0.5 {{ $card['text'] }}">{{ $card['label'] }}</p>
                </div>
            @endforeach
        </div>

        {{-- Filter --}}
        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
            <form method="GET" class="flex flex-wrap gap-3 items-end">
                <div class="flex-1 min-w-40">
                    <label class="block text-xs font-medium text-slate-500 mb-1.5">Cari</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Nama, ID pegawai, No. SIM..."
                        class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-500 mb-1.5">Status</label>
                    <select name="status"
                        class="rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none">
                        <option value="">Semua</option>
                        <option value="available" @selected(request('status') === 'available')>Tersedia</option>
                        <option value="on_duty" @selected(request('status') === 'on_duty')>Bertugas</option>
                        <option value="off" @selected(request('status') === 'off')>Cuti/Off</option>
                        <option value="inactive" @selected(request('status') === 'inactive')>Nonaktif</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-500 mb-1.5">Tipe SIM</label>
                    <select name="license_type"
                        class="rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none">
                        <option value="">Semua</option>
                        @foreach (['A', 'B1', 'B2', 'C'] as $type)
                            <option value="{{ $type }}" @selected(request('license_type') === $type)>SIM {{ $type }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit"
                        class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 transition-colors">Filter</button>
                    <a href="{{ route('admin.drivers.index') }}"
                        class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50 transition-colors">Reset</a>
                </div>
            </form>
        </div>

        {{-- Flash --}}
        <x-flash-message />


        {{-- Table --}}
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50">
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-400">
                                Driver</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-400">ID
                                Pegawai</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-400">No.
                                SIM</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-400">
                                Tipe SIM</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-400">
                                Berlaku Hingga</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-400">
                                Region</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-400">
                                Status</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide text-slate-400">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($drivers as $driver)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-blue-100 text-xs font-bold text-blue-700">
                                            {{ strtoupper(substr($driver->user->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <p class="font-medium text-slate-700">{{ $driver->user->name }}</p>
                                            <p class="text-xs text-slate-400">{{ $driver->user->phone ?? '-' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 font-mono text-xs text-slate-600">{{ $driver->user->employee_id }}
                                </td>
                                <td class="px-4 py-3 font-mono text-xs text-slate-600">{{ $driver->license_number }}</td>
                                <td class="px-4 py-3">
                                    <span
                                        class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-semibold text-blue-700">
                                        SIM {{ $driver->license_type }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <p
                                        class="{{ $driver->isLicenseExpired() ? 'text-red-600 font-semibold' : 'text-slate-600' }} text-sm">
                                        {{ $driver->license_expiry->format('d M Y') }}
                                    </p>
                                    @if ($driver->isLicenseExpired())
                                        <p class="text-xs text-red-500">Expired!</p>
                                    @elseif($driver->license_expiry->diffInDays(now()) <= 30)
                                        <p class="text-xs text-amber-500">Segera expired</p>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-xs text-slate-600">{{ $driver->user->region?->name ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    @php
                                        $statusMap = [
                                            'available' => 'bg-emerald-100 text-emerald-700',
                                            'on_duty' => 'bg-amber-100 text-amber-700',
                                            'off' => 'bg-blue-100 text-blue-700',
                                            'inactive' => 'bg-slate-100 text-slate-500',
                                        ];
                                        $statusLabel = [
                                            'available' => 'Tersedia',
                                            'on_duty' => 'Bertugas',
                                            'off' => 'Cuti/Off',
                                            'inactive' => 'Nonaktif',
                                        ];
                                    @endphp
                                    <span
                                        class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $statusMap[$driver->status] ?? 'bg-slate-100 text-slate-500' }}">
                                        {{ $statusLabel[$driver->status] ?? $driver->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <a href="{{ route('admin.drivers.show', $driver) }}"
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
                                        <a href="{{ route('admin.drivers.edit', $driver) }}"
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
                                <td colspan="8" class="px-4 py-12 text-center text-sm text-slate-400">Belum ada data
                                    driver</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($drivers->hasPages())
                <div class="border-t border-slate-100 px-4 py-3">{{ $drivers->links() }}</div>
            @endif
        </div>
    </div>
@endsection
