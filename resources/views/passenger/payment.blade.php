@extends('passenger.layouts.app')

@section('title', 'Complete Payment')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="h3 mb-0 text-primary">Complete Your Payment</h2>
                <span class="badge bg-warning text-dark fs-6">Booking #{{ $booking->id }}</span>
            </div>

            <div class="row">
                <!-- Booking Summary -->
                <div class="col-lg-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-primary text-white py-3">
                            <h5 class="mb-0">
                                <i class="fas fa-receipt me-2"></i>Booking Summary
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-bus text-primary fa-2x"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">{{ $booking->schedule->bus->bus_number }}</h6>
                                    <small class="text-muted">Coach</small>
                                </div>
                            </div>

                            <div class="route-info mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <h5 class="text-dark mb-1">{{ $booking->schedule->route->origin }}</h5>
                                        <small class="text-muted">Origin</small>
                                    </div>
                                    <div class="mx-3">
                                        <i class="fas fa-arrow-right text-muted"></i>
                                    </div>
                                    <div>
                                        <h5 class="text-dark mb-1">{{ $booking->schedule->route->destination }}</h5>
                                        <small class="text-muted">Destination</small>
                                    </div>
                                </div>
                            </div>

                            <div class="trip-details">
                                <div class="row">
                                    <div class="col-6 mb-3">
                                        <small class="text-muted d-block">Departure</small>
                                        <strong>{{ $booking->schedule->departure_time->format('D, M j, Y') }}</strong>
                                        <br>
                                        <strong class="text-primary">{{ $booking->schedule->departure_time->format('g:i A') }}</strong>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <small class="text-muted d-block">Seat Number</small>
                                        <strong class="text-success fs-5">#{{ $booking->seat_number }}</strong>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted d-block">Duration</small>
                                        <strong>Approx. {{ $booking->schedule->estimated_duration ?? '4h 30m' }}</strong>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted d-block">Passenger</small>
                                        <strong>{{ auth()->user()->name }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Section -->
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-success text-white py-3">
                            <h5 class="mb-0">
                                <i class="fas fa-credit-card me-2"></i>Payment Details
                            </h5>
                        </div>
                        <div class="card-body">
                            <!-- Amount Display -->
                            <div class="text-center mb-4 p-3 bg-light rounded">
                                <small class="text-muted">Total Amount to Pay</small>
                                <h2 class="text-success mb-0">ZMW {{ number_format($booking->schedule->fare, 2) }}</h2>
                            </div>

                            <!-- Payment Methods -->
                            <div class="mb-4">
                                <h6 class="mb-3">Select Payment Method</h6>
                                
                                <div class="d-grid gap-2">
                                    <button type="button" id="payWithFlutterwave" class="btn btn-primary btn-lg py-3">
                                        <i class="fas fa-lock me-2"></i>
                                        Pay Securely with Flutterwave
                                    </button>
                                    
                                    <div class="text-center mt-2">
                                        <small class="text-muted">Supports Card, Mobile Money, Bank Transfer</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Security -->
                            <div class="alert alert-light border mt-4">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-shield-alt text-success me-2"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <small class="text-muted">
                                            <strong>Secure Payment</strong><br>
                                            Your payment information is encrypted and secure. We do not store your card details.
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <!-- Loading Spinner -->
                            <div id="loadingSpinner" class="text-center d-none">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Processing...</span>
                                </div>
                                <p class="mt-2 text-muted">Redirecting to secure payment gateway...</p>
                            </div>

                            <!-- Error Alert -->
                            <div id="errorAlert" class="alert alert-danger d-none" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <span id="errorMessage"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Support Info -->
                    <div class="card border-0 shadow-sm mt-4">
                        <div class="card-body text-center">
                            <small class="text-muted">
                                <i class="fas fa-headset me-1"></i>
                                Need help? Contact support: 
                                <a href="mailto:support@busticket.com" class="text-decoration-none">support@busticket.com</a>
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Important Notes -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card border-0 bg-light">
                        <div class="card-body">
                            <h6 class="mb-3">
                                <i class="fas fa-info-circle text-info me-2"></i>Important Information
                            </h6>
                            <div class="row">
                                <div class="col-md-4 mb-2">
                                    <div class="d-flex">
                                        <i class="fas fa-ticket-alt text-primary me-2 mt-1"></i>
                                        <small class="text-muted">Ticket will be generated automatically after successful payment</small>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <div class="d-flex">
                                        <i class="fas fa-clock text-warning me-2 mt-1"></i>
                                        <small class="text-muted">Booking expires in 30 minutes if not paid</small>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <div class="d-flex">
                                        <i class="fas fa-sync-alt text-success me-2 mt-1"></i>
                                        <small class="text-muted">Instant refund for cancelled trips (terms apply)</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.card {
    border-radius: 15px;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

.route-info {
    position: relative;
    padding: 20px 0;
}

.route-info:before {
    content: '';
    position: absolute;
    left: 20%;
    right: 20%;
    top: 50%;
    height: 2px;
    background: linear-gradient(90deg, transparent, #667eea, transparent);
    transform: translateY(-50%);
}

.badge {
    border-radius: 10px;
    padding: 8px 12px;
}

.alert-light {
    border-radius: 10px;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const payButton = document.getElementById('payWithFlutterwave');
    const spinner = document.getElementById('loadingSpinner');
    const errorAlert = document.getElementById('errorAlert');
    const errorMessage = document.getElementById('errorMessage');

    payButton.addEventListener('click', function() {
        // Reset previous errors
        errorAlert.classList.add('d-none');
        
        // Show loading state
        payButton.disabled = true;
        payButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
        spinner.classList.remove('d-none');

        // Initialize payment
        fetch('{{ route("passenger.payment.initialize", $booking->id) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.status === 'success') {
                // Redirect to Flutterwave payment page
                window.location.href = data.data.payment_url;
            } else {
                throw new Error(data.message || 'Failed to initialize payment');
            }
        })
        .catch(error => {
            console.error('Payment Error:', error);
            
            // Show error message
            errorMessage.textContent = error.message || 'An error occurred while initializing payment. Please try again.';
            errorAlert.classList.remove('d-none');
            
            // Reset button state
            payButton.disabled = false;
            payButton.innerHTML = '<i class="fas fa-lock me-2"></i>Pay Securely with Flutterwave';
            spinner.classList.add('d-none');

            // Scroll to error message
            errorAlert.scrollIntoView({ behavior: 'smooth', block: 'center' });
        });
    });

    // Add some interactive effects
    const cards = document.querySelectorAll('.card');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
            this.style.transition = 'all 0.3s ease';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});
</script>
@endpush