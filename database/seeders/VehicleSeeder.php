<?php
namespace Database\Seeders;

use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    public function run()
    {
        $vehicles = [
            [
                'vehicle_number' => 'B 1234 CD',
                'type' => 'passenger', 
                'brand' => 'Toyota',
                'model' => 'Innova',
                'capacity' => 7,
                'status' => 'available',
                'ownership' => 'company',
            ],
            [
                'vehicle_number' => 'B 5678 EF',
                'type' => 'cargo',
                'brand' => 'Mitsubishi',
                'model' => 'Fuso',
                'capacity' => 8000,
                'status' => 'available',
                'ownership' => 'rental',
            ]
        ];

        foreach ($vehicles as $vehicle) {
            Vehicle::create($vehicle);
        }
    }
}