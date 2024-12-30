<?php

namespace App\Http\Controllers\Approver;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingApproval;
use App\Models\User; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; 
class ApprovalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); 
    }

    public function index()
    {
        $user = Auth::user(); 
        
        $pendingApprovals = Booking::whereHas('approvals', function($query) use ($user) {
            $query->where('approver_id', $user->id)
                  ->where('status', 'pending');
        })->with(['user', 'vehicle', 'driver', 'approvals.approver'])
          ->latest()
          ->paginate(10);

        return view('approver.approvals.index', compact('pendingApprovals'));
    }

    public function show(Booking $booking)
    {
        $this->authorize('view', $booking);
        
        $booking->load(['user', 'vehicle', 'driver', 'approvals.approver']);
        
        return view('approver.approvals.show', compact('booking'));
    }

    public function approve(Request $request, Booking $booking)
    {
        $this->authorize('approve', $booking);
        
        try {
            DB::beginTransaction();
            
            // Update approval status
            $approval = BookingApproval::where('booking_id', $booking->id)
                ->where('approver_id', Auth::id()) 
                ->firstOrFail();
                
            $approval->update([
                'status' => 'approved',
                'notes' => $request->notes
            ]);

            // kalo ini was the last required approval
            if ($booking->current_approval_level == 2) {
                $booking->update([
                    'status' => 'approved'
                ]);
            } else {
                // buat next level approval
                $nextApprover = User::where('role', 'approver')
                    ->where('approval_level', 2)
                    ->first();

                if ($nextApprover) {
                    BookingApproval::create([
                        'booking_id' => $booking->id,
                        'approver_id' => $nextApprover->id,
                        'level' => 2,
                        'status' => 'pending'
                    ]);

                    $booking->update([
                        'current_approval_level' => 2
                    ]);
                }
            }

            DB::commit();

            // log activity
            Log::info('Booking approved', [
                'booking_id' => $booking->id,
                'approver_id' => Auth::id(),
                'level' => $approval->level
            ]);

            return redirect()->route('approver.approvals.index')
                ->with('success', 'Pemesanan berhasil disetujui.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error approving booking', [
                'error' => $e->getMessage(),
                'booking_id' => $booking->id
            ]);

            return back()->with('error', 'Terjadi kesalahan saat menyetujui pemesanan.');
        }
    }

    public function reject(Request $request, Booking $booking)
    {
        $this->authorize('approve', $booking);

        $request->validate([
            'notes' => 'required|string'
        ]);
        
        try {
            DB::beginTransaction();

            // update approval status
            $approval = BookingApproval::where('booking_id', $booking->id)
                ->where('approver_id', Auth::id()) 
                ->firstOrFail();
                
            $approval->update([
                'status' => 'rejected',
                'notes' => $request->notes
            ]);

            // update booking status
            $booking->update([
                'status' => 'rejected'
            ]);

            // update vehicle status back to available
            $booking->vehicle->update([
                'status' => 'available'
            ]);

            DB::commit();

            // log activity
            Log::info('Booking rejected', [
                'booking_id' => $booking->id,
                'approver_id' => Auth::id(), 
                'level' => $approval->level
            ]);

            return redirect()->route('approver.approvals.index')
                ->with('success', 'Pemesanan telah ditolak.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error rejecting booking', [
                'error' => $e->getMessage(),
                'booking_id' => $booking->id
            ]);

            return back()->with('error', 'Terjadi kesalahan saat menolak pemesanan.');
        }
    }

    public function history()
    {
        $user = Auth::user(); 
        
        $approvalHistory = Booking::whereHas('approvals', function($query) use ($user) {
            $query->where('approver_id', $user->id)
                  ->whereIn('status', ['approved', 'rejected']);
        })->with(['user', 'vehicle', 'driver', 'approvals.approver'])
          ->latest()
          ->paginate(10);

        return view('approver.approvals.history', compact('approvalHistory'));
    }
}