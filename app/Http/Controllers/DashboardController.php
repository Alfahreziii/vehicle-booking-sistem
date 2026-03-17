<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Vehicle;
use App\Models\Driver;
use App\Models\BookingApproval;
use App\Models\FuelLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // ── Stat cards ────────────────────────────────────
        $stats = $this->getStats($user);

        // ── Grafik pemakaian kendaraan (12 bulan terakhir) ─
        $usageChart = $this->getMonthlyUsageChart();

        // ── Grafik status kendaraan ────────────────────────
        $vehicleStatusChart = $this->getVehicleStatusChart();

        // ── Grafik booking per status bulan ini ───────────
        $bookingStatusChart = $this->getBookingStatusChart();

        // ── Tabel booking terbaru ─────────────────────────
        $recentBookings = Booking::with(['requester', 'vehicle', 'driver.user'])
            ->when(
                $user->hasRole('approver'),
                fn($q) =>
                $q->whereHas(
                    'approvals',
                    fn($q2) =>
                    $q2->where('approver_id', $user->id)
                )
            )
            ->latest()
            ->take(6)
            ->get();

        // ── Pending approvals (untuk approver) ────────────
        $pendingApprovals = 0;
        if ($user->hasRole(['approver', 'admin'])) {
            $pendingApprovals = BookingApproval::where('approver_id', $user->id)
                ->where('status', 'waiting')
                ->count();
        }

        // ── Kendaraan yang perlu servis ───────────────────
        $vehiclesDueService = Vehicle::where('is_active', true)
            ->whereNotNull('last_service_date')
            ->where(
                DB::raw('current_odometer - service_interval_km'),
                '>=',
                DB::raw('(SELECT COALESCE(MAX(odometer_after), 0) FROM fuel_logs WHERE vehicle_id = vehicles.id)')
            )
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'stats',
            'usageChart',
            'vehicleStatusChart',
            'bookingStatusChart',
            'recentBookings',
            'pendingApprovals',
            'vehiclesDueService',
        ));
    }

    // ── Private methods ────────────────────────────────────

    private function getStats($user): array
    {
        $isAdmin    = $user->hasRole('admin');
        $isApprover = $user->hasRole('approver');

        return [
            'total_bookings_month' => Booking::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),

            'pending_approval' => BookingApproval::where('approver_id', $user->id)
                ->where('status', 'waiting')
                ->count(),

            'active_vehicles' => Vehicle::where('status', 'in_use')->count(),

            'available_vehicles' => Vehicle::where('status', 'available')
                ->where('is_active', true)
                ->count(),

            'completed_this_month' => Booking::where('status', 'completed')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),

            'total_fuel_cost_month' => FuelLog::whereMonth('log_date', now()->month)
                ->whereYear('log_date', now()->year)
                ->sum('total_cost'),

            'rejected_this_month' => Booking::where('status', 'rejected')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),

            'total_drivers' => Driver::where('status', 'available')->count(),
        ];
    }

    private function getMonthlyUsageChart(): array
    {
        $data = Booking::select(
            DB::raw('MONTH(departure_at) as month'),
            DB::raw('YEAR(departure_at) as year'),
            DB::raw('COUNT(*) as total'),
            DB::raw('SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed'),
            DB::raw('SUM(CASE WHEN status = "rejected" THEN 1 ELSE 0 END) as rejected'),
        )
            ->where('departure_at', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $labels   = [];
        $totals   = [];
        $completed = [];
        $rejected  = [];

        // Isi 12 bulan terakhir, bahkan kalau tidak ada data
        for ($i = 11; $i >= 0; $i--) {
            $date   = now()->subMonths($i);
            $month  = (int) $date->format('m');
            $year   = (int) $date->format('Y');

            $labels[]   = $date->translatedFormat('M Y');
            $row        = $data->first(fn($d) => (int)$d->month === $month && (int)$d->year === $year);
            $totals[]   = $row ? (int) $row->total : 0;
            $completed[] = $row ? (int) $row->completed : 0;
            $rejected[]  = $row ? (int) $row->rejected : 0;
        }

        return compact('labels', 'totals', 'completed', 'rejected');
    }

    private function getVehicleStatusChart(): array
    {
        $data = Vehicle::where('is_active', true)
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        return [
            'labels' => ['Tersedia', 'Digunakan', 'Perawatan', 'Tidak Aktif'],
            'data'   => [
                $data['available']   ?? 0,
                $data['in_use']      ?? 0,
                $data['maintenance'] ?? 0,
                $data['inactive']    ?? 0,
            ],
            'colors' => ['#10b981', '#f59e0b', '#ef4444', '#94a3b8'],
        ];
    }

    private function getBookingStatusChart(): array
    {
        $data = Booking::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        return [
            'labels' => ['Menunggu', 'Disetujui', 'Sedang Jalan', 'Selesai', 'Ditolak', 'Dibatalkan'],
            'data'   => [
                ($data['pending'] ?? 0) + ($data['in_review'] ?? 0),
                $data['approved']  ?? 0,
                $data['in_use']    ?? 0,
                $data['completed'] ?? 0,
                $data['rejected']  ?? 0,
                $data['cancelled'] ?? 0,
            ],
            'colors' => ['#f59e0b', '#3b82f6', '#8b5cf6', '#10b981', '#ef4444', '#94a3b8'],
        ];
    }
}
