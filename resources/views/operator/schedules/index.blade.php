@extends('operator.layouts.app')

@section('title', 'Schedule Management')
@section('page-title', 'Schedule Management')
@section('page-subtitle', 'Manage your bus schedules')

@section('header-actions')
    <div class="btn-group">
        <a href="{{ route('operator.schedules.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> New Schedule
        </a>
        <button type="button" class="btn btn-outline-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">
            <span class="visually-hidden">Toggle Dropdown</span>
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="{{ route('operator.schedules.index', ['status' => 'scheduled']) }}">View Active</a></li>
            <li><a class="dropdown-item" href="{{ route('operator.schedules.index', ['status' => 'completed']) }}">View Completed</a></li>
            <li><a class="dropdown-item" href="{{ route('operator.schedules.index') }}">View All</a></li>
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
        <form method="GET" action="{{ route('operator.schedules.index') }}">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="all" {{ old('status', $filters['status'] ?? '') == 'all' ? 'selected' : '' }}>All Statuses</option>
                        <option value="scheduled" {{ old('status', $filters['status'] ?? '') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                        <option value="completed" {{ old('status', $filters['status'] ?? '') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ old('status', $filters['status'] ?? '') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="date_from" class="form-label">From Date</label>
                    <input type="date" class="form-control" id="date_from" name="date_from" value="{{ old('date_from', $filters['date_from'] ?? '') }}">
                </div>
                <div class="col-md-3">
                    <label for="date_to" class="form-label">To Date</label>
                    <input type="date" class="form-control" id="date_to" name="date_to" value="{{ old('date_to', $filters['date_to'] ?? '') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Apply Filters</button>
                        <a href="{{ route('operator.schedules.index') }}" class="btn btn-secondary w-100">Clear</a>

                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Schedules Card -->
<div class="card shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="card-title mb-0">
            <i class="fas fa-calendar me-2 text-primary"></i>Bus Schedules
            <span class="badge bg-primary ms-2">{{ $schedules->total() }}</span>
        </h5>
    </div>
    <div class="card-body">
        @if($schedules->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Trip Details</th>
                            <th>Bus & Route</th>
                            <th>Timing</th>
                            <th>Fare & Seats</th>
                            <th>Status</th>
                            <th>Bookings</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($schedules as $schedule)
                            <tr>
                                <td>
                                    <strong>#{{ $schedule->id }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $schedule->departure_time->format('M j, Y') }}</small>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary rounded-circle p-2 me-2">
                                            <i class="fas fa-bus text-white"></i>
                                        </div>
                                        <div>
                                            <strong class="d-block">{{ $schedule->bus->bus_number }}</strong>
                                            <small class="text-muted">{{ $schedule->route->origin }} → {{ $schedule->route->destination }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <strong>{{ $schedule->departure_time->format('g:i A') }}</strong>
                                    <br>
                                    <small class="text-muted">to {{ $schedule->arrival_time->format('g:i A') }}</small>
                                </td>
                                <td>
                                    <strong class="text-primary">${{ number_format($schedule->fare, 2) }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $schedule->available_seats }} / {{ $schedule->bus->capacity }} seats</small>
                                </td>
                                <td>
                                    <span class="badge bg-{{ match($schedule->status) {
                                        'scheduled' => 'success',
                                        'completed' => 'info',
                                        'cancelled' => 'danger',
                                        default => 'secondary'
                                    } }}">
                                        {{ ucfirst($schedule->status) }}
                                    </span>
                                    <br>
                                    <small class="text-muted">{{ $schedule->departure_time->diffForHumans() }}</small>
                                </td>
                                <td>
                                    <div class="text-center">
                                        <strong class="d-block">{{ $schedule->bookings_count ?? $schedule->bookings->count() }}</strong>
                                        <small class="text-muted">bookings</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('operator.schedules.show', $schedule->id) }}" 
                                           class="btn btn-outline-primary" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($schedule->status === 'scheduled' && $schedule->bookings->count() === 0)
                                            <a href="{{ route('operator.schedules.edit', $schedule->id) }}" 
                                               class="btn btn-outline-warning" title="Edit Schedule">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif
                                        @if($schedule->status === 'scheduled')
                                            <button type="button" class="btn btn-outline-danger" 
                                                    title="Cancel Schedule"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#cancelModal{{ $schedule->id }}">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @endif
                                    </div>

                                    <!-- Cancel Modal -->
                                    @if($schedule->status === 'scheduled')
                                    <div class="modal fade" id="cancelModal{{ $schedule->id }}" tabindex="-1">
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
                                                            Cancelling will automatically cancel all bookings.
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
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    Showing {{ $schedules->firstItem() }} to {{ $schedules->lastItem() }} of {{ $schedules->total() }} schedules
                </div>
                <div>
                    {{ $schedules->appends($filters)->links() }}
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-calendar-times fa-4x text-muted"></i>
                </div>
                <h5 class="text-muted">No schedules found</h5>
                <p class="text-muted">
                    @if(isset($filters['status']) && $filters['status'] !== 'all')
                        No {{ $filters['status'] }} schedules match your filters.
                    @else
                        You haven't created any schedules yet.
                    @endif
                </p>
                <a href="{{ route('operator.schedules.create') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-plus me-2"></i>Create Your First Schedule
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Quick Stats -->
@if($schedules->count() > 0)
<div class="row mt-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h4 class="mb-0">{{ $schedules->where('status', 'scheduled')->count() }}</h4>
                        <small>Active Schedules</small>
                    </div>
                    <i class="fas fa-play-circle fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h4 class="mb-0">{{ $schedules->where('status', 'completed')->count() }}</h4>
                        <small>Completed</small>
                    </div>
                    <i class="fas fa-check-circle fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h4 class="mb-0">{{ $schedules->sum('bookings_count') }}</h4>
                        <small>Total Bookings</small>
                    </div>
                    <i class="fas fa-ticket-alt fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h4 class="mb-0">${{ number_format($schedules->sum('fare'), 2) }}</h4>
                        <small>Potential Revenue</small>
                    </div>
                    <i class="fas fa-dollar-sign fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection