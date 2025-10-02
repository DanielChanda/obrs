@extends('passenger.layouts.app')

@section('title', 'My Profile')
@section('page-title', 'My Profile')

@section('content')
<div class="row">
    <!-- Profile Information -->
    <div class="col-md-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user me-2 text-primary"></i>Personal Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="text-center mb-3">
                            <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center" 
                                 style="width: 80px; height: 80px;">
                                <i class="fas fa-user fa-2x text-white"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-9">
                        <div class="row mb-2">
                            <div class="col-sm-4 fw-semibold">Full Name:</div>
                            <div class="col-sm-8">{{ $user->name }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4 fw-semibold">Email:</div>
                            <div class="col-sm-8">{{ $user->email }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4 fw-semibold">Phone:</div>
                            <div class="col-sm-8">{{ $user->phone ?? 'Not provided' }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4 fw-semibold">Address:</div>
                            <div class="col-sm-8">{{ $user->address ?? 'Not provided' }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4 fw-semibold">Member Since:</div>
                            <div class="col-sm-8">{{ $user->created_at->format('M j, Y') }}</div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-3">
                    <a href="{{ route('passenger.profile.edit') }}" class="btn btn-primary me-2">
                        <i class="fas fa-edit me-1"></i>Edit Profile
                    </a>
                    <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                        <i class="fas fa-key me-1"></i>Change Password
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Stats -->
    <div class="col-md-4">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h6 class="card-title mb-0">
                    <i class="fas fa-chart-bar me-2 text-primary"></i>Quick Stats
                </h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Total Bookings:</span>
                    <strong class="text-primary">{{ $user->bookings->count() }}</strong>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Upcoming Trips:</span>
                    <strong class="text-success">{{ $user->bookings()->whereHas('schedule', fn($q) => $q->where('departure_time', '>', now()))->count() }}</strong>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span>Completed Trips:</span>
                    <strong class="text-info">{{ $user->bookings()->where('status', 'completed')->count() }}</strong>
                </div>
            </div>
        </div>
        
        <!-- Recent Bookings -->
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h6 class="card-title mb-0">
                    <i class="fas fa-clock me-2 text-primary"></i>Recent Bookings
                </h6>
            </div>
            <div class="card-body">
                @forelse($recentBookings as $booking)
                    <div class="border-start border-primary ps-3 mb-2">
                        <small class="d-block fw-semibold">
                            {{ $booking->schedule->route->origin }} → {{ $booking->schedule->route->destination }}
                        </small>
                        <small class="text-muted">
                            {{ $booking->schedule->departure_time->format('M j, g:i A') }}
                        </small>
                        <div>
                            <span class="badge bg-{{ $booking->status === 'confirmed' ? 'success' : 'warning' }} badge-sm">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-muted small mb-0">No recent bookings</p>
                @endforelse
                
                @if($user->bookings->count() > 5)
                    <div class="text-center mt-2">
                        <a href="{{ route('passenger.profile.booking-history') }}" class="btn btn-sm btn-outline-primary">
                            View All Bookings
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('passenger.profile.change-password') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Current Password</label>
                        <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                               id="current_password" name="current_password" required>
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="new_password" class="form-label">New Password</label>
                        <input type="password" class="form-control @error('new_password') is-invalid @enderror" 
                               id="new_password" name="new_password" required>
                        @error('new_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" id="new_password_confirmation" 
                               name="new_password_confirmation" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Password</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-show modal if there are password errors
    @if($errors->has('current_password') || $errors->has('new_password'))
        document.addEventListener('DOMContentLoaded', function() {
            var modal = new bootstrap.Modal(document.getElementById('changePasswordModal'));
            modal.show();
        });
    @endif
</script>
@endpush