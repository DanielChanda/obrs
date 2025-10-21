@extends('operator.layouts.app')

@section('title', 'Route Management')
@section('page-title', 'Route Management')
@section('page-subtitle', 'Manage your travel routes')

@section('header-actions')
    <a href="{{ route('operator.routes.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Add New Route
    </a>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="card-title mb-0">
            <i class="fas fa-route me-2 text-primary"></i>My Routes
            <span class="badge bg-primary ms-2">{{ $routes->total() }}</span>
        </h5>
    </div>
    <div class="card-body">
        @if($routes->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Route</th>
                            <th>Distance</th>
                            <th>Total Schedules</th>
                            <th>Active Schedules</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($routes as $route)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary rounded-circle p-2 me-3">
                                            <i class="fas fa-route text-white"></i>
                                        </div>
                                        <div>
                                            <strong class="d-block">{{ $route->origin }} → {{ $route->destination }}</strong>
                                            <small class="text-muted">ID: {{ $route->id }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($route->distance)
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-road text-muted me-2"></i>
                                            <span>{{ number_format($route->distance) }} km</span>
                                        </div>
                                    @else
                                        <span class="text-muted">Not set</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="text-center">
                                        <strong class="d-block">{{ $route->schedules_count }}</strong>
                                        <small class="text-muted">schedules</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-center">
                                        <strong class="d-block text-success">
                                            {{ $route->schedules()->where('status', 'scheduled')->count() }}
                                        </strong>
                                        <small class="text-muted">active</small>
                                    </div>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $route->created_at->format('M j, Y') }}</small>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('operator.routes.show', $route->id) }}" 
                                           class="btn btn-outline-primary" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('operator.routes.edit', $route->id) }}" 
                                           class="btn btn-outline-warning" title="Edit Route">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-outline-danger" 
                                                title="Delete Route"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteModal{{ $route->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>

                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteModal{{ $route->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Confirm Delete</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Are you sure you want to delete your route <strong>{{ $route->origin }} → {{ $route->destination }}</strong>?</p>
                                                    @if($route->schedules_count > 0)
                                                        <div class="alert alert-danger">
                                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                                            This route has {{ $route->schedules_count }} schedule(s). 
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
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    Showing {{ $routes->firstItem() }} to {{ $routes->lastItem() }} of {{ $routes->total() }} routes
                </div>
                <div>
                    {{ $routes->links(('pagination::bootstrap-5')) }}
                </div>
            </div>
        @else
            <!-- UPDATED EMPTY STATE MESSAGE -->
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-route fa-4x text-muted"></i>
                </div>
                <h5 class="text-muted">No routes in your collection yet</h5>
                <p class="text-muted">Create your first route to start scheduling trips.</p>
                <a href="{{ route('operator.routes.create') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-plus me-2"></i>Create Your First Route
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Quick Stats -->
@if($routes->count() > 0)
<div class="row mt-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h4 class="mb-0">{{ $routes->total() }}</h4>
                        <small>My Routes</small>
                    </div>
                    <i class="fas fa-route fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h4 class="mb-0">{{ $routes->sum('distance') }} km</h4>
                        <small>Total Distance</small>
                    </div>
                    <i class="fas fa-road fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h4 class="mb-0">{{ $routes->sum('schedules_count') }}</h4>
                        <small>Total Schedules</small>
                    </div>
                    <i class="fas fa-calendar fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h4 class="mb-0">{{ $routes->where('distance', '>', 0)->count() }}</h4>
                        <small>Routes with Distance</small>
                    </div>
                    <i class="fas fa-ruler fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Popular Routes -->
<div class="card shadow-sm mt-4">
    <div class="card-header bg-white py-3">
        <h5 class="card-title mb-0">
            <i class="fas fa-chart-line me-2 text-primary"></i>My Most Used Routes
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            @foreach($routes->sortByDesc('schedules_count')->take(3) as $route)
                <div class="col-md-4 mb-3">
                    <div class="card border-0 bg-light h-100">
                        <div class="card-body text-center">
                            <h6 class="text-primary">{{ $route->origin }} → {{ $route->destination }}</h6>
                            <div class="h3 text-success mb-2">{{ $route->schedules_count }}</div>
                            <small class="text-muted">schedules</small>
                            @if($route->distance)
                                <div class="mt-2">
                                    <small class="text-muted">
                                        <i class="fas fa-road me-1"></i>{{ $route->distance }} km
                                    </small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif
@endsection