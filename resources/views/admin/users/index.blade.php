@extends('admin.layouts.app')

@section('title', 'User Management')
@section('page-title', 'User Management')
@section('page-subtitle', 'Manage all system users')

@section('content')
<div class="card shadow-sm mb-4">
    <div class="card-header bg-white py-3">
        <h6 class="card-title mb-0">
            <i class="fas fa-users me-2 text-primary"></i>Filter by Role
        </h6>
    </div>
    <div class="card-body">
        <ul class="nav nav-pills">
            <li class="nav-item">
                <a class="nav-link {{ ($filters['role'] ?? 'all') == 'all' ? 'active' : '' }}"
                   href="{{ route('admin.users.index', ['role' => 'all']) }}">
                   All Users
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ ($filters['role'] ?? '') == 'operator' ? 'active' : '' }}"
                   href="{{ route('admin.users.index', ['role' => 'operator']) }}">
                   Operators
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ ($filters['role'] ?? '') == 'passenger' ? 'active' : '' }}"
                   href="{{ route('admin.users.index', ['role' => 'passenger']) }}">
                   Passengers
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ ($filters['role'] ?? '') == 'admin' ? 'active' : '' }}"
                   href="{{ route('admin.users.index', ['role' => 'admin']) }}">
                   Admins
                </a>
            </li>
        </ul>
    </div>
</div>

<!-- Existing Users Table -->
<div class="card shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">
            <i class="fas fa-users me-2 text-primary"></i>Users
            <span class="badge bg-primary ms-2">{{ $users->total() }}</span>
        </h5>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Add User
        </a>
    </div>
    <div class="card-body">
        @if($users->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>#{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="badge bg-{{ $user->role === 'operator' ? 'success' : ($user->role === 'passenger' ? 'info' : 'secondary') }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td>{{ $user->created_at->format('M j, Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-outline-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this user?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $users->appends($filters)->links(('pagination::bootstrap-5')) }}
        @else
            <p class="text-center text-muted py-5">No users found.</p>
        @endif
    </div>
</div>
@endsection
