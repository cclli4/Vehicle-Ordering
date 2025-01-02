{{-- resources/views/approver/approvals/index.blade.php --}}
@extends('layouts.approver')

@section('content')
<div class="container mx-auto px-4 py-8">
    {{-- Header Section --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <h1 class="text-2xl font-bold">Daftar Persetujuan Pemesanan</h1>
        <a href="/approver/approvals/history" 
           class="inline-flex items-center bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
            </svg>
            Lihat Riwayat
        </a>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md" role="alert">
        <p>{{ session('success') }}</p>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md" role="alert">
        <p>{{ session('error') }}</p>
    </div>
    @endif

    {{-- Main Content --}}
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        @if($pendingApprovals->isEmpty())
            <div class="p-6 text-center text-gray-500">
                Tidak ada pemesanan yang memerlukan persetujuan.
            </div>
        @else
            {{-- Desktop Table View --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
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
                                Tanggal Mulai
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
                        @foreach($pendingApprovals as $booking)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $booking->booking_number }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $booking->user->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $booking->vehicle->vehicle_number }} - 
                                {{ $booking->vehicle->brand }} {{ $booking->vehicle->model }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $booking->start_date->format('d M Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Menunggu Persetujuan
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="/approver/approvals/{{$booking->id}}/show" 
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
                @foreach($pendingApprovals as $booking)
                <div class="p-4 space-y-3">
                    <div class="flex justify-between items-start">
                        <div class="space-y-1">
                            <p class="font-medium text-gray-900">{{ $booking->booking_number }}</p>
                            <p class="text-sm text-gray-500">{{ $booking->user->name }}</p>
                        </div>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            Menunggu Persetujuan
                        </span>
                    </div>
                    
                    <div class="space-y-1">
                        <p class="text-sm text-gray-500">
                            {{ $booking->vehicle->vehicle_number }} - 
                            {{ $booking->vehicle->brand }} {{ $booking->vehicle->model }}
                        </p>
                        <p class="text-sm text-gray-500">
                            {{ $booking->start_date->format('d M Y H:i') }}
                        </p>
                    </div>
                    
                    <div class="flex justify-end">
                        <a href="/approver/approvals/{{$booking->id}}/show" 
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
        {{ $pendingApprovals->links() }}
    </div>
</div>
@endsection