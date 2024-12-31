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
       $this->middleware('role:approver');
   }

   public function index()
   {
       try {
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

       Log::info('Pending approvals', [
        'count' => $pendingApprovals->count(),
        'data' => $pendingApprovals->toArray()
    ]);

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

       // Total pemesanan yang sudah diproses
       $totalProcessed = BookingApproval::where('approver_id', $user->id)
           ->whereIn('status', ['approved', 'rejected'])
           ->count();

       // Rata-rata waktu pemrosesan (dalam jam)
       $averageProcessingTime = BookingApproval::where('approver_id', $user->id)
           ->whereIn('status', ['approved', 'rejected'])
           ->whereNotNull('updated_at')
           ->get()
           ->avg(function($approval) {
               return $approval->created_at->diffInHours($approval->updated_at);
           }) ?? 0;

       return view('approver.dashboard', compact(
           'pendingApprovals',
           'approvalStats',
           'recentApprovals',
           'totalProcessed',
           'averageProcessingTime'
       ));
    } catch (\Exception $e) {
        Log::error('Error in approver dashboard', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return redirect()->back()->with('error', 'An error occurred while loading the dashboard.');
    }
}
   public function getApprovalStatistics()
   {
       $user = Auth::user();

       // Statistik per bulan untuk tahun ini
       $monthlyStats = BookingApproval::where('approver_id', $user->id)
           ->whereYear('created_at', now()->year)
           ->selectRaw('MONTH(created_at) as month, status, COUNT(*) as total')
           ->groupBy('month', 'status')
           ->get()
           ->groupBy('month')
           ->map(function($group) {
               return [
                   'approved' => $group->where('status', 'approved')->sum('total'),
                   'rejected' => $group->where('status', 'rejected')->sum('total'),
                   'pending' => $group->where('status', 'pending')->sum('total'),
               ];
           });

       // Statistik per departemen
       $departmentStats = BookingApproval::where('approver_id', $user->id)
           ->whereIn('status', ['approved', 'rejected'])
           ->with('booking.user')
           ->get()
           ->groupBy('booking.user.department')
           ->map(function($group) {
               return [
                   'total' => $group->count(),
                   'approved' => $group->where('status', 'approved')->count(),
                   'rejected' => $group->where('status', 'rejected')->count(),
               ];
           });

       return response()->json([
           'monthly' => $monthlyStats,
           'departments' => $departmentStats
       ]);
   }

   public function getPendingApprovals()
   {
       $user = Auth::user();

       $pendingApprovals = Booking::whereHas('approvals', function($query) use ($user) {
           $query->where('approver_id', $user->id)
                 ->where('status', 'pending');
       })
       ->with(['user', 'vehicle', 'driver', 'approvals.approver'])
       ->latest()
       ->paginate(10);

       return view('approver.approvals.pending', compact('pendingApprovals'));
   }

   public function getApprovalHistory()
   {
       $user = Auth::user();

       $approvalHistory = BookingApproval::where('approver_id', $user->id)
           ->whereIn('status', ['approved', 'rejected'])
           ->with(['booking.user', 'booking.vehicle'])
           ->latest()
           ->paginate(10);

       return view('approver.approvals.history', compact('approvalHistory'));
   }

   public function getDashboardMetrics()
   {
       $user = Auth::user();
       
       // Approval rate (percentage of approved vs total processed)
       $totalProcessed = BookingApproval::where('approver_id', $user->id)
           ->whereIn('status', ['approved', 'rejected'])
           ->count();
           
       $totalApproved = BookingApproval::where('approver_id', $user->id)
           ->where('status', 'approved')
           ->count();
           
       $approvalRate = $totalProcessed > 0 ? 
           round(($totalApproved / $totalProcessed) * 100, 1) : 0;

       // Average processing time trend (last 6 months)
       $monthlyProcessingTime = BookingApproval::where('approver_id', $user->id)
           ->whereIn('status', ['approved', 'rejected'])
           ->whereNotNull('updated_at')
           ->where('created_at', '>=', now()->subMonths(6))
           ->get()
           ->groupBy(function($approval) {
               return $approval->created_at->format('Y-m');
           })
           ->map(function($group) {
               return round($group->avg(function($approval) {
                   return $approval->created_at->diffInHours($approval->updated_at);
               }), 1);
           });

       return response()->json([
           'approval_rate' => $approvalRate,
           'processing_time_trend' => $monthlyProcessingTime
       ]);
   }
}