<?php
// routes/api.php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\VehicleUsageController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Vehicle Usage Routes
Route::middleware('auth')->group(function () {
    Route::get('/vehicle-usage/monthly', [VehicleUsageController::class, 'getMonthlyUsage']);
    Route::get('/vehicle-usage/by-type', [VehicleUsageController::class, 'getVehicleTypeDistribution']);
    Route::get('/vehicle-usage/status', [VehicleUsageController::class, 'getVehicleStatus']);
});