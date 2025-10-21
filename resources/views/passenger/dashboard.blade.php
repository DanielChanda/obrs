@extends('passenger.layouts.app')

@section('title', 'Passenger Dashboard')
@section('page-title', 'My Dashboard')

@section('header-actions')
    <a href="{{ route('passenger.search.form') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Book New Trip
    </a>
@endsection

@section('content')
<div class="row">
    <!-- Quick Stats -->
    <div class="col-md-3 mb-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h4 class="mb-0">{{ $totalBookings }}</h4>
                        <small>Total Bookings</small>
                    </div>
                    <i class="fas fa-ticket-alt fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h4 class="mb-0">{{ $upcomingTrips }}</h4>
                        <small>Upcoming Trips</small>
                    </div>
                    <i class="fas fa-calendar-check fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h4 class="mb-0">{{ $completedTrips }}</h4>
                        <small>Completed Trips</small>
                    </div>
                    <i class="fas fa-check-circle fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h4 class="mb-0">ZMW{{ number_format($totalSpent, 2) }}</h4>
                        <small>Total Spent</small>
                    </div>
                    <i class="fas fa-dollar-sign fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Bookings -->
<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h5 class="card-title mb-0">
            <i class="fas fa-history me-2 text-primary"></i>Recent Bookings
        </h5>
    </div>
    <div class="card-body">
        @forelse($bookings as $booking)
            @include('passenger.partials.booking-card', ['booking' => $booking])
        @empty
            <div class="text-center py-5">
                <i class="fas fa-ticket-alt fa-4x text-muted mb-3"></i>
                <h5 class="text-muted">No bookings yet</h5>
                <p class="text-muted">Start by booking your first trip!</p>
                <a href="{{ route('passenger.search.form') }}" class="btn btn-primary">
                    <i class="fas fa-search me-1"></i> Search Trips
                </a>
            </div>
        @endforelse
        
        @if($bookings->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $bookings->links(('pagination::bootstrap-5')) }}
            </div>
        @endif
    </div>
</div>
@endsection