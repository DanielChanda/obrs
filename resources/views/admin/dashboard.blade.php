@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<h3>Admin Dashboard</h3>
<div class="row mt-3">
    <div class="col-md-4">
        <div class="card text-bg-primary mb-3">
            <div class="card-body">
                <h5 class="card-title">Total Users</h5>
                <p class="card-text fs-4">{{ $users }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-bg-success mb-3">
            <div class="card-body">
                <h5 class="card-title">Total Bookings</h5>
                <p class="card-text fs-4">{{ $bookings }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-bg-warning mb-3">
            <div class="card-body">
                <h5 class="card-title">Revenue</h5>
                <p class="card-text fs-4">${{ number_format($revenue, 2) }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
