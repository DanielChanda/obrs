@extends('operator.layouts.app')

@section('title', 'Bus Details - ' . $bus->bus_number)
@section('page-title', 'Bus Details')
@section('page-subtitle', $bus->bus_number)

@section('header-actions')
    <div class="btn-group">
        <a href="{{ route('operator.buses.edit', $bus->id) }}" class="btn btn-warning">
            <i class="fas fa-edit me-1"></i> Edit Bus
        </a>
        <a href="{{ route('operator.schedules.create') }}?bus_id={{ $bus->id }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Create Schedule
        </a>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-4">
        <!-- Bus Information Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0">
                    <i class="fas fa-bus me-2 text-primary"></i>Bus Information
                </h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center" 
                         style="width: 80px; height: 80px;">
                        <i class="fas fa-bus fa-2x text-white"></i>
                    </div>
                    <h4 class="mt-3">{{ $bus->bus_number }}</h4>
                    <span class="badge bg-{{ $bus->status === 'active' ? 'success' : 'secondary' }} fs-6">
                        {{ ucfirst($bus->status) }}
                    </span>
                </div>
                
                <table class="table table-borderless">
                    <tr>
                        <td class="fw-semibold" width="40%">Bus Type:</td>
                        <td>{{ $bus->bus_type }}</td>
                    </tr>
                    <tr>
                        <td class="fw-semibold">Capacity:</td>
                        <td>
                            <i class="fas fa-users text-muted me-2"></i>
                            {{ $bus->capacity }} seats
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-semibold">Added On:</td>
                        <td>{{ $bus->created_at->format('M j, Y') }}</td>
                    </tr>
                    <tr>
                        <td class="fw-semibold">Last Updated:</td>
                        <td>{{ $bus->updated_at->format('M j, Y') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h6 class="card-title mb-0">
                    <i class="fas fa-chart-pie me-2 text-primary"></i>Bus Statistics
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
                <div class="d-flex justify-content-between align-items-center">
                    <span>Total Bookings:</span>
                    <strong class="text-info">{{ $stats['totalBookings'] }}</strong>
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
                <a href="{{ route('operator.schedules.create') }}?bus_id={{ $bus->id }}" 
                   class="btn btn-sm btn-primary">
                    <i class="fas fa-plus me-1"></i> New Schedule
                </a>
            </div>
            <div class="card-body">
                @if($bus->schedules->count() > 0)
                    @foreach($bus->schedules->take(5) as $schedule)
                        <div class="border-start border-primary ps-3 mb-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">{{ $schedule->route->origin }} → {{ $schedule->route->destination }}</h6>
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
                                        </span>
                                    </small>
                                </div>
                                <div class="text-end">
                                    <small class="text-muted d-block">{{ $schedule->available_seats }} seats left</small>
                                    <a href="#" class="btn btn-sm btn-outline-primary">View</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    
                    @if($bus->schedules->count() > 5)
                        <div class="text-center mt-3">
                            <a href="#" class="btn btn-sm btn-outline-primary">View All Schedules</a>
                        </div>
                    @endif
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-calendar-times fa-2x text-muted mb-3"></i>
                        <p class="text-muted mb-0">No schedules found for this bus</p>
                        <a href="{{ route('operator.schedules.create') }}?bus_id={{ $bus->id }}" 
                           class="btn btn-sm btn-primary mt-2">
                            Create First Schedule
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Bus Actions -->
        <div class="card shadow-sm mt-4">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0">
                    <i class="fas fa-cog me-2 text-primary"></i>Bus Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-4 mb-3">
                        <a href="{{ route('operator.schedules.create') }}?bus_id={{ $bus->id }}" 
                           class="btn btn-outline-primary w-100 h-100 py-3">
                            <i class="fas fa-plus fa-2x mb-2"></i><br>
                            New Schedule
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <a href="{{ route('operator.buses.edit', $bus->id) }}" 
                           class="btn btn-outline-warning w-100 h-100 py-3">
                            <i class="fas fa-edit fa-2x mb-2"></i><br>
                            Edit Bus
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <button class="btn btn-outline-danger w-100 h-100 py-3"
                                data-bs-toggle="modal" 
                                data-bs-target="#deleteModal">
                            <i class="fas fa-trash fa-2x mb-2"></i><br>
                            Delete Bus
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
                <p>Are you sure you want to delete bus <strong>{{ $bus->bus_number }}</strong>?</p>
                @if($bus->schedules->count() > 0)
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        This bus has {{ $bus->schedules->count() }} schedule(s). 
                        Deleting it will affect these schedules.
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('operator.buses.destroy', $bus->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Bus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection