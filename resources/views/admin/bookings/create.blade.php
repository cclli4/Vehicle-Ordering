{{-- resources/views/admin/bookings/create.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-2xl font-bold mb-6">Buat Pemesanan Kendaraan</h2>

            @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">
                            Mohon periksa kembali form anda.
                        </p>
                        <ul class="mt-2 text-sm text-red-700">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endif

            <form action="{{ route('admin.bookings.store') }}" method="POST">
                @csrf
                
                <!-- Pilih Kendaraan -->
                <div class="mb-6">
                    <label for="vehicle_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Kendaraan
                    </label>
                    <select id="vehicle_id" name="vehicle_id" required 
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="">Pilih Kendaraan</option>
                        @foreach($vehicles as $vehicle)
                            <option value="{{ $vehicle->id }}" {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                                {{ $vehicle->vehicle_number }} - {{ $vehicle->brand }} {{ $vehicle->model }} ({{ $vehicle->type }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Pilih Driver -->
                <div class="mb-6">
                    <label for="driver_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Driver
                    </label>
                    <select id="driver_id" name="driver_id" required 
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="">Pilih Driver</option>
                        @foreach($drivers as $driver)
                            <option value="{{ $driver->id }}" {{ old('driver_id') == $driver->id ? 'selected' : '' }}>
                                {{ $driver->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tujuan Penggunaan -->
                <div class="mb-6">
                    <label for="purpose" class="block text-sm font-medium text-gray-700 mb-2">
                        Tujuan Penggunaan
                    </label>
                    <textarea id="purpose" name="purpose" rows="3" required
                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border-gray-300 rounded-md">{{ old('purpose') }}</textarea>
                </div>

                <!-- Tanggal dan Waktu -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Waktu Mulai
                        </label>
                        <input type="datetime-local" id="start_date" name="start_date" 
                            value="{{ old('start_date') }}" required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Waktu Selesai
                        </label>
                        <input type="datetime-local" id="end_date" name="end_date" 
                            value="{{ old('end_date') }}" required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                </div>

                <!-- Pilih Approver -->
                <div class="mb-6">
                    <label for="approver_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Approver Level 1
                    </label>
                    <select id="approver_id" name="approver_id" required 
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="">Pilih Approver</option>
                        @foreach($approvers as $approver)
                            <option value="{{ $approver->id }}" {{ old('approver_id') == $approver->id ? 'selected' : '' }}>
                                {{ $approver->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Catatan -->
                <div class="mb-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Catatan
                    </label>
                    <textarea id="notes" name="notes" rows="2"
                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border-gray-300 rounded-md">{{ old('notes') }}</textarea>
                </div>

                <!-- Tombol Submit -->
                <div class="flex justify-end">
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Buat Pemesanan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Validasi tanggal
    document.getElementById('end_date').addEventListener('change', function() {
        const startDate = new Date(document.getElementById('start_date').value);
        const endDate = new Date(this.value);
        
        if (endDate <= startDate) {
            alert('Waktu selesai harus lebih besar dari waktu mulai');
            this.value = '';
        }
    });
</script>
@endpush
@endsection