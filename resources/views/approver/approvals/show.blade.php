{{-- resources/views/approver/approvals/show.blade.php --}}
@extends('layouts.approver')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <a href="/approver/approvals/index"
               class="text-indigo-600 hover:text-indigo-900">
                &larr; Kembali ke Daftar Persetujuan
            </a>
        </div>

        @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
            <p>{{ session('error') }}</p>
        </div>
        @endif

        <div class="bg-white shadow rounded-lg overflow-hidden">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">
                    Detail Pemesanan #{{ $booking->booking_number }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Dibuat pada {{ $booking->created_at->format('d M Y H:i') }}
                </p>
            </div>

            <!-- Informasi Pemesanan -->
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Pemohon</h3>
                        <p class="mt-1">{{ $booking->user->name }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Departemen</h3>
                        <p class="mt-1">{{ $booking->user->department ?? '-' }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Kendaraan</h3>
                        <p class="mt-1">{{ $booking->vehicle->vehicle_number }} - 
                           {{ $booking->vehicle->brand }} {{ $booking->vehicle->model }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Driver</h3>
                        <p class="mt-1">{{ $booking->driver->name }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Waktu Mulai</h3>
                        <p class="mt-1">{{ $booking->start_date->format('d M Y H:i') }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Waktu Selesai</h3>
                        <p class="mt-1">{{ $booking->end_date->format('d M Y H:i') }}</p>
                    </div>
                </div>

                <div class="mt-4">
                    <h3 class="text-sm font-medium text-gray-500">Tujuan Penggunaan</h3>
                    <p class="mt-1">{{ $booking->purpose }}</p>
                </div>

                @if($booking->notes)
                <div class="mt-4">
                    <h3 class="text-sm font-medium text-gray-500">Catatan</h3>
                    <p class="mt-1">{{ $booking->notes }}</p>
                </div>
                @endif
            </div>

            <!-- Riwayat Persetujuan -->
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Riwayat Persetujuan</h3>
                <div class="space-y-4">
                    @foreach($booking->approvals as $approval)
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            @if($approval->status === 'approved')
                                <span class="h-6 w-6 rounded-full bg-green-100 flex items-center justify-center">
                                    <svg class="h-4 w-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </span>
                            @elseif($approval->status === 'rejected')
                                <span class="h-6 w-6 rounded-full bg-red-100 flex items-center justify-center">
                                    <svg class="h-4 w-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                </span>
                            @else
                                <span class="h-6 w-6 rounded-full bg-gray-100 flex items-center justify-center">
                                    <svg class="h-4 w-4 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 000 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                                    </svg>
                                </span>
                            @endif
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-sm font-medium text-gray-900">
                                Level {{ $approval->level }} - {{ $approval->approver->name }}
                            </p>
                            <p class="mt-1 text-sm text-gray-500">
                                Status: 
                                @if($approval->status === 'approved')
                                    <span class="text-green-600">Disetujui</span>
                                @elseif($approval->status === 'rejected')
                                    <span class="text-red-600">Ditolak</span>
                                @else
                                    <span class="text-yellow-600">Menunggu</span>
                                @endif
                            </p>
                            @if($approval->notes)
                            <p class="mt-1 text-sm text-gray-500">
                                Catatan: {{ $approval->notes }}
                            </p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Form Persetujuan -->
            @if($booking->status === 'pending' && $booking->current_approval_level === auth()->user()->approval_level)
            <div class="px-6 py-4">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Form Persetujuan</h3>
                
                <div class="space-y-4">
                    <!-- Form Setuju -->
                    <form action="{{ route('approver.approvals.approve', $booking->id) }}" method="POST" class="inline">
                        @csrf
                        <div class="mb-4">
                            <label for="approve_notes" class="block text-sm font-medium text-gray-700">Catatan (opsional)</label>
                            <textarea id="approve_notes" name="notes" rows="2" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                        </div>
                        <button type="submit" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Setujui Pemesanan
                        </button>
                    </form>

                    <!-- Form Tolak -->
                    <form action="{{ route('approver.approvals.reject', $booking->id) }}" method="POST" class="mt-4">
                        @csrf
                        {{-- Alasan Penolakan --}}
                        <div class="mb-4">
                            <label for="reject_notes" class="block text-sm font-medium text-gray-700">
                                Alasan Penolakan <span class="text-red-500">*</span>
                            </label>
                            <textarea id="reject_notes" name="notes" rows="2" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </textarea>
                        </div>

                        {{-- Tombol Tolak --}}
                        <button type="submit"
                            class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Tolak Pemesanan
                        </button>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Konfirmasi sebelum menolak pemesanan
    document.querySelector('form[action*="reject"]')?.addEventListener('submit', function(e) {
        e.preventDefault();
        if (confirm('Apakah Anda yakin ingin menolak pemesanan ini?')) {
            this.submit();
        }
    });
</script>
@endpush
@endsection