@extends('layouts.app')

@section('title', 'Detail Pemesanan')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Pemesanan</h1>
        <div>
            <a href="{{ route('admin.bookings.edit', $booking) }}" class="btn btn-warning">
                <i class="fas fa-edit fa-sm text-white-50"></i> Edit
            </a>
            <a href="{{ route('admin.bookings.print', $booking) }}" class="btn btn-success" target="_blank">
                <i class="fas fa-print fa-sm text-white-50"></i> Cetak
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Pemesanan</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">No. Pemesanan</label>
                        <p>{{ $booking->booking_number }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tanggal Pemesanan</label>
                        <p>{{ $booking->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Pemohon</label>
                        <p>{{ $booking->user->name }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Kendaraan</label>
                        <p>{{ $booking->vehicle->vehicle_number }} - {{ $booking->vehicle->brand }} {{ $booking->vehicle->model }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Driver</label>
                        <p>{{ $booking->driver->name }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tujuan Penggunaan</label>
                        <p>{{ $booking->purpose }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Waktu Mulai</label>
                        <p>{{ $booking->start_date->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Waktu Selesai</label>
                        <p>{{ $booking->end_date->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Status Persetujuan</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Status</label>
                        <p>
                            <span class="badge badge-{{ $booking->status_color }}">
                                {{ $booking->status_label }}
                            </span>
                        </p>
                    </div>  
                    <div class="mb-3">
                        <label class="form-label fw-bold">Approver Level 1</label>
                        <p>{{ $booking->approval1->approver->name ?? '-' }}</p>
                        @if($booking->approval1)
                        <p>
                            <span class="badge badge-{{ $booking->approval1->status_color }}">
                                {{ $booking->approval1->status_label }}
                            </span>
                        </p>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Approver Level 2</label>
                        <p>{{ $booking->approval2->approver->name ?? '-' }}</p>
                        @if($booking->approval2)
                        <p>
                            <span class="badge badge-{{ $booking->approval2->status_color }}">
                                {{ $booking->approval2->status_label }}
                            </span>
                        </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection