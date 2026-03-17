@extends('layouts.app')

@section('title', 'Detail Kendaraan')
@section('page-title', 'Detail Kendaraan')

@section('content')
    <div class="max-w-6xl mx-auto space-y-5">

        {{-- Back + Actions --}}
        <div class="flex items-center justify-between">
            <a href="{{ route('admin.vehicles.index') }}"
                class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-700">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali ke daftar
            </a>
            <div class="flex gap-2">
                <a href="{{ route('admin.vehicles.edit', $vehicle) }}"
                    class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50 transition-colors shadow-sm">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                </a>
                <button onclick="document.getElementById('modal-fuel').classList.remove('hidden')"
                    class="inline-flex items-center gap-2 rounded-lg bg-purple-600 px-4 py-2 text-sm font-semibold text-white hover:bg-purple-700 transition-colors shadow-sm">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah BBM
                </button>
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

        {{-- Header Card --}}
        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-amber-100">
                        <svg class="h-8 w-8 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8 17a2 2 0 100-4 2 2 0 000 4zm8 0a2 2 0 100-4 2 2 0 000 4zM3 5h2l2 7h9l2-6H6" />
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="font-mono text-sm font-bold text-slate-600">{{ $vehicle->plate_number }}</span>
                            {!! $vehicle->status_badge !!}
                            @if ($vehicle->ownership === 'rented')
                                <span
                                    class="inline-flex items-center rounded-full bg-purple-100 px-2.5 py-0.5 text-xs font-semibold text-purple-700">Sewa</span>
                            @endif
                        </div>
                        <h2 class="mt-1 text-xl font-semibold text-slate-800">
                            {{ $vehicle->brand }} {{ $vehicle->model }}
                        </h2>
                        <p class="text-sm text-slate-500 mt-0.5">
                            {{ $vehicle->year }} · {{ $vehicle->color }} · {{ $vehicle->type_label }} ·
                            {{ $vehicle->region->name }}
                        </p>
                    </div>
                </div>

                {{-- Stats --}}
                <div class="flex gap-6 text-center">
                    <div>
                        <p class="text-xl font-bold text-slate-800">{{ number_format($vehicle->current_odometer) }}</p>
                        <p class="text-xs text-slate-400 mt-0.5">Odometer (km)</p>
                    </div>
                    <div>
                        <p class="text-xl font-bold text-purple-700">Rp {{ number_format($totalFuelCost, 0, ',', '.') }}
                        </p>
                        <p class="text-xs text-slate-400 mt-0.5">Total Biaya BBM</p>
                    </div>
                    <div>
                        <p class="text-xl font-bold text-blue-700">{{ $vehicle->bookings->count() }}</p>
                        <p class="text-xs text-slate-400 mt-0.5">Total Booking</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid gap-5 lg:grid-cols-3">

            {{-- Kolom kiri --}}
            <div class="lg:col-span-2 space-y-5">

                {{-- Info Kendaraan --}}
                <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                    <h3 class="mb-4 text-sm font-semibold text-slate-700">Informasi Kendaraan</h3>
                    <dl class="grid grid-cols-2 gap-x-8 gap-y-3 text-sm">
                        @foreach ([
            ['label' => 'Plat Nomor', 'value' => $vehicle->plate_number, 'mono' => true],
            ['label' => 'Merk & Model', 'value' => $vehicle->brand . ' ' . $vehicle->model, 'mono' => false],
            ['label' => 'Tahun', 'value' => $vehicle->year, 'mono' => false],
            ['label' => 'Warna', 'value' => $vehicle->color ?? '-', 'mono' => false],
            ['label' => 'Jenis', 'value' => $vehicle->type_label, 'mono' => false],
            ['label' => 'Kepemilikan', 'value' => $vehicle->ownership_label, 'mono' => false],
            ['label' => 'Perusahaan Sewa', 'value' => $vehicle->rental_company ?? '-', 'mono' => false],
            ['label' => 'Region', 'value' => $vehicle->region->name, 'mono' => false],
            ['label' => 'No. Rangka', 'value' => $vehicle->chassis_number ?? '-', 'mono' => true],
            ['label' => 'No. Mesin', 'value' => $vehicle->engine_number ?? '-', 'mono' => true],
            ['label' => 'Konsumsi BBM', 'value' => $vehicle->fuel_consumption . ' km/l', 'mono' => false],
            ['label' => 'Interval Servis', 'value' => number_format($vehicle->service_interval_km) . ' km', 'mono' => false],
        ] as $item)
                            <div>
                                <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">{{ $item['label'] }}
                                </dt>
                                <dd class="mt-1 font-medium text-slate-700 {{ $item['mono'] ? 'font-mono' : '' }}">
                                    {{ $item['value'] }}</dd>
                            </div>
                        @endforeach
                    </dl>
                </div>

                {{-- Riwayat BBM --}}
                <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
                        <h3 class="text-sm font-semibold text-slate-700">Riwayat Pengisian BBM</h3>
                        <span class="text-xs text-slate-400">{{ $vehicle->fuelLogs->count() }} catatan</span>
                    </div>
                    @if ($vehicle->fuelLogs->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b border-slate-100 bg-slate-50">
                                        <th
                                            class="px-4 py-2.5 text-left text-xs font-semibold uppercase tracking-wide text-slate-400">
                                            Tanggal</th>
                                        <th
                                            class="px-4 py-2.5 text-left text-xs font-semibold uppercase tracking-wide text-slate-400">
                                            SPBU</th>
                                        <th
                                            class="px-4 py-2.5 text-right text-xs font-semibold uppercase tracking-wide text-slate-400">
                                            Liter</th>
                                        <th
                                            class="px-4 py-2.5 text-right text-xs font-semibold uppercase tracking-wide text-slate-400">
                                            Biaya</th>
                                        <th
                                            class="px-4 py-2.5 text-right text-xs font-semibold uppercase tracking-wide text-slate-400">
                                            Efisiensi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    @foreach ($vehicle->fuelLogs->take(10) as $log)
                                        <tr class="hover:bg-slate-50">
                                            <td class="px-4 py-2.5 text-slate-600">{{ $log->log_date->format('d M Y') }}
                                            </td>
                                            <td class="px-4 py-2.5 text-slate-500 text-xs">{{ $log->fuel_station ?? '-' }}
                                            </td>
                                            <td class="px-4 py-2.5 text-right font-medium text-slate-700">
                                                {{ number_format($log->liters, 1) }} L</td>
                                            <td class="px-4 py-2.5 text-right text-slate-600">Rp
                                                {{ number_format($log->total_cost, 0, ',', '.') }}</td>
                                            <td class="px-4 py-2.5 text-right text-xs text-slate-500">
                                                {{ $log->fuel_efficiency ? $log->fuel_efficiency . ' km/L' : '-' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="border-t border-slate-200 bg-slate-50">
                                    <tr>
                                        <td colspan="2" class="px-4 py-2.5 text-xs font-semibold text-slate-500">Total
                                        </td>
                                        <td class="px-4 py-2.5 text-right font-bold text-slate-800">
                                            {{ number_format($totalFuelLiter, 1) }} L</td>
                                        <td class="px-4 py-2.5 text-right font-bold text-purple-700">Rp
                                            {{ number_format($totalFuelCost, 0, ',', '.') }}</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="px-5 py-8 text-center text-sm text-slate-400">Belum ada catatan BBM</div>
                    @endif
                </div>

                {{-- Riwayat Booking --}}
                <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
                        <h3 class="text-sm font-semibold text-slate-700">Riwayat Pemesanan</h3>
                        <span class="text-xs text-slate-400">{{ $vehicle->bookings->count() }} booking</span>
                    </div>
                    <div class="divide-y divide-slate-50">
                        @forelse($vehicle->bookings->take(5) as $booking)
                            <div class="flex items-center justify-between px-5 py-3 hover:bg-slate-50 transition-colors">
                                <div>
                                    <span class="font-mono text-xs text-slate-400">{{ $booking->booking_code }}</span>
                                    <p class="text-sm font-medium text-slate-700 mt-0.5">{{ $booking->purpose }}</p>
                                    <p class="text-xs text-slate-400">{{ $booking->requester->name }} ·
                                        {{ $booking->departure_at->format('d M Y') }}</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    {!! $booking->status_badge !!}
                                    <a href="{{ route('admin.bookings.show', $booking) }}"
                                        class="text-xs font-medium text-blue-600 hover:text-blue-800">Detail</a>
                                </div>
                            </div>
                        @empty
                            <div class="px-5 py-8 text-center text-sm text-slate-400">Belum ada riwayat pemesanan</div>
                        @endforelse
                    </div>
                </div>

            </div>

            {{-- Kolom kanan --}}
            <div class="space-y-5">

                {{-- Jadwal Servis --}}
                <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
                        <h3 class="text-sm font-semibold text-slate-700">Jadwal Servis</h3>
                    </div>
                    <div class="divide-y divide-slate-50">
                        @forelse($vehicle->serviceSchedules->take(5) as $service)
                            <div class="px-5 py-3">
                                <div class="flex items-start justify-between gap-2">
                                    <div>
                                        <p class="text-sm font-medium text-slate-700">{{ $service->service_type }}</p>
                                        <p class="text-xs text-slate-400 mt-0.5">
                                            {{ $service->scheduled_date->format('d M Y') }}
                                            @if ($service->scheduled_odometer)
                                                · {{ number_format($service->scheduled_odometer) }} km
                                            @endif
                                        </p>
                                    </div>
                                    {!! $service->status_badge !!}
                                </div>
                                @if ($service->estimated_cost)
                                    <p class="mt-1 text-xs text-slate-500">
                                        Est. Rp {{ number_format($service->estimated_cost, 0, ',', '.') }}
                                    </p>
                                @endif
                            </div>
                        @empty
                            <div class="px-5 py-6 text-center text-sm text-slate-400">Belum ada jadwal servis</div>
                        @endforelse
                    </div>
                </div>

                {{-- Delete --}}
                <div class="rounded-xl border border-red-200 bg-red-50 p-4">
                    <h3 class="text-sm font-semibold text-red-700 mb-1">Hapus Kendaraan</h3>
                    <p class="text-xs text-red-600 mb-3">Data kendaraan akan dihapus secara soft delete dan tidak muncul di
                        sistem.</p>
                    <form action="{{ route('admin.vehicles.destroy', $vehicle) }}" method="POST"
                        onsubmit="return confirm('Yakin ingin menghapus kendaraan {{ $vehicle->plate_number }}?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-full rounded-lg border border-red-300 bg-white px-4 py-2 text-sm font-semibold text-red-600 hover:bg-red-600 hover:text-white transition-colors">
                            Hapus Kendaraan
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>

    {{-- Modal Tambah BBM --}}
    <div id="modal-fuel" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
        <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">
            <h3 class="text-base font-semibold text-slate-800 mb-1">Tambah Log BBM</h3>
            <p class="text-sm text-slate-500 mb-4">Catat pengisian BBM untuk {{ $vehicle->plate_number }}</p>

            <form action="{{ route('admin.vehicles.fuel-log', $vehicle) }}" method="POST" class="space-y-3">
                @csrf
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Tanggal <span
                                class="text-red-500">*</span></label>
                        <input type="date" name="log_date" value="{{ now()->format('Y-m-d') }}"
                            max="{{ now()->format('Y-m-d') }}"
                            class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Jumlah (liter) <span
                                class="text-red-500">*</span></label>
                        <input type="number" name="liters" min="0.1" step="0.1" placeholder="0.0"
                            class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Harga/liter (Rp) <span
                                class="text-red-500">*</span></label>
                        <input type="number" name="cost_per_liter" min="0" placeholder="10000"
                            class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">SPBU</label>
                        <input type="text" name="fuel_station" placeholder="Nama SPBU"
                            class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Odometer Sebelum <span
                                class="text-red-500">*</span></label>
                        <input type="number" name="odometer_before" value="{{ $vehicle->current_odometer }}"
                            min="0"
                            class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Odometer Sesudah <span
                                class="text-red-500">*</span></label>
                        <input type="number" name="odometer_after" min="{{ $vehicle->current_odometer + 1 }}"
                            placeholder="{{ $vehicle->current_odometer + 1 }}"
                            class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100">
                    </div>
                </div>

                <div class="flex gap-2 justify-end pt-2">
                    <button type="button" onclick="document.getElementById('modal-fuel').classList.add('hidden')"
                        class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">
                        Batal
                    </button>
                    <button type="submit"
                        class="rounded-lg bg-purple-600 px-4 py-2 text-sm font-semibold text-white hover:bg-purple-700">
                        Simpan Log BBM
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection
