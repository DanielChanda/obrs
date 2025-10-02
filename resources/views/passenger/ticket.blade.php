@extends('passenger.layouts.app')

@section('title', 'E-Ticket #' . $booking->id)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <!-- E-Ticket Header -->
        <div class="text-center mb-4">
            <div class="d-flex align-items-center justify-content-center mb-3">
                <i class="fas fa-bus fa-2x text-primary me-3"></i>
                <h1 class="h2 mb-0 text-primary">Online Bus Reservation System</h1>
            </div>
            <p class="text-muted">Your Digital Travel Ticket</p>
        </div>

        <!-- E-Ticket Card -->
        <div class="card shadow-lg border-0">
            <!-- Ticket Header with Status -->
            <div class="card-header bg-primary text-white py-3">
                <div class="row align-items-center">
                    <div class="col">
                        <h4 class="mb-0"><i class="fas fa-ticket-alt me-2"></i>E-Ticket</h4>
                    </div>
                    <div class="col-auto">
                        <span class="badge bg-light text-primary fs-6 p-2">
                            <i class="fas fa-check-circle me-1"></i>
                            {{ ucfirst($booking->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                <!-- Booking Information -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-light rounded-circle p-2 me-3">
                                <i class="fas fa-receipt text-primary"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Booking Reference</small>
                                <strong class="h5">#{{ $booking->id }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-light rounded-circle p-2 me-3">
                                <i class="fas fa-user text-primary"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Passenger</small>
                                <strong class="h5">{{ $booking->user->name }}</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Trip Details -->
                <div class="bg-light rounded p-4 mb-4">
                    <div class="row text-center">
                        <div class="col-md-5">
                            <div class="departure-info">
                                <h5 class="text-primary mb-1">{{ $booking->schedule->route->origin }}</h5>
                                <p class="text-muted mb-1">Departure</p>
                                <strong class="h6">{{ $booking->schedule->departure_time->format('l, M j, Y') }}</strong>
                                <br>
                                <strong class="h5 text-primary">{{ $booking->schedule->departure_time->format('g:i A') }}</strong>
                            </div>
                        </div>
                        <div class="col-md-2 align-self-center">
                            <div class="journey-arrow">
                                <i class="fas fa-long-arrow-alt-right fa-2x text-muted"></i>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="arrival-info">
                                <h5 class="text-primary mb-1">{{ $booking->schedule->route->destination }}</h5>
                                <p class="text-muted mb-1">Arrival</p>
                                <strong class="h6">{{ $booking->schedule->arrival_time->format('l, M j, Y') }}</strong>
                                <br>
                                <strong class="h5 text-primary">{{ $booking->schedule->arrival_time->format('g:i A') }}</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bus and Seat Details -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="detail-card">
                            <div class="d-flex align-items-center">
                                <div class="bg-warning rounded-circle p-2 me-3">
                                    <i class="fas fa-bus text-white"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Bus Details</small>
                                    <strong>{{ $booking->schedule->bus->bus_number }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $booking->schedule->bus->bus_type }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-card">
                            <div class="d-flex align-items-center">
                                <div class="bg-success rounded-circle p-2 me-3">
                                    <i class="fas fa-chair text-white"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Seat Number</small>
                                    <strong class="h4">{{ $booking->seat_number }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fare and Payment Status -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="bg-info text-white rounded p-3 text-center">
                            <small class="d-block">Total Fare</small>
                            <strong class="h3">ZMW{{ number_format($booking->schedule->fare, 2) }}</strong>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="bg-{{ $booking->payment_status === 'paid' ? 'success' : 'warning' }} text-white rounded p-3 text-center">
                            <small class="d-block">Payment Status</small>
                            <strong class="h5">{{ ucfirst($booking->payment_status) }}</strong>
                        </div>
                    </div>
                </div>

                <!-- QR Code Section -->
                <div class="text-center border-top pt-4">
                    <h5 class="mb-3">
                        <i class="fas fa-qrcode me-2 text-primary"></i>Scan for Verification
                    </h5>
                    <div class="d-flex justify-content-center">
                        <div class="border rounded p-3 bg-white">
                            {!! QrCode::encoding('UTF-8')->size(150)->generate($qrData) !!}
                        </div>
                    </div>
                    <small class="text-muted mt-2 d-block">
                        Present this QR code to the bus operator during boarding
                    </small>
                </div>
            </div>

            <!-- Ticket Footer -->
            <div class="card-footer bg-light py-3">
                <div class="row align-items-center">
                    <div class="col">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Please arrive at the boarding point 30 minutes before departure
                        </small>
                    </div>
                    <div class="col-auto">
                        <small class="text-muted">Generated on: {{ now()->format('M j, Y g:i A') }}</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="d-flex justify-content-center gap-3 mt-4">
            <a href="{{ route('passenger.ticket.download', $booking->id) }}" 
               class="btn btn-primary btn-lg">
                <i class="fas fa-download me-2"></i>Download PDF
            </a>
            <a href="{{ route('passenger.dashboard') }}" class="btn btn-outline-secondary btn-lg">
                <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
            </a>
            <button onclick="window.print()" class="btn btn-outline-primary btn-lg">
                <i class="fas fa-print me-2"></i>Print Ticket
            </button>
        </div>

        <!-- Important Instructions -->
        <div class="alert alert-info mt-4">
            <h6><i class="fas fa-exclamation-circle me-2"></i>Important Instructions:</h6>
            <ul class="mb-0">
                <li>Carry a valid government-issued ID for verification</li>
                <li>This e-ticket is valid for travel only on the specified date and time</li>
                <li>Seat number is final and cannot be changed without prior approval</li>
                <li>For cancellations or changes, contact customer support</li>
            </ul>
        </div>
    </div>
</div>

<style>
.detail-card {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 15px;
    height: 100%;
}

.journey-arrow {
    position: relative;
}

.journey-arrow::before {
    content: '';
    position: absolute;
    top: 50%;
    left: -20px;
    right: -20px;
    height: 2px;
    background: #e9ecef;
    z-index: 1;
}

.departure-info, .arrival-info {
    position: relative;
    z-index: 2;
}

.card {
    border: 2px solid #e3f2fd;
}

.card-header {
    border-bottom: 2px dashed #dee2e6;
}

.bg-light {
    background-color: #f8f9fa !important;
}

/* Print Styles */
@media print {
    .btn {
        display: none !important;
    }
    
    .alert {
        display: none !important;
    }
    
    .card {
        border: 1px solid #000 !important;
        box-shadow: none !important;
    }
}
</style>

@endsection