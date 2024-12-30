<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Vehicle;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminDashboardController extends Controller
{
    public function index()
    {
        try {
            // Statistik Dasar
            $totalVehicles = Vehicle::count();
            $availableVehicles = Vehicle::where('status', 'available')->count();
            $activeBookings = Booking::whereIn('status', ['pending', 'approved'])->count();

            // Data Pemesanan Bulanan - data tidak null
            $monthlyBookings = DB::table('bookings')
                ->select(
                    DB::raw('MONTH(created_at) as month'),
                    DB::raw('COUNT(*) as total')
                )
                ->whereYear('created_at', Carbon::now()->year)
                ->groupBy(DB::raw('MONTH(created_at)'))
                ->orderBy('month')
                ->get()
                ->toArray();

            // kalau ga ada data booking, buat data dummy buat 12 bulan
            if (empty($monthlyBookings)) {
                $monthlyBookings = collect(range(1, 12))->map(function ($month) {
                    return [
                        'month' => $month,
                        'total' => 0
                    ];
                })->toArray();
            }

            // Status Kendaraan - data tidak null
            $vehicleStatusRaw = DB::table('vehicles')
                ->select('status', DB::raw('count(*) as total'))
                ->groupBy('status')
                ->get();

            // Transform status kendaraan ke format yang diinginkan
            $vehicleStatus = $vehicleStatusRaw->map(function ($item) {
                return [
                    'status' => match($item->status) {
                        'available' => 'Tersedia',
                        'in_use' => 'Sedang Digunakan',
                        'maintenance' => 'Dalam Perbaikan',
                        default => ucfirst($item->status)
                    },
                    'total' => $item->total
                ];
            })->toArray();

            // kalau ga ada data kendaraan, buat data dummy
            if (empty($vehicleStatus)) {
                $vehicleStatus = [
                    ['status' => 'Tersedia', 'total' => 0],
                    ['status' => 'Sedang Digunakan', 'total' => 0],
                    ['status' => 'Dalam Perbaikan', 'total' => 0]
                ];
            }

            // Debug buat liat struktur data
            // dd($monthlyBookings, $vehicleStatus);

            return view('admin.dashboard', compact(
                'totalVehicles',
                'availableVehicles',
                'activeBookings',
                'monthlyBookings',
                'vehicleStatus'
            ));

        } catch (\Exception $e) {
            // Log error menggunakan Log facade
            Log::error('Error in AdminDashboardController: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Return view dengan data minimal
            return view('admin.dashboard', [
                'totalVehicles' => 0,
                'availableVehicles' => 0,
                'activeBookings' => 0,
                'monthlyBookings' => [],
                'vehicleStatus' => []
            ])->with('error', 'Terjadi kesalahan saat memuat data dashboard.');
        }
    }
}