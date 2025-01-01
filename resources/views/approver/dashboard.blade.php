{{-- resources/views/approver/dashboard.blade.php --}}
@extends('layouts.approver')

@section('content')
<div class="container mx-auto px-4 py-8">
    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    {{-- Statistik Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        {{-- Pending Approvals Card --}}
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 mr-4">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Menunggu Persetujuan</p>
                    <p class="text-3xl font-bold text-yellow-600">{{ $approvalStats['pending'] }}</p>
                </div>
            </div>
        </div>

        {{-- Approved Card --}}
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 mr-4">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Disetujui</p>
                    <p class="text-3xl font-bold text-green-600">{{ $approvalStats['approved'] }}</p>
                </div>
            </div>
        </div>

        {{-- Rejected Card --}}
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 mr-4">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Ditolak</p>
                    <p class="text-3xl font-bold text-red-600">{{ $approvalStats['rejected'] }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Pending Approvals List --}}
    <div class="bg-white rounded-lg shadow mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">Pemesanan Yang Perlu Disetujui</h2>
        </div>
        <div class="p-6">
            @if($pendingApprovals->isEmpty())
                <p class="text-gray-500 text-center py-4">Tidak ada pemesanan yang perlu disetujui</p>
            @else
                <div class="space-y-4">
                    @foreach($pendingApprovals as $booking)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-800">{{ $booking->booking_number }}</p>
                                <p class="text-sm text-gray-500">
                                    {{ $booking->user->name }} - {{ $booking->vehicle->vehicle_number }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    {{ $booking->start_date->format('d M Y H:i') }}
                                </p>
                            </div>
                            <a href="/approver/approvals/{{$booking->id}}/show "
                               class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700">
                                Review
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Recent History --}}
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">Riwayat Persetujuan Terbaru</h2>
        </div>
        <div class="p-6">
            @if($recentApprovals->isEmpty())
                <p class="text-gray-500 text-center py-4">Belum ada riwayat persetujuan</p>
            @else
                <div class="space-y-4">
                    @foreach($recentApprovals as $approval)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-800">{{ $approval->booking->booking_number }}</p>
                                <p class="text-sm text-gray-500">
                                    {{ $approval->booking->user->name }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    {{ $approval->created_at->format('d M Y H:i') }}
                                </p>
                            </div>
                            <span class="px-3 py-1 text-xs rounded-full 
                                {{ $approval->status === 'approved' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $approval->status === 'approved' ? 'Disetujui' : 'Ditolak' }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection