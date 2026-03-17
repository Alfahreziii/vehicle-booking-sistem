@extends('layouts.app')

@section('title', 'Laporan Pemesanan')
@section('page-title', 'Laporan Pemesanan')

@section('content')
    <div class="max-w-7xl mx-auto space-y-5">

        {{-- Header --}}
        <div>
            <h2 class="text-xl font-semibold text-slate-800">Laporan Pemesanan Kendaraan</h2>
            <p class="text-sm text-slate-500 mt-0.5">Filter dan export laporan dalam format Excel</p>
        </div>

        {{-- Filter Card --}}
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <h3 class="text-sm font-semibold text-slate-700 mb-4 flex items-center gap-2">
                <svg class="h-4 w-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z" />
                </svg>
                Filter Laporan
            </h3>

            <form method="GET" action="{{ route('admin.reports.index') }}" id="filter-form">
                <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">

                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1.5">Tanggal Mulai</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}"
                            class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-700 focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1.5">Tanggal Akhir</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}"
                            class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-700 focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1.5">Status</label>
                        <select name="status"
                            class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-700 focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100">
                            <option value="">Semua Status</option>
                            @foreach ([
            'pending' => 'Menunggu',
            'in_review' => 'Direview',
            'approved' => 'Disetujui',
            'in_use' => 'Sedang Digunakan',
            'completed' => 'Selesai',
            'rejected' => 'Ditolak',
            'cancelled' => 'Dibatalkan',
        ] as $val => $label)
                                <option value="{{ $val }}" @selected(request('status') === $val)>{{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1.5">Region</label>
                        <select name="region_id"
                            class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-700 focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100">
                            <option value="">Semua Region</option>
                            @foreach ($regions as $region)
                                <option value="{{ $region->id }}" @selected(request('region_id') == $region->id)>
                                    {{ $region->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mt-4 flex items-center gap-2">
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 transition-colors">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Tampilkan
                    </button>
                    <a href="{{ route('admin.reports.index') }}"
                        class="inline-flex items-center gap-2 rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50 transition-colors">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-3 gap-4">
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm text-center">
                <p class="text-2xl font-bold text-slate-800">{{ $summary['total'] }}</p>
                <p class="text-xs text-slate-500 mt-0.5">Total Data</p>
            </div>
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 shadow-sm text-center">
                <p class="text-2xl font-bold text-emerald-700">{{ $summary['completed'] }}</p>
                <p class="text-xs text-emerald-600 mt-0.5">Selesai</p>
            </div>
            <div class="rounded-xl border border-red-200 bg-red-50 p-4 shadow-sm text-center">
                <p class="text-2xl font-bold text-red-700">{{ $summary['rejected'] }}</p>
                <p class="text-xs text-red-600 mt-0.5">Ditolak</p>
            </div>
        </div>

        {{-- Tabel Preview + Export Button --}}
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">

            {{-- Table header --}}
            <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
                <div>
                    <h3 class="text-sm font-semibold text-slate-800">Preview Data</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Menampilkan {{ $bookings->count() }} dari
                        {{ $bookings->total() }} data</p>
                </div>

                {{-- Export Button --}}
                <form method="GET" action="{{ route('admin.reports.export') }}" id="export-form">
                    <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                    <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                    <input type="hidden" name="status" value="{{ request('status') }}">
                    <input type="hidden" name="region_id" value="{{ request('region_id') }}">

                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700 transition-colors">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Export Excel
                        @if ($bookings->total() > 0)
                            <span class="rounded-full bg-emerald-500 px-1.5 py-0.5 text-xs">
                                {{ $bookings->total() }} baris
                            </span>
                        @endif
                    </button>
                </form>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50">
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-400">
                                Kode</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-400">
                                Pemohon</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-400">
                                Kendaraan</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-400">
                                Tujuan</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-400">Tgl
                                Berangkat</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-400">
                                Approval</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-400">
                                Status</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-400">BBM
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($bookings as $booking)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-3 font-mono text-xs text-slate-500">{{ $booking->booking_code }}</td>
                                <td class="px-4 py-3">
                                    <p class="font-medium text-slate-700 text-xs">{{ $booking->requester->name }}</p>
                                    <p class="text-slate-400 text-xs">{{ $booking->requester->region?->name }}</p>
                                </td>
                                <td class="px-4 py-3">
                                    <p class="text-xs font-medium text-slate-700">{{ $booking->vehicle->brand }}
                                        {{ $booking->vehicle->model }}</p>
                                    <p class="text-xs text-slate-400">{{ $booking->vehicle->plate_number }}</p>
                                </td>
                                <td class="px-4 py-3">
                                    <p class="text-xs text-slate-700 max-w-xs truncate"
                                        title="{{ $booking->destination }}">
                                        {{ $booking->destination }}
                                    </p>
                                </td>
                                <td class="px-4 py-3 text-xs text-slate-600">
                                    {{ $booking->departure_at->format('d M Y') }}<br>
                                    <span class="text-slate-400">{{ $booking->departure_at->format('H:i') }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex gap-1">
                                        @foreach ($booking->approvals as $approval)
                                            <span
                                                class="rounded-full px-1.5 py-0.5 text-xs font-semibold
                                    {{ $approval->status === 'approved'
                                        ? 'bg-emerald-100 text-emerald-700'
                                        : ($approval->status === 'rejected'
                                            ? 'bg-red-100 text-red-700'
                                            : 'bg-slate-100 text-slate-500') }}">
                                                L{{ $approval->level }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-4 py-3">{!! $booking->status_badge !!}</td>
                                <td class="px-4 py-3 text-xs text-slate-600">
                                    @if ($booking->fuelLog->count() > 0)
                                        {{ number_format($booking->fuelLog->sum('liters'), 1) }} L<br>
                                        <span class="text-slate-400">
                                            Rp {{ number_format($booking->fuelLog->sum('total_cost'), 0, ',', '.') }}
                                        </span>
                                    @else
                                        <span class="text-slate-300">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-12 text-center">
                                    <svg class="mx-auto h-10 w-10 text-slate-300 mb-3" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="text-sm text-slate-400">Tidak ada data dengan filter yang dipilih</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($bookings->hasPages())
                <div class="border-t border-slate-100 px-4 py-3">
                    {{ $bookings->links() }}
                </div>
            @endif
        </div>

    </div>
@endsection
