@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <div class="max-w-7xl mx-auto space-y-6">

        {{-- Greeting --}}
        <div>
            <h2 class="text-xl font-semibold text-slate-800">
                Selamat datang, {{ auth()->user()->name }} 👋
            </h2>
            <p class="text-sm text-slate-500 mt-0.5">
                {{ now()->translatedFormat('l, d F Y') }} ·
                <span class="capitalize">{{ auth()->user()->getRoleNames()->first() }}</span>
                · {{ auth()->user()->region?->name ?? 'Semua Region' }}
            </p>
        </div>

        {{-- Alert pending approval --}}
        @if ($pendingApprovals > 0)
            <div
                class="flex items-center gap-3 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                <svg class="h-5 w-5 shrink-0 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span>Ada <strong>{{ $pendingApprovals }} pemesanan</strong> yang menunggu persetujuan Anda.</span>
                <a href="{{ route('approvals.index') }}"
                    class="ml-auto shrink-0 rounded-lg bg-amber-500 px-3 py-1.5 text-xs font-semibold text-white hover:bg-amber-600 transition-colors">
                    Proses Sekarang
                </a>
            </div>
        @endif

        {{-- ── Stat Cards ── --}}
        <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">

            {{-- Booking bulan ini --}}
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100">
                        <svg class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-slate-400">Bulan ini</span>
                </div>
                <p class="mt-3 text-2xl font-bold text-slate-800">{{ $stats['total_bookings_month'] }}</p>
                <p class="mt-0.5 text-xs text-slate-500">Total Pemesanan</p>
            </div>

            {{-- Kendaraan aktif --}}
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-100">
                        <svg class="h-5 w-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8 17a2 2 0 100-4 2 2 0 000 4zm8 0a2 2 0 100-4 2 2 0 000 4zM3 5h2l2 7h9l2-6H6" />
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-slate-400">Saat ini</span>
                </div>
                <p class="mt-3 text-2xl font-bold text-slate-800">{{ $stats['active_vehicles'] }}</p>
                <p class="mt-0.5 text-xs text-slate-500">Kendaraan Digunakan</p>
            </div>

            {{-- Kendaraan tersedia --}}
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-100">
                        <svg class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-slate-400">Saat ini</span>
                </div>
                <p class="mt-3 text-2xl font-bold text-slate-800">{{ $stats['available_vehicles'] }}</p>
                <p class="mt-0.5 text-xs text-slate-500">Kendaraan Tersedia</p>
            </div>

            {{-- Biaya BBM --}}
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-purple-100">
                        <svg class="h-5 w-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-slate-400">Bulan ini</span>
                </div>
                <p class="mt-3 text-2xl font-bold text-slate-800">
                    Rp {{ number_format($stats['total_fuel_cost_month'], 0, ',', '.') }}
                </p>
                <p class="mt-0.5 text-xs text-slate-500">Total Biaya BBM</p>
            </div>

        </div>

        {{-- ── Charts Row ── --}}
        <div class="grid gap-5 lg:grid-cols-3">

            {{-- Grafik pemakaian 12 bulan (bar chart) --}}
            <div class="lg:col-span-2 rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-sm font-semibold text-slate-800">Pemakaian Kendaraan</h3>
                        <p class="text-xs text-slate-400 mt-0.5">12 bulan terakhir</p>
                    </div>
                    <div class="flex items-center gap-4 text-xs">
                        <span class="flex items-center gap-1.5">
                            <span class="h-2.5 w-2.5 rounded-full bg-blue-500"></span>
                            Total
                        </span>
                        <span class="flex items-center gap-1.5">
                            <span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                            Selesai
                        </span>
                        <span class="flex items-center gap-1.5">
                            <span class="h-2.5 w-2.5 rounded-full bg-red-400"></span>
                            Ditolak
                        </span>
                    </div>
                </div>
                <div class="relative h-64">
                    <canvas id="usageChart"></canvas>
                </div>
            </div>

            {{-- Grafik status kendaraan (donut) --}}
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="mb-4">
                    <h3 class="text-sm font-semibold text-slate-800">Status Kendaraan</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Kondisi armada saat ini</p>
                </div>
                <div class="relative h-48 flex items-center justify-center">
                    <canvas id="vehicleStatusChart"></canvas>
                </div>
                {{-- Legend --}}
                <div class="mt-4 space-y-2">
                    @foreach (['Tersedia' => '#10b981', 'Digunakan' => '#f59e0b', 'Perawatan' => '#ef4444', 'Tidak Aktif' => '#94a3b8'] as $label => $color)
                        <div class="flex items-center justify-between text-xs">
                            <span class="flex items-center gap-2">
                                <span class="h-2.5 w-2.5 rounded-full" style="background: {{ $color }}"></span>
                                <span class="text-slate-600">{{ $label }}</span>
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ── Bottom Row ── --}}
        <div class="grid gap-5 lg:grid-cols-3">

            {{-- Grafik booking status bulan ini --}}
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="mb-4">
                    <h3 class="text-sm font-semibold text-slate-800">Status Booking</h3>
                    <p class="text-xs text-slate-400 mt-0.5">{{ now()->translatedFormat('F Y') }}</p>
                </div>
                <div class="relative h-48">
                    <canvas id="bookingStatusChart"></canvas>
                </div>
            </div>

            {{-- Booking terbaru --}}
            <div class="lg:col-span-2 rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
                    <h3 class="text-sm font-semibold text-slate-800">Pemesanan Terbaru</h3>
                    @if (auth()->user()->hasRole('admin'))
                        <a href="{{ route('admin.bookings.index') }}"
                            class="text-xs font-medium text-blue-600 hover:text-blue-800">Lihat semua →</a>
                    @endif
                </div>
                <div class="divide-y divide-slate-50">
                    @forelse($recentBookings as $booking)
                        <div class="flex items-center gap-4 px-5 py-3 hover:bg-slate-50 transition-colors">
                            {{-- Status dot --}}
                            <div
                                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full
                        {{ match ($booking->status) {
                            'completed' => 'bg-emerald-100',
                            'approved', 'in_use' => 'bg-blue-100',
                            'rejected' => 'bg-red-100',
                            default => 'bg-amber-100',
                        } }}">
                                <svg class="h-4 w-4
                            {{ match ($booking->status) {
                                'completed' => 'text-emerald-600',
                                'approved', 'in_use' => 'text-blue-600',
                                'rejected' => 'text-red-600',
                                default => 'text-amber-600',
                            } }}"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8 17a2 2 0 100-4 2 2 0 000 4zm8 0a2 2 0 100-4 2 2 0 000 4zM3 5h2l2 7h9l2-6H6" />
                                </svg>
                            </div>
                            {{-- Info --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <span class="font-mono text-xs text-slate-400">{{ $booking->booking_code }}</span>
                                    {!! $booking->status_badge !!}
                                </div>
                                <p class="mt-0.5 text-sm font-medium text-slate-700 truncate">{{ $booking->purpose }}</p>
                                <p class="text-xs text-slate-400">{{ $booking->requester->name }} ·
                                    {{ $booking->departure_at->format('d M Y') }}</p>
                            </div>
                            {{-- Vehicle --}}
                            <div class="hidden text-right md:block shrink-0">
                                <p class="text-xs font-medium text-slate-600">{{ $booking->vehicle->brand }}
                                    {{ $booking->vehicle->model }}</p>
                                <p class="text-xs text-slate-400">{{ $booking->vehicle->plate_number }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="px-5 py-10 text-center text-sm text-slate-400">
                            Belum ada pemesanan
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // ── Warna dari Tailwind ──────────────────────────────
            const colors = {
                blue: {
                    bg: 'rgba(59, 130, 246, 0.15)',
                    border: '#3b82f6'
                },
                emerald: {
                    bg: 'rgba(16, 185, 129, 0.15)',
                    border: '#10b981'
                },
                red: {
                    bg: 'rgba(239, 68, 68, 0.15)',
                    border: '#ef4444'
                },
            };

            // ── Global Chart defaults ────────────────────────────
            Chart.defaults.font.family = "'Inter', sans-serif";
            Chart.defaults.font.size = 12;
            Chart.defaults.color = '#94a3b8';

            // ── 1. Usage Chart (Bar) ─────────────────────────────
            const usageData = @json($usageChart);

            new Chart(document.getElementById('usageChart'), {
                type: 'bar',
                data: {
                    labels: usageData.labels,
                    datasets: [{
                            label: 'Total',
                            data: usageData.totals,
                            backgroundColor: colors.blue.bg,
                            borderColor: colors.blue.border,
                            borderWidth: 2,
                            borderRadius: 6,
                            borderSkipped: false,
                        },
                        {
                            label: 'Selesai',
                            data: usageData.completed,
                            backgroundColor: colors.emerald.bg,
                            borderColor: colors.emerald.border,
                            borderWidth: 2,
                            borderRadius: 6,
                            borderSkipped: false,
                        },
                        {
                            label: 'Ditolak',
                            data: usageData.rejected,
                            backgroundColor: colors.red.bg,
                            borderColor: colors.red.border,
                            borderWidth: 2,
                            borderRadius: 6,
                            borderSkipped: false,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#1e293b',
                            titleColor: '#f8fafc',
                            bodyColor: '#cbd5e1',
                            padding: 10,
                            cornerRadius: 8,
                        },
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            border: {
                                display: false
                            },
                            ticks: {
                                maxRotation: 45
                            },
                        },
                        y: {
                            grid: {
                                color: '#f1f5f9'
                            },
                            border: {
                                display: false,
                                dash: [4, 4]
                            },
                            ticks: {
                                stepSize: 1
                            },
                            beginAtZero: true,
                        },
                    },
                },
            });

            // ── 2. Vehicle Status Chart (Doughnut) ───────────────
            const vehicleData = @json($vehicleStatusChart);

            new Chart(document.getElementById('vehicleStatusChart'), {
                type: 'doughnut',
                data: {
                    labels: vehicleData.labels,
                    datasets: [{
                        data: vehicleData.data,
                        backgroundColor: vehicleData.colors,
                        borderWidth: 0,
                        hoverOffset: 6,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#1e293b',
                            titleColor: '#f8fafc',
                            bodyColor: '#cbd5e1',
                            padding: 10,
                            cornerRadius: 8,
                        },
                    },
                },
            });

            // ── 3. Booking Status Chart (Horizontal Bar) ─────────
            const bookingData = @json($bookingStatusChart);

            new Chart(document.getElementById('bookingStatusChart'), {
                type: 'bar',
                data: {
                    labels: bookingData.labels,
                    datasets: [{
                        data: bookingData.data,
                        backgroundColor: bookingData.colors,
                        borderRadius: 6,
                        borderSkipped: false,
                        borderWidth: 0,
                    }],
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#1e293b',
                            titleColor: '#f8fafc',
                            bodyColor: '#cbd5e1',
                            padding: 10,
                            cornerRadius: 8,
                        },
                    },
                    scales: {
                        x: {
                            grid: {
                                color: '#f1f5f9'
                            },
                            border: {
                                display: false
                            },
                            ticks: {
                                stepSize: 1
                            },
                            beginAtZero: true,
                        },
                        y: {
                            grid: {
                                display: false
                            },
                            border: {
                                display: false
                            },
                        },
                    },
                },
            });

        });
    </script>
@endpush
