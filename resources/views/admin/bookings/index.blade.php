@extends('layouts.app')

@section('title', 'Daftar Pemesanan Kendaraan')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Daftar Pemesanan Kendaraan</h1>
        <a href="{{ route('admin.bookings.create') }}" class="btn btn-primary">
            <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Pemesanan
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Pemesanan</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No. Pemesanan</th>
                            <th>Tanggal</th>
                            <th>Pemohon</th>
                            <th>Kendaraan</th>
                            <th>Driver</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $booking)
                        <tr>
                            <td>{{ $booking->booking_number }}</td>
                            <td>{{ $booking->created_at->format('d/m/Y') }}</td>
                            <td>{{ $booking->user->name }}</td>
                            <td>{{ $booking->vehicle->vehicle_number }} - {{ $booking->vehicle->brand }}</td>
                            <td>{{ $booking->driver->name }}</td>
                            <td>
                                <span class="badge badge-{{ $booking->status_color }}">
                                    {{ $booking->status_label }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.bookings.edit', $booking) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('admin.bookings.print', $booking) }}" class="btn btn-success btn-sm" target="_blank">
                                    <i class="fas fa-print"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush

@push('scripts')
<script src="/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="/vendor/datatables/dataTables.bootstrap4.min.js"></script>
<script>
$(document).ready(function() {
    $('#dataTable').DataTable();
});
</script>
@endpush