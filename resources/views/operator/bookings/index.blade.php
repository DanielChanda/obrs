@extends('operator.layouts.app')

@section('title', 'Booking Management')
@section('page-title', 'Booking Management')
@section('page-subtitle', 'Manage passenger bookings')

@section('header-actions')
    <div class="btn-group">
        <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
            <i class="fas fa-download me-1"></i> Export
        </button>
        <ul class="dropdown-menu">
            <li>
                <a class="dropdown-item" href="{{ route('operator.bookings.export.csv', request()->all()) }}">
                    Export as CSV
                </a>
            </li>
            <li>
                <a class="dropdown-item" href="{{ route('operator.bookings.export.pdf', request()->all()) }}">
                    Export as PDF
                </a>
            </li>
        </ul>

    </div>
@endsection

@section('content')
<!-- Filters Card -->
<div class="card shadow-sm mb-4">
    <div class="card-header bg-white py-3">
        <h6 class="card-title mb-0">
            <i class="fas fa-filter me-2 text-primary"></i>Filters
        </h6>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('operator.bookings.index') }}">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="status" class="form-label">Booking Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="all" {{ ($filters['status'] ?? '') == 'all' ? 'selected' : '' }}>All Statuses</option>
                        <option value="pending" {{ ($filters['status'] ?? '') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ ($filters['status'] ?? '') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="cancelled" {{ ($filters['status'] ?? '') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        <option value="completed" {{ ($filters['status'] ?? '') == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="payment_status" class="form-label">Payment Status</label>
                    <select class="form-select" id="payment_status" name="payment_status">
                        <option value="all" {{ ($filters['payment_status'] ?? '') == 'all' ? 'selected' : '' }}>All Payments</option>
                        <option value="unpaid" {{ ($filters['payment_status'] ?? '') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                        <option value="paid" {{ ($filters['payment_status'] ?? '') == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="refunded" {{ ($filters['payment_status'] ?? '') == 'refunded' ? 'selected' : '' }}>Refunded</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="schedule_id" class="form-label">Schedule</label>
                    <select class="form-select" id="schedule_id" name="schedule_id">
                        <option value="">All Schedules</option>
                        @foreach($schedules as $schedule)
                            <option value="{{ $schedule->id }}" 
                                {{ ($filters['schedule_id'] ?? '') == $schedule->id ? 'selected' : '' }}>
                                #{{ $schedule->id }}: {{ $schedule->route->origin }} → {{ $schedule->route->destination }} 
                                ({{ $schedule->departure_time->format('M j') }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="date_from" class="form-label">From Date</label>
                    <input type="date" class="form-control" id="date_from" name="date_from" value="{{ $filters['date_from'] ?? '' }}">
                </div>
                <div class="col-md-3">
                    <label for="date_to" class="form-label">To Date</label>
                    <input type="date" class="form-control" id="date_to" name="date_to" value="{{ $filters['date_to'] ?? '' }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Apply Filters</button>
                        <a href="{{ route('operator.bookings.index') }}" class="btn btn-outline-secondary">Clear</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Bookings Card -->
<div class="card shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="card-title mb-0">
            <i class="fas fa-ticket-alt me-2 text-primary"></i>Passenger Bookings
            <span class="badge bg-primary ms-2">{{ $bookings->total() }}</span>
        </h5>
    </div>
    <div class="card-body">
        @if($bookings->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Booking ID</th>
                            <th>Passenger</th>
                            <th>Trip Details</th>
                            <th>Seat</th>
                            <th>Fare</th>
                            <th>Status</th>
                            <th>Payment</th>
                            <th>Booked On</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $booking)
                            <tr>
                                <td>
                                    <strong>#{{ $booking->id }}</strong>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary rounded-circle p-2 me-2">
                                            <i class="fas fa-user text-white"></i>
                                        </div>
                                        <div>
                                            <strong class="d-block">{{ $booking->user->name }}</strong>
                                            <small class="text-muted">{{ $booking->user->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <strong>{{ $booking->schedule->route->origin }} → {{ $booking->schedule->route->destination }}</strong>
                                    <br>
                                    <small class="text-muted">
                                        {{ $booking->schedule->departure_time->format('M j, g:i A') }}
                                        <br>
                                        {{ $booking->schedule->bus->bus_number }}
                                    </small>
                                </td>
                                <td>
                                    <span class="badge bg-secondary fs-6">#{{ $booking->seat_number }}</span>
                                </td>
                                <td>
                                    <strong class="text-primary">ZMW{{ number_format($booking->schedule->fare, 2) }}</strong>
                                </td>
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
                                    <span class="badge bg-{{ match($booking->payment_status) {
                                        'paid' => 'success',
                                        'unpaid' => 'warning',
                                        'refunded' => 'secondary',
                                        default => 'secondary'
                                    } }}">
                                        {{ ucfirst($booking->payment_status) }}
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $booking->created_at->format('M j, g:i A') }}</small>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('operator.bookings.show', $booking->id) }}" 
                                           class="btn btn-outline-primary" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($booking->status === 'pending')
                                            <form action="{{ route('operator.bookings.confirm', $booking->id) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-outline-success" 
                                                        title="Confirm Booking" onclick="return confirm('Confirm this booking?')">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        @endif
                                        @if(in_array($booking->status, ['pending', 'confirmed']))
                                            <form action="{{ route('operator.bookings.cancel', $booking->id) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-outline-danger" 
                                                        title="Cancel Booking" onclick="return confirm('Cancel this booking?')">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        @endif
                                        @if($booking->status === 'confirmed' && $booking->schedule->departure_time <= now())
                                            <form action="{{-- route('operator.bookings.check-in', $booking->id) --}}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-outline-info" 
                                                        title="Check In Passenger" onclick="return confirm('Check in passenger?')">
                                                    <i class="fas fa-user-check"></i>
                                                </button>
                                            </form>
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
                    Showing {{ $bookings->firstItem() }} to {{ $bookings->lastItem() }} of {{ $bookings->total() }} bookings
                </div>
                <div>
                    {{ $bookings->appends($filters)->links() }}
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-ticket-alt fa-4x text-muted"></i>
                </div>
                <h5 class="text-muted">No bookings found</h5>
                <p class="text-muted">
                    @if(count($filters) > 0)
                        No bookings match your current filters.
                    @else
                        No bookings have been made for your schedules yet.
                    @endif
                </p>
                @if(count($filters) > 0)
                    <a href="{{ route('operator.bookings.index') }}" class="btn btn-primary">
                        Clear Filters
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>

<!-- Quick Stats -->
@if($bookings->count() > 0)
<div class="row mt-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h4 class="mb-0">{{ $bookings->total() }}</h4>
                        <small>Total Bookings</small>
                    </div>
                    <i class="fas fa-ticket-alt fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h4 class="mb-0">ZMW{{ number_format($bookings->sum(function($b) { return $b->schedule->fare; }), 2) }}</h4>
                        <small>Total Revenue</small>
                    </div>
                    <i class="fas fa-dollar-sign fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h4 class="mb-0">{{ $bookings->where('status', 'confirmed')->count() }}</h4>
                        <small>Confirmed</small>
                    </div>
                    <i class="fas fa-check-circle fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h4 class="mb-0">{{ $bookings->where('payment_status', 'paid')->count() }}</h4>
                        <small>Paid Bookings</small>
                    </div>
                    <i class="fas fa-credit-card fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection