@props(['booking'])

<div class="card booking-card mb-3 shadow-sm">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="d-flex align-items-center mb-2">
                    <span class="badge bg-{{ $booking->status === 'confirmed' ? 'success' : 'warning' }} me-2">
                        {{ ucfirst($booking->status) }}
                    </span>
                    <small class="text-muted">Booking #{{ $booking->id }}</small>
                </div>
                
                <h6 class="card-title mb-1">
                    <i class="fas fa-route me-2 text-primary"></i>
                    {{ $booking->schedule->route->origin }} → {{ $booking->schedule->route->destination }}
                </h6>
                
                <div class="row text-muted small">
                    <div class="col-auto">
                        <i class="fas fa-bus me-1"></i> {{ $booking->schedule->bus->bus_number }}
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar me-1"></i> 
                        {{ $booking->schedule->departure_time->format('M j, Y') }}
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clock me-1"></i> 
                        {{ $booking->schedule->departure_time->format('g:i A') }}
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-chair me-1"></i> Seat# {{ $booking->seat_number }}
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 text-end">
                <div class="h5 text-primary mb-2">ZMW{{ number_format($booking->schedule->fare, 2) }}</div>
                
                @if($booking->payment_status === 'paid')
                    <a href="{{ route('passenger.ticket', $booking->id) }}" 
                       class="btn btn-success btn-sm">
                        <i class="fas fa-ticket-alt me-1"></i>View Ticket
                    </a>
                @else
                    <span class="badge bg-secondary">Payment Pending</span>
                @endif
            </div>
        </div>
    </div>
</div>