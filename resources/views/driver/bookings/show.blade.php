@extends('layouts.app')

@section('title', 'Detail Penugasan')
@section('page-title', 'Detail Penugasan')

@section('content')
    <div class="max-w-4xl mx-auto space-y-5">

        {{-- Back --}}
        <a href="{{ route('driver.bookings.index') }}"
            class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-700">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali ke daftar
        </a>

        {{-- Header --}}
        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <div class="flex items-center gap-3">
                        <span class="font-mono text-sm font-semibold text-slate-400">{{ $booking->booking_code }}</span>
                        {!! $booking->status_badge !!}
                    </div>
                    <h2 class="mt-2 text-xl font-semibold text-slate-800">{{ $booking->purpose }}</h2>
                    <p class="mt-1 text-sm text-slate-500">
                        Pemohon: <strong>{{ $booking->requester->name }}</strong>
                        · {{ $booking->requester->department?->name ?? '-' }}
                        · {{ $booking->requester->region?->name ?? '-' }}
                    </p>
                </div>
            </div>
        </div>

        <div class="grid gap-5 lg:grid-cols-2">

            {{-- Info Perjalanan --}}
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <h3 class="mb-4 text-sm font-semibold text-slate-700 flex items-center gap-2">
                    <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-blue-100">
                        <svg class="h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    Informasi Perjalanan
                </h3>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-slate-400">Destinasi</dt>
                        <dd class="font-medium text-slate-700 text-right max-w-xs">{{ $booking->destination }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-slate-400">Tanggal Berangkat</dt>
                        <dd class="font-medium text-slate-700">{{ $booking->departure_at->format('d M Y, H:i') }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-slate-400">Estimasi Kembali</dt>
                        <dd class="font-medium text-slate-700">{{ $booking->return_at->format('d M Y, H:i') }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-slate-400">Durasi</dt>
                        <dd class="font-medium text-slate-700">
                            {{ $booking->departure_at->diffForHumans($booking->return_at, true) }}
                        </dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-slate-400">Jumlah Penumpang</dt>
                        <dd class="font-medium text-slate-700">{{ $booking->passenger_count }} orang</dd>
                    </div>
                    @if ($booking->description)
                        <div class="pt-2 border-t border-slate-100">
                            <dt class="text-slate-400 mb-1">Keterangan</dt>
                            <dd class="text-slate-600">{{ $booking->description }}</dd>
                        </div>
                    @endif
                    @if ($booking->cancellation_reason)
                        <div class="pt-2 border-t border-slate-100">
                            <dt class="text-red-400 mb-1">Alasan Pembatalan</dt>
                            <dd class="text-red-600">{{ $booking->cancellation_reason }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            {{-- Info Kendaraan --}}
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <h3 class="mb-4 text-sm font-semibold text-slate-700 flex items-center gap-2">
                    <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-amber-100">
                        <svg class="h-4 w-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8 17a2 2 0 100-4 2 2 0 000 4zm8 0a2 2 0 100-4 2 2 0 000 4zM3 5h2l2 7h9l2-6H6" />
                        </svg>
                    </div>
                    Kendaraan yang Dikemudikan
                </h3>
                <div class="rounded-lg bg-slate-50 border border-slate-100 p-4">
                    <p class="font-semibold text-slate-800 text-base mb-3">
                        {{ $booking->vehicle->brand }} {{ $booking->vehicle->model }}
                    </p>
                    <dl class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-slate-400">Plat Nomor</dt>
                            <dd class="font-mono font-semibold text-slate-700">{{ $booking->vehicle->plate_number }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-slate-400">Jenis</dt>
                            <dd class="text-slate-700">{{ $booking->vehicle->type_label }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-slate-400">Warna</dt>
                            <dd class="text-slate-700">{{ $booking->vehicle->color ?? '-' }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-slate-400">Tahun</dt>
                            <dd class="text-slate-700">{{ $booking->vehicle->year }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-slate-400">Region</dt>
                            <dd class="text-slate-700">{{ $booking->vehicle->region->name }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-slate-400">Konsumsi BBM</dt>
                            <dd class="text-slate-700">{{ $booking->vehicle->fuel_consumption }} km/l</dd>
                        </div>
                    </dl>
                </div>
            </div>

        </div>

        {{-- Status Persetujuan --}}
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <h3 class="mb-4 text-sm font-semibold text-slate-700 flex items-center gap-2">
                <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-emerald-100">
                    <svg class="h-4 w-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                Status Persetujuan
            </h3>

            <div class="flex items-center gap-3 flex-wrap">
                @foreach ($booking->approvals as $approval)
                    <div
                        class="flex items-center gap-2 rounded-lg border px-3 py-2
                {{ $approval->status === 'approved'
                    ? 'border-emerald-200 bg-emerald-50'
                    : ($approval->status === 'rejected'
                        ? 'border-red-200 bg-red-50'
                        : 'border-slate-200 bg-slate-50') }}">
                        <div
                            class="flex h-6 w-6 items-center justify-center rounded-full text-xs font-bold
                    {{ $approval->status === 'approved'
                        ? 'bg-emerald-100 text-emerald-700'
                        : ($approval->status === 'rejected'
                            ? 'bg-red-100 text-red-700'
                            : 'bg-slate-100 text-slate-500') }}">
                            {{ $approval->level }}
                        </div>
                        <div>
                            <p class="text-xs font-medium text-slate-700">{{ $approval->approver->name }}</p>
                            <p
                                class="text-xs {{ $approval->status === 'approved'
                                    ? 'text-emerald-600'
                                    : ($approval->status === 'rejected'
                                        ? 'text-red-600'
                                        : 'text-slate-400') }}">
                                {{ match ($approval->status) {
                                    'approved' => '✓ Disetujui',
                                    'rejected' => '✗ Ditolak',
                                    default => '⏳ Menunggu',
                                } }}
                                @if ($approval->acted_at)
                                    · {{ $approval->acted_at->format('d M Y') }}
                                @endif
                            </p>
                        </div>
                    </div>
                    @if (!$loop->last)
                        <svg class="h-4 w-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    @endif
                @endforeach
            </div>

            @if ($booking->status === 'approved')
                <div class="mt-4 rounded-lg border border-blue-200 bg-blue-50 px-4 py-3">
                    <p class="text-sm font-semibold text-blue-700">Booking disetujui — Anda siap berangkat!</p>
                    <p class="text-xs text-blue-600 mt-0.5">
                        Berangkat {{ $booking->departure_at->format('d M Y, H:i') }} →
                        Estimasi kembali {{ $booking->return_at->format('d M Y, H:i') }}
                    </p>
                </div>
            @elseif($booking->status === 'in_use')
                <div class="mt-4 rounded-lg border border-purple-200 bg-purple-50 px-4 py-3">
                    <p class="text-sm font-semibold text-purple-700">Perjalanan sedang berlangsung</p>
                    <p class="text-xs text-purple-600 mt-0.5">
                        Estimasi kembali {{ $booking->return_at->format('d M Y, H:i') }}
                    </p>
                </div>
            @endif
        </div>

    </div>
@endsection
