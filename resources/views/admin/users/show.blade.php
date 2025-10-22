@extends('admin.layouts.app')

@section('title', 'User Details - ' . $user->name)
@section('page-title', 'User Details')
@section('page-subtitle', $user->name)

@section('content')
<div class="row">
    <div class="col-md-4">
        <!-- User Profile Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user-circle me-2 text-primary"></i>User Profile
                </h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" 
                         style="width: 80px; height: 80px; font-size: 2.5rem;">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <h4 class="mt-3">{{ $user->name }}</h4>
                    <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'operator' ? 'success' : 'info') }} fs-6">
                        {{ ucfirst($user->role) }}
                    </span>
                </div>
                
                <table class="table table-borderless">
                    <tr>
                        <td class="fw-semibold" width="30%"><i class="fas fa-envelope text-muted me-2"></i>Email:</td>
                        <td>{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <td class="fw-semibold"><i class="fas fa-phone text-muted me-2"></i>Phone:</td>
                        <td>{{ $user->phone ?? 'Not provided' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-semibold"><i class="fas fa-map-marker-alt text-muted me-2"></i>Address:</td>
                        <td>{{ $user->address ?? 'Not provided' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-semibold"><i class="fas fa-calendar-alt text-muted me-2"></i>Joined:</td>
                        <td>{{ $user->created_at->format('M j, Y') }}</td>
                    </tr>
                </table>
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-1"></i> Edit User
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <!-- User Activity -->
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0">
                    <i class="fas fa-history me-2 text-primary"></i>User Activity
                </h5>
            </div>
            <div class="card-body">
                @if($user->role === 'passenger')
                    <h6 class="mb-3">Recent Bookings</h6>
                    @forelse($user->bookings->take(5) as $booking)
                        <div class="border-start border-primary ps-3 mb-3">
                            <p class="mb-0"><strong>Route:</strong> {{ $booking->schedule->route->origin }} to {{ $booking->schedule->route->destination }}</p>
                            <p class="mb-0"><strong>Bus:</strong> {{ $booking->schedule->bus->bus_number }}</p>
                            <small class="text-muted">Booked on: {{ $booking->created_at->format('d M Y') }} | Status: <span class="badge bg-info">{{ $booking->status }}</span></small>
                        </div>
                    @empty
                        <p class="text-muted">No booking history found.</p>
                    @endforelse
                @elseif($user->role === 'operator')
                    <h6 class="mb-3">Managed Buses ({{ $user->buses->count() }})</h6>
                    @if($user->buses->count() > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($user->buses->take(5) as $bus)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-bus me-2"></i>{{ $bus->bus_number }} ({{ $bus->bus_type }})</span>
                                    <span class="badge bg-{{ $bus->status == 'active' ? 'success' : 'secondary' }}">{{ ucfirst($bus->status) }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted">This operator has not added any buses.</p>
                    @endif

                    <hr>

                    <h6 class="mb-3">Managed Routes ({{ $user->routes->count() }})</h6>
                    @if($user->routes->count() > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($user->routes->take(5) as $route)
                                <li class="list-group-item">
                                    <i class="fas fa-route me-2"></i>{{ $route->origin }} to {{ $route->destination }}
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted">This operator has not created any routes.</p>
                    @endif
                @else
                    <p class="text-muted">No specific activity to display for this role.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // You can add any specific JS for this page here if needed
</script>
@endpush
