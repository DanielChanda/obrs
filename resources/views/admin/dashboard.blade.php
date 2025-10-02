@extends('admin.layouts.app')
@section('page-title', 'AdminDashboard')
@section('total-revenue', 'ZMW'.number_format($totalRevenue, 2))
@section('active-users', $totalUsers)
@section('title', 'Admin Dashboard')

@section('content')
<div class="row g-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white shadow-sm">
            <div class="card-body">
                <h4>{{ $totalUsers }}</h4>
                <small>Total Users</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white shadow-sm">
            <div class="card-body">
                <h4>{{ $totalOperators }}</h4>
                <small>Total Operators</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white shadow-sm">
            <div class="card-body">
                <h4>{{ $totalBookings }}</h4>
                <small>Total Bookings</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white shadow-sm">
            <div class="card-body">
                <h4>ZMW{{ number_format($totalRevenue, 2) }}</h4>
                <small>Total Revenue</small>
            </div>
        </div>
    </div>
</div>
@endsection
