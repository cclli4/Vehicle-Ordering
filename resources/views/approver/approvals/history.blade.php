{{-- resources/views/approver/approvals/history.blade.php --}}
@extends('layouts.approver')

@section('content')
<div class="container mx-auto px-4 py-8">
    {{-- Header Section --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <h1 class="text-2xl font-bold">Riwayat Persetujuan</h1>
        <a href="/approver/approvals" 
           class="inline-flex items-center bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Kembali ke Daftar Persetujuan
        </a>
    </div>

    {{-- Main Content --}}
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        @if($approvalHistory->isEmpty())
            <div class="p-6 text-center text-gray-500">
                Belum ada riwayat persetujuan.
            </div>
        @else
            {{-- Desktop Table View --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                No. Pemesanan
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Pemohon
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Kendaraan
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($approvalHistory as $booking)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $booking->updated_at->format('d M Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $booking->booking_number }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $booking->user->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $booking->vehicle->vehicle_number }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $approval = $booking->approvals->where('approver_id', auth()->id())->first();
                                    $statusClass = $approval->status === 'approved' 
                                        ? 'bg-green-100 text-green-800'
                                        : 'bg-red-100 text-red-800';
                                    $statusText = $approval->status === 'approved' 
                                        ? 'Disetujui'
                                        : 'Ditolak';
                                @endphp
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                    {{ $statusText }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('approver.approvals.show', $booking->id) }}" 
                                   class="text-indigo-600 hover:text-indigo-900 inline-flex items-center">
                                    Detail
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Mobile Card View --}}
            <div class="md:hidden divide-y divide-gray-200">
                @foreach($approvalHistory as $booking)
                <div class="p-4 space-y-3">
                    <div class="flex justify-between items-start">
                        <div class="space-y-1">
                            <p class="font-medium text-gray-900">{{ $booking->booking_number }}</p>
                            <p class="text-sm text-gray-500">{{ $booking->updated_at->format('d M Y H:i') }}</p>
                        </div>
                        @php
                            $approval = $booking->approvals->where('approver_id', auth()->id())->first();
                            $statusClass = $approval->status === 'approved' 
                                ? 'bg-green-100 text-green-800'
                                : 'bg-red-100 text-red-800';
                            $statusText = $approval->status === 'approved' 
                                ? 'Disetujui'
                                : 'Ditolak';
                        @endphp
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusClass }}">
                            {{ $statusText }}
                        </span>
                    </div>
                    
                    <div class="space-y-1">
                        <p class="text-sm text-gray-500">
                            <span class="font-medium">Pemohon:</span> {{ $booking->user->name }}
                        </p>
                        <p class="text-sm text-gray-500">
                            <span class="font-medium">Kendaraan:</span> {{ $booking->vehicle->vehicle_number }}
                        </p>
                    </div>
                    
                    <div class="flex justify-end">
                        <a href="{{ route('approver.approvals.show', $booking->id) }}" 
                           class="text-indigo-600 hover:text-indigo-900 inline-flex items-center">
                            Detail
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $approvalHistory->links() }}
    </div>
</div>
@endsection