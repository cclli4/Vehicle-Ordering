<?php

namespace App\Exports;

use App\Models\Booking;
use League\Csv\Writer;
use Carbon\Carbon;

class BookingsExport
{
    protected $startDate;
    protected $endDate;
    protected $status;

    public function __construct($startDate = null, $endDate = null, $status = null)
    {
        $this->startDate = $startDate ? Carbon::parse($startDate) : null;
        $this->endDate = $endDate ? Carbon::parse($endDate) : null;
        $this->status = $status;
    }

    public function export()
    {
        // Create CSV writer
        $csv = Writer::createFromPath(storage_path('app/public/temp.csv'), 'w+');
        $csv->setDelimiter(',');
        $csv->setEnclosure('"');
        $csv->setEscape('\\');

        // Set headers
        $headers = [
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
        ];

        // Insert headers
        $csv->insertOne($headers);

        // Get data
        $query = Booking::with(['user', 'vehicle', 'driver', 'approvals.approver']);

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('created_at', [
                $this->startDate->startOfDay(),
                $this->endDate->endOfDay()
            ]);
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        $bookings = $query->orderBy('created_at', 'desc')->get();

        // Add data rows
        foreach ($bookings as $booking) {
            $approval1 = $booking->approvals->where('level', 1)->first();
            $approval2 = $booking->approvals->where('level', 2)->first();

            $row = [
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
            ];

            $csv->insertOne($row);
        }

        // Generate filename and move file
        $fileName = 'laporan_pemesanan_' . Carbon::now()->format('dmY_His') . '.csv';
        $newPath = storage_path('app/public/' . $fileName);
        rename(storage_path('app/public/temp.csv'), $newPath);

        return $fileName;
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
}