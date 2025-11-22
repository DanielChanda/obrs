@extends('operator.layouts.app')

@section('title', 'Profile')
@section('page-title', 'Operator Profile')
@section('page-subtitle', 'Manage your operator account details')

@section('content')
<div class="card shadow-sm">
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('operator.profile.update') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">First name</label>
                <input type="text" name="first_name" value="{{ old('first_name', $operator->first_name) }}" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Last name</label>
                <input type="text" name="last_name" value="{{ old('last_name', $operator->last_name) }}" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Phone</label>
                <input type="text" name="phone" value="{{ old('phone', $operator->phone) }}" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">New Password</label>
                <input type="password" name="password" class="form-control">
                <small class="text-muted">Leave blank to keep current password</small>
            </div>
            <div class="mb-3">
                <label class="form-label">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control">
            </div>
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save me-1"></i> Save Changes
            </button>
        </form>
    </div>
</div>
@endsection
