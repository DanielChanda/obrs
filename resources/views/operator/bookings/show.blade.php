@extends('operator.layouts.app')

@section('title', 'Booking Details #' . $booking->id)
@section('page-title', 'Booking Details')
@section('page-subtitle', 'Booking #' . $booking->id)

@section('header-actions')
    <div class="btn-group">
        <a href="{{ route('operator.bookings.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to Bookings
        </a>
        @if($booking->status === 'pending')
            <form action="{{ route('operator.bookings.confirm', $booking->id) }}" method="POST" class="d-inline">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-success" onclick="return confirm('Confirm this booking?')">
                    <i class="fas fa-check me-1"></i> Confirm
                </button>
            </form>
        @endif
        @if(in_array($booking->status, ['pending', 'confirmed']))
            <form action="{{ route('operator.bookings.cancel', $booking->id) }}" method="POST" class="d-inline">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Cancel this booking?')">
                    <i class="fas fa-times me-1"></i> Cancel
                </button>
            </form>
        @endif
        @if($booking->status === 'confirmed' && $booking->schedule->departure_time <= now())
            <form action="{{ route('operator.bookings.check-in', $booking->id) }}" method="POST" class="d-inline">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-info" onclick="return confirm('Check in passenger?')">
                    <i class="fas fa-user-check me-1"></i> Check In
                </button>
            </form>
        @endif
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-6">
        <!-- Booking Information -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0">
                    <i class="fas fa-ticket-alt me-2 text-primary"></i>Booking Information
                </h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center" 
                         style="width: 80px; height: 80px;">
                        <i class="fas fa-ticket-alt fa-2x text-white"></i>
                    </div>
                    <h4 class="mt-3">Booking #{{ $booking->id }}</h4>
                    <div class="d-flex justify-content-center gap-2">
                        <span class="badge bg-{{ match($booking->status) {
                            'confirmed' => 'success',
                            'pending' => 'warning',
                            'cancelled' => 'danger',
                            'completed' => 'info',
                            default => 'secondary'
                        } }} fs-6">
                            {{ ucfirst($booking->status) }}
                        </span>
                        <span class="badge bg-{{ $booking->payment_status === 'paid' ? 'success' : 'warning' }} fs-6">
                            {{ ucfirst($booking->payment_status) }}
                        </span>
                    </div>
                </div>
                
                <table class="table table-borderless">
                    <tr>
                        <td class="fw-semibold" width="40%">Booking Date:</td>
                        <td>{{ $booking->created_at->format('M j, Y g:i A') }}</td>
                    </tr>
                    <tr>
                        <td class="fw-semibold">Seat Number:</td>
                        <td>
                            <span class="badge bg-secondary fs-6">#{{ $booking->seat_number }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-semibold">Total Fare:</td>
                        <td class="h5 text-primary">${{ number_format($booking->schedule->fare, 2) }}</td>
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

        <!-- Passenger Information -->
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user me-2 text-primary"></i>Passenger Information
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary rounded-circle p-2 me-3">
                        <i class="fas fa-user text-white"></i>
                    </div>
                    <div>
                        <h5 class="mb-1">{{ $booking->user->name }}</h5>
                        <p class="text-muted mb-0">{{ $booking->user->email }}</p>
                    </div>
                </div>
                
                <table class="table table-sm table-borderless">
                    @if($booking->user->phone)
                    <tr>
                        <td class="fw-semibold" width="40%">Phone:</td>
                        <td>{{ $booking->user->phone }}</td>
                    </tr>
                    @endif
                    @if($booking->user->address)
                    <tr>
                        <td class="fw-semibold">Address:</td>
                        <td>{{ $booking->user->address }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td class="fw-semibold">Member Since:</td>
                        <td>{{ $booking->user->created_at->format('M j, Y') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <!-- Trip Information -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0">
                    <i class="fas fa-route me-2 text-primary"></i>Trip Information
                </h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <h4>{{ $booking->schedule->route->origin }} → {{ $booking->schedule->route->destination }}</h4>
                    <p class="text-muted">{{ $booking->schedule->departure_time->format('l, F j, Y') }}</p>
                </div>
                
                <div class="row text-center mb-4">
                    <div class="col-5">
                        <h5 class="text-primary">{{ $booking->schedule->route->origin }}</h5>
                        <p class="text-muted mb-0">Departure</p>
                        <strong>{{ $booking->schedule->departure_time->format('g:i A') }}</strong>
                    </div>
                    <div class="col-2 align-self-center">
                        <i class="fas fa-arrow-right fa-2x text-muted"></i>
                    </div>
                    <div class="col-5">
                        <h5 class="text-primary">{{ $booking->schedule->route->destination }}</h5>
                        <p class="text-muted mb-0">Arrival</p>
                        <strong>{{ $booking->schedule->arrival_time->format('g:i A') }}</strong>
                    </div>
                </div>
                
                <table class="table table-sm table-borderless">
                    <tr>
                        <td class="fw-semibold" width="40%">Bus:</td>
                        <td>{{ $booking->schedule->bus->bus_number }} ({{ $booking->schedule->bus->bus_type }})</td>
                    </tr>
                    <tr>
                        <td class="fw-semibold">Operator:</td>
                        <td>{{ $booking->schedule->bus->operator->name }}</td>
                    </tr>
                    <tr>
                        <td class="fw-semibold">Travel Time:</td>
                        <td>{{ $booking->schedule->departure_time->diff($booking->schedule->arrival_time)->format('%h hours %i minutes') }}</td>
                    </tr>
                    @if($booking->schedule->route->distance)
                    <tr>
                        <td class="fw-semibold">Distance:</td>
                        <td>{{ number_format($booking->schedule->route->distance) }} km</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0">
                    <i class="fas fa-cog me-2 text-primary"></i>Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if($booking->status === 'pending')
                        <form action="{{ route('operator.bookings.confirm', $booking->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success w-100" onclick="return confirm('Confirm this booking?')">
                                <i class="fas fa-check me-2"></i>Confirm Booking
                            </button>
                        </form>
                    @endif
                    
                    @if(in_array($booking->status, ['pending', 'confirmed']))
                        <form action="{{ route('operator.bookings.cancel', $booking->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Cancel this booking?')">
                                <i class="fas fa-times me-2"></i>Cancel Booking
                            </button>
                        </form>
                    @endif
                    
                    @if($booking->status === 'confirmed' && $booking->schedule->departure_time <= now())
                        <form action="{{ route('operator.bookings.check-in', $booking->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-info w-100" onclick="return confirm('Check in passenger?')">
                                <i class="fas fa-user-check me-2"></i>Check In Passenger
                            </button>
                        </form>
                    @endif
                    
                    <a href="javascript:window.print()" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-print me-2"></i>Print Booking Details
                    </a>
                </div>
            </div>
        </div>

        <!-- QR Code for Check-in -->
        @if($booking->status === 'confirmed')
        <div class="card shadow-sm mt-4">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0">
                    <i class="fas fa-qrcode me-2 text-primary"></i>Check-in Code
                </h5>
            </div>
            <div class="card-body text-center">
                <div class="mb-3">
                    {!! QrCode::size(120)->generate("BOOKING-{$booking->id}-{$booking->user->id}") !!}
                </div>
                <small class="text-muted">Scan for quick check-in</small>
                <p class="text-muted mt-2 small">Valid for: {{ $booking->schedule->bus->bus_number }}</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection