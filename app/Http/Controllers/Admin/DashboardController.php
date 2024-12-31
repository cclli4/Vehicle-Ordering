<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            // Statistik Dasar
            $totalVehicles = Vehicle::count();
            $availableVehicles = Vehicle::where('status', 'available')->count();
            $inUseVehicles = Vehicle::where('status', 'in_use')->count();
            $maintenanceVehicles = Vehicle::where('status', 'maintenance')->count();
            $activeBookings = Booking::whereIn('status', ['pending', 'approved'])->count();

            // Data untuk Monthly Usage Chart
            $monthlyBookings = DB::table('bookings')
                ->select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as total'))
                ->whereYear('created_at', date('Y'))
                ->groupBy('month')
                ->orderBy('month')
                ->get()
                ->pluck('total', 'month')
                ->toArray();

            // Isi data untuk bulan yang kosong
            $monthlyData = [];
            for ($i = 1; $i <= 12; $i++) {
                $monthlyData[] = $monthlyBookings[$i] ?? 0;
            }

            // Data untuk Vehicle Type Distribution
            $vehicleTypes = Vehicle::select('type', DB::raw('count(*) as total'))
                ->groupBy('type')
                ->get()
                ->map(function($item) {
                    return [
                        'label' => $item->type === 'passenger' ? 'Angkutan Orang' : 'Angkutan Barang',
                        'value' => $item->total
                    ];
                });

            return view('admin.dashboard', compact(
                'totalVehicles',
                'availableVehicles',
                'inUseVehicles',
                'maintenanceVehicles',
                'activeBookings',
                'monthlyData',
                'vehicleTypes'
            ));

        } catch (\Exception $e) {
            Log::error('Error in dashboard: ' . $e->getMessage());
            return view('admin.dashboard', [
                'totalVehicles' => 0,
                'availableVehicles' => 0,
                'inUseVehicles' => 0,
                'maintenanceVehicles' => 0,
                'activeBookings' => 0,
                'monthlyData' => array_fill(0, 12, 0),
                'vehicleTypes' => []
            ])->with('error', 'Terjadi kesalahan saat memuat data dashboard.');
        }
    }
}