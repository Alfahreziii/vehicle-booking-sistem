@extends('layouts.app')

@section('title', 'Penugasan Saya')
@section('page-title', 'Penugasan Saya')

@section('content')
    <div class="max-w-5xl mx-auto space-y-5">

        {{-- Header --}}
        <div>
            <h2 class="text-xl font-semibold text-slate-800">Penugasan Saya</h2>
            <p class="text-sm text-slate-500 mt-0.5">Daftar semua penugasan mengemudi Anda</p>
        </div>

        {{-- Info Driver --}}
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center gap-4">
                <div
                    class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100 text-lg font-bold text-blue-700">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-slate-800">{{ auth()->user()->name }}</p>
                    <p class="text-sm text-slate-500 mt-0.5">
                        SIM {{ $driver->license_type }} · {{ $driver->license_number }}
                        · Berlaku s.d. {{ $driver->license_expiry->format('d M Y') }}
                    </p>
                </div>
                <div class="hidden sm:flex gap-6 text-center">
                    <div>
                        <p class="text-xl font-bold text-slate-800">{{ $bookings->total() }}</p>
                        <p class="text-xs text-slate-400 mt-0.5">Total Penugasan</p>
                    </div>
                    <div>
                        <p class="text-xl font-bold text-emerald-700">{{ $statusCounts['completed'] ?? 0 }}</p>
                        <p class="text-xs text-slate-400 mt-0.5">Selesai</p>
                    </div>
                    <div>
                        <p class="text-xl font-bold text-amber-600">
                            {{ ($statusCounts['approved'] ?? 0) + ($statusCounts['in_use'] ?? 0) }}</p>
                        <p class="text-xs text-slate-400 mt-0.5">Aktif</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Status Cards --}}
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
            @foreach ([['label' => 'Menunggu', 'key' => 'in_review', 'bg' => 'bg-amber-50', 'border' => 'border-amber-200', 'text' => 'text-amber-700'], ['label' => 'Disetujui', 'key' => 'approved', 'bg' => 'bg-blue-50', 'border' => 'border-blue-200', 'text' => 'text-blue-700'], ['label' => 'Selesai', 'key' => 'completed', 'bg' => 'bg-emerald-50', 'border' => 'border-emerald-200', 'text' => 'text-emerald-700'], ['label' => 'Dibatalkan', 'key' => 'cancelled', 'bg' => 'bg-slate-50', 'border' => 'border-slate-200', 'text' => 'text-slate-600']] as $card)
                <div class="rounded-xl border {{ $card['border'] }} {{ $card['bg'] }} p-4 text-center shadow-sm">
                    <p class="text-2xl font-bold {{ $card['text'] }}">{{ $statusCounts[$card['key']] ?? 0 }}</p>
                    <p class="text-xs font-medium mt-0.5 {{ $card['text'] }}">{{ $card['label'] }}</p>
                </div>
            @endforeach
        </div>

        {{-- Filter --}}
        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
            <form method="GET" class="flex flex-wrap gap-3 items-end">
                <div>
                    <label class="block text-xs font-medium text-slate-500 mb-1.5">Filter Status</label>
                    <select name="status"
                        class="rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100">
                        <option value="">Semua Status</option>
                        @foreach ([
            'pending' => 'Menunggu',
            'in_review' => 'Direview',
            'approved' => 'Disetujui',
            'in_use' => 'Sedang Jalan',
            'completed' => 'Selesai',
            'rejected' => 'Ditolak',
            'cancelled' => 'Dibatalkan',
        ] as $val => $label)
                            <option value="{{ $val }}" @selected(request('status') === $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit"
                        class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 transition-colors">
                        Filter
                    </button>
                    <a href="{{ route('driver.bookings.index') }}"
                        class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50 transition-colors">
                        Reset
                    </a>
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
                                Status</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide text-slate-400">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($bookings as $booking)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-3 font-mono text-xs font-semibold text-slate-500">
                                    {{ $booking->booking_code }}
                                </td>
                                <td class="px-4 py-3">
                                    <p class="font-medium text-slate-700">{{ $booking->requester->name }}</p>
                                    <p class="text-xs text-slate-400">{{ $booking->requester->region?->name }}</p>
                                </td>
                                <td class="px-4 py-3">
                                    <p class="text-slate-700">{{ $booking->vehicle->brand }}
                                        {{ $booking->vehicle->model }}</p>
                                    <p class="text-xs font-mono text-slate-400">{{ $booking->vehicle->plate_number }}</p>
                                </td>
                                <td class="px-4 py-3">
                                    <p class="max-w-xs truncate text-slate-600" title="{{ $booking->destination }}">
                                        {{ $booking->destination }}
                                    </p>
                                </td>
                                <td class="px-4 py-3">
                                    <p class="text-slate-700">{{ $booking->departure_at->format('d M Y') }}</p>
                                    <p class="text-xs text-slate-400">{{ $booking->departure_at->format('H:i') }}</p>
                                </td>
                                <td class="px-4 py-3">{!! $booking->status_badge !!}</td>
                                <td class="px-4 py-3 text-center">
                                    <a href="{{ route('driver.bookings.show', $booking) }}"
                                        class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 text-slate-500 hover:bg-blue-50 hover:border-blue-200 hover:text-blue-600 transition-colors">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-14 text-center">
                                    <svg class="mx-auto h-10 w-10 text-slate-300 mb-3" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    <p class="text-sm font-medium text-slate-500">Belum ada penugasan</p>
                                    <p class="text-xs text-slate-400 mt-1">Penugasan akan muncul di sini setelah admin
                                        membuat pemesanan</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($bookings->hasPages())
                <div class="border-t border-slate-100 px-4 py-3">
                    {{ $bookings->links() }}
                </div>
            @endif
        </div>

    </div>
@endsection
