@extends('admin.layouts.app')

@section('title', 'Edit Bus')
@section('page-title', 'Edit Bus')
@section('page-subtitle', 'Update details for bus ' . $bus->bus_number)

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="card-title">Edit Bus Form</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.buses.update', $bus->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="bus_number" class="form-label">Bus Number</label>
                    <input type="text" class="form-control @error('bus_number') is-invalid @enderror" id="bus_number" name="bus_number" value="{{ old('bus_number', $bus->bus_number) }}" required>
                    @error('bus_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="bus_type" class="form-label">Bus Type</label>
                    <input type="text" class="form-control @error('bus_type') is-invalid @enderror" id="bus_type" name="bus_type" value="{{ old('bus_type', $bus->bus_type) }}" required>
                    @error('bus_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="capacity" class="form-label">Capacity</label>
                    <input type="number" class="form-control @error('capacity') is-invalid @enderror" id="capacity" name="capacity" value="{{ old('capacity', $bus->capacity) }}" required>
                    @error('capacity')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="operator_id" class="form-label">Operator</label>
                    <select class="form-select @error('operator_id') is-invalid @enderror" id="operator_id" name="operator_id" required>
                        <option value="">Select an Operator</option>
                        @foreach($operators as $id => $name)
                            <option value="{{ $id }}" {{ old('operator_id', $bus->operator_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                    @error('operator_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                    <option value="active" {{ old('status', $bus->status) == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $bus->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-end">
                <a href="{{ route('admin.buses.show', $bus->id) }}" class="btn btn-secondary me-2">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Update Bus
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
