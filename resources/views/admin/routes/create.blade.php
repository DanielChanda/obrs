@extends('admin.layouts.app')

@section('title', 'Add New Route')
@section('page-title', 'Add New Route')
@section('page-subtitle', 'Create a new route and assign it to an operator')

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
                        <i class="fas fa-plus me-2 text-primary"></i>Create New Route
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.routes.store') }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="origin" class="form-label">Origin City *</label>
                                <input type="text" class="form-control @error('origin') is-invalid @enderror" id="origin"
                                    name="origin" value="{{ old('origin') }}" placeholder="e.g., Lusaka, Kitwe, Ndola"
                                    required>
                                @error('origin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="destination" class="form-label">Destination City *</label>
                                <input type="text" class="form-control @error('destination') is-invalid @enderror"
                                    id="destination" name="destination" value="{{ old('destination') }}"
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
                                    id="distance" name="distance" value="{{ old('distance') }}" placeholder="e.g., 250, 450"
                                    min="1" max="5000">
                                @error('distance')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="operator_id" class="form-label">Operator</label>
                                <select class="form-select @error('operator_id') is-invalid @enderror" id="operator_id"
                                    name="operator_id" required>
                                    <option value="">Select an Operator</option>
                                    @foreach($operators as $id => $name)
                                        <option value="{{ $id }}" {{ old('operator_id') == $id ? 'selected' : '' }}>{{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('operator_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-2"></i> Create Route
                            </button>
                            <a href="{{ route('admin.routes.index') }}" class="btn btn-secondary btn-lg">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection