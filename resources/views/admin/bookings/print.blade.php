{{-- resources/views/admin/bookings/print.blade.php --}}
<!DOCTYPE html>
<html>
<head>
   <meta charset="utf-8">
   <title>Print Booking #{{ $booking->booking_number }}</title>
   <style>
       body {
           font-family: Arial, sans-serif;
           line-height: 1.6;
           margin: 0;
           padding: 20px;
       }
       .header {
           text-align: center;
           margin-bottom: 30px;
       }
       .header h1 {
           margin: 0;
           font-size: 24px;
       }
       .booking-info {
           margin-bottom: 30px;
       }
       .booking-info table {
           width: 100%;
           border-collapse: collapse;
       }
       .booking-info th, .booking-info td {
           padding: 8px;
           border: 1px solid #ddd;
           text-align: left;
       }
       .booking-info th {
           background-color: #f5f5f5;
           width: 200px;
       }
       .approval-info {
           margin-bottom: 30px;
       }
       .signatures {
           margin-top: 50px;
           display: flex;
           justify-content: space-between;
       }
       .signature-box {
           text-align: center;
           width: 200px;
       }
       .signature-line {
           border-top: 1px solid #000;
           margin-top: 50px;
       }
       @media print {
           .no-print {
               display: none;
           }
       }
   </style>
</head>
<body>
   <div class="header">
       <h1>FORM PEMESANAN KENDARAAN</h1>
       <p>No: {{ $booking->booking_number }}</p>
   </div>

   <div class="booking-info">
       <table>
           <tr>
               <th>Pemohon</th>
               <td>{{ $booking->user->name }}</td>
           </tr>
           <tr>
               <th>Kendaraan</th>
               <td>{{ $booking->vehicle->vehicle_number }} - {{ $booking->vehicle->brand }} {{ $booking->vehicle->model }}</td>
           </tr>
           <tr>
               <th>Driver</th>
               <td>{{ $booking->driver->name }}</td>
           </tr>
           <tr>
               <th>Tujuan Penggunaan</th>
               <td>{{ $booking->purpose }}</td>
           </tr>
           <tr>
               <th>Waktu Mulai</th>
               <td>{{ $booking->start_date->format('d/m/Y H:i') }}</td>
           </tr>
           <tr>
               <th>Waktu Selesai</th>
               <td>{{ $booking->end_date->format('d/m/Y H:i') }}</td>
           </tr>
           <tr>
               <th>Status</th>
               <td>{{ ucfirst($booking->status) }}</td>
           </tr>
           <tr>
               <th>Catatan</th>
               <td>{{ $booking->notes ?? '-' }}</td>
           </tr>
       </table>
   </div>

   <div class="approval-info">
       <h3>Status Persetujuan:</h3>
       <table>
           <tr>
               <th>Level</th>
               <th>Approver</th>
               <th>Status</th>
               <th>Tanggal</th>
               <th>Catatan</th>
           </tr>
           @foreach($booking->approvals as $approval)
           <tr>
               <td>Level {{ $approval->level }}</td>
               <td>{{ $approval->approver->name }}</td>
               <td>{{ ucfirst($approval->status) }}</td>
               <td>{{ $approval->updated_at->format('d/m/Y H:i') }}</td>
               <td>{{ $approval->notes ?? '-' }}</td>
           </tr>
           @endforeach
       </table>
   </div>

   <div class="signatures">
       <div class="signature-box">
           <p>Pemohon</p>
           <div class="signature-line"></div>
           <p>{{ $booking->user->name }}</p>
       </div>
       @foreach($booking->approvals as $approval)
       <div class="signature-box">
           <p>Approver Level {{ $approval->level }}</p>
           <div class="signature-line"></div>
           <p>{{ $approval->approver->name }}</p>
       </div>
       @endforeach
       <div class="signature-box">
           <p>Driver</p>
           <div class="signature-line"></div>
           <p>{{ $booking->driver->name }}</p>
       </div>
   </div>

   <button class="no-print" onclick="window.print()">Print</button>
</body>
</html>