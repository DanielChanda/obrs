@extends('operator.layouts.app')

@section('title', 'My Buses')
@section('page-title', 'Bus Fleet Management')
@section('page-subtitle', 'Manage your bus fleet')

@section('header-actions')
    <a href="{{ route('operator.buses.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Add New Bus
    </a>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="card-title mb-0">
            <i class="fas fa-bus me-2 text-primary"></i>My Bus Fleet
            <span class="badge bg-primary ms-2">{{ $buses->total() }}</span>
        </h5>
    </div>
    <div class="card-body">
        @if($buses->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Bus Number</th>
                            <th>Type</th>
                            <th>Capacity</th>
                            <th>Status</th>
                            <th>Schedules</th>
                            <th>Added Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($buses as $bus)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary rounded-circle p-2 me-3">
                                            <i class="fas fa-bus text-white"></i>
                                        </div>
                                        <div>
                                            <strong class="d-block">{{ $bus->bus_number }}</strong>
                                            <small class="text-muted">ID: {{ $bus->id }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $bus->bus_type }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-users text-muted me-2"></i>
                                        <span>{{ $bus->capacity }} seats</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $bus->status === 'active' ? 'success' : 'secondary' }}">
                                        <i class="fas fa-circle me-1" style="font-size: 0.5rem;"></i>
                                        {{ ucfirst($bus->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="text-center">
                                        <strong class="d-block">{{ $bus->schedules_count ?? $bus->schedules->count() }}</strong>
                                        <small class="text-muted">schedules</small>
                                    </div>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $bus->created_at->format('M j, Y') }}</small>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('operator.buses.show', $bus->id) }}" 
                                           class="btn btn-outline-primary" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('operator.buses.edit', $bus->id) }}" 
                                           class="btn btn-outline-warning" title="Edit Bus">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-outline-danger" 
                                                title="Delete Bus"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteModal{{ $bus->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>

                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteModal{{ $bus->id }}" tabindex="-1">
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
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    Showing {{ $buses->firstItem() }} to {{ $buses->lastItem() }} of {{ $buses->total() }} buses
                </div>
                <div>
                    {{ $buses->links(('pagination::bootstrap-5')) }}
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-bus fa-4x text-muted"></i>
                </div>
                <h5 class="text-muted">No buses in your fleet yet</h5>
                <p class="text-muted">Start by adding your first bus to manage schedules and bookings.</p>
                <a href="{{ route('operator.buses.create') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-plus me-2"></i>Add Your First Bus
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Quick Stats -->
@if($buses->count() > 0)
<div class="row mt-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h4 class="mb-0">{{ $buses->where('status', 'active')->count() }}</h4>
                        <small>Active Buses</small>
                    </div>
                    <i class="fas fa-bus fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h4 class="mb-0">{{ $buses->sum('capacity') }}</h4>
                        <small>Total Capacity</small>
                    </div>
                    <i class="fas fa-users fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h4 class="mb-0">{{ $buses->count() }}</h4>
                        <small>Total Buses</small>
                    </div>
                    <i class="fas fa-warehouse fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h4 class="mb-0">{{ $buses->where('status', 'inactive')->count() }}</h4>
                        <small>Inactive Buses</small>
                    </div>
                    <i class="fas fa-wrench fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection