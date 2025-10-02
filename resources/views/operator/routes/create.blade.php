@extends('operator.layouts.app')

@section('title', 'Add New Route')
@section('page-title', 'Add New Route')
@section('page-subtitle', 'Create a new travel route')

@section('header-actions')
    <a href="{{ route('operator.routes.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back to Routes
    </a>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0">
                    <i class="fas fa-plus-circle me-2 text-primary"></i>Route Information
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('operator.routes.store') }}">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="origin" class="form-label">Origin City *</label>
                            <input type="text" class="form-control @error('origin') is-invalid @enderror" 
                                   id="origin" name="origin" value="{{ old('origin') }}" 
                                   placeholder="e.g., Lusaka, Kitwe, Ndola" required>
                            @error('origin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Starting point of the journey</small>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="destination" class="form-label">Destination City *</label>
                            <input type="text" class="form-control @error('destination') is-invalid @enderror" 
                                   id="destination" name="destination" value="{{ old('destination') }}" 
                                   placeholder="e.g., Livingstone, Chipata, Solwezi" required>
                            @error('destination')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Ending point of the journey</small>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="distance" class="form-label">Distance (km)</label>
                            <input type="number" class="form-control @error('distance') is-invalid @enderror" 
                                   id="distance" name="distance" value="{{ old('distance') }}" 
                                   placeholder="e.g., 250, 450" min="1" max="5000">
                            @error('distance')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Approximate distance in kilometers (optional)</small>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <div class="card bg-light h-100">
                                <div class="card-body">
                                    <h6 class="card-title">Route Preview</h6>
                                    <div id="routePreview" class="text-center text-muted py-3">
                                        <i class="fas fa-route fa-2x mb-2"></i>
                                        <p class="mb-0">Enter origin and destination to see preview</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save me-2"></i> Create Route
                        </button>
                        <a href="{{ route('operator.routes.index') }}" class="btn btn-secondary btn-lg">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Popular Routes Suggestions -->
        <div class="card shadow-sm mt-4">
            <div class="card-header bg-info text-white py-3">
                <h6 class="card-title mb-0">
                    <i class="fas fa-lightbulb me-2"></i>Popular Route Suggestions
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Common Zambian Routes:</h6>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-arrow-right text-muted me-2"></i>Lusaka → Livingstone</li>
                            <li><i class="fas fa-arrow-right text-muted me-2"></i>Lusaka → Kitwe</li>
                            <li><i class="fas fa-arrow-right text-muted me-2"></i>Lusaka → Ndola</li>
                            <li><i class="fas fa-arrow-right text-muted me-2"></i>Kitwe → Livingstone</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6>Distance References:</h6>
                        <ul class="list-unstyled">
                            <li><small>Lusaka to Livingstone: ~470 km</small></li>
                            <li><small>Lusaka to Kitwe: ~320 km</small></li>
                            <li><small>Lusaka to Ndola: ~350 km</small></li>
                            <li><small>Kitwe to Livingstone: ~450 km</small></li>
                        </ul>
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
                <div class="route-display">
                    <h5 class="text-primary">${origin} → ${destination}</h5>
                    <small class="text-muted">New Route Preview</small>
                </div>
            `;
        } else {
            routePreview.innerHTML = `
                <i class="fas fa-route fa-2x mb-2"></i>
                <p class="mb-0">Enter origin and destination to see preview</p>
            `;
        }
    }
    
    originInput.addEventListener('input', updateRoutePreview);
    destinationInput.addEventListener('input', updateRoutePreview);
});
</script>
@endpush