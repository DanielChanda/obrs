@extends('admin.layouts.app')

@section('title', 'Edit User')

@section('content')
<h4>Edit User</h4>
<div class="card shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
            @csrf @method('PUT')
            <div class="mb-3">
                <label>Name</label>
                <input type="text" name="name" value="{{ $user->name }}" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" value="{{ $user->email }}" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Password (leave blank to keep current)</label>
                <input type="password" name="password" class="form-control">
            </div>
            <div class="mb-3">
                <label>Role</label>
                <select name="role" class="form-select" required>
                    <option value="passenger" {{ $user->role=='passenger'?'selected':'' }}>Passenger</option>
                    <option value="operator" {{ $user->role=='operator'?'selected':'' }}>Operator</option>
                    <option value="admin" {{ $user->role=='admin'?'selected':'' }}>Admin</option>
                </select>
            </div>
            <button class="btn btn-success">Update</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
