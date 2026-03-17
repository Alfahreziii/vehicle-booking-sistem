@extends('layouts.app')

@section('title', 'Manajemen Booking')
@section('page-title', 'Pemesanan Kendaraan')

@section('content')
    <div class="max-w-7xl mx-auto space-y-5">

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-slate-800">Pemesanan Kendaraan</h2>
                <p class="text-sm text-slate-500 mt-0.5">Kelola semua pemesanan kendaraan perusahaan</p>
            </div>
            <a href="{{ route('admin.bookings.create') }}"
                class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 transition-colors">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Buat Pemesanan
            </a>
        </div>

        {{-- Status Cards --}}
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-5">
            @foreach ([['label' => 'Menunggu', 'key' => 'in_review', 'bg' => 'bg-amber-50', 'text' => 'text-amber-600', 'border' => 'border-amber-200'], ['label' => 'Disetujui', 'key' => 'approved', 'bg' => 'bg-blue-50', 'text' => 'text-blue-600', 'border' => 'border-blue-200'], ['label' => 'Digunakan', 'key' => 'in_use', 'bg' => 'bg-purple-50', 'text' => 'text-purple-600', 'border' => 'border-purple-200'], ['label' => 'Selesai', 'key' => 'completed', 'bg' => 'bg-emerald-50', 'text' => 'text-emerald-600', 'border' => 'border-emerald-200'], ['label' => 'Ditolak', 'key' => 'rejected', 'bg' => 'bg-red-50', 'text' => 'text-red-600', 'border' => 'border-red-200']] as $card)
                <div class="rounded-xl border {{ $card['border'] }} {{ $card['bg'] }} p-4 text-center shadow-sm">
                    <p class="text-2xl font-bold {{ $card['text'] }}">
                        {{ $statusCounts[$card['key']] ?? 0 }}
                    </p>
                    <p class="mt-0.5 text-xs font-medium {{ $card['text'] }}">{{ $card['label'] }}</p>
                </div>
            @endforeach
        </div>

        {{-- Flash Messages --}}
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                class="flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                <svg class="h-5 w-5 shrink-0 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
                <button @click="show = false" class="ml-auto text-emerald-500 hover:text-emerald-700">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                class="flex items-center gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                <svg class="h-5 w-5 shrink-0 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('error') }}</span>
                <button @click="show = false" class="ml-auto text-red-500 hover:text-red-700">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif

        {{-- Filter --}}
        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
            <form method="GET" class="flex flex-wrap items-end gap-3">
                <div class="flex-1 min-w-48">
                    <label class="block text-xs font-medium text-slate-500 mb-1.5">Cari</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Kode, tujuan, destinasi..."
                        class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-700 placeholder:text-slate-400 focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100">
                </div>
                <div class="min-w-40">
                    <label class="block text-xs font-medium text-slate-500 mb-1.5">Status</label>
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
                            <option value="{{ $val }}" @selected(request('status') === $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 transition-colors">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Filter
                    </button>
                    <a href="{{ route('admin.bookings.index') }}"
                        class="inline-flex items-center rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50 transition-colors">
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
                                Kode Booking</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-400">
                                Pemohon</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-400">
                                Kendaraan</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-400">
                                Driver</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-400">
                                Tujuan</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-400">Tgl
                                Berangkat</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-400">
                                Approval</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-400">
                                Status</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide text-slate-400">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($bookings as $booking)
                            <tr class="hover:bg-slate-50 transition-colors">

                                {{-- Kode --}}
                                <td class="px-4 py-3">
                                    <span class="font-mono text-xs font-semibold text-slate-600">
                                        {{ $booking->booking_code }}
                                    </span>
                                </td>

                                {{-- Pemohon --}}
                                <td class="px-4 py-3">
                                    <p class="font-medium text-slate-700">{{ $booking->requester->name }}</p>
                                    <p class="text-xs text-slate-400">{{ $booking->requester->region?->name }}</p>
                                </td>

                                {{-- Kendaraan --}}
                                <td class="px-4 py-3">
                                    <p class="text-slate-700">{{ $booking->vehicle->brand }}
                                        {{ $booking->vehicle->model }}</p>
                                    <p class="text-xs font-mono text-slate-400">{{ $booking->vehicle->plate_number }}</p>
                                </td>

                                {{-- Driver --}}
                                <td class="px-4 py-3 text-slate-600">
                                    {{ $booking->driver->user->name }}
                                </td>

                                {{-- Tujuan --}}
                                <td class="px-4 py-3">
                                    <p class="max-w-xs truncate text-slate-600" title="{{ $booking->destination }}">
                                        {{ $booking->destination }}
                                    </p>
                                </td>

                                {{-- Tanggal --}}
                                <td class="px-4 py-3">
                                    <p class="text-slate-700">{{ $booking->departure_at->format('d M Y') }}</p>
                                    <p class="text-xs text-slate-400">{{ $booking->departure_at->format('H:i') }}</p>
                                </td>

                                {{-- Approval --}}
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-1">
                                        @foreach ($booking->approvals as $approval)
                                            <span
                                                class="inline-flex h-6 w-6 items-center justify-center rounded-full text-xs font-bold
                                    {{ $approval->status === 'approved'
                                        ? 'bg-emerald-100 text-emerald-700'
                                        : ($approval->status === 'rejected'
                                            ? 'bg-red-100 text-red-700'
                                            : 'bg-slate-100 text-slate-500') }}"
                                                title="Level {{ $approval->level }}: {{ $approval->approver->name }}">
                                                {{ $approval->level }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>

                                {{-- Status --}}
                                <td class="px-4 py-3">
                                    {!! $booking->status_badge !!}
                                </td>

                                {{-- Aksi --}}
                                <td class="px-4 py-3 text-center">
                                    <a href="{{ route('admin.bookings.show', $booking) }}"
                                        class="inline-flex items-center justify-center h-8 w-8 rounded-lg border border-slate-200 text-slate-500 hover:bg-blue-50 hover:border-blue-200 hover:text-blue-600 transition-colors"
                                        title="Lihat Detail">
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
                                <td colspan="9" class="px-4 py-14 text-center">
                                    <svg class="mx-auto h-10 w-10 text-slate-300 mb-3" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    <p class="text-sm font-medium text-slate-500">Belum ada data pemesanan</p>
                                    <p class="text-xs text-slate-400 mt-1">Buat pemesanan baru dengan klik tombol di atas
                                    </p>
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
