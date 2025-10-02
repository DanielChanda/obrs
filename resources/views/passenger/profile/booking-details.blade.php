@extends('passenger.layouts.app')

@section('title', 'Booking Details #' . $booking->id)
@section('page-title', 'Booking Details #' . $booking->id)

@section('header-actions')
    <a href="{{ route('passenger.profile.booking-history') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back to History
    </a>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <!-- Booking Information -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-ticket-alt me-2 text-primary"></i>Booking Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-semibold" width="40%">Booking ID:</td>
                                <td>#{{ $booking->id }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Status:</td>
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
                            </tr>
                            <tr>
                                <td class="fw-semibold">Payment Status:</td>
                                <td>
                                    <span class="badge bg-{{ $booking->payment_status === 'paid' ? 'success' : 'warning' }}">
                                        {{ ucfirst($booking->payment_status) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Booking Date:</td>
                                <td>{{ $booking->created_at->format('M j, Y g:i A') }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-semibold" width="40%">Seat Number:</td>
                                <td>{{ $booking->seat_number }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Fare:</td>
                                <td class="h5 text-primary">ZMW{{ number_format($booking->schedule->fare, 2) }}</td>
                            </tr>
                            @if($booking->payment)
                            <tr>
                                <td class="fw-semibold">Payment Method:</td>
                                <td>{{ ucfirst($booking->payment->method) }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Transaction ID:</td>
                                <td><code>{{ $booking->payment->transaction_id }}</code></td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Trip Details -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-route me-2 text-primary"></i>Trip Details
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-5">
                        <h5 class="text-primary">{{ $booking->schedule->route->origin }}</h5>
                        <p class="text-muted mb-0">Departure</p>
                        <strong>{{ $booking->schedule->departure_time->format('M j, Y g:i A') }}</strong>
                    </div>
                    <div class="col-md-2 align-self-center">
                        <i class="fas fa-arrow-right fa-2x text-muted"></i>
                    </div>
                    <div class="col-md-5">
                        <h5 class="text-primary">{{ $booking->schedule->route->destination }}</h5>
                        <p class="text-muted mb-0">Arrival</p>
                        <strong>{{ $booking->schedule->arrival_time->format('M j, Y g:i A') }}</strong>
                    </div>
                </div>
                
                <hr>
                
                <div class="row">
                    <div class="col-md-6">
                        <strong>Bus Details:</strong><br>
                        <span class="text-muted">
                            {{ $booking->schedule->bus->bus_number }} ({{ $booking->schedule->bus->bus_type }})
                        </span>
                    </div>
                    <div class="col-md-6">
                        <strong>Operator:</strong><br>
                        <span class="text-muted">
                            {{ $booking->schedule->bus->operator->name }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Actions Sidebar -->
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h6 class="card-title mb-0">
                    <i class="fas fa-cog me-2 text-primary"></i>Actions
                </h6>
            </div>
            <div class="card-body">
                @if($booking->payment_status === 'paid')
                    <a href="{{ route('passenger.ticket', $booking->id) }}" 
                       class="btn btn-success w-100 mb-2">
                        <i class="fas fa-ticket-alt me-1"></i> View E-Ticket
                    </a>
                    <a href="{{ route('passenger.ticket.download', $booking->id) }}" 
                       class="btn btn-outline-primary w-100 mb-2">
                        <i class="fas fa-download me-1"></i> Download PDF
                    </a>
                @endif
                
                @if($booking->status === 'confirmed' && $booking->schedule->departure_time > now())
                    <button class="btn btn-outline-danger w-100 mb-2" 
                            onclick="confirmCancel({{ $booking->id }})">
                        <i class="fas fa-times me-1"></i> Cancel Booking
                    </button>
                @endif
                
                <a href="javascript:window.print()" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-print me-1"></i> Print Details
                </a>
            </div>
        </div>
        
        <!-- QR Code for Quick Reference -->
        @if($booking->payment_status === 'paid')
        <div class="card shadow-sm mt-4">
            <div class="card-header bg-white">
                <h6 class="card-title mb-0">
                    <i class="fas fa-qrcode me-2 text-primary"></i>Quick Reference
                </h6>
            </div>
            <div class="card-body text-center">
                <div class="mb-2">
                    {!! QrCode::size(120)->generate("OBRS-BOOKING-{$booking->id}") !!}
                </div>
                <small class="text-muted">Scan for quick reference</small>
            </div>
        </div>
        @endif
    </div>
</div>

@if($booking->status === 'confirmed' && $booking->schedule->departure_time > now())
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
                    Cancellation may be subject to terms and conditions. Please check the cancellation policy.
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
@endif
@endsection

@push('scripts')
<script>
function confirmCancel(bookingId) {
    // Set form action
    document.getElementById('cancelForm').action = `/bookings/${bookingId}/cancel`;
    
    // Show modal
    var modal = new bootstrap.Modal(document.getElementById('cancelModal'));
    modal.show();
}
</script>
@endpush