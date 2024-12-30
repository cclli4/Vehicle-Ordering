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

{{-- Vehicle Usage Trends --}}
<div class="bg-white rounded-lg shadow-lg p-6 mb-8">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-700">Penggunaan Kendaraan</h3>
        <div class="text-sm text-gray-500">Analisis Penggunaan</div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Vehicle Type Distribution --}}
        <div class="relative" style="height: 300px;">
            <canvas id="vehicleTypeChart"></canvas>
        </div>
        {{-- Usage Distribution --}}
        <div class="relative" style="height: 300px;">
            <canvas id="usageDistributionChart"></canvas>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    try {
        // Vehicle Status Chart (kode yang sudah ada)
        const vehicleStatusCtx = document.getElementById('vehicleStatusChart').getContext('2d');
        const vehicleStatusChart = new Chart(vehicleStatusCtx, {
            type: 'doughnut',
                data: vehicleStatusData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '65%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                font: {
                                    size: 12
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            titleFont: {
                                size: 14,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 13
                            },
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.raw || 0;
                                    const total = context.dataset.data.reduce((acc, val) => acc + val, 0);
                                    const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    },
                    animation: {
                        animateScale: true,
                        animateRotate: true
                    }
                }
        });

        // Monthly Usage Chart
        const monthlyUsageCtx = document.getElementById('monthlyUsageChart').getContext('2d');
        const monthlyUsageChart = new Chart(monthlyUsageCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Penggunaan',
                    data: [65, 75, 70, 80, 85, 90, 88, 87, 92, 88, 85, 95],
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
                        grid: {
                            display: true,
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Vehicle Type Distribution
        const typeCtx = document.getElementById('vehicleTypeChart').getContext('2d');
        new Chart(typeCtx, {
            type: 'pie',
            data: {
                labels: ['Angkutan Orang', 'Angkutan Barang'],
                datasets: [{
                    data: [60, 40],
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

        // Usage Distribution Chart
        const usageCtx = document.getElementById('usageDistributionChart').getContext('2d');
        new Chart(usageCtx, {
            type: 'bar',
            data: {
                labels: ['Kantor Pusat', 'Cabang', 'Site A', 'Site B', 'Site C'],
                datasets: [{
                    label: 'Penggunaan',
                    data: [30, 25, 20, 15, 10],
                    backgroundColor: 'rgb(59, 130, 246)'
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
                        beginAtZero: true
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
