<div class="btn-group" role="group" aria-label="Booking actions">
    <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-sm btn-info" title="View">
        <i class="fas fa-eye"></i>
    </a>
    <a href="{{ route('admin.bookings.edit', $booking) }}" class="btn btn-sm btn-warning" title="Edit">
        <i class="fas fa-edit"></i>
    </a>
    <a href="{{ route('admin.bookings.print', $booking) }}" class="btn btn-sm btn-success" title="Print" target="_blank">
        <i class="fas fa-print"></i>
    </a>
</div>