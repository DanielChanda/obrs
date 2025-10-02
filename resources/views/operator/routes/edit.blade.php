@extends('operator.layouts.app')

@section('title', 'Edit Route')
@section('page-title', 'Route Management')
@section('page-subtitle', 'Edit existing route details')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h5 class="card-title mb-0"><i class="fas fa-edit text-warning me-2"></i>Edit Route</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('operator.routes.update', $route->id) }}">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label">Origin</label>
                <input type="text" name="origin" class="form-control" value="{{ $route->origin }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Destination</label>
                <input type="text" name="destination" class="form-control" value="{{ $route->destination }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Distance (km)</label>
                <input type="number" name="distance" class="form-control" value="{{ $route->distance }}">
            </div>
            <div class="d-flex justify-content-end">
                <a href="{{ route('operator.routes.index') }}" class="btn btn-secondary me-2">Cancel</a>
                <button class="btn btn-primary">Update Route</button>
            </div>
        </form>
    </div>
</div>
@endsection

