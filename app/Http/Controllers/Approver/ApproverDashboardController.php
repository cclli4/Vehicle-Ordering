<?php

namespace App\Http\Controllers\Approver;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingApproval;
use Illuminate\Support\Facades\Auth;

class ApproverDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // ambil pemesanan yang menunggu persetujuan
        $pendingApprovals = Booking::whereHas('approvals', function($query) use ($user) {
            $query->where('approver_id', $user->id)
                  ->where('status', 'pending');
        })
            ->with(['user', 'vehicle', 'driver'])
            ->latest()
            ->take(5)
            ->get();

        // Statistik persetujuan
        $approvalStats = [
            'pending' => BookingApproval::where('approver_id', $user->id)
                ->where('status', 'pending')
                ->count(),
            'approved' => BookingApproval::where('approver_id', $user->id)
                ->where('status', 'approved')
                ->count(),
            'rejected' => BookingApproval::where('approver_id', $user->id)
                ->where('status', 'rejected')
                ->count(),
        ];

        // ambil riwayat persetujuan terbaru
        $recentApprovals = BookingApproval::where('approver_id', $user->id)
            ->whereIn('status', ['approved', 'rejected'])
            ->with(['booking.user', 'booking.vehicle'])
            ->latest()
            ->take(5)
            ->get();

        return view('approver.dashboard', compact(
            'pendingApprovals',
            'approvalStats',
            'recentApprovals'
        ));
    }
}
