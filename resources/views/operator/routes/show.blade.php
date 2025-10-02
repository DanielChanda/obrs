@extends('operator.layouts.app')

@section('title', 'Route Details - ' . $route->origin . ' → ' . $route->destination)
@section('page-title', 'Route Details')
@section('page-subtitle', $route->origin . ' → ' . $route->destination)

@section('header-actions')
    <div class="btn-group">
        <a href="{{ route('operator.routes.edit', $route->id) }}" class="btn btn-warning">
            <i class="fas fa-edit me-1"></i> Edit Route
        </a>
        <a href="{{ route('operator.schedules.create') }}?route_id={{ $route->id }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Create Schedule
        </a>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-4">
        <!-- Route Information Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0">
                    <i class="fas fa-route me-2 text-primary"></i>Route Information
                </h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center" 
                         style="width: 80px; height: 80px;">
                        <i class="fas fa-route fa-2x text-white"></i>
                    </div>
                    <h4 class="mt-3">{{ $route->origin }} → {{ $route->destination }}</h4>
                    @if($route->distance)
                        <span class="badge bg-info fs-6">
                            <i class="fas fa-road me-1"></i>{{ number_format($route->distance) }} km
                        </span>
                    @endif
                </div>
                
                <table class="table table-borderless">
                    <tr>
                        <td class="fw-semibold" width="40%">Origin:</td>
                        <td>
                            <i class="fas fa-map-marker-alt text-danger me-2"></i>
                            {{ $route->origin }}
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-semibold">Destination:</td>
                        <td>
                            <i class="fas fa-flag-checkered text-success me-2"></i>
                            {{ $route->destination }}
                        </td>
                    </tr>
                    @if($route->distance)
                    <tr>
                        <td class="fw-semibold">Distance:</td>
                        <td>
                            <i class="fas fa-road text-muted me-2"></i>
                            {{ number_format($route->distance) }} kilometers
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <td class="fw-semibold">Created On:</td>
                        <td>{{ $route->created_at->format('M j, Y') }}</td>
                    </tr>
                    <tr>
                        <td class="fw-semibold">Last Updated:</td>
                        <td>{{ $route->updated_at->format('M j, Y') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h6 class="card-title mb-0">
                    <i class="fas fa-chart-pie me-2 text-primary"></i>Route Statistics
                </h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Total Schedules:</span>
                    <strong class="text-primary">{{ $stats['totalSchedules'] }}</strong>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Active Schedules:</span>
                    <strong class="text-success">{{ $stats['activeSchedules'] }}</strong>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Upcoming Schedules:</span>
                    <strong class="text-info">{{ $stats['upcomingSchedules'] }}</strong>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span>Average Fare:</span>
                    <strong class="text-warning">
                        @if($stats['totalSchedules'] > 0)
                            ${{ number_format($route->schedules->avg('fare') ?? 0, 2) }}
                        @else
                            N/A
                        @endif
                    </strong>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <!-- Recent Schedules -->
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h5 class="card-title mb-0">
                    <i class="fas fa-calendar me-2 text-primary"></i>Recent Schedules
                </h5>
                <a href="{{ route('operator.schedules.create') }}?route_id={{ $route->id }}" 
                   class="btn btn-sm btn-primary">
                    <i class="fas fa-plus me-1"></i> New Schedule
                </a>
            </div>
            <div class="card-body">
                @if($route->schedules->count() > 0)
                    @foreach($route->schedules()->orderBy('departure_time', 'desc')->take(5)->get() as $schedule)
                        <div class="border-start border-primary ps-3 mb-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">
                                        <i class="fas fa-bus text-muted me-2"></i>
                                        {{ $schedule->bus->bus_number }} ({{ $schedule->bus->bus_type }})
                                    </h6>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>
                                        {{ $schedule->departure_time->format('M j, Y g:i A') }}
                                    </small>
                                    <br>
                                    <small class="text-muted">
                                        <i class="fas fa-dollar-sign me-1"></i>
                                        ${{ number_format($schedule->fare, 2) }} • 
                                        <span class="badge bg-{{ $schedule->status === 'scheduled' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($schedule->status) }}
                                        </span> •
                                        {{ $schedule->available_seats }} seats available
                                    </small>
                                </div>
                                <div class="text-end">
                                    <small class="text-muted d-block">{{ $schedule->departure_time->diffForHumans() }}</small>
                                    <a href="#" class="btn btn-sm btn-outline-primary">View</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    
                    @if($route->schedules->count() > 5)
                        <div class="text-center mt-3">
                            <a href="{{ route('operator.schedules.index') }}?route_id={{ $route->id }}" 
                               class="btn btn-sm btn-outline-primary">View All Schedules</a>
                        </div>
                    @endif
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-calendar-times fa-2x text-muted mb-3"></i>
                        <p class="text-muted mb-0">No schedules found for this route</p>
                        <a href="{{ route('operator.schedules.create') }}?route_id={{ $route->id }}" 
                           class="btn btn-sm btn-primary mt-2">
                            Create First Schedule
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Route Actions -->
        <div class="card shadow-sm mt-4">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0">
                    <i class="fas fa-cog me-2 text-primary"></i>Route Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-4 mb-3">
                        <a href="{{ route('operator.schedules.create') }}?route_id={{ $route->id }}" 
                           class="btn btn-outline-primary w-100 h-100 py-3">
                            <i class="fas fa-plus fa-2x mb-2"></i><br>
                            New Schedule
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <a href="{{ route('operator.routes.edit', $route->id) }}" 
                           class="btn btn-outline-warning w-100 h-100 py-3">
                            <i class="fas fa-edit fa-2x mb-2"></i><br>
                            Edit Route
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <button class="btn btn-outline-danger w-100 h-100 py-3"
                                data-bs-toggle="modal" 
                                data-bs-target="#deleteModal">
                            <i class="fas fa-trash fa-2x mb-2"></i><br>
                            Delete Route
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the route <strong>{{ $route->origin }} → {{ $route->destination }}</strong>?</p>
                @if($route->schedules->count() > 0)
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        This route has {{ $route->schedules->count() }} schedule(s). 
                        <strong>Deleting it will remove all associated schedules!</strong>
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('operator.routes.destroy', $route->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Route</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection