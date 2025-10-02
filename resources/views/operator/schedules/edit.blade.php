@extends('operator.layouts.app')

@section('title', 'Edit Schedule #' . $schedule->id)
@section('page-title', 'Edit Schedule')
@section('page-subtitle', 'Update schedule information')

@section('header-actions')
    <a href="{{ route('operator.schedules.show', $schedule->id) }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back to Schedule
    </a>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0">
                    <i class="fas fa-edit me-2 text-primary"></i>Edit Schedule: #{{ $schedule->id }}
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('operator.schedules.update', $schedule->id) }}" id="scheduleForm">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="bus_id" class="form-label">Select Bus *</label>
                            <select class="form-select @error('bus_id') is-invalid @enderror" 
                                    id="bus_id" name="bus_id" required>
                                <option value="">Choose a bus...</option>
                                @foreach($buses as $bus)
                                    <option value="{{ $bus->id }}" 
                                        {{ old('bus_id', $schedule->bus_id) == $bus->id ? 'selected' : '' }}
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
                                        {{ old('route_id', $schedule->route_id) == $route->id ? 'selected' : '' }}
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
                                   value="{{ old('departure_time', $schedule->departure_time->format('Y-m-d\TH:i')) }}" 
                                   min="{{ now()->format('Y-m-d\TH:i') }}" required>
                            @error('departure_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="arrival_time" class="form-label">Arrival Time *</label>
                            <input type="datetime-local" class="form-control @error('arrival_time') is-invalid @enderror" 
                                   id="arrival_time" name="arrival_time" 
                                   value="{{ old('arrival_time', $schedule->arrival_time->format('Y-m-d\TH:i')) }}" required>
                            @error('arrival_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="fare" class="form-label">Ticket Fare ($) *</label>
                            <input type="number" step="0.01" class="form-control @error('fare') is-invalid @enderror" 
                                   id="fare" name="fare" value="{{ old('fare', $schedule->fare) }}" 
                                   min="1" max="1000" required>
                            @error('fare')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Status *</label>
                            <select class="form-select @error('status') is-invalid @enderror" 
                                    id="status" name="status" required>
                                <option value="scheduled" {{ old('status', $schedule->status) == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                <option value="completed" {{ old('status', $schedule->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ old('status', $schedule->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save me-2"></i> Update Schedule
                        </button>
                        <a href="{{ route('operator.schedules.show', $schedule->id) }}" class="btn btn-secondary btn-lg">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Current Schedule Info -->
        <div class="card shadow-sm mt-4">
            <div class="card-header bg-info text-white py-3">
                <h6 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>Current Schedule Details
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td class="fw-semibold">Bus:</td>
                                <td>{{ $schedule->bus->bus_number }} ({{ $schedule->bus->bus_type }})</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Route:</td>
                                <td>{{ $schedule->route->origin }} → {{ $schedule->route->destination }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Current Fare:</td>
                                <td>${{ number_format($schedule->fare, 2) }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td class="fw-semibold">Departure:</td>
                                <td>{{ $schedule->departure_time->format('M j, Y g:i A') }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Arrival:</td>
                                <td>{{ $schedule->arrival_time->format('M j, Y g:i A') }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Available Seats:</td>
                                <td>{{ $schedule->available_seats }} / {{ $schedule->bus->capacity }}</td>
                            </tr>
                        </table>
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
    const departureInput = document.getElementById('departure_time');
    const arrivalInput = document.getElementById('arrival_time');
    
    // Set min time for arrival based on departure
    departureInput.addEventListener('change', function() {
        arrivalInput.min = this.value;
        if (arrivalInput.value && arrivalInput.value < this.value) {
            arrivalInput.value = this.value;
        }
    });
    
    // Auto-set arrival time to 1 hour after departure if empty or invalid
    departureInput.addEventListener('change', function() {
        if (this.value) {
            const departureDate = new Date(this.value);
            const currentArrival = new Date(arrivalInput.value);
            
            if (!arrivalInput.value || currentArrival <= departureDate) {
                departureDate.setHours(departureDate.getHours() + 1);
                arrivalInput.value = departureDate.toISOString().slice(0, 16);
            }
        }
    });
});
</script>
@endpush