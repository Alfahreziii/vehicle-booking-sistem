@extends('layouts.app')
@section('title', 'Detail Driver')
@section('page-title', 'Detail Driver')

@section('content')
    <div class="max-w-4xl mx-auto space-y-5">

        <div class="flex items-center justify-between">
            <a href="{{ route('admin.drivers.index') }}"
                class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-700">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali
            </a>
            <a href="{{ route('admin.drivers.edit', $driver) }}"
                class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50 transition-colors shadow-sm">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit
            </a>
        </div>

        <x-flash-message />

        {{-- Header --}}
        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-center gap-5">
                <div
                    class="flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-100 text-xl font-bold text-blue-700">
                    {{ strtoupper(substr($driver->user->name, 0, 2)) }}
                </div>
                <div class="flex-1">
                    <div class="flex items-center gap-2 flex-wrap">
                        <h2 class="text-xl font-semibold text-slate-800">{{ $driver->user->name }}</h2>
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
                            class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $statusMap[$driver->status] ?? '' }}">
                            {{ $statusLabel[$driver->status] ?? $driver->status }}
                        </span>
                        {!! $driver->license_status !!}
                    </div>
                    <p class="text-sm text-slate-500 mt-0.5">
                        {{ $driver->user->employee_id }} · {{ $driver->user->department?->name }} ·
                        {{ $driver->user->region?->name }}
                    </p>
                </div>
                <div class="hidden sm:flex gap-6 text-center">
                    <div>
                        <p class="text-xl font-bold text-slate-800">{{ $totalBookings }}</p>
                        <p class="text-xs text-slate-400 mt-0.5">Total Tugas</p>
                    </div>
                    <div>
                        <p class="text-xl font-bold text-emerald-700">{{ $completedBookings }}</p>
                        <p class="text-xs text-slate-400 mt-0.5">Selesai</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid gap-5 lg:grid-cols-2">

            {{-- Info SIM --}}
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <h3 class="mb-4 text-sm font-semibold text-slate-700">Data SIM</h3>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-slate-400">Nomor SIM</dt>
                        <dd class="font-mono font-semibold text-slate-700">{{ $driver->license_number }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-slate-400">Tipe SIM</dt>
                        <dd>
                            <span
                                class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-semibold text-blue-700">
                                SIM {{ $driver->license_type }}
                            </span>
                        </dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-slate-400">Berlaku Hingga</dt>
                        <dd class="{{ $driver->isLicenseExpired() ? 'text-red-600 font-semibold' : 'text-slate-700' }}">
                            {{ $driver->license_expiry->format('d M Y') }}
                        </dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-slate-400">Status SIM</dt>
                        <dd>{!! $driver->license_status !!}</dd>
                    </div>
                </dl>
            </div>

            {{-- Info Kontak --}}
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <h3 class="mb-4 text-sm font-semibold text-slate-700">Informasi Kontak</h3>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-slate-400">Email</dt>
                        <dd class="text-slate-700">{{ $driver->user->email }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-slate-400">Telepon</dt>
                        <dd class="text-slate-700">{{ $driver->user->phone ?? '-' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-slate-400">Departemen</dt>
                        <dd class="text-slate-700">{{ $driver->user->department?->name ?? '-' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-slate-400">Region</dt>
                        <dd class="text-slate-700">{{ $driver->user->region?->name ?? '-' }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        {{-- Riwayat Penugasan --}}
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
                <h3 class="text-sm font-semibold text-slate-700">Riwayat Penugasan</h3>
                <span class="text-xs text-slate-400">{{ $totalBookings }} penugasan</span>
            </div>
            <div class="divide-y divide-slate-50">
                @forelse($driver->bookings->take(8) as $booking)
                    <div class="flex items-center justify-between px-5 py-3 hover:bg-slate-50 transition-colors">
                        <div>
                            <span class="font-mono text-xs text-slate-400">{{ $booking->booking_code }}</span>
                            <p class="text-sm font-medium text-slate-700 mt-0.5">{{ $booking->purpose }}</p>
                            <p class="text-xs text-slate-400">
                                {{ $booking->vehicle->brand }} {{ $booking->vehicle->model }} ·
                                {{ $booking->departure_at->format('d M Y') }}
                            </p>
                        </div>
                        <div class="flex items-center gap-2">
                            {!! $booking->status_badge !!}
                            <a href="{{ route('admin.bookings.show', $booking) }}"
                                class="text-xs font-medium text-blue-600 hover:text-blue-800">Detail</a>
                        </div>
                    </div>
                @empty
                    <div class="px-5 py-8 text-center text-sm text-slate-400">Belum ada riwayat penugasan</div>
                @endforelse
            </div>
        </div>

        {{-- Hapus Driver --}}
        <div class="rounded-xl border border-red-200 bg-red-50 p-4">
            <h3 class="text-sm font-semibold text-red-700 mb-1">Hapus Driver</h3>
            <p class="text-xs text-red-600 mb-3">Data driver akan dihapus dari sistem.</p>
            <form action="{{ route('admin.drivers.destroy', $driver) }}" method="POST"
                onsubmit="return confirm('Yakin ingin menghapus driver {{ $driver->user->name }}?')">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="rounded-lg border border-red-300 bg-white px-4 py-2 text-sm font-semibold text-red-600 hover:bg-red-600 hover:text-white transition-colors">
                    Hapus Driver
                </button>
            </form>
        </div>

    </div>
@endsection
