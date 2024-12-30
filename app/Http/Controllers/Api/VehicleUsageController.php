<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VehicleUsageController extends Controller
{
    public function getMonthlyUsage()
    {
        $monthlyData = Booking::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
            DB::raw('COUNT(*) as totalBookings'),
            DB::raw('COUNT(CASE WHEN status = "completed" THEN 1 END) as completedBookings')
        )
            ->where('created_at', '>=', Carbon::now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(function ($item) {
                $date = Carbon::createFromFormat('Y-m', $item->month);
                return [
                    'month' => $date->format('M Y'),
                    'totalBookings' => $item->totalBookings,
                    'completedBookings' => $item->completedBookings,
                ];
            });

        return response()->json($monthlyData);
    }

    public function getVehicleTypeDistribution()
    {
        $typeDistribution = Vehicle::select('type', DB::raw('COUNT(*) as value'))
            ->groupBy('type')
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->type === 'passenger' ? 'Angkutan Orang' : 'Angkutan Barang',
                    'value' => $item->value
                ];
            });

        return response()->json($typeDistribution);
    }

    public function getVehicleStatus()
    {
        $statusDistribution = Vehicle::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get()
            ->map(function ($item) {
                return [
                    'status' => match($item->status) {
                        'available' => 'Tersedia',
                        'in_use' => 'Sedang Digunakan',
                        'maintenance' => 'Maintenance',
                        default => $item->status
                    },
                    'count' => $item->count
                ];
            });

        return response()->json($statusDistribution);
    }
}