{{-- resources/views/approver/approvals/index.blade.php --}}

@extends('layouts.app')

@section('title', 'Persetujuan Booking')
@section('page-title', 'Persetujuan Booking')

@section('content')
    <div class="max-w-7xl mx-auto space-y-6">

        {{-- Header --}}
        <div>
            <h2 class="text-xl font-semibold text-slate-800">Persetujuan Kendaraan</h2>
            <p class="text-sm text-slate-500 mt-0.5">Daftar pemesanan yang membutuhkan persetujuan Anda</p>
        </div>

        {{-- Flash --}}
        <x-flash-message />


        {{-- Menunggu Persetujuan Saya --}}
        <div>
            <div class="flex items-center gap-2 mb-3">
                <h3 class="text-sm font-semibold text-slate-700">Menunggu Persetujuan Saya</h3>
                @if ($pendingCount > 0)
                    <span
                        class="flex h-5 min-w-5 items-center justify-center rounded-full bg-red-500 px-1.5 text-xs font-semibold text-white">
                        {{ $pendingCount }}
                    </span>
                @endif
            </div>

            @if ($pendingBookings->isEmpty())
                <div class="rounded-xl border border-dashed border-slate-200 bg-white py-12 text-center">
                    <svg class="mx-auto h-10 w-10 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="mt-3 text-sm font-medium text-slate-500">Tidak ada pemesanan yang menunggu persetujuan</p>
                    <p class="text-xs text-slate-400 mt-1">Semua sudah diproses</p>
                </div>
            @else
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($pendingBookings as $booking)
                        <div
                            class="group rounded-xl border border-amber-200 bg-white shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden">

                            {{-- Card top accent --}}
                            <div class="h-1 bg-amber-400"></div>

                            <div class="p-4">
                                {{-- Header --}}
                                <div class="flex items-start justify-between mb-3">
                                    <div>
                                        <span
                                            class="font-mono text-xs font-semibold text-slate-500">{{ $booking->booking_code }}</span>
                                        <p class="mt-0.5 text-sm font-semibold text-slate-800">{{ $booking->purpose }}</p>
                                    </div>
                                    <span
                                        class="shrink-0 rounded-full bg-amber-100 px-2 py-0.5 text-xs font-semibold text-amber-700">
                                        Menunggu
                                    </span>
                                </div>

                                {{-- Detail --}}
                                <div class="space-y-2 text-xs text-slate-600">
                                    <div class="flex items-center gap-2">
                                        <svg class="h-3.5 w-3.5 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        <span>{{ $booking->requester->name }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg class="h-3.5 w-3.5 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span class="truncate">{{ $booking->destination }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg class="h-3.5 w-3.5 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span>{{ $booking->departure_at->format('d M Y, H:i') }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg class="h-3.5 w-3.5 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M8 17a2 2 0 100-4 2 2 0 000 4zm8 0a2 2 0 100-4 2 2 0 000 4zM3 5h2l2 7h9l2-6H6" />
                                        </svg>
                                        <span>{{ $booking->vehicle->brand }} {{ $booking->vehicle->model }} ·
                                            {{ $booking->vehicle->plate_number }}</span>
                                    </div>
                                </div>

                                {{-- Approval chain progress --}}
                                <div class="mt-3 flex items-center gap-1.5">
                                    @foreach ($booking->approvals as $approval)
                                        <div class="flex items-center gap-1">
                                            <div class="flex h-6 w-6 items-center justify-center rounded-full text-xs font-bold
                                {{ $approval->status === 'approved'
                                    ? 'bg-emerald-100 text-emerald-700'
                                    : ($approval->status === 'rejected'
                                        ? 'bg-red-100 text-red-700'
                                        : ($booking->current_approval_level + 1 === $approval->level
                                            ? 'bg-amber-100 text-amber-700 ring-2 ring-amber-400'
                                            : 'bg-slate-100 text-slate-400')) }}"
                                                title="{{ $approval->approver->name }}">
                                                {{ $approval->level }}
                                            </div>
                                            @if (!$loop->last)
                                                <svg class="h-3 w-3 text-slate-300" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 5l7 7-7 7" />
                                                </svg>
                                            @endif
                                        </div>
                                    @endforeach
                                    <span class="ml-1 text-xs text-slate-400">Level
                                        {{ $booking->current_approval_level + 1 }} dari
                                        {{ $booking->total_approver_levels }}</span>
                                </div>

                                {{-- Action --}}
                                <div class="mt-4 pt-3 border-t border-slate-100 flex gap-2">
                                    <a href="{{ route('approvals.show', $booking) }}"
                                        class="flex-1 rounded-lg bg-blue-600 px-3 py-2 text-center text-xs font-semibold text-white hover:bg-blue-700 transition-colors">
                                        Proses Sekarang
                                    </a>
                                    <a href="{{ route('approvals.show', $booking) }}"
                                        class="rounded-lg border border-slate-200 px-3 py-2 text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">
                                        Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Riwayat --}}
        <div>
            <h3 class="text-sm font-semibold text-slate-700 mb-3">Riwayat Persetujuan Saya</h3>

            <div class="rounded-xl border border-slate-200 bg-white overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50">
                                <th
                                    class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-400">
                                    Kode</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-400">
                                    Pemohon</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-400">
                                    Tujuan</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-400">
                                    Tgl Berangkat</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-400">
                                    Keputusan Saya</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-400">
                                    Status Booking</th>
                                <th class="px-4 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($historyBookings as $booking)
                                @php
                                    $myApproval = $booking->approvals->firstWhere('approver_id', auth()->id());
                                @endphp
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-4 py-3 font-mono text-xs text-slate-500">{{ $booking->booking_code }}
                                    </td>
                                    <td class="px-4 py-3 font-medium text-slate-700">{{ $booking->requester->name }}</td>
                                    <td class="px-4 py-3 text-slate-600">
                                        <div class="max-w-xs truncate" title="{{ $booking->destination }}">
                                            {{ $booking->destination }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-slate-600">{{ $booking->departure_at->format('d M Y') }}
                                    </td>
                                    <td class="px-4 py-3">
                                        @if ($myApproval)
                                            <span
                                                class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-semibold
                                    {{ $myApproval->status === 'approved' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                                @if ($myApproval->status === 'approved')
                                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor" stroke-width="2.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    Disetujui
                                                @else
                                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor" stroke-width="2.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                    Ditolak
                                                @endif
                                            </span>
                                            <span class="ml-1 text-xs text-slate-400">L{{ $myApproval->level }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">{!! $booking->status_badge !!}</td>
                                    <td class="px-4 py-3">
                                        <a href="{{ route('approvals.show', $booking) }}"
                                            class="text-xs font-medium text-blue-600 hover:text-blue-800">Detail</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-10 text-center text-sm text-slate-400">Belum ada
                                        riwayat persetujuan</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($historyBookings->hasPages())
                    <div class="border-t border-slate-100 px-4 py-3">
                        {{ $historyBookings->links() }}
                    </div>
                @endif
            </div>
        </div>

    </div>
@endsection
