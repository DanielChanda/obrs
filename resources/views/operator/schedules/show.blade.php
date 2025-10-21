@extends('operator.layouts.app')

@section('title', 'Schedule Details #' . $schedule->id)
@section('page-title', 'Schedule Details')
@section('page-subtitle', $schedule->route->origin . ' → ' . $schedule->route->destination)

@section('header-actions')
    <div class="btn-group">
        <a href="{{ route('operator.schedules.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to Schedules
        </a>
        @if($schedule->status === 'scheduled' && $schedule->bookings->count() === 0)
            <a href="{{ route('operator.schedules.edit', $schedule->id) }}" class="btn btn-warning">
                <i class="fas fa-edit me-1"></i> Edit Schedule
            </a>
        @endif
        @if($schedule->status === 'scheduled')
            <button type="button" class="btn btn-danger" 
                    data-bs-toggle="modal" data-bs-target="#cancelModal">
                <i class="fas fa-times me-1"></i> Cancel Schedule
            </button>
        @endif
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-4">
        <!-- Schedule Information Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2 text-primary"></i>Schedule Information
                </h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center" 
                         style="width: 80px; height: 80px;">
                        <i class="fas fa-calendar-alt fa-2x text-white"></i>
                    </div>
                    <h4 class="mt-3">Schedule #{{ $schedule->id }}</h4>
                    <span class="badge bg-{{ match($schedule->status) {
                        'scheduled' => 'success',
                        'completed' => 'info',
                        'cancelled' => 'danger',
                        default => 'secondary'
                    } }} fs-6">
                        {{ ucfirst($schedule->status) }}
                    </span>
                </div>
                
                <table class="table table-borderless">
                    <tr>
                        <td class="fw-semibold" width="40%">Bus:</td>
                        <td>
                            <i class="fas fa-bus text-primary me-2"></i>
                            {{ $schedule->bus->bus_number }} ({{ $schedule->bus->bus_type }})
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-semibold">Route:</td>
                        <td>
                            <i class="fas fa-route text-success me-2"></i>
                            {{ $schedule->route->origin }} → {{ $schedule->route->destination }}
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-semibold">Departure:</td>
                        <td>
                            <i class="fas fa-clock text-warning me-2"></i>
                            {{ $schedule->departure_time->format('M j, Y g:i A') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-semibold">Arrival:</td>
                        <td>
                            <i class="fas fa-flag-checkered text-info me-2"></i>
                            {{ $schedule->arrival_time->format('M j, Y g:i A') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-semibold">Fare:</td>
                        <td>
                            <i class="fas fa-dollar-sign text-success me-2"></i>
                            ZMW{{ number_format($schedule->fare, 2) }}
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-semibold">Available Seats:</td>
                        <td>
                            <i class="fas fa-users text-muted me-2"></i>
                            {{ $schedule->available_seats }} / {{ $schedule->bus->capacity }}
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-semibold">Travel Time:</td>
                        <td>
                            <i class="fas fa-hourglass-half text-muted me-2"></i>
                            {{ $schedule->departure_time->diff($schedule->arrival_time)->format('%h hours %i minutes') }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h6 class="card-title mb-0">
                    <i class="fas fa-chart-bar me-2 text-primary"></i>Booking Statistics
                </h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Total Bookings:</span>
                    <strong class="text-primary">{{ $stats['totalBookings'] }}</strong>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Confirmed Bookings:</span>
                    <strong class="text-success">{{ $stats['confirmedBookings'] }}</strong>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Revenue:</span>
                    <strong class="text-success">ZMW{{ number_format($stats['revenue'], 2) }}</strong>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span>Occupancy Rate:</span>
                    <strong class="text-info">
                        @if($schedule->bus->capacity > 0)
                            {{ number_format(($stats['totalBookings'] / $schedule->bus->capacity) * 100, 1) }}%
                        @else
                            0%
                        @endif
                    </strong>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <!-- Bookings List -->
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0">
                    <i class="fas fa-ticket-alt me-2 text-primary"></i>Bookings
                    <span class="badge bg-primary ms-2">{{ $schedule->bookings->count() }}</span>
                </h5>
            </div>
            <div class="card-body">
                @if($schedule->bookings->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Booking ID</th>
                                    <th>Passenger</th>
                                    <th>Seat</th>
                                    <th>Status</th>
                                    <th>Payment</th>
                                    <th>Booked On</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($schedule->bookings as $booking)
                                    <tr>
                                        <td>#{{ $booking->id }}</td>
                                        <td>{{ $booking->user->name }}</td>
                                        <td>{{ $booking->seat_number }}</td>
                                        <td>
                                            <span class="badge bg-{{ $booking->status === 'confirmed' ? 'success' : 'warning' }}">
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $booking->payment_status === 'paid' ? 'success' : 'warning' }}">
                                                {{ ucfirst($booking->payment_status) }}
                                            </span>
                                        </td>
                                        <td>{{ $booking->created_at->format('M j, g:i A') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-ticket-alt fa-2x text-muted mb-3"></i>
                        <p class="text-muted mb-0">No bookings for this schedule yet</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Schedule Timeline -->
        <div class="card shadow-sm mt-4">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0">
                    <i class="fas fa-road me-2 text-primary"></i>Journey Timeline
                </h5>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-marker bg-primary"></div>
                        <div class="timeline-content">
                            <h6 class="text-primary">Departure</h6>
                            <p class="mb-1">{{ $schedule->departure_time->format('l, F j, Y') }}</p>
                            <p class="text-muted mb-0">{{ $schedule->departure_time->format('g:i A') }} from {{ $schedule->route->origin }}</p>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-marker bg-success"></div>
                        <div class="timeline-content">
                            <h6 class="text-success">Estimated Arrival</h6>
                            <p class="mb-1">{{ $schedule->arrival_time->format('l, F j, Y') }}</p>
                            <p class="text-muted mb-0">{{ $schedule->arrival_time->format('g:i A') }} at {{ $schedule->route->destination }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-3 p-3 bg-light rounded">
                    <div class="row text-center">
                        <div class="col-4">
                            <strong class="d-block text-primary">{{ $schedule->departure_time->diff($schedule->arrival_time)->format('%h') }}</strong>
                            <small class="text-muted">Hours</small>
                        </div>
                        <div class="col-4">
                            <strong class="d-block text-primary">{{ $schedule->departure_time->diff($schedule->arrival_time)->format('%i') }}</strong>
                            <small class="text-muted">Minutes</small>
                        </div>
                        <div class="col-4">
                            <strong class="d-block text-primary">
                                @if($schedule->route->distance)
                                    {{ number_format($schedule->route->distance) }} km
                                @else
                                    N/A
                                @endif
                            </strong>
                            <small class="text-muted">Distance</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Schedule Actions -->
        <div class="card shadow-sm mt-4">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0">
                    <i class="fas fa-cog me-2 text-primary"></i>Schedule Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    @if($schedule->status === 'scheduled')
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('operator.schedules.edit', $schedule->id) }}" 
                               class="btn btn-outline-warning w-100 h-100 py-3 {{ $schedule->bookings->count() > 0 ? 'disabled' : '' }}"
                               @if($schedule->bookings->count() > 0) title="Cannot edit schedule with bookings" @endif>
                                <i class="fas fa-edit fa-2x mb-2"></i><br>
                                Edit Schedule
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <button class="btn btn-outline-danger w-100 h-100 py-3"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#cancelModal">
                                <i class="fas fa-times fa-2x mb-2"></i><br>
                                Cancel Schedule
                            </button>
                        </div>
                    @endif
                    <div class="col-md-4 mb-3">
                        <a href="javascript:window.print()" class="btn btn-outline-secondary w-100 h-100 py-3">
                            <i class="fas fa-print fa-2x mb-2"></i><br>
                            Print Details
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Modal -->
@if($schedule->status === 'scheduled')
<div class="modal fade" id="cancelModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cancel Schedule</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to cancel this schedule?</p>
                @if($schedule->bookings->count() > 0)
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        This schedule has {{ $schedule->bookings->count() }} booking(s). 
                        Cancelling will automatically cancel all bookings and may require refunds.
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Keep Schedule</button>
                <form action="{{ route('operator.schedules.cancel', $schedule->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-danger">Cancel Schedule</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}
.timeline-item {
    position: relative;
    margin-bottom: 20px;
}
.timeline-marker {
    position: absolute;
    left: -30px;
    top: 0;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 3px solid white;
    box-shadow: 0 0 0 3px;
}
.timeline-content {
    padding: 10px;
    background: white;
    border-radius: 5px;
}
</style>
@endsection