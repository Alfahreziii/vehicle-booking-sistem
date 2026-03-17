<?php
// app/Http/Controllers/Admin/ReportController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Exports\BookingExport;
use App\Models\Booking;
use App\Models\Region;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function __construct(protected ActivityLogService $activityLogService) {}

    public function index(Request $request)
    {
        $regions = Region::orderBy('name')->get();

        // Preview data (paginated)
        $bookings = Booking::with(['requester', 'vehicle', 'driver.user', 'approvals'])
            ->when(
                $request->start_date,
                fn($q) =>
                $q->where('departure_at', '>=', $request->start_date . ' 00:00:00')
            )
            ->when(
                $request->end_date,
                fn($q) =>
                $q->where('departure_at', '<=', $request->end_date . ' 23:59:59')
            )
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when(
                $request->region_id,
                fn($q) =>
                $q->whereHas(
                    'vehicle',
                    fn($q2) =>
                    $q2->where('region_id', $request->region_id)
                )
            )
            ->orderBy('departure_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        // Summary stats
        $summary = [
            'total'     => $bookings->total(),
            'completed' => Booking::where('status', 'completed')
                ->when($request->start_date, fn($q) => $q->where('departure_at', '>=', $request->start_date))
                ->when($request->end_date,   fn($q) => $q->where('departure_at', '<=', $request->end_date))
                ->count(),
            'rejected'  => Booking::where('status', 'rejected')
                ->when($request->start_date, fn($q) => $q->where('departure_at', '>=', $request->start_date))
                ->when($request->end_date,   fn($q) => $q->where('departure_at', '<=', $request->end_date))
                ->count(),
        ];

        return view('admin.reports.index', compact('bookings', 'regions', 'summary'));
    }

    public function export(Request $request)
    {
        $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date'   => ['nullable', 'date', 'after_or_equal:start_date'],
            'status'     => ['nullable', 'in:pending,in_review,approved,rejected,in_use,completed,cancelled'],
            'region_id'  => ['nullable', 'exists:regions,id'],
        ]);

        $this->activityLogService->logAction(
            action: 'exported',
            description: 'Export laporan pemesanan oleh ' . Auth::user()->name,
            new: array_filter($request->only(['start_date', 'end_date', 'status', 'region_id'])),
        );

        $filename = 'laporan-pemesanan-' . now()->format('Ymd-His') . '.xlsx';

        return Excel::download(
            new BookingExport(
                startDate: $request->start_date,
                endDate: $request->end_date,
                status: $request->status,
                regionId: $request->region_id ? (int) $request->region_id : null,
            ),
            $filename
        );
    }
}
