@extends('layouts.admin')

@section('title', 'Edit Kendaraan')

@section('content')
<div class="container mx-auto">
    <div class="max-w-xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-2xl font-semibold mb-6">Edit Kendaraan</h2>

            <form action="{{ route('admin.vehicles.update', $vehicle->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="vehicle_number" class="block text-sm font-medium text-gray-700">Nomor Kendaraan</label>
                    <input type="text" name="vehicle_number" id="vehicle_number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required value="{{ old('vehicle_number', $vehicle->vehicle_number) }}">
                    @error('vehicle_number')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="type" class="block text-sm font-medium text-gray-700">Tipe</label>
                    <select name="type" id="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        <option value="passenger" {{ old('type', $vehicle->type) == 'passenger' ? 'selected' : '' }}>Angkutan Orang</option>
                        <option value="cargo" {{ old('type', $vehicle->type) == 'cargo' ? 'selected' : '' }}>Angkutan Barang</option>
                    </select>
                    @error('type')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="brand" class="block text-sm font-medium text-gray-700">Merk</label>
                    <input type="text" name="brand" id="brand" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required value="{{ old('brand', $vehicle->brand) }}">
                    @error('brand')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="model" class="block text-sm font-medium text-gray-700">Model</label>
                    <input type="text" name="model" id="model" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required value="{{ old('model', $vehicle->model) }}">
                    @error('model')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="capacity" class="block text-sm font-medium text-gray-700">Kapasitas</label>
                    <input type="number" name="capacity" id="capacity" min="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required value="{{ old('capacity', $vehicle->capacity) }}">
                    @error('capacity')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="ownership" class="block text-sm font-medium text-gray-700">Kepemilikan</label>
                    <select name="ownership" id="ownership" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        <option value="company" {{ old('ownership', $vehicle->ownership) == 'company' ? 'selected' : '' }}>Perusahaan</option>
                        <option value="rental" {{ old('ownership', $vehicle->ownership) == 'rental' ? 'selected' : '' }}>Sewa</option>
                    </select>
                    @error('ownership')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <a href="{{ route('admin.vehicles.index') }}" class="btn bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 mr-2">Batal</a>
                    <button type="submit" class="btn bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 