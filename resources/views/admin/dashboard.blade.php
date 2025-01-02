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
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .dashboard-card {
            margin-bottom: 1rem;
        }
        .chart-container {
            height: 250px !important;
        }
    }
    
    @media (max-width: 640px) {
        .chart-container {
            height: 200px !important;
        }
    }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-4 sm:py-8">
    {{-- Alert Error --}}
    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 sm:mb-6" role="alert">
            <p class="font-medium">Error</p>
            <p>{{ session('error') }}</p>
        </div>
    @endif

    {{-- Statistik Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-4 sm:mb-8">
        {{-- Total Kendaraan Card --}}
        <div class="dashboard-card bg-white rounded-lg shadow-lg p-4 sm:p-6 border-l-4 border-indigo-500">
            <div class="flex items-center">
                <div class="p-2 sm:p-3 rounded-full bg-indigo-100 mr-3 sm:mr-4">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-gray-500 text-xs sm:text-sm">Total Kendaraan</p>
                    <p class="text-xl sm:text-3xl font-bold text-indigo-600">{{ number_format($totalVehicles) }}</p>
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
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 mb-4 sm:mb-8">
        {{-- Vehicle Status Chart --}}
        <div class="bg-white rounded-lg shadow-lg p-4 sm:p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base sm:text-lg font-semibold text-gray-700">Status Kendaraan</h3>
                <div class="text-xs sm:text-sm text-gray-500">Real-time Status</div>
            </div>
            <div class="chart-container relative" style="height: 300px;">
                <canvas id="vehicleStatusChart"></canvas>
            </div>
        </div>

        {{-- Monthly Usage Chart --}}
        <div class="bg-white rounded-lg shadow-lg p-4 sm:p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base sm:text-lg font-semibold text-gray-700">Tren Penggunaan Bulanan</h3>
                <div class="text-xs sm:text-sm text-gray-500">{{ date('Y') }}</div>
            </div>
            <div class="chart-container relative" style="height: 300px;">
                <canvas id="monthlyUsageChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Vehicle Type Distribution --}}
    <div class="bg-white rounded-lg shadow-lg p-4 sm:p-6 mb-4 sm:mb-8">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-base sm:text-lg font-semibold text-gray-700">Distribusi Tipe Kendaraan</h3>
        </div>
        <div class="chart-container relative" style="height: 300px;">
            <canvas id="vehicleTypeChart"></canvas>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    try {
        // Responsive font settings
        Chart.defaults.font.size = window.innerWidth < 768 ? 10 : 12;
        
        // Function to handle responsive chart options
        const getResponsiveOptions = (type) => {
            const isMobile = window.innerWidth < 768;
            const baseOptions = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: isMobile ? 'bottom' : 'right',
                        labels: {
                            boxWidth: isMobile ? 12 : 20,
                            padding: isMobile ? 10 : 20
                        }
                    }
                }
            };

            if (type === 'line') {
                return {
                    ...baseOptions,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                display: !isMobile
                            }
                        },
                        x: {
                            ticks: {
                                maxRotation: isMobile ? 45 : 0,
                                minRotation: isMobile ? 45 : 0
                            }
                        }
                    },
                    elements: {
                        point: {
                            radius: isMobile ? 2 : 3
                        },
                        line: {
                            borderWidth: isMobile ? 2 : 3
                        }
                    }
                };
            }

            return baseOptions;
        };

        // Update charts on window resize
        const updateChartsResponsiveness = () => {
            vehicleStatusChart.options = getResponsiveOptions('doughnut');
            monthlyUsageChart.options = getResponsiveOptions('line');
            vehicleTypeChart.options = getResponsiveOptions('pie');
            
            vehicleStatusChart.update();
            monthlyUsageChart.update();
            vehicleTypeChart.update();
        };

        // Vehicle Status Chart
        const vehicleStatusCtx = document.getElementById('vehicleStatusChart').getContext('2d');
        const vehicleStatusChart = new Chart(vehicleStatusCtx, {
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
                    borderWidth: window.innerWidth < 768 ? 1 : 2,
                    borderColor: '#ffffff'
                }]
            },
            options: getResponsiveOptions('doughnut')
        });

        // Monthly Usage Chart with similar responsive adjustments...
        const monthlyUsageCtx = document.getElementById('monthlyUsageChart').getContext('2d');
        const monthlyUsageChart = new Chart(monthlyUsageCtx, {
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
            options: getResponsiveOptions('line')
        });

        // Vehicle Type Distribution with similar responsive adjustments...
        const typeCtx = document.getElementById('vehicleTypeChart').getContext('2d');
        const vehicleTypes = @json($vehicleTypes);
        const vehicleTypeChart = new Chart(typeCtx, {
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
            options: getResponsiveOptions('pie')
        });

        // Add resize listener
        let resizeTimeout;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(updateChartsResponsiveness, 250);
        });

    } catch (error) {
        console.error('Error creating charts:', error);
    }
});
</script>
@endpush
@endsection