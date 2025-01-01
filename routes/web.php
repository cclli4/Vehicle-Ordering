<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\VehicleController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Approver\DashboardController as ApproverDashboardController;
use App\Http\Controllers\Approver\ApprovalController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TestController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirect root ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
    
    // Profile Routes
    Route::get('profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});

// Admin Routes
// hapus middleware role dulu
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Vehicle Management
    Route::resource('vehicles', VehicleController::class);
    Route::post('vehicles/{vehicle}/maintenance', [VehicleController::class, 'setMaintenance'])->name('vehicles.maintenance');
    Route::post('vehicles/{vehicle}/available', [VehicleController::class, 'setAvailable'])->name('vehicles.available');
    
    // Booking Management
    Route::resource('bookings', BookingController::class);
    Route::get('bookings/{booking}/print', [BookingController::class, 'print'])->name('bookings.print');
    
    
    // User Management
    Route::resource('users', UserController::class);
    Route::post('users/{user}/activate', [UserController::class, 'activate'])->name('users.activate');
    Route::post('users/{user}/deactivate', [UserController::class, 'deactivate'])->name('users.deactivate');
    
    // Reports
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/export-bookings', [ReportController::class, 'exportBookings'])->name('reports.export-bookings');
    Route::get('reports/vehicles', [ReportController::class, 'vehiclesReport'])->name('reports.vehicles');
    Route::get('reports/usage', [ReportController::class, 'usageReport'])->name('reports.usage');
});

// Approver Routes
Route::middleware(['auth'])->prefix('approver')->name('approver')->group(function () {
    // Route::get('dashboard', [App\Http\Controllers\Approver\DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('dashboard', [ApproverDashboardController::class, 'index'])->name('dashboard');

    // Approval Management
    Route::get('approvals', [ApprovalController::class, 'index'])->name('approvals.index');
    Route::get('approvals/{booking}', [ApprovalController::class, 'show'])->name('approvals.show');
    Route::post('approvals/{booking}/approve', [ApprovalController::class, 'approve'])->name('approvals.approve');
    Route::post('approvals/{booking}/reject', [ApprovalController::class, 'reject'])->name('approvals.reject');
    // Route::get('/approvals/history', [ApprovalController::class, 'history'])->name('approvals.history');
    Route::get('/history', [ApprovalController::class, 'history'])->name('approvals.history');
    
    // Reports Access
    Route::get('reports/bookings', [ReportController::class, 'index'])->name('reports.bookings');
});
// Route::get('test', [ApproverDashboardController::class, 'test'])->name('test');

// Error Pages
Route::fallback(function () {
    return view('errors.404');
});