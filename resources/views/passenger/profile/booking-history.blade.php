@extends('passenger.layouts.app')

@section('title', 'Booking History')
@section('page-title', 'Booking History')

@section('header-actions')
    <a href="{{ route('passenger.profile.show') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back to Profile
    </a>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">
            <i class="fas fa-history me-2 text-primary"></i>Booking History
            @if($filters['status'] !== 'all')
                <small class="text-muted">({{ $filters['statusOptions'][$filters['status']] }})</small>
            @endif
        </h5>
        <div class="btn-group">
            <button type="button" class="btn btn-outline-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                <i class="fas fa-filter me-1"></i> 
                {{ $filters['statusOptions'][$filters['status']] }}
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                @foreach($filters['statusOptions'] as $key => $label)
                    <li>
                        <a class="dropdown-item {{ $filters['status'] === $key ? 'active' : '' }}" 
                           href="?status={{ $key }}">
                            {{ $label }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="card-body">
        @if($bookings->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Route</th>
                            <th>Departure</th>
                            <th>Seat</th>
                            <th>Fare</th>
                            <th>Status</th>
                            <th>Payment</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $booking)
                            <tr>
                                <td>
                                    <strong>#{{ $booking->id }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $booking->created_at->format('M j, Y') }}</small>
                                </td>
                                <td>
                                    <strong>{{ $booking->schedule->route->origin }} → {{ $booking->schedule->route->destination }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $booking->schedule->bus->bus_number }}</small>
                                </td>
                                <td>
                                    {{ $booking->schedule->departure_time->format('M j, Y') }}
                                    <br>
                                    <small class="text-muted">{{ $booking->schedule->departure_time->format('g:i A') }}</small>
                                </td>
                                <td>{{ $booking->seat_number }}</td>
                                <td>ZMW{{ number_format($booking->schedule->fare, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ match($booking->status) {
                                        'confirmed' => 'success',
                                        'pending' => 'warning',
                                        'cancelled' => 'danger',
                                        'completed' => 'info',
                                        default => 'secondary'
                                    } }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $booking->payment_status === 'paid' ? 'success' : 'warning' }}">
                                        {{ ucfirst($booking->payment_status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        @if($booking->payment_status === 'paid')
                                            <a href="{{ route('passenger.ticket', $booking->id) }}" 
                                               class="btn btn-outline-primary" title="View Ticket">
                                                <i class="fas fa-ticket-alt"></i>
                                            </a>
                                        @endif
                                        <a href="{{ route('passenger.profile.booking-details', $booking->id) }}" 
                                           class="btn btn-outline-info" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($booking->status === 'confirmed' && $booking->schedule->departure_time > now())
                                            <button class="btn btn-outline-danger" 
                                                    title="Cancel Booking"
                                                    onclick="confirmCancel({{ $booking->id }})">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    Showing {{ $bookings->firstItem() }} to {{ $bookings->lastItem() }} 
                    of {{ $bookings->total() }} results
                </div>
                <div>
                    {{ $bookings->appends(['status' => $filters['status']])->links() }}
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-ticket-alt fa-4x text-muted mb-3"></i>
                <h5 class="text-muted">No bookings found</h5>
                <p class="text-muted">
                    @if($filters['status'] !== 'all')
                        No {{ $filters['statusOptions'][$filters['status']] }} found.
                    @else
                        You haven't made any bookings yet.
                    @endif
                </p>
                <a href="{{ route('passenger.search.form') }}" class="btn btn-primary">
                    <i class="fas fa-search me-1"></i> Book Your First Trip
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Cancel Booking Modal -->
<div class="modal fade" id="cancelModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cancel Booking</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to cancel this booking?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Cancellation may be subject to terms and conditions.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Keep Booking</button>
                <form id="cancelForm" method="POST" action="#">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Cancel Booking</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmCancel(bookingId) {
    // Set form action (you'll need to create this route)
    document.getElementById('cancelForm').action = `/bookings/${bookingId}/cancel`;
    
    // Show modal
    var modal = new bootstrap.Modal(document.getElementById('cancelModal'));
    modal.show();
}
</script>
@endpush