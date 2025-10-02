@extends('operator.layouts.app')

@section('title', 'Add New Bus')
@section('page-title', 'Add New Bus')
@section('page-subtitle', 'Add a new bus to your fleet')

@section('header-actions')
    <a href="{{ route('operator.buses.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back to Buses
    </a>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0">
                    <i class="fas fa-plus-circle me-2 text-primary"></i>Bus Information
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('operator.buses.store') }}">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="bus_number" class="form-label">Bus Number *</label>
                            <input type="text" class="form-control @error('bus_number') is-invalid @enderror" 
                                   id="bus_number" name="bus_number" value="{{ old('bus_number') }}" 
                                   placeholder="e.g., BUS-001, KA-01-AB-1234" required>
                            @error('bus_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Unique identifier for your bus</small>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="bus_type" class="form-label">Bus Type *</label>
                            <select class="form-select @error('bus_type') is-invalid @enderror" 
                                    id="bus_type" name="bus_type" required>
                                <option value="">Select Bus Type</option>
                                <option value="Mini" {{ old('bus_type') == 'Mini' ? 'selected' : '' }}>Mini Bus</option>
                                <option value="Standard" {{ old('bus_type') == 'Standard' ? 'selected' : '' }}>Standard Bus</option>
                                <option value="Coach" {{ old('bus_type') == 'Coach' ? 'selected' : '' }}>Coach Bus</option>
                                <option value="Luxury" {{ old('bus_type') == 'Luxury' ? 'selected' : '' }}>Luxury Bus</option>
                                <option value="Double Decker" {{ old('bus_type') == 'Double Decker' ? 'selected' : '' }}>Double Decker</option>
                                <option value="Sleeper" {{ old('bus_type') == 'Sleeper' ? 'selected' : '' }}>Sleeper Bus</option>
                            </select>
                            @error('bus_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="capacity" class="form-label">Passenger Capacity *</label>
                            <input type="number" class="form-control @error('capacity') is-invalid @enderror" 
                                   id="capacity" name="capacity" value="{{ old('capacity') }}" 
                                   min="1" max="100" required>
                            @error('capacity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Number of seats available</small>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Status *</label>
                            <select class="form-select @error('status') is-invalid @enderror" 
                                    id="status" name="status" required>
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Inactive buses won't be available for scheduling</small>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save me-2"></i> Add Bus to Fleet
                        </button>
                        <a href="{{ route('operator.buses.index') }}" class="btn btn-secondary btn-lg">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Help Card -->
        <div class="card shadow-sm mt-4">
            <div class="card-header bg-info text-white py-3">
                <h6 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>Bus Management Tips
                </h6>
            </div>
            <div class="card-body">
                <ul class="mb-0">
                    <li>Use unique bus numbers for easy identification</li>
                    <li>Set buses to "Inactive" during maintenance periods</li>
                    <li>Accurate capacity ensures proper seat allocation</li>
                    <li>You can edit bus details anytime</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection