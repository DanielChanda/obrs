@extends('operator.layouts.app')

@section('title', 'Create New Schedule')
@section('page-title', 'Create New Schedule')
@section('page-subtitle', 'Schedule a new bus trip')

@section('header-actions')
    <a href="{{ route('operator.schedules.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back to Schedules
    </a>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0">
                    <i class="fas fa-plus-circle me-2 text-primary"></i>Schedule Information
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('operator.schedules.store') }}" id="scheduleForm">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="bus_id" class="form-label">Select Bus *</label>
                            <select class="form-select @error('bus_id') is-invalid @enderror" 
                                    id="bus_id" name="bus_id" required>
                                <option value="">Choose a bus...</option>
                                @foreach($buses as $bus)
                                    <option value="{{ $bus->id }}" 
                                        {{ (old('bus_id', $selectedBus) == $bus->id) ? 'selected' : '' }}
                                        data-capacity="{{ $bus->capacity }}">
                                        {{ $bus->bus_number }} ({{ $bus->bus_type }} - {{ $bus->capacity }} seats)
                                    </option>
                                @endforeach
                            </select>
                            @error('bus_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="route_id" class="form-label">Select Route *</label>
                            <select class="form-select @error('route_id') is-invalid @enderror" 
                                    id="route_id" name="route_id" required>
                                <option value="">Choose a route...</option>
                                @foreach($routes as $route)
                                    <option value="{{ $route->id }}" 
                                        {{ (old('route_id', $selectedRoute) == $route->id) ? 'selected' : '' }}
                                        data-distance="{{ $route->distance }}">
                                        {{ $route->origin }} → {{ $route->destination }}
                                        @if($route->distance) ({{ $route->distance }} km) @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('route_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="departure_time" class="form-label">Departure Time *</label>
                            <input type="datetime-local" class="form-control @error('departure_time') is-invalid @enderror" 
                                   id="departure_time" name="departure_time" 
                                   value="{{ old('departure_time') }}" 
                                   min="{{ now()->format('Y-m-d\TH:i') }}" required>
                            @error('departure_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror>
                            <small class="form-text text-muted">Date and time when the bus departs</small>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="arrival_time" class="form-label">Arrival Time *</label>
                            <input type="datetime-local" class="form-control @error('arrival_time') is-invalid @enderror" 
                                   id="arrival_time" name="arrival_time" 
                                   value="{{ old('arrival_time') }}" required>
                            @error('arrival_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror>
                            <small class="form-text text-muted">Estimated arrival time</small>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="fare" class="form-label">Ticket Fare ($) *</label>
                            <input type="number" step="0.01" class="form-control @error('fare') is-invalid @enderror" 
                                   id="fare" name="fare" value="{{ old('fare') }}" 
                                   min="1" max="1000" required>
                            @error('fare')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror>
                            <small class="form-text text-muted">Price per ticket</small>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <div class="card bg-light h-100">
                                <div class="card-body">
                                    <h6 class="card-title">Schedule Summary</h6>
                                    <div id="schedulePreview" class="text-muted">
                                        <p class="mb-1">Select bus and route to see preview</p>
                                        <small>Available seats: <span id="previewSeats">-</span></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save me-2"></i> Create Schedule
                        </button>
                        <a href="{{ route('operator.schedules.index') }}" class="btn btn-secondary btn-lg">
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
                    <i class="fas fa-lightbulb me-2"></i>Schedule Creation Tips
                </h6>
            </div>
            <div class="card-body">
                <ul class="mb-0">
                    <li>Ensure the bus is available during the selected time period</li>
                    <li>Consider travel time when setting arrival time</li>
                    <li>Set competitive fares based on route distance and demand</li>
                    <li>Double-check dates and times before creating the schedule</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const busSelect = document.getElementById('bus_id');
    const routeSelect = document.getElementById('route_id');
    const departureInput = document.getElementById('departure_time');
    const arrivalInput = document.getElementById('arrival_time');
    const fareInput = document.getElementById('fare');
    const previewDiv = document.getElementById('schedulePreview');
    const previewSeats = document.getElementById('previewSeats');
    
    function updateSchedulePreview() {
        const busOption = busSelect.options[busSelect.selectedIndex];
        const routeOption = routeSelect.options[routeSelect.selectedIndex];
        const departure = departureInput.value;
        const fare = fareInput.value;
        
        if (busOption.value && routeOption.value && departure && fare) {
            const busText = busOption.text.split(' (')[0];
            const routeText = routeOption.text.split(' (')[0];
            const capacity = busOption.getAttribute('data-capacity');
            
            previewSeats.textContent = capacity;
            
            previewDiv.innerHTML = `
                <p class="mb-1"><strong>${busText}</strong></p>
                <p class="mb-1">${routeText}</p>
                <p class="mb-1">Departure: ${new Date(departure).toLocaleString()}</p>
                <p class="mb-1">Fare: $${parseFloat(fare).toFixed(2)}</p>
                <small>Available seats: ${capacity}</small>
            `;
        } else {
            previewDiv.innerHTML = `
                <p class="mb-1">Select bus and route to see preview</p>
                <small>Available seats: <span id="previewSeats">-</span></small>
            `;
            previewSeats = document.getElementById('previewSeats');
        }
    }
    
    // Set min time for arrival based on departure
    departureInput.addEventListener('change', function() {
        arrivalInput.min = this.value;
        if (arrivalInput.value && arrivalInput.value < this.value) {
            arrivalInput.value = '';
        }
        updateSchedulePreview();
    });
    
    busSelect.addEventListener('change', updateSchedulePreview);
    routeSelect.addEventListener('change', updateSchedulePreview);
    fareInput.addEventListener('input', updateSchedulePreview);
    arrivalInput.addEventListener('change', updateSchedulePreview);
    
    // Auto-set arrival time to 1 hour after departure if empty
    departureInput.addEventListener('change', function() {
        if (this.value && !arrivalInput.value) {
            const departureDate = new Date(this.value);
            departureDate.setHours(departureDate.getHours() + 1);
            arrivalInput.value = departureDate.toISOString().slice(0, 16);
        }
    });
});
</script>
@endpush