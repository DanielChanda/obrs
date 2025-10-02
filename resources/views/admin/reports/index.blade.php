@extends('admin.layouts.app')

@section('title', 'System Reports')
@section('page-title', 'Reports & Analytics')
@section('page-subtitle', 'Track system-wide performance and revenue')

@section('content')
<!-- Action Buttons -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="btn-group">
        <button class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
            <i class="fas fa-download me-1"></i> Export Reports
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="{{ route('admin.reports.export.csv') }}"><i class="fas fa-file-csv me-2"></i>Export as CSV</a></li>
            <li><a class="dropdown-item" href="{{ route('admin.reports.export.pdf') }}"><i class="fas fa-file-pdf me-2"></i>Export as PDF</a></li>
        </ul>
        
        @if($availableYears)
        <button class="btn btn-outline-secondary dropdown-toggle ms-2" data-bs-toggle="dropdown">
            <i class="fas fa-filter me-1"></i> Filter
        </button>
        <div class="dropdown-menu p-3" style="min-width: 300px;">
            <form method="GET" action="{{ route('admin.reports.index') }}" id="filterForm">
                <div class="mb-3">
                    <label class="form-label small">Year</label>
                    <select name="year" class="form-select form-select-sm" onchange="document.getElementById('filterForm').submit()">
                        <option value="">All Years</option>
                        @foreach($availableYears as $availableYear)
                        <option value="{{ $availableYear }}" {{ $filterYear == $availableYear ? 'selected' : '' }}>{{ $availableYear }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label small">Month</label>
                    <select name="month" class="form-select form-select-sm" onchange="document.getElementById('filterForm').submit()">
                        <option value="">All Months</option>
                        @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ $filterMonth == $i ? 'selected' : '' }}>
                            {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                        </option>
                        @endfor
                    </select>
                </div>
                <div class="d-grid">
                    <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-secondary btn-sm">Clear Filters</a>
                </div>
            </form>
        </div>
        @endif
    </div>
    
    <div class="d-flex align-items-center">
        @if($filterYear || $filterMonth)
        <span class="badge bg-info me-2">
            <i class="fas fa-filter me-1"></i>
            Filtered: 
            @if($filterYear && $filterMonth)
                {{ DateTime::createFromFormat('!m', $filterMonth)->format('F') }} {{ $filterYear }}
            @elseif($filterYear)
                Year {{ $filterYear }}
            @endif
        </span>
        @endif
        
        <form method="POST" action="{{ route('admin.reports.clear-cache') }}" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-outline-warning btn-sm" title="Refresh data cache">
                <i class="fas fa-sync-alt"></i>
            </button>
        </form>
    </div>
</div>

<!-- Summary Statistics -->
<div class="row g-3 mb-4">
    <div class="col-xl-2 col-md-3 col-6">
        <div class="card bg-primary text-white shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">{{ $totalOperators }}</h4>
                        <small class="opacity-75">Operators</small>
                    </div>
                    <div class="bg-white bg-opacity-25 p-2 rounded">
                        <i class="fas fa-users-cog"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-2 col-md-3 col-6">
        <div class="card bg-success text-white shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">{{ $totalPassengers }}</h4>
                        <small class="opacity-75">Passengers</small>
                    </div>
                    <div class="bg-white bg-opacity-25 p-2 rounded">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-2 col-md-3 col-6">
        <div class="card bg-info text-white shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">{{ $totalBuses }}</h4>
                        <small class="opacity-75">Buses</small>
                    </div>
                    <div class="bg-white bg-opacity-25 p-2 rounded">
                        <i class="fas fa-bus"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-2 col-md-3 col-6">
        <div class="card bg-warning text-white shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">{{ $totalBookings }}</h4>
                        <small class="opacity-75">Total Bookings</small>
                    </div>
                    <div class="bg-white bg-opacity-25 p-2 rounded">
                        <i class="fas fa-ticket-alt"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-4 col-md-6">
        <div class="card bg-dark text-white shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">ZMW{{ number_format($totalRevenue, 2) }}</h4>
                        <small class="opacity-75">Total Revenue</small>
                    </div>
                    <div class="bg-white bg-opacity-25 p-2 rounded">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>
                @if($filterYear || $filterMonth)
                <div class="mt-2">
                    <small class="opacity-75">
                        <i class="fas fa-info-circle me-1"></i>
                        Filtered revenue data
                    </small>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="row g-4">
    <!-- Main Chart -->
    <div class="col-xxl-8 col-lg-7">
        <div class="card shadow-sm h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Monthly Bookings & Revenue Trends</span>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="toggleChartType">
                    <label class="form-check-label small" for="toggleChartType">Toggle Chart Type</label>
                </div>
            </div>
            <div class="card-body">
                <canvas id="advancedReportsChart" height="300"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Quick Stats Sidebar -->
    <div class="col-xxl-4 col-lg-5">
        <!-- Monthly Performance -->
        <div class="card shadow-sm mb-4">
            <div class="card-header">Current Month Performance</div>
            <div class="card-body">
                @php
                    $currentMonth = now()->format('F Y');
                    $currentMonthData = $monthlyStats->firstWhere('month', now()->month);
                @endphp
                @if($currentMonthData)
                <div class="row text-center">
                    <div class="col-6 border-end">
                        <h5 class="text-primary mb-1">{{ $currentMonthData->total_bookings }}</h5>
                        <small class="text-muted">Bookings</small>
                    </div>
                    <div class="col-6">
                        <h5 class="text-success mb-1">ZMW{{ number_format($currentMonthData->revenue, 2) }}</h5>
                        <small class="text-muted">Revenue</small>
                    </div>
                </div>
                @else
                <div class="text-center text-muted py-3">
                    <i class="fas fa-chart-line fa-2x mb-2"></i>
                    <p class="mb-0">No data for {{ $currentMonth }}</p>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Top Performing Months -->
        <div class="card shadow-sm">
            <div class="card-header">Top Performing Months</div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @foreach($monthlyStats->sortByDesc('revenue')->take(5) as $stat)
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <small class="fw-bold d-block">
                                {{ DateTime::createFromFormat('!m', $stat->month)->format('M') }} {{ $stat->year }}
                            </small>
                            <small class="text-muted">{{ $stat->total_bookings }} bookings</small>
                        </div>
                        <span class="badge bg-success rounded-pill">ZMW{{ number_format($stat->revenue, 2) }}</span>
                    </div>
                    @endforeach
                    @if($monthlyStats->isEmpty())
                    <div class="list-group-item text-center text-muted py-4">
                        <i class="fas fa-chart-bar fa-2x mb-2"></i>
                        <p class="mb-0">No data available</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Data Summary Table -->
@if(!$monthlyStats->isEmpty())
<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Monthly Data Summary</span>
                <small class="text-muted">Last updated: {{ now()->format('M j, Y g:i A') }}</small>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Month</th>
                                <th class="text-center">Bookings</th>
                                <th class="text-end">Revenue</th>
                                <th class="text-center">Avg. per Booking</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($monthlyStats->sortByDesc('year')->sortByDesc('month') as $stat)
                            <tr>
                                <td>
                                    <strong>{{ DateTime::createFromFormat('!m', $stat->month)->format('F Y') }}</strong>
                                </td>
                                <td class="text-center">{{ $stat->total_bookings }}</td>
                                <td class="text-end text-success fw-bold">ZMW{{ number_format($stat->revenue, 2) }}</td>
                                <td class="text-center">
                                    ZMW{{ $stat->total_bookings > 0 ? number_format($stat->revenue / $stat->total_bookings, 2) : '0.00' }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@endsection

@push('styles')
<style>
.card {
    border: none;
    transition: transform 0.2s ease-in-out;
}
.card:hover {
    transform: translateY(-2px);
}
.bg-opacity-25 {
    opacity: 0.25;
}
.list-group-item {
    border: none;
    padding: 1rem 1.25rem;
}
.table th {
    border-top: none;
    font-weight: 600;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('advancedReportsChart').getContext('2d');
    let chartType = 'bar';
    
    // Initialize chart
    const advancedChart = new Chart(ctx, {
        data: {
            labels: @json($chartLabels),
            datasets: [
                {
                    type: 'bar',
                    label: 'Bookings',
                    data: @json($chartBookingData),
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1,
                    yAxisID: 'yBookings',
                    order: 2
                },
                {
                    type: 'line',
                    label: 'Revenue',
                    data: @json($chartRevenueData),
                    backgroundColor: 'rgba(75, 192, 192, 0.1)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    yAxisID: 'yRevenue',
                    order: 1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Monthly Performance Overview',
                    font: { size: 16 }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (context.dataset.type === 'line') {
                                label += ': ZMW' + context.parsed.y.toLocaleString();
                            } else {
                                label += ': ' + context.parsed.y.toLocaleString();
                            }
                            return label;
                        }
                    }
                },
                legend: {
                    display: true,
                    position: 'top'
                }
            },
            scales: {
                x: {
                    title: { 
                        display: true, 
                        text: 'Month',
                        font: { weight: 'bold' }
                    },
                    grid: { display: false }
                },
                yBookings: {
                    type: 'linear',
                    position: 'left',
                    title: { 
                        display: true, 
                        text: 'Number of Bookings',
                        font: { weight: 'bold' }
                    },
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString();
                        }
                    }
                },
                yRevenue: {
                    type: 'linear',
                    position: 'right',
                    title: { 
                        display: true, 
                        text: 'Revenue (ZMW)',
                        font: { weight: 'bold' }
                    },
                    beginAtZero: true,
                    grid: { drawOnChartArea: false },
                    ticks: {
                        callback: function(value) {
                            return 'ZMW' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });

    // Toggle chart type
    document.getElementById('toggleChartType').addEventListener('change', function() {
        chartType = this.checked ? 'line' : 'bar';
        
        advancedChart.data.datasets.forEach(dataset => {
            if (dataset.label === 'Bookings') {
                dataset.type = chartType;
                if (chartType === 'line') {
                    dataset.borderWidth = 2;
                    dataset.backgroundColor = 'rgba(54, 162, 235, 0.1)';
                } else {
                    dataset.borderWidth = 1;
                    dataset.backgroundColor = 'rgba(54, 162, 235, 0.7)';
                }
            }
        });
        
        advancedChart.update();
    });

    // Add resize observer for better responsiveness
    const resizeObserver = new ResizeObserver(() => {
        advancedChart.resize();
    });
    resizeObserver.observe(document.getElementById('advancedReportsChart'));
});
</script>

@if(session('success'))
<script>
    Toastify({
        text: "{{ session('success') }}",
        duration: 3000,
        close: true,
        gravity: "top",
        position: "right",
        backgroundColor: "#28a745",
    }).showToast();
</script>
@endif
@endpush