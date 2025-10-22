@extends('admin.layouts.app')

@section('title', 'Bus Details - ' . $bus->bus_number)
@section('page-title', 'Bus Details')
@section('page-subtitle', $bus->bus_number)

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
                        <td class="fw-semibold" width="40%">Operator:</td>
                        <td>
                            <a href="{{ route('admin.users.show', $bus->operator->id) }}">{{ $bus->operator->name }}</a>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-semibold">Bus Type:</td>
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
                </table>
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.buses.edit', $bus->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-1"></i> Edit Bus
                    </a>
                    <a href="{{ route('admin.buses.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <!-- Upcoming Schedules -->
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h5 class="card-title mb-0">
                    <i class="fas fa-calendar me-2 text-primary"></i>Upcoming Schedules
                </h5>
            </div>
            <div class="card-body">
                @if($bus->schedules->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Route</th>
                                    <th>Departure</th>
                                    <th>Arrival</th>
                                    <th>Status</th>
                                    <th>Bookings</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bus->schedules as $schedule)
                                    <tr>
                                        <td>{{ $schedule->route->origin }} → {{ $schedule->route->destination }}</td>
                                        <td>{{ $schedule->departure_time->format('d M Y, H:i') }}</td>
                                        <td>{{ $schedule->arrival_time->format('d M Y, H:i') }}</td>
                                        <td><span class="badge bg-{{ $schedule->status === 'scheduled' ? 'success' : 'secondary' }}">{{ ucfirst($schedule->status) }}</span></td>
                                        <td>{{ $schedule->bookings_count ?? $schedule->bookings->count() }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-calendar-times fa-2x text-muted mb-3"></i>
                        <p class="text-muted mb-0">No schedules found for this bus.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
