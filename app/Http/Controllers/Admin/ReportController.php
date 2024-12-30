<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Exports\BookingsExport;
use Illuminate\Http\Request;
use Carbon\Carbon;
use League\Csv\Writer;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    public function exportBookings(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'nullable|in:pending,approved,rejected,completed'
        ]);

        try {
            // Buat CSV writer
            $csv = Writer::createFromString('');
            $csv->setDelimiter(',');
            $csv->setEnclosure('"');
            $csv->setEscape('\\');

            // Tambahkan headers
            $csv->insertOne([
                'No. Pemesanan',
                'Tanggal Pemesanan',
                'Pemohon',
                'Departemen',
                'Kendaraan',
                'Driver',
                'Tujuan Penggunaan',
                'Waktu Mulai',
                'Waktu Selesai',
                'Status',
                'Approver Level 1',
                'Status Level 1',
                'Approver Level 2',
                'Status Level 2',
                'Catatan'
            ]);

            // Query data
            $query = Booking::with(['user', 'vehicle', 'driver', 'approvals.approver']);

            if ($request->start_date && $request->end_date) {
                $query->whereBetween('created_at', [
                    Carbon::parse($request->start_date)->startOfDay(),
                    Carbon::parse($request->end_date)->endOfDay()
                ]);
            }

            if ($request->status) {
                $query->where('status', $request->status);
            }

            $bookings = $query->orderBy('created_at', 'desc')->get();

            // Tambahkan data ke CSV
            foreach ($bookings as $booking) {
                $approval1 = $booking->approvals->where('level', 1)->first();
                $approval2 = $booking->approvals->where('level', 2)->first();

                $csv->insertOne([
                    $booking->booking_number,
                    $booking->created_at->format('d/m/Y H:i'),
                    $booking->user->name,
                    $booking->user->department ?? '-',
                    $booking->vehicle->vehicle_number . ' - ' . $booking->vehicle->brand . ' ' . $booking->vehicle->model,
                    $booking->driver->name,
                    $booking->purpose,
                    $booking->start_date->format('d/m/Y H:i'),
                    $booking->end_date->format('d/m/Y H:i'),
                    $this->getStatusLabel($booking->status),
                    $approval1 ? $approval1->approver->name : '-',
                    $approval1 ? $this->getStatusLabel($approval1->status) : '-',
                    $approval2 ? $approval2->approver->name : '-',
                    $approval2 ? $this->getStatusLabel($approval2->status) : '-',
                    $booking->notes ?? '-'
                ]);
            }

            // Simpan file
            $fileName = 'laporan_pemesanan_' . Carbon::now()->format('dmY_His') . '.csv';
            Storage::put('public/exports/' . $fileName, $csv->toString());

            return response()->download(
                storage_path('app/public/exports/' . $fileName),
                $fileName,
                ['Content-Type' => 'text/csv; charset=UTF-8']
            )->deleteFileAfterSend();

        } catch (\Exception $e) {
            Log::error('Error exporting bookings: ' . $e->getMessage(), [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return back()->with('error', 'Terjadi kesalahan saat mengexport laporan.');
        }
    }

    private function getStatusLabel($status): string
    {
        return match($status) {
            'pending' => 'Menunggu Persetujuan',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'completed' => 'Selesai',
            default => ucfirst($status)
        };
    }

    public function vehiclesReport()
    {
        // Implementasi laporan kendaraan
        return view('admin.reports.vehicles');
    }

    public function usageReport()
    {
        // Implementasi laporan penggunaan
        return view('admin.reports.usage');
    }
}