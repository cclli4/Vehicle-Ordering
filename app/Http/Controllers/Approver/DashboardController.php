<?php

namespace App\Http\Controllers\Approver;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingApproval;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('role:approver'); // Pastikan role approver aktif jika menggunakan middleware role
    }

    public function test()
    {
        return "test";
    }

    public function index()
    {
        try {
            $user = Auth::user(); 

            if (!$user) {
                return redirect()->route('login')->with('error', 'Please log in to continue.');
            }
    
            Log::info('Pending approvals query', [
                'user_id' => $user->id,
            ]);

            // Statistik Persetujuan
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

            // Daftar Pemesanan yang Menunggu Persetujuan
            $pendingApprovals = Booking::whereHas('approvals', function ($query) use ($user) {
                $query->where('approver_id', $user->id)
                      ->where('status', 'pending');
            })->with(['user', 'vehicle', 'driver']) 
              ->latest()
              ->get();

            // Riwayat Persetujuan Terbaru
            $recentApprovals = BookingApproval::where('approver_id', $user->id)
                ->whereIn('status', ['approved', 'rejected'])
                ->with('booking') 
                ->latest()
                ->take(5) 
                ->get();

            // Kirim ke view
            return view('approver.dashboard', compact(
                'approvalStats',
                'pendingApprovals',
                'recentApprovals'
            ));
        } catch (\Exception $e) {
            Log::error('Error in approver dashboard', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'An error occurred while loading the dashboard.');
        }
    }
}
