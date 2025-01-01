<?php

namespace App\Http\Controllers\Approver;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingApproval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingReviewController extends Controller
{
    public function show(Booking $booking)
    {
        // Ensure the current approver has pending approval for this booking
        $approval = BookingApproval::where('booking_id', $booking->id)
            ->where('approver_id', Auth::id())
            ->where('status', 'pending')
            ->firstOrFail();

        return view('approver.bookings.review', compact('booking', 'approval'));
    }

    public function approve(Request $request, Booking $booking)
    {
        $approval = BookingApproval::where('booking_id', $booking->id)
            ->where('approver_id', Auth::id())
            ->where('status', 'pending')
            ->firstOrFail();

        $approval->update([
            'status' => 'approved',
            'notes' => $request->notes
        ]);

        // Check if this was the final approval needed
        $pendingApprovals = $booking->approvals()->where('status', 'pending')->count();
        if ($pendingApprovals === 0) {
            $booking->update(['status' => 'approved']);
        }

        return redirect()->route('approver.dashboard')
            ->with('success', 'Pemesanan berhasil disetujui');
    }

    public function reject(Request $request, Booking $booking)
    {
        $approval = BookingApproval::where('booking_id', $booking->id)
            ->where('approver_id', Auth::id())
            ->where('status', 'pending')
            ->firstOrFail();

        $approval->update([
            'status' => 'rejected',
            'notes' => $request->notes
        ]);

        // When rejected by any approver, the booking is rejected
        $booking->update(['status' => 'rejected']);

        return redirect()->route('approver.dashboard')
            ->with('success', 'Pemesanan telah ditolak');
    }
}