@extends('admin.layouts.app')

@section('title', 'Edit Route - ' . $route->origin . ' → ' . $route->destination)
@section('page-title', 'Edit Route')
@section('page-subtitle', 'Update route information')

@section('header-actions')
    <a href="{{ route('admin.routes.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back to Routes
    </a>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0">
                    <i class="fas fa-edit me-2 text-primary"></i>Edit Route: {{ $route->origin }} → {{ $route->destination }}
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.routes.update', $route->id) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="origin" class="form-label">Origin City *</label>
                            <input type="text" class="form-control @error('origin') is-invalid @enderror" 
                                   id="origin" name="origin" value="{{ old('origin', $route->origin) }}" 
                                   placeholder="e.g., Lusaka, Kitwe, Ndola" required>
                            @error('origin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="destination" class="form-label">Destination City *</label>
                            <input type="text" class="form-control @error('destination') is-invalid @enderror" 
                                   id="destination" name="destination" value="{{ old('destination', $route->destination) }}" 
                                   placeholder="e.g., Livingstone, Chipata, Solwezi" required>
                            @error('destination')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="distance" class="form-label">Distance (km)</label>
                            <input type="number" class="form-control @error('distance') is-invalid @enderror" 
                                   id="distance" name="distance" value="{{ old('distance', $route->distance) }}" 
                                   placeholder="e.g., 250, 450" min="1" max="5000">
                            @error('distance')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <div class="card bg-light h-100">
                                <div class="card-body">
                                    <h6 class="card-title">Route Preview</h6>
                                    <div id="routePreview" class="text-center py-3">
                                        <h5 class="text-primary">{{ $route->origin }} → {{ $route->destination }}</h5>
                                        @if($route->distance)
                                            <small class="text-muted">{{ number_format($route->distance) }} km</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save me-2"></i> Update Route
                        </button>
                        <a href="{{ route('admin.routes.index') }}" class="btn btn-secondary btn-lg">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Route Statistics -->
        <div class="card shadow-sm mt-4">
            <div class="card-header bg-white py-3">
                <h6 class="card-title mb-0">
                    <i class="fas fa-chart-bar me-2 text-primary"></i>Route Statistics
                </h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-4">
                        <div class="border rounded p-3">
                            <h4 class="text-primary">{{ $route->schedules->count() }}</h4>
                            <small class="text-muted">Total Schedules</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded p-3">
                            <h4 class="text-success">{{ $route->schedules()->where('status', 'scheduled')->count() }}</h4>
                            <small class="text-muted">Active Schedules</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded p-3">
                            <h4 class="text-info">{{ $route->distance ? number_format($route->distance) . ' km' : 'N/A' }}</h4>
                            <small class="text-muted">Distance</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const originInput = document.getElementById('origin');
    const destinationInput = document.getElementById('destination');
    const routePreview = document.getElementById('routePreview');
    
    function updateRoutePreview() {
        const origin = originInput.value.trim();
        const destination = destinationInput.value.trim();
        
        if (origin && destination) {
            routePreview.innerHTML = `
                <h5 class="text-primary">${origin} → ${destination}</h5>
                <small class="text-muted">Route Preview</small>
            `;
        }
    }
    
    originInput.addEventListener('input', updateRoutePreview);
    destinationInput.addEventListener('input', updateRoutePreview);
});
</script>
@endpush