{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.admin')

@push('styles')
<style>
    .dashboard-card {
        transition: all 0.3s ease;
    }
    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-8">
    {{-- Alert Error --}}
    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
            <p class="font-medium">Error</p>
            <p>{{ session('error') }}</p>
        </div>
    @endif

    {{-- Statistik Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        {{-- Total Kendaraan Card --}}
        <div class="dashboard-card bg-white rounded-lg shadow-lg p-6 border-l-4 border-indigo-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-indigo-100 mr-4">
                    <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Total Kendaraan</p>
                    <p class="text-3xl font-bold text-indigo-600">{{ number_format($totalVehicles) }}</p>
                </div>
            </div>
        </div>

        {{-- Kendaraan Tersedia Card --}}
        <div class="dashboard-card bg-white rounded-lg shadow-lg p-6 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 mr-4">
                    <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Kendaraan Tersedia</p>
                    <p class="text-3xl font-bold text-green-600">{{ number_format($availableVehicles) }}</p>
                </div>
            </div>
        </div>

        {{-- Pemesanan Aktif Card --}}
        <div class="dashboard-card bg-white rounded-lg shadow-lg p-6 border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 mr-4">
                    <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Pemesanan Aktif</p>
                    <p class="text-3xl font-bold text-blue-600">{{ number_format($activeBookings) }}</p>
                </div>
            </div>
        </div>
    </div>

     {{-- Chart Section --}}
     <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        {{-- Vehicle Status Chart --}}
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-700">Status Kendaraan</h3>
                <div class="text-sm text-gray-500">Real-time Status</div>
            </div>
            <div class="relative" style="height: 300px;">
                <canvas id="vehicleStatusChart"></canvas>
            </div>
        </div>

        {{-- Monthly Usage Chart --}}
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-700">Tren Penggunaan Bulanan</h3>
                <div class="text-sm text-gray-500">{{ date('Y') }}</div>
            </div>
            <div class="relative" style="height: 300px;">
                <canvas id="monthlyUsageChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Vehicle Type Distribution --}}
    <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-700">Distribusi Tipe Kendaraan</h3>
        </div>
        <div class="relative" style="height: 300px;">
            <canvas id="vehicleTypeChart"></canvas>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    try {
        // Vehicle Status Chart
        const vehicleStatusCtx = document.getElementById('vehicleStatusChart').getContext('2d');
        new Chart(vehicleStatusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Tersedia', 'Sedang Digunakan', 'Maintenance'],
                datasets: [{
                    data: [
                        {{ $availableVehicles }}, 
                        {{ $inUseVehicles }}, 
                        {{ $maintenanceVehicles }}
                    ],
                    backgroundColor: [
                        'rgb(34, 197, 94)',
                        'rgb(59, 130, 246)',
                        'rgb(249, 115, 22)'
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Monthly Usage Chart
        const monthlyUsageCtx = document.getElementById('monthlyUsageChart').getContext('2d');
        new Chart(monthlyUsageCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Jumlah Pemesanan',
                    data: @json($monthlyData),
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });

        // Vehicle Type Distribution
        const typeCtx = document.getElementById('vehicleTypeChart').getContext('2d');
        const vehicleTypes = @json($vehicleTypes);
        new Chart(typeCtx, {
            type: 'pie',
            data: {
                labels: vehicleTypes.map(t => t.label),
                datasets: [{
                    data: vehicleTypes.map(t => t.value),
                    backgroundColor: [
                        'rgb(99, 102, 241)',
                        'rgb(249, 115, 22)'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

    } catch (error) {
        console.error('Error creating charts:', error);
    }
});
</script>
@endpush
@endsection