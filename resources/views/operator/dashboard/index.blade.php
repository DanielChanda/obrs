@extends('operator.layouts.app')

@section('title', 'Operator Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Welcome back, ' . $operator->name)

@section('header-actions')
    <div class="btn-group">
        <a href="{{ route('operator.schedules.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> New Schedule
        </a>
        <a href="{{ route('operator.buses.create') }}" class="btn btn-outline-primary">
            <i class="fas fa-bus me-1"></i> Add Bus
        </a>
    </div>
@endsection

@section('content')
<!-- Stats Cards -->
<div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Buses
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['totalBuses'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-bus fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Active Schedules
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['activeSchedules'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Total Bookings
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['totalBookings'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-ticket-alt fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Today's Bookings
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['todayBookings'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Schedules -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-calendar me-1"></i>Recent Schedules
                </h6>
            </div>
            <div class="card-body">
                @forelse($recentSchedules as $schedule)
                    <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                        <div class="flex-shrink-0">
                            <div class="bg-primary rounded-circle p-2 text-white">
                                <i class="fas fa-bus"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">{{ $schedule->route->origin }} → {{ $schedule->route->destination }}</h6>
                            <small class="text-muted">
                                {{ $schedule->departure_time->format('M j, g:i A') }} • 
                                {{ $schedule->bus->bus_number }} • 
                                <span class="badge bg-{{ $schedule->status === 'scheduled' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($schedule->status) }}
                                </span>
                            </small>
                        </div>
                        <div class="text-end">
                            <strong class="text-primary">ZMW{{ number_format($schedule->fare, 2) }}</strong>
                            <br>
                            <small class="text-muted">{{ $schedule->available_seats }} seats</small>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4">
                        <i class="fas fa-calendar-times fa-2x text-muted mb-2"></i>
                        <p class="text-muted mb-0">No schedules found</p>
                    </div>
                @endforelse
                
                @if($recentSchedules->count() > 0)
                    <div class="text-center mt-2">
                        <a href="{{ route('operator.schedules.index') }}" class="btn btn-sm btn-outline-primary">
                            View All Schedules
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Bookings -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-ticket-alt me-1"></i>Recent Bookings
                </h6>
            </div>
            <div class="card-body">
                @forelse($recentBookings as $booking)
                    <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                        <div class="flex-shrink-0">
                            <div class="bg-success rounded-circle p-2 text-white">
                                <i class="fas fa-user"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">{{ $booking->user->name }}</h6>
                            <small class="text-muted">
                                {{ $booking->schedule->route->origin }} → {{ $booking->schedule->route->destination }}
                                <br>
                                Seat {{ $booking->seat_number }} • 
                                <span class="badge bg-{{ $booking->status === 'confirmed' ? 'success' : 'warning' }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </small>
                        </div>
                        <div class="text-end">
                            <small class="text-muted">{{ $booking->created_at->format('M j') }}</small>
                            <br>
                            <span class="badge bg-{{ $booking->payment_status === 'paid' ? 'success' : 'warning' }}">
                                {{ ucfirst($booking->payment_status) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4">
                        <i class="fas fa-ticket-alt fa-2x text-muted mb-2"></i>
                        <p class="text-muted mb-0">No bookings found</p>
                    </div>
                @endforelse
                
                @if($recentBookings->count() > 0)
                    <div class="text-center mt-2">
                        <a href="{{ route('operator.bookings.index') }}" class="btn btn-sm btn-outline-primary">
                            View All Bookings
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-bolt me-1"></i>Quick Actions
                </h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('operator.schedules.create') }}" class="btn btn-outline-primary w-100 h-100 py-3">
                            <i class="fas fa-plus fa-2x mb-2"></i><br>
                            New Schedule
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('operator.buses.create') }}" class="btn btn-outline-success w-100 h-100 py-3">
                            <i class="fas fa-bus fa-2x mb-2"></i><br>
                            Add Bus
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('operator.routes.index') }}" class="btn btn-outline-info w-100 h-100 py-3">
                            <i class="fas fa-route fa-2x mb-2"></i><br>
                            Manage Routes
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('operator.bookings.index') }}" class="btn btn-outline-warning w-100 h-100 py-3">
                            <i class="fas fa-ticket-alt fa-2x mb-2"></i><br>
                            View Bookings
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection