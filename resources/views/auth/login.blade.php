@extends('layouts.app')

@section('title', 'Login - Bus Booking System')

@section('content')
<div class="container-fluid">
    <div class="row min-vh-100">
        <!-- Left Side - Brand/Info Section -->
        <div class="col-lg-6 d-none d-lg-flex bg-primary text-white">
            <div class="d-flex align-items-center justify-content-center w-100">
                <div class="text-center px-5">
                    <div class="mb-5">
                        <i class="fas fa-bus fa-5x text-white-50 mb-4"></i>
                        <h1 class="display-5 fw-bold">Bus Booking System</h1>
                        <p class="lead">Efficient, Reliable, and Secure Bus Reservation Platform</p>
                    </div>
                    
                    <div class="row text-start mt-5">
                        <div class="col-12 mb-4">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-shield-alt fa-2x me-3 text-white-50"></i>
                                <div>
                                    <h5>Secure & Reliable</h5>
                                    <small class="text-white-50">Your data is protected with enterprise-grade security</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mb-4">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-bolt fa-2x me-3 text-white-50"></i>
                                <div>
                                    <h5>Fast & Efficient</h5>
                                    <small class="text-white-50">Quick booking process with real-time availability</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-headset fa-2x me-3 text-white-50"></i>
                                <div>
                                    <h5>24/7 Support</h5>
                                    <small class="text-white-50">Round-the-clock customer support available</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="col-lg-6 d-flex align-items-center justify-content-center bg-light">
            <div class="w-100" style="max-width: 400px;">
                <!-- Header -->
                <div class="text-center mb-5">
                    <div class="d-lg-none mb-4">
                        <i class="fas fa-bus fa-3x text-primary mb-3"></i>
                        <h2 class="text-primary fw-bold">Bus Booking System</h2>
                    </div>
                    <h3 class="fw-bold text-dark mb-2">Welcome Back</h3>
                    <p class="text-muted">Sign in to your account to continue</p>
                </div>

                <!-- Alert Messages -->
                @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <strong>Error!</strong> {{ $errors->first() }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                @if(session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <!-- Login Form -->
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('login') }}" id="loginForm">
                            @csrf

                            <!-- Email Field -->
                            <div class="mb-4">
                                <label for="email" class="form-label fw-semibold">
                                    <i class="fas fa-envelope me-2 text-primary"></i>Email Address
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-user text-muted"></i>
                                    </span>
                                    <input type="email" 
                                           class="form-control border-start-0 @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email') }}" 
                                           required 
                                           autofocus
                                           placeholder="Enter your email address">
                                </div>
                                @error('email')
                                    <div class="invalid-feedback d-block">
                                        <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Password Field -->
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label for="password" class="form-label fw-semibold">
                                        <i class="fas fa-lock me-2 text-primary"></i>Password
                                    </label>
                                    @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-decoration-none text-primary small">
                                        Forgot Password?
                                    </a>
                                    @endif
                                </div>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-key text-muted"></i>
                                    </span>
                                    <input type="password" 
                                           class="form-control border-start-0 @error('password') is-invalid @enderror" 
                                           id="password" 
                                           name="password" 
                                           required 
                                           placeholder="Enter your password">
                                    <button class="btn btn-outline-secondary border-start-0" type="button" id="togglePassword">
                                        <i class="fas fa-eye" id="toggleIcon"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block">
                                        <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Remember Me & Additional Options -->
                            <div class="mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label text-muted" for="remember">
                                        Keep me signed in
                                    </label>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-primary btn-lg w-100 py-2 mb-3" id="loginButton">
                                <i class="fas fa-sign-in-alt me-2"></i>
                                <span id="buttonText">Sign In</span>
                                <div class="spinner-border spinner-border-sm ms-2 d-none" id="spinner" role="status"></div>
                            </button>

                            <!-- Demo Credentials (Remove in production) -->
                            <!-- <div class="alert alert-info mt-4">
                                <h6 class="alert-heading mb-2"><i class="fas fa-info-circle me-2"></i>Demo Access</h6>
                                <small class="d-block mb-1"><strong>Admin:</strong> admin@example.com / password</small>
                                <small class="d-block mb-1"><strong>Operator:</strong> operator@example.com / password</small>
                                <small class="d-block"><strong>Passenger:</strong> passenger@example.com / password</small>
                            </div> -->
                        </form>
                    </div>
                </div>

                <!-- Additional Links -->
                <div class="text-center mt-4">
                    <p class="text-muted mb-0">
                        Don't have an account? 
                        @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="text-decoration-none fw-semibold text-primary">
                            Create one here
                        </a>
                        @else
                        <span class="text-muted">Contact administrator for access</span>
                        @endif
                    </p>
                </div>

                <!-- Security Notice -->
                <div class="text-center mt-4">
                    <small class="text-muted">
                        <i class="fas fa-shield-alt me-1"></i>
                        Protected by reCAPTCHA and subject to our 
                        <a href="#" class="text-decoration-none">Privacy Policy</a> and 
                        <a href="#" class="text-decoration-none">Terms of Service</a>.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.min-vh-100 {
    min-height: 100vh;
}

.card {
    border: none;
    border-radius: 1rem;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 0.75rem;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

.input-group-text {
    border-radius: 0.5rem 0 0 0.5rem;
}

.form-control {
    border-radius: 0 0.5rem 0.5rem 0;
    transition: all 0.3s ease;
}

.form-control:focus {
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    border-color: #667eea;
}

.alert {
    border-radius: 0.75rem;
    border: none;
}

/* Password toggle button */
#togglePassword {
    border-radius: 0 0.5rem 0.5rem 0;
}

/* Loading animation */
@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

.btn-primary:active {
    animation: pulse 0.3s ease;
}

/* Responsive adjustments */
@media (max-width: 991.98px) {
    .bg-primary {
        min-height: 300px;
    }
    
    .display-5 {
        font-size: 2rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Password visibility toggle
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');
    
    if (togglePassword) {
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            toggleIcon.classList.toggle('fa-eye');
            toggleIcon.classList.toggle('fa-eye-slash');
        });
    }

    // Form submission handling
    const loginForm = document.getElementById('loginForm');
    const loginButton = document.getElementById('loginButton');
    const buttonText = document.getElementById('buttonText');
    const spinner = document.getElementById('spinner');

    if (loginForm) {
        loginForm.addEventListener('submit', function() {
            // Show loading state
            loginButton.disabled = true;
            buttonText.textContent = 'Signing In...';
            spinner.classList.remove('d-none');
            
            // Add slight delay for better UX
            setTimeout(() => {
                loginButton.disabled = false;
                buttonText.textContent = 'Sign In';
                spinner.classList.add('d-none');
            }, 3000);
        });
    }

    // Auto-focus on email field with slight delay for better UX
    setTimeout(() => {
        const emailField = document.getElementById('email');
        if (emailField) {
            emailField.focus();
        }
    }, 500);

    // Input validation styling
    const inputs = document.querySelectorAll('input[required]');
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.value.trim() !== '') {
                this.classList.add('is-valid');
            } else {
                this.classList.remove('is-valid');
            }
        });
    });
});
</script>
@endpush