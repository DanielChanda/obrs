@extends('operator.layouts.app')

@section('title', 'Reports')
@section('page-title', 'Reports & Analytics')
@section('page-subtitle', 'View system activity and performance')

@section('content')
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white shadow-sm">
            <div class="card-body">
                <h4>{{ $totalBuses }}</h4>
                <small>Total Buses</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white shadow-sm">
            <div class="card-body">
                <h4>{{ $totalSchedules }}</h4>
                <small>Total Schedules</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white shadow-sm">
            <div class="card-body">
                <h4>{{ $totalBookings }}</h4>
                <small>Total Bookings</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white shadow-sm">
            <div class="card-body">
                <h4>${{ number_format($revenue, 2) }}</h4>
                <small>Total Revenue</small>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-header">
        <h5 class="card-title">Monthly Trends</h5>
    </div>
    <div class="card-body">
        <canvas id="bookingRevenueChart" height="120"></canvas>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header">
        <h5 class="card-title">Monthly Breakdown</h5>
    </div>
    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Month</th>
                    <th>Total Bookings</th>
                    <th>Revenue</th>
                </tr>
            </thead>
            <tbody>
                @foreach($monthlyStats as $stat)
                    <tr>
                        <td>{{ \Carbon\Carbon::create()->month($stat->month)->format('F') }}</td>
                        <td>{{ $stat->total }}</td>
                        <td>${{ number_format($stat->revenue, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const ctx = document.getElementById('bookingRevenueChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($months),
            datasets: [
                {
                    label: 'Bookings',
                    data: @json($bookingsPerMonth),
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Revenue ($)',
                    data: @json($revenuePerMonth),
                    type: 'line',
                    borderColor: 'rgba(255, 206, 86, 1)',
                    backgroundColor: 'rgba(255, 206, 86, 0.5)',
                    fill: true,
                    tension: 0.3
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$' + value;
                        }
                    }
                }
            }
        }
    });
</script>
@endpush
