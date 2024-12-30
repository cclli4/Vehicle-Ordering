<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicles = Vehicle::latest()->paginate(10);
        return view('admin.vehicles.index', compact('vehicles'));
    }

    public function create()
    {
        return view('admin.vehicles.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_number' => 'required|string|unique:vehicles',
            'type' => 'required|in:passenger,cargo',
            'brand' => 'required|string',
            'model' => 'required|string',
            'capacity' => 'required|integer|min:1',
            'ownership' => 'required|in:company,rental',
        ]);

        try {
            Vehicle::create($validated);
            return redirect()->route('admin.vehicles.index')
                ->with('success', 'Kendaraan berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Error creating vehicle: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menambahkan kendaraan.');
        }
    }

    public function show(Vehicle $vehicle)
    {
        return view('admin.vehicles.show', compact('vehicle'));
    }

    public function edit(Vehicle $vehicle)
    {
        return view('admin.vehicles.edit', compact('vehicle'));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $validated = $request->validate([
            'vehicle_number' => 'required|string|unique:vehicles,vehicle_number,' . $vehicle->id,
            'type' => 'required|in:passenger,cargo',
            'brand' => 'required|string',
            'model' => 'required|string',
            'capacity' => 'required|integer|min:1',
            'ownership' => 'required|in:company,rental',
        ]);

        try {
            $vehicle->update($validated);
            return redirect()->route('admin.vehicles.index')
                ->with('success', 'Kendaraan berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Error updating vehicle: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memperbarui kendaraan.');
        }
    }

    public function destroy(Vehicle $vehicle)
    {
        try {
            $vehicle->delete();
            return redirect()->route('admin.vehicles.index')
                ->with('success', 'Kendaraan berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Error deleting vehicle: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menghapus kendaraan.');
        }
    }

    public function setMaintenance(Vehicle $vehicle)
    {
        try {
            $vehicle->update(['status' => 'maintenance']);
            return back()->with('success', 'Status kendaraan berhasil diubah ke maintenance.');
        } catch (\Exception $e) {
            Log::error('Error setting vehicle maintenance: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat mengubah status kendaraan.');
        }
    }

    public function setAvailable(Vehicle $vehicle)
    {
        try {
            $vehicle->update(['status' => 'available']);
            return back()->with('success', 'Status kendaraan berhasil diubah ke tersedia.');
        } catch (\Exception $e) {
            Log::error('Error setting vehicle available: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat mengubah status kendaraan.');
        }
    }
}