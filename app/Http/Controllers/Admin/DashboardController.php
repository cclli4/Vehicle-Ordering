<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Vehicle;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalVehicles = Vehicle::count();
        $availableVehicles = Vehicle::where('status', 'available')->count();
        $activeBookings = Booking::whereIn('status', ['pending', 'approved'])->count();
        
        // Data untuk grafik pemesanan per bulan
        $monthlyBookings = Booking::selectRaw('COUNT(*) as total, MONTH(created_at) as month')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->get();
        
        // Data untuk grafik status kendaraan
        $vehicleStatus = Vehicle::selectRaw('COUNT(*) as total, status')
            ->groupBy('status')
            ->get();

        return view('admin.dashboard', compact(
            'totalVehicles',
            'availableVehicles',
            'activeBookings',
            'monthlyBookings',
            'vehicleStatus'
        ));
    }
}