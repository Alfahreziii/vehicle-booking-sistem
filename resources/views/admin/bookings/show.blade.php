@extends('layouts.app')

@section('title', 'Detail Booking ' . $booking->booking_code)
@section('page-title', 'Detail Pemesanan')

@section('content')
    <div class="max-w-6xl mx-auto space-y-5">

        {{-- Back + Header --}}
        <div class="flex items-center justify-between">
            <a href="{{ route('admin.bookings.index') }}"
                class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-700 transition-colors">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali ke daftar
            </a>

            {{-- Action buttons --}}
            <div class="flex items-center gap-2">

                {{-- Tombol Complete --}}
                @if ($booking->status === 'approved' || $booking->status === 'in_use')
                    <button onclick="document.getElementById('modal-complete').classList.remove('hidden')"
                        class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700 transition-colors">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        Selesaikan
                    </button>
                @endif

                {{-- Tombol Cancel --}}
                @if (in_array($booking->status, ['pending', 'in_review', 'approved']))
                    <button onclick="document.getElementById('modal-cancel').classList.remove('hidden')"
                        class="inline-flex items-center gap-2 rounded-lg border border-red-200 bg-red-50 px-4 py-2 text-sm font-semibold text-red-600 hover:bg-red-100 transition-colors">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Batalkan
                    </button>
                @endif

            </div>
        </div>

        {{-- Flash --}}
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                class="flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                <svg class="h-5 w-5 shrink-0 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ session('success') }}
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
                {{ session('error') }}
            </div>
        @endif

        {{-- Booking Header Card --}}
        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <div class="flex items-center gap-3">
                        <span class="font-mono text-sm font-semibold text-slate-400">{{ $booking->booking_code }}</span>
                        {!! $booking->status_badge !!}
                    </div>
                    <h2 class="mt-2 text-xl font-semibold text-slate-800">{{ $booking->purpose }}</h2>
                    <div class="mt-2 flex flex-wrap items-center gap-3 text-sm text-slate-500">
                        <span class="flex items-center gap-1.5">
                            <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            {{ $booking->requester->name }}
                        </span>
                        <span class="text-slate-300">·</span>
                        <span>{{ $booking->requester->department?->name ?? '-' }}</span>
                        <span class="text-slate-300">·</span>
                        <span>{{ $booking->requester->region?->name ?? '-' }}</span>
                        <span class="text-slate-300">·</span>
                        <span class="flex items-center gap-1.5">
                            <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Dibuat {{ $booking->created_at->diffForHumans() }}
                        </span>
                    </div>
                </div>

                {{-- Progress indicator --}}
                <div class="text-right">
                    <p class="text-xs text-slate-400 mb-1.5">Progress Persetujuan</p>
                    <div class="flex items-center gap-1.5">
                        @foreach ($booking->approvals as $approval)
                            <div class="flex flex-col items-center gap-1">
                                <div class="flex h-8 w-8 items-center justify-center rounded-full text-xs font-bold
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
                                <span class="text-xs text-slate-400">L{{ $approval->level }}</span>
                            </div>
                            @if (!$loop->last)
                                <svg class="h-3 w-3 text-slate-300 mb-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="grid gap-5 lg:grid-cols-3">

            {{-- Kolom kiri (2/3) --}}
            <div class="lg:col-span-2 space-y-5">

                {{-- Info Perjalanan --}}
                <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                    <h3 class="mb-4 text-sm font-semibold text-slate-700 flex items-center gap-2">
                        <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-blue-100">
                            <svg class="h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                            </svg>
                        </div>
                        Informasi Perjalanan
                    </h3>

                    <div class="grid grid-cols-2 gap-x-8 gap-y-4 text-sm">
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Destinasi</p>
                            <p class="mt-1 font-medium text-slate-700">{{ $booking->destination }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Jumlah Penumpang</p>
                            <p class="mt-1 font-medium text-slate-700">{{ $booking->passenger_count }} orang</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Tanggal Berangkat</p>
                            <p class="mt-1 font-medium text-slate-700">{{ $booking->departure_at->format('d M Y, H:i') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Estimasi Kembali</p>
                            <p class="mt-1 font-medium text-slate-700">{{ $booking->return_at->format('d M Y, H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Durasi Perjalanan</p>
                            <p class="mt-1 font-medium text-slate-700">
                                {{ $booking->departure_at->diffForHumans($booking->return_at, true) }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Odometer</p>
                            <p class="mt-1 font-medium text-slate-700">
                                @if ($booking->odometer_start)
                                    {{ number_format($booking->odometer_start) }} km
                                    @if ($booking->odometer_end)
                                        → {{ number_format($booking->odometer_end) }} km
                                        <span class="text-emerald-600 font-semibold">
                                            (+{{ number_format($booking->odometer_end - $booking->odometer_start) }} km)
                                        </span>
                                    @endif
                                @else
                                    <span class="text-slate-400">Belum diisi</span>
                                @endif
                            </p>
                        </div>
                        @if ($booking->description)
                            <div class="col-span-2">
                                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Keterangan</p>
                                <p class="mt-1 text-slate-600">{{ $booking->description }}</p>
                            </div>
                        @endif
                        @if ($booking->cancellation_reason)
                            <div class="col-span-2">
                                <p class="text-xs font-medium uppercase tracking-wide text-red-400">Alasan Pembatalan</p>
                                <p class="mt-1 text-red-600">{{ $booking->cancellation_reason }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Kendaraan & Driver --}}
                <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                    <h3 class="mb-4 text-sm font-semibold text-slate-700 flex items-center gap-2">
                        <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-amber-100">
                            <svg class="h-4 w-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8 17a2 2 0 100-4 2 2 0 000 4zm8 0a2 2 0 100-4 2 2 0 000 4zM3 5h2l2 7h9l2-6H6" />
                            </svg>
                        </div>
                        Kendaraan & Driver
                    </h3>

                    <div class="grid grid-cols-2 gap-4">
                        {{-- Kendaraan --}}
                        <div class="rounded-lg border border-slate-100 bg-slate-50 p-4">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-amber-100">
                                    <svg class="h-5 w-5 text-amber-600" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M8 17a2 2 0 100-4 2 2 0 000 4zm8 0a2 2 0 100-4 2 2 0 000 4zM3 5h2l2 7h9l2-6H6" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Kendaraan</p>
                                    <p class="font-semibold text-slate-800">{{ $booking->vehicle->brand }}
                                        {{ $booking->vehicle->model }}</p>
                                </div>
                            </div>
                            <div class="space-y-1.5 text-xs">
                                <div class="flex justify-between">
                                    <span class="text-slate-400">Plat Nomor</span>
                                    <span
                                        class="font-semibold font-mono text-slate-700">{{ $booking->vehicle->plate_number }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-400">Jenis</span>
                                    <span class="text-slate-700">{{ $booking->vehicle->type_label }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-400">Tahun</span>
                                    <span class="text-slate-700">{{ $booking->vehicle->year }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-400">Kepemilikan</span>
                                    <span class="text-slate-700">{{ $booking->vehicle->ownership_label }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-400">Region</span>
                                    <span class="text-slate-700">{{ $booking->vehicle->region->name }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-400">Konsumsi BBM</span>
                                    <span class="text-slate-700">{{ $booking->vehicle->fuel_consumption }} km/l</span>
                                </div>
                            </div>
                        </div>

                        {{-- Driver --}}
                        <div class="rounded-lg border border-slate-100 bg-slate-50 p-4">
                            <div class="flex items-center gap-3 mb-3">
                                <div
                                    class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 text-sm font-bold text-blue-700">
                                    {{ strtoupper(substr($booking->driver->user->name, 0, 2)) }}
                                </div>
                                <div>
                                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Driver</p>
                                    <p class="font-semibold text-slate-800">{{ $booking->driver->user->name }}</p>
                                </div>
                            </div>
                            <div class="space-y-1.5 text-xs">
                                <div class="flex justify-between">
                                    <span class="text-slate-400">No. SIM</span>
                                    <span class="font-mono text-slate-700">{{ $booking->driver->license_number }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-400">Tipe SIM</span>
                                    <span class="text-slate-700">{{ $booking->driver->license_type }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-400">Berlaku s.d.</span>
                                    <span
                                        class="{{ $booking->driver->isLicenseExpired() ? 'text-red-600 font-semibold' : 'text-slate-700' }}">
                                        {{ $booking->driver->license_expiry->format('d M Y') }}
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-400">Status SIM</span>
                                    <span>{!! $booking->driver->license_status !!}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-400">Phone</span>
                                    <span class="text-slate-700">{{ $booking->driver->user->phone ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Fuel Log --}}
                @if ($booking->fuelLog->count() > 0)
                    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                        <h3 class="mb-4 text-sm font-semibold text-slate-700 flex items-center gap-2">
                            <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-purple-100">
                                <svg class="h-4 w-4 text-purple-600" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 10h2l1 7h12l1-7h2M7 10V7a5 5 0 0110 0v3" />
                                </svg>
                            </div>
                            Riwayat Pengisian BBM
                        </h3>

                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b border-slate-100">
                                        <th
                                            class="pb-2 text-left text-xs font-semibold uppercase tracking-wide text-slate-400">
                                            Tanggal</th>
                                        <th
                                            class="pb-2 text-left text-xs font-semibold uppercase tracking-wide text-slate-400">
                                            SPBU</th>
                                        <th
                                            class="pb-2 text-right text-xs font-semibold uppercase tracking-wide text-slate-400">
                                            Liter</th>
                                        <th
                                            class="pb-2 text-right text-xs font-semibold uppercase tracking-wide text-slate-400">
                                            Harga/L</th>
                                        <th
                                            class="pb-2 text-right text-xs font-semibold uppercase tracking-wide text-slate-400">
                                            Total</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    @foreach ($booking->fuelLog as $log)
                                        <tr>
                                            <td class="py-2.5 text-slate-600">
                                                {{ \Carbon\Carbon::parse($log->log_date)->format('d M Y') }}</td>
                                            <td class="py-2.5 text-slate-600">{{ $log->fuel_station ?? '-' }}</td>
                                            <td class="py-2.5 text-right font-medium text-slate-700">
                                                {{ number_format($log->liters, 1) }} L</td>
                                            <td class="py-2.5 text-right text-slate-600">Rp
                                                {{ number_format($log->cost_per_liter, 0, ',', '.') }}</td>
                                            <td class="py-2.5 text-right font-semibold text-slate-800">Rp
                                                {{ number_format($log->total_cost, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="border-t border-slate-200">
                                    <tr>
                                        <td colspan="2" class="pt-2.5 text-xs font-semibold text-slate-500">Total</td>
                                        <td class="pt-2.5 text-right font-bold text-slate-800">
                                            {{ number_format($booking->fuelLog->sum('liters'), 1) }} L</td>
                                        <td></td>
                                        <td class="pt-2.5 text-right font-bold text-emerald-700">Rp
                                            {{ number_format($booking->fuelLog->sum('total_cost'), 0, ',', '.') }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                @endif

            </div>

            {{-- Kolom kanan (1/3) --}}
            <div class="space-y-5">

                {{-- Timeline Approval --}}
                <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                    <h3 class="mb-4 text-sm font-semibold text-slate-700 flex items-center gap-2">
                        <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-emerald-100">
                            <svg class="h-4 w-4 text-emerald-600" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        Timeline Persetujuan
                    </h3>

                    <div class="space-y-1">
                        {{-- Submitted --}}
                        <div class="flex gap-3">
                            <div class="flex flex-col items-center">
                                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-blue-100">
                                    <svg class="h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                    </svg>
                                </div>
                                <div class="mt-1 w-px flex-1 bg-slate-200 min-h-6"></div>
                            </div>
                            <div class="pb-4 pt-1">
                                <p class="text-sm font-medium text-slate-700">Pengajuan Dibuat</p>
                                <p class="text-xs text-slate-400 mt-0.5">oleh {{ $booking->requester->name }}</p>
                                <p class="text-xs text-slate-400">{{ $booking->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>

                        {{-- Approval levels --}}
                        @foreach ($booking->approvals as $approval)
                            <div class="flex gap-3">
                                <div class="flex flex-col items-center">
                                    <div
                                        class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full
                                {{ $approval->status === 'approved'
                                    ? 'bg-emerald-100'
                                    : ($approval->status === 'rejected'
                                        ? 'bg-red-100'
                                        : ($booking->current_approval_level + 1 === $approval->level
                                            ? 'bg-amber-100'
                                            : 'bg-slate-100')) }}">
                                        @if ($approval->status === 'approved')
                                            <svg class="h-4 w-4 text-emerald-600" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                            </svg>
                                        @elseif($approval->status === 'rejected')
                                            <svg class="h-4 w-4 text-red-600" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        @elseif($booking->current_approval_level + 1 === $approval->level)
                                            <div class="h-3 w-3 rounded-full bg-amber-400 animate-pulse"></div>
                                        @else
                                            <div class="h-3 w-3 rounded-full bg-slate-300"></div>
                                        @endif
                                    </div>
                                    @if (!$loop->last || $booking->status === 'completed')
                                        <div class="mt-1 w-px flex-1 bg-slate-200 min-h-6"></div>
                                    @endif
                                </div>
                                <div class="pb-4 pt-1">
                                    <p class="text-sm font-medium text-slate-700">
                                        Persetujuan Level {{ $approval->level }}
                                        <span
                                            class="ml-1 text-xs font-normal
                                    {{ $approval->status === 'approved'
                                        ? 'text-emerald-600'
                                        : ($approval->status === 'rejected'
                                            ? 'text-red-600'
                                            : ($booking->current_approval_level + 1 === $approval->level
                                                ? 'text-amber-600'
                                                : 'text-slate-400')) }}">
                                            {{ match ($approval->status) {
                                                'approved' => '✓ Disetujui',
                                                'rejected' => '✗ Ditolak',
                                                'waiting' => $booking->current_approval_level + 1 === $approval->level ? '⏳ Menunggu' : '○ Belum giliran',
                                                default => '',
                                            } }}
                                        </span>
                                    </p>
                                    <p class="text-xs text-slate-500 mt-0.5">{{ $approval->approver->name }}</p>
                                    @if ($approval->acted_at)
                                        <p class="text-xs text-slate-400">{{ $approval->acted_at->format('d M Y, H:i') }}
                                        </p>
                                    @endif
                                    @if ($approval->notes)
                                        <div
                                            class="mt-1.5 rounded-lg bg-slate-50 px-2.5 py-1.5 text-xs text-slate-600 italic border border-slate-100">
                                            "{{ $approval->notes }}"
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach

                        {{-- Completed --}}
                        @if ($booking->status === 'completed')
                            <div class="flex gap-3">
                                <div class="flex flex-col items-center">
                                    <div
                                        class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-100">
                                        <svg class="h-4 w-4 text-emerald-600" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 3l14 9-14 9V3z" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="pt-1">
                                    <p class="text-sm font-medium text-emerald-700">Perjalanan Selesai</p>
                                    <p class="text-xs text-slate-400 mt-0.5">
                                        {{ $booking->updated_at->format('d M Y, H:i') }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Activity Log --}}
                @php
                    $logs = \App\Models\ActivityLog::where('model_type', \App\Models\Booking::class)
                        ->where('model_id', $booking->id)
                        ->with('user')
                        ->latest('created_at')
                        ->take(8)
                        ->get();
                @endphp
                @if ($logs->count() > 0)
                    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                        <h3 class="mb-4 text-sm font-semibold text-slate-700 flex items-center gap-2">
                            <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-slate-100">
                                <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            Log Aktivitas
                        </h3>
                        <div class="space-y-3">
                            @foreach ($logs as $log)
                                <div class="flex gap-2.5">
                                    <div
                                        class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-slate-100 text-xs font-bold text-slate-500 mt-0.5">
                                        {{ strtoupper(substr($log->user?->name ?? 'S', 0, 1)) }}
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs text-slate-600">{{ $log->description }}</p>
                                        <p class="text-xs text-slate-400 mt-0.5">{{ $log->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>

    {{-- ── Modal Complete ── --}}
    <div id="modal-complete" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
        <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">
            <h3 class="text-base font-semibold text-slate-800 mb-1">Selesaikan Perjalanan</h3>
            <p class="text-sm text-slate-500 mb-4">Masukkan odometer akhir untuk menyelesaikan booking ini.</p>

            <form action="{{ route('admin.bookings.complete', $booking) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-xs font-medium text-slate-600 mb-1.5">
                        Odometer Akhir (km)
                        @if ($booking->odometer_start)
                            <span class="text-slate-400 font-normal">— min. {{ number_format($booking->odometer_start) }}
                                km</span>
                        @endif
                    </label>
                    <input type="number" name="odometer_end" min="{{ $booking->odometer_start ?? 0 }}"
                        placeholder="{{ $booking->odometer_start ? 'Min: ' . $booking->odometer_start : 'Masukkan odometer...' }}"
                        class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100 @error('odometer_end') border-red-400 @enderror">
                    @error('odometer_end')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex gap-2 justify-end">
                    <button type="button" onclick="document.getElementById('modal-complete').classList.add('hidden')"
                        class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">
                        Batal
                    </button>
                    <button type="submit"
                        class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
                        Konfirmasi Selesai
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── Modal Cancel ── --}}
    <div id="modal-cancel" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
        <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">
            <h3 class="text-base font-semibold text-slate-800 mb-1">Batalkan Pemesanan</h3>
            <p class="text-sm text-slate-500 mb-4">Tindakan ini tidak dapat dibatalkan. Kendaraan dan driver akan
                dibebaskan.</p>

            <form action="{{ route('admin.bookings.cancel', $booking) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-xs font-medium text-slate-600 mb-1.5">
                        Alasan Pembatalan <span class="text-red-500">*</span>
                    </label>
                    <textarea name="cancellation_reason" rows="3" placeholder="Tuliskan alasan pembatalan (minimal 10 karakter)..."
                        class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm focus:border-red-400 focus:outline-none focus:ring-2 focus:ring-red-100 @error('cancellation_reason') border-red-400 @enderror"></textarea>
                    @error('cancellation_reason')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex gap-2 justify-end">
                    <button type="button" onclick="document.getElementById('modal-cancel').classList.add('hidden')"
                        class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">
                        Tutup
                    </button>
                    <button type="submit"
                        class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">
                        Ya, Batalkan
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection
