{{-- resources/views/admin/reports/index.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">Laporan Pemesanan Kendaraan</h1>

        @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
            <p>{{ session('error') }}</p>
        </div>
        @endif

        <div class="bg-white shadow rounded-lg p-6">
            <form action="{{ route('admin.reports.export-bookings') }}" method="GET">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Tanggal Mulai -->
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Mulai
                        </label>
                        <input type="date" id="start_date" name="start_date" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>

                    <!-- Tanggal Akhir -->
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Akhir
                        </label>
                        <input type="date" id="end_date" name="end_date" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                </div>

                <!-- Status -->
                <div class="mb-6">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        Status Pemesanan
                    </label>
                    <select id="status" name="status" 
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="">Semua Status</option>
                        <option value="pending">Menunggu Persetujuan</option>
                        <option value="approved">Disetujui</option>
                        <option value="rejected">Ditolak</option>
                        <option value="completed">Selesai</option>
                    </select>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="reset" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Reset Filter
                    </button>
                    <button type="submit" 
                            class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Export Excel
                    </button>
                </div>
            </form>
        </div>

        <!-- Panduan Export -->
        <div class="mt-8 bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Panduan Export Laporan</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <ul class="list-disc list-inside">
                            <li>Pilih rentang tanggal untuk memfilter data berdasarkan periode tertentu</li>
                            <li>Pilih status pemesanan untuk memfilter berdasarkan status tertentu</li>
                            <li>Kosongkan filter untuk mengexport semua data</li>
                            <li>File akan diexport dalam format .xlsx</li>
                            <li>Hasil export akan berisi detail lengkap pemesanan termasuk status persetujuan</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Validasi tanggal
    document.getElementById('end_date').addEventListener('change', function() {
        const startDate = document.getElementById('start_date').value;
        if (startDate && this.value < startDate) {
            alert('Tanggal akhir harus lebih besar atau sama dengan tanggal mulai');
            this.value = '';
        }
    });

    // Reset form
    document.querySelector('button[type="reset"]').addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('start_date').value = '';
        document.getElementById('end_date').value = '';
        document.getElementById('status').value = '';
    });
</script>
@endpush
@endsection