{{-- resources/views/approver/approvals/history.blade.php --}}
@extends('layouts.approver')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Riwayat Persetujuan</h1>
        <a href="{{ route('approver.approvals.index') }}" 
           class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded">
            Kembali ke Daftar Persetujuan
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        @if($approvalHistory->isEmpty())
            <div class="p-6 text-center text-gray-500">
                Belum ada riwayat persetujuan.
            </div>
        @else
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
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                {{ $statusText }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <a href="{{ route('approver.approvals.show', $booking->id) }}" 
                               class="text-indigo-600 hover:text-indigo-900">
                                Detail
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div class="mt-4">
        {{ $approvalHistory->links() }}
    </div>
</div>
@endsection