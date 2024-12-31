<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Vehicle;
use App\Models\User;
use App\Models\BookingApproval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth; 

class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); 
    }

    public function create()
    {
        $vehicles = Vehicle::where('status', 'available')->get();
        $drivers = User::where('role', 'driver')->get();
        $approvers = User::where('role', 'approver')
            ->where('approval_level', 1)
            ->get();

        return view('admin.bookings.create', compact('vehicles', 'drivers', 'approvers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'driver_id' => 'required|exists:users,id',
            'approver_id' => 'required|exists:users,id', 
            'purpose' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'notes' => 'nullable|string'
        ]);

        try {
            $userId = Auth::id();
            
            $booking = Booking::create([
                'user_id' => $userId,
                'vehicle_id' => $validated['vehicle_id'],
                'driver_id' => $validated['driver_id'],
                'purpose' => $validated['purpose'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'notes' => $validated['notes'] ?? null,
                'status' => 'pending', 
                'booking_number' => 'BK' . date('YmdHis') . rand(1000, 9999), // Generate booking number
            ]);

            // Create approval record
            BookingApproval::create([
                'booking_id' => $booking->id,
                'approver_id' => $validated['approver_id'], 
                'level' => 1,
                'status' => 'pending'
            ]);

            // Update vehicle status
            Vehicle::where('id', $validated['vehicle_id'])
                  ->update(['status' => 'in_use']);

            // Log activity
            Log::info('New booking created', [
                'booking_id' => $booking->id,
                'created_by' => $userId
            ]);

            return redirect()->route('admin.bookings.index')
                ->with('success', 'Pemesanan berhasil dibuat.');
        } catch (\Exception $e) {
            Log::error('Error creating booking', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id() ?? 'unknown'
            ]);

            return back()->with('error', 'Terjadi kesalahan saat membuat pemesanan.');
        }
    }

    // method index buat menampilkan daftar pemesanan
    public function index()
    {
        $bookings = Booking::with(['user', 'vehicle', 'driver', 'approvals.approver'])
            ->latest()
            ->paginate(10);

        return view('admin.bookings.index', compact('bookings'));
    }

    // method show buat menampilkan detail pemesanan
    public function show(Booking $booking)
    {
        $booking->load(['user', 'vehicle', 'driver', 'approvals.approver']);
        return view('admin.bookings.show', compact('booking'));
    }

    public function edit(Booking $booking)
    {
        $vehicles = Vehicle::all();
        $drivers = User::where('role', 'driver')->get();
        return view('admin.bookings.edit', compact('booking', 'vehicles', 'drivers'));
    }

    public function update(Request $request, Booking $booking)
{
    $validatedData = $request->validate([
        'vehicle_id' => 'required|exists:vehicles,id',
        'driver_id' => 'required|exists:users,id',
        'purpose' => 'required|string',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after:start_date',
        'notes' => 'nullable|string',
    ]);

    $booking->update($validatedData);

    return redirect()->route('admin.bookings.show', $booking)
        ->with('success', 'Booking updated successfully');
}

public function print(Booking $booking)
{
    return view('admin.bookings.print', compact('booking'));
}

}