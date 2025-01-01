{{-- resources/views/approver/bookings/review.blade.php --}}
@extends('layouts.approver')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">Review Pemesanan</h2>
            </div>

            <div class="p-6 space-y-6">
                {{-- Booking Details --}}
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Nomor Pemesanan</h3>
                        <p class="mt-1 text-lg font-semibold">{{ $booking->booking_number }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Status</h3>
                        <p class="mt-1">
                            <span class="px-3 py-1 text-sm rounded-full bg-yellow-100 text-yellow-800">
                                Menunggu Persetujuan
                            </span>
                        </p>
                    </div>
                </div>

                {{-- User & Vehicle Details --}}
                <div class="border-t border-gray-200 pt-6">
                    <dl class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Pemohon</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $booking->user->name }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Kendaraan</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $booking->vehicle->vehicle_number }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Tanggal Mulai</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $booking->start_date->format('d M Y H:i') }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Tanggal Selesai</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $booking->end_date->format('d M Y H:i') }}</dd>
                        </div>

                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Tujuan</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $booking->destination }}</dd>
                        </div>

                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Keperluan</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $booking->purpose }}</dd>
                        </div>
                    </dl>
                </div>

                {{-- Approval Form --}}
                <div class="border-t border-gray-200 pt-6">
                    <div class="space-y-4">
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700">Catatan (Opsional)</label>
                            <textarea id="notes" name="notes" rows="3" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                        </div>

                        <div class="flex justify-end space-x-4">
                            <form action="{{ route('approver.bookings.reject', $booking) }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="notes" id="reject-notes">
                                <button type="submit" onclick="setNotes('reject-notes')"
                                    class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700">
                                    Tolak
                                </button>
                            </form>

                            <form action="{{ route('approver.bookings.approve', $booking) }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="notes" id="approve-notes">
                                <button type="submit" onclick="setNotes('approve-notes')"
                                    class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700">
                                    Setujui
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function setNotes(targetId) {
    document.getElementById(targetId).value = document.getElementById('notes').value;
}
</script>
@endsection