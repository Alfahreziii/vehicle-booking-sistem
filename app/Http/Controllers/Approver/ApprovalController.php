<?php

namespace App\Http\Controllers\Approver;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateApprovalRequest;
use App\Services\ApprovalService;
use App\Models\Booking;
use App\Models\BookingApproval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApprovalController extends Controller
{
    public function __construct(protected ApprovalService $approvalService) {}

    /**
     * Daftar booking yang menunggu approval dari user ini
     */
    public function index(Request $request)
    {
        // Booking yang menunggu giliran saya
        $pendingBookings = Booking::whereHas('approvals', function ($q) {
            $q->where('approver_id', Auth::id())
                ->where('status', 'waiting');
        })
            ->with(['requester', 'vehicle', 'driver.user', 'approvals.approver'])
            ->whereIn('status', ['pending', 'in_review'])
            ->latest()
            ->get()
            ->filter(fn($b) => $this->approvalService->canProcess($b));

        // Riwayat yang sudah saya proses
        $historyBookings = Booking::whereHas('approvals', function ($q) {
            $q->where('approver_id', Auth::id())
                ->whereIn('status', ['approved', 'rejected']);
        })
            ->with(['requester', 'vehicle', 'driver.user', 'approvals'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $pendingCount = $pendingBookings->count();

        return view('approver.approvals.index', compact(
            'pendingBookings',
            'historyBookings',
            'pendingCount',
        ));
    }

    /**
     * Detail booking untuk diproses
     */
    public function show(Booking $booking)
    {
        // Pastikan approver ini memang terdaftar di booking ini
        $myApproval = BookingApproval::where('booking_id', $booking->id)
            ->where('approver_id', Auth::id())
            ->firstOrFail();

        $booking->load([
            'requester.region',
            'requester.department',
            'vehicle.region',
            'driver.user',
            'approvals.approver',
        ]);

        $canProcess = $this->approvalService->canProcess($booking);

        return view('approver.approvals.show', compact('booking', 'myApproval', 'canProcess'));
    }

    /**
     * Proses approve atau reject
     */
    public function process(UpdateApprovalRequest $request, Booking $booking)
    {
        // Validasi apakah user ini berhak proses sekarang
        if (!$this->approvalService->canProcess($booking)) {
            return back()->with('error', 'Anda tidak berwenang atau booking ini sudah diproses.');
        }

        try {
            if ($request->action === 'approve') {
                $this->approvalService->approve($booking, $request->notes ?? '');
                $message = 'Booking berhasil disetujui.';
            } else {
                $this->approvalService->reject($booking, $request->notes);
                $message = 'Booking berhasil ditolak.';
            }

            return redirect()
                ->route('approvals.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
