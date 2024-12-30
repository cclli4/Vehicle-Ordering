@extends('layouts.app')

@section('title', 'Edit Pemesanan')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Pemesanan</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Edit Pemesanan</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.bookings.update', $booking) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label for="vehicle_id" class="form-label">Kendaraan</label>
                    <select name="vehicle_id" id="vehicle_id" class="form-control @error('vehicle_id') is-invalid @enderror" required>
                        <option value="">Pilih Kendaraan</option>
                        @foreach($vehicles as $vehicle)
                        <option value="{{ $vehicle->id }}" {{ old('vehicle_id', $booking->vehicle_id) == $vehicle->id ? 'selected' : '' }}>
                            {{ $vehicle->vehicle_number }} - {{ $vehicle->brand }} {{ $vehicle->model }}
                        </option>
                        @endforeach
                    </select>
                    @error('vehicle_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="driver_id" class="form-label">Driver</label>
                    <select name="driver_id" id="driver_id" class="form-control @error('driver_id') is-invalid @enderror" required>
                        <option value="">Pilih Driver</option>
                        @foreach($drivers as $driver)
                        <option value="{{ $driver->id }}" {{ old('driver_id', $booking->driver_id) == $driver->id ? 'selected' : '' }}>
                            {{ $driver->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('driver_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="purpose" class="form-label">Tujuan Penggunaan</label>
                    <textarea name="purpose" id="purpose" class="form-control @error('purpose') is-invalid @enderror" rows="3" required>{{ old('purpose', $booking->purpose) }}</textarea>
                    @error('purpose')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="start_date" class="form-label">Waktu Mulai</label>
                            <input type="datetime-local" name="start_date" id="start_date" 
                                class="form-control @error('start_date') is-invalid @enderror"
                                value="{{ old('start_date', $booking->start_date->format('Y-m-d\TH:i')) }}" required>
                            @error('start_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="end_date" class="form-label">Waktu Selesai</label>
                            <input type="datetime-local" name="end_date" id="end_date" 
                                class="form-control @error('end_date') is-invalid @enderror"
                                value="{{ old('end_date', $booking->end_date->format('Y-m-d\TH:i')) }}" required>
                            @error('end_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="notes" class="form-label">Catatan</label>
                    <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes', $booking->notes) }}</textarea>
                    @error('notes')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection