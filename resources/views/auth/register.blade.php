@extends('layouts.app')

@section('title', 'Create Account - Bus Booking System')

@section('content')
<div class="container-fluid">
    <div class="row min-vh-100">
        <!-- Left Side - Brand/Info Section -->
        <div class="col-lg-6 d-none d-lg-flex bg-gradient-primary text-white">
            <div class="d-flex align-items-center justify-content-center w-100">
                <div class="text-center px-5">
                    <div class="mb-5">
                        <i class="fas fa-bus fa-5x text-white-50 mb-4"></i>
                        <h1 class="display-5 fw-bold">Join Our Community</h1>
                        <p class="lead">Create your account and start booking bus tickets with ease</p>
                    </div>
                    
                    <div class="row text-start mt-5">
                        <div class="col-12 mb-4">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-rocket fa-2x me-3 text-white-50"></i>
                                <div>
                                    <h5>Quick Bookings</h5>
                                    <small class="text-white-50">Book your bus tickets in just a few clicks</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mb-4">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-map-marked-alt fa-2x me-3 text-white-50"></i>
                                <div>
                                    <h5>Multiple Routes</h5>
                                    <small class="text-white-50">Access to hundreds of routes nationwide</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-star fa-2x me-3 text-white-50"></i>
                                <div>
                                    <h5>Exclusive Deals</h5>
                                    <small class="text-white-50">Get special discounts and offers</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Registration Form -->
        <div class="col-lg-6 d-flex align-items-center justify-content-center bg-light">
            <div class="w-100" style="max-width: 450px;">
                <!-- Header -->
                <div class="text-center mb-5">
                    <div class="d-lg-none mb-4">
                        <i class="fas fa-bus fa-3x text-primary mb-3"></i>
                        <h2 class="text-primary fw-bold">Bus Booking System</h2>
                    </div>
                    <h3 class="fw-bold text-dark mb-2">Create Account</h3>
                    <p class="text-muted">Join thousands of satisfied travelers</p>
                </div>

                <!-- Alert Messages -->
                @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <strong>Please fix the following errors:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <!-- Registration Form -->
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('register') }}" id="registerForm">
                            @csrf

                            <!-- Name Field -->
                            <div class="mb-3">
                                <label for="name" class="form-label fw-semibold">
                                    <i class="fas fa-user me-2 text-primary"></i>Full Name
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-user text-muted"></i>
                                    </span>
                                    <input type="text" 
                                           class="form-control border-start-0 @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name') }}" 
                                           required 
                                           autofocus
                                           placeholder="Enter your full name">
                                </div>
                                @error('name')
                                    <div class="invalid-feedback d-block">
                                        <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Email Field -->
                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold">
                                    <i class="fas fa-envelope me-2 text-primary"></i>Email Address
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-at text-muted"></i>
                                    </span>
                                    <input type="email" 
                                           class="form-control border-start-0 @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email') }}" 
                                           required 
                                           placeholder="Enter your email address">
                                </div>
                                @error('email')
                                    <div class="invalid-feedback d-block">
                                        <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Password Field -->
                            <div class="mb-3">
                                <label for="password" class="form-label fw-semibold">
                                    <i class="fas fa-lock me-2 text-primary"></i>Password
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-key text-muted"></i>
                                    </span>
                                    <input type="password" 
                                           class="form-control border-start-0 @error('password') is-invalid @enderror" 
                                           id="password" 
                                           name="password" 
                                           required 
                                           placeholder="Create a strong password">
                                    <button class="btn btn-outline-secondary border-start-0" type="button" id="togglePassword">
                                        <i class="fas fa-eye" id="toggleIcon"></i>
                                    </button>
                                </div>
                                <div class="password-strength mt-2">
                                    <div class="progress" style="height: 4px;">
                                        <div class="progress-bar" id="passwordStrength" role="progressbar" style="width: 0%"></div>
                                    </div>
                                    <small class="text-muted" id="passwordHint">Use 8+ characters with mix of letters, numbers & symbols</small>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block">
                                        <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Confirm Password Field -->
                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label fw-semibold">
                                    <i class="fas fa-lock me-2 text-primary"></i>Confirm Password
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-key text-muted"></i>
                                    </span>
                                    <input type="password" 
                                           class="form-control border-start-0" 
                                           id="password_confirmation" 
                                           name="password_confirmation" 
                                           required 
                                           placeholder="Confirm your password">
                                    <button class="btn btn-outline-secondary border-start-0" type="button" id="toggleConfirmPassword">
                                        <i class="fas fa-eye" id="toggleConfirmIcon"></i>
                                    </button>
                                </div>
                                <div class="mt-2">
                                    <small class="text-muted" id="passwordMatch"></small>
                                </div>
                            </div>

                            <!-- Terms and Conditions -->
                            <div class="mb-4">
                                <div class="form-check">
                                    <input class="form-check-input @error('terms') is-invalid @enderror" 
                                           type="checkbox" 
                                           name="terms" 
                                           id="terms" 
                                           {{ old('terms') ? 'checked' : '' }}>
                                    <label class="form-check-label text-muted" for="terms">
                                        I agree to the <a href="#" class="text-decoration-none">Terms of Service</a> 
                                        and <a href="#" class="text-decoration-none">Privacy Policy</a>
                                    </label>
                                </div>
                                @error('terms')
                                    <div class="invalid-feedback d-block">
                                        <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-success btn-lg w-100 py-2 mb-3" id="registerButton">
                                <i class="fas fa-user-plus me-2"></i>
                                <span id="buttonText">Create Account</span>
                                <div class="spinner-border spinner-border-sm ms-2 d-none" id="spinner" role="status"></div>
                            </button>

                            <!-- Social Registration (Optional) -->
                            <div class="text-center mb-4">
                                <div class="position-relative">
                                    <hr>
                                    <span class="position-absolute top-50 start-50 translate-middle bg-light px-3 text-muted small">
                                        Or continue with
                                    </span>
                                </div>
                                <div class="d-grid gap-2 mt-4">
                                    <button type="button" class="btn btn-outline-primary">
                                        <i class="fab fa-google me-2"></i>Google
                                    </button>
                                    <button type="button" class="btn btn-outline-dark">
                                        <i class="fab fa-apple me-2"></i>Apple
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Login Link -->
                <div class="text-center mt-4">
                    <p class="text-muted mb-0">
                        Already have an account? 
                        <a href="{{ route('login') }}" class="text-decoration-none fw-semibold text-primary">
                            Sign in here
                        </a>
                    </p>
                </div>

                <!-- Security Notice -->
                <div class="text-center mt-4">
                    <small class="text-muted">
                        <i class="fas fa-shield-alt me-1"></i>
                        Your information is secure and encrypted
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

.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.card {
    border: none;
    border-radius: 1rem;
}

.btn-success {
    background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
    border: none;
    border-radius: 0.75rem;
    transition: all 0.3s ease;
}

.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(86, 171, 47, 0.4);
}

.input-group-text {
    border-radius: 0.5rem 0 0 0.5rem;
}

.form-control {
    border-radius: 0 0.5rem 0.5rem 0;
    transition: all 0.3s ease;
}

.form-control:focus {
    box-shadow: 0 0 0 0.2rem rgba(86, 171, 47, 0.25);
    border-color: #56ab2f;
}

.alert {
    border-radius: 0.75rem;
    border: none;
}

.password-strength .progress {
    border-radius: 2px;
}

/* Password strength colors */
.progress-bar[data-strength="weak"] {
    background-color: #dc3545;
    width: 25% !important;
}

.progress-bar[data-strength="fair"] {
    background-color: #fd7e14;
    width: 50% !important;
}

.progress-bar[data-strength="good"] {
    background-color: #ffc107;
    width: 75% !important;
}

.progress-bar[data-strength="strong"] {
    background-color: #198754;
    width: 100% !important;
}

/* Responsive adjustments */
@media (max-width: 991.98px) {
    .bg-gradient-primary {
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
    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('password_confirmation');
    const toggleIcon = document.getElementById('toggleIcon');
    const toggleConfirmIcon = document.getElementById('toggleConfirmIcon');
    
    function togglePasswordVisibility(input, icon) {
        const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
        input.setAttribute('type', type);
        icon.classList.toggle('fa-eye');
        icon.classList.toggle('fa-eye-slash');
    }
    
    if (togglePassword) {
        togglePassword.addEventListener('click', function() {
            togglePasswordVisibility(passwordInput, toggleIcon);
        });
    }
    
    if (toggleConfirmPassword) {
        toggleConfirmPassword.addEventListener('click', function() {
            togglePasswordVisibility(confirmPasswordInput, toggleConfirmIcon);
        });
    }

    // Password strength indicator
    const passwordField = document.getElementById('password');
    const passwordStrength = document.getElementById('passwordStrength');
    const passwordHint = document.getElementById('passwordHint');
    
    function checkPasswordStrength(password) {
        let strength = 0;
        
        // Length check
        if (password.length >= 8) strength++;
        if (password.length >= 12) strength++;
        
        // Character variety checks
        if (/[a-z]/.test(password)) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[^a-zA-Z0-9]/.test(password)) strength++;
        
        return Math.min(strength, 4); // Max strength of 4
    }
    
    function updatePasswordStrength() {
        const password = passwordField.value;
        const strength = checkPasswordStrength(password);
        
        const strengthLevels = ['weak', 'fair', 'good', 'strong'];
        const strengthText = ['Very Weak', 'Weak', 'Fair', 'Good', 'Strong'];
        const strengthColors = ['#dc3545', '#fd7e14', '#ffc107', '#198754'];
        const strengthHints = [
            'Use 8+ characters with mix of letters, numbers & symbols',
            'Add uppercase letters and numbers',
            'Good! Add symbols for better security',
            'Strong password!',
            'Very strong password!'
        ];
        
        passwordStrength.setAttribute('data-strength', strengthLevels[strength - 1] || 'weak');
        passwordStrength.style.backgroundColor = strengthColors[strength - 1] || '#dc3545';
        passwordStrength.style.width = `${(strength / 4) * 100}%`;
        passwordHint.textContent = strengthHints[strength];
        passwordHint.style.color = strengthColors[strength - 1] || '#6c757d';
    }
    
    if (passwordField) {
        passwordField.addEventListener('input', updatePasswordStrength);
    }

    // Password confirmation check
    const confirmPasswordField = document.getElementById('password_confirmation');
    const passwordMatch = document.getElementById('passwordMatch');
    
    function checkPasswordMatch() {
        const password = passwordField.value;
        const confirmPassword = confirmPasswordField.value;
        
        if (confirmPassword === '') {
            passwordMatch.textContent = '';
            return;
        }
        
        if (password === confirmPassword) {
            passwordMatch.innerHTML = '<i class="fas fa-check text-success me-1"></i>Passwords match';
            passwordMatch.style.color = '#198754';
        } else {
            passwordMatch.innerHTML = '<i class="fas fa-times text-danger me-1"></i>Passwords do not match';
            passwordMatch.style.color = '#dc3545';
        }
    }
    
    if (confirmPasswordField) {
        confirmPasswordField.addEventListener('input', checkPasswordMatch);
        passwordField.addEventListener('input', checkPasswordMatch);
    }

    // Form submission handling
    const registerForm = document.getElementById('registerForm');
    const registerButton = document.getElementById('registerButton');
    const buttonText = document.getElementById('buttonText');
    const spinner = document.getElementById('spinner');

    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            // Basic validation
            const password = passwordField.value;
            const confirmPassword = confirmPasswordField.value;
            const terms = document.getElementById('terms').checked;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                passwordMatch.innerHTML = '<i class="fas fa-exclamation-triangle text-danger me-1"></i>Please make sure passwords match';
                passwordMatch.style.color = '#dc3545';
                confirmPasswordField.focus();
                return;
            }
            
            if (!terms) {
                e.preventDefault();
                alert('Please agree to the Terms of Service and Privacy Policy');
                return;
            }
            
            // Show loading state
            registerButton.disabled = true;
            buttonText.textContent = 'Creating Account...';
            spinner.classList.remove('d-none');
        });
    }

    // Auto-focus on name field with slight delay for better UX
    setTimeout(() => {
        const nameField = document.getElementById('name');
        if (nameField) {
            nameField.focus();
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