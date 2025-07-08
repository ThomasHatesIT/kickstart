@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center py-5" style="background-color: #ffffff;">">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7 col-sm-9">
                <div class="card border-0 shadow-lg" style="border-radius: 20px; background-color: #ffffff; border: 1px solid #e9ecef !important;">
                    <div class="card-body p-5">
                        <!-- Header -->
                        <div class="text-center mb-5">
                            <h2 class="fw-bold text-dark mb-2">Welcome Back</h2>
                            <p class="text-muted">Sign in to your account</p>
                        </div>

                        <form method="POST" action="/login" class="space-y-4">
                            @csrf

                            <!-- Email Field -->
                            <x-form-field>
                                <x-form-label for="email" class="form-label fw-semibold text-dark mb-2">Email Address</x-form-label>
                                <x-form-input 
                                    type="email" 
                                    name="email" 
                                    id="email" 
                                    value="{{ old('email') }}" 
                                    required 
                                    class="form-control form-control-lg rounded-pill border-0 shadow-sm"
                                    style="background-color: #f8f9fa; padding: 15px 20px;"
                                    placeholder="Enter your email"
                                />
                                <x-form-error name="email" />
                            </x-form-field>

                            <!-- Password Field -->
                            <x-form-field>
                                <x-form-label for="password" class="form-label fw-semibold text-dark mb-2 mt-4">Password</x-form-label>
                                <div class="position-relative">
                                    <x-form-input 
                                        type="password" 
                                        name="password" 
                                        id="password" 
                                        required
                                        class="form-control form-control-lg rounded-pill border-0 shadow-sm"
                                        style="background-color: #f8f9fa; padding: 15px 20px; padding-right: 50px;"
                                        placeholder="Enter your password"
                                    />
                                    <button type="button" class="btn position-absolute end-0 top-50 translate-middle-y me-3" style="border: none; background: none;" onclick="togglePassword()">
                                        <i class="fas fa-eye text-muted" id="toggleIcon"></i>
                                    </button>
                                </div>
                                <x-form-error name="password" />
                            </x-form-field>

                            <!-- Remember Me & Forgot Password -->
                            <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                    <label class="form-check-label text-sm text-muted" for="remember">
                                        Remember me
                                    </label>
                                </div>
                                <a href="#" class="text-decoration-none text-primary small">Forgot password?</a>
                            </div>

                            <!-- Login Button -->
                            <div class="d-grid gap-2 mt-4">
                                <x-form-button class="btn btn-primary btn-lg rounded-pill py-3 fw-semibold" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; transition: all 0.3s ease;">
                                    Sign In
                                </x-form-button>
                            </div>

                            <!-- Cancel Button -->
                            <div class="d-grid gap-2 mt-3">
                                <a 
                                    href="/" 
                                    class="btn btn-outline-secondary btn-lg rounded-pill py-3 text-decoration-none"
                                    style="transition: all 0.3s ease;"
                                >
                                    Cancel
                                </a>
                            </div>

                            <!-- Divider -->
                            <div class="position-relative text-center my-5">
                                <hr class="text-muted">
                                <span class="position-absolute top-50 start-50 translate-middle bg-white px-3 text-muted small">
                                    OR CONTINUE WITH
                                </span>
                            </div>

                            <!-- Google Sign-In Button -->
                            <div class="d-grid gap-2">
                                <a 
                                    href="{{ route('redirect.ToGoogle') }}" 
                                    class="btn btn-outline-dark btn-lg rounded-pill py-3 d-flex align-items-center justify-content-center gap-3 text-decoration-none"
                                    style="transition: all 0.3s ease; border: 2px solid #dee2e6;"
                                >
                                    <svg width="20" height="20" viewBox="0 0 24 24">
                                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                                    </svg>
                                    <span class="fw-semibold">Continue with Google</span>
                                </a>
                            </div>

                            <!-- Sign Up Link -->
                            <div class="text-center mt-4">
                                <p class="text-muted small mb-0">
                                    Don't have an account? 
                                    <a href="/register" class="text-primary text-decoration-none fw-semibold">Sign up here</a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add this CSS for additional styling -->
<style>
    /* Hover effects for buttons */
    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
    }

    /* Input focus effects */
    .form-control:focus {
        border-color: transparent;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        background-color: #fff !important;
    }

    /* Animation for form fields */
    .form-control {
        transition: all 0.3s ease;
    }

    /* Google button hover effect */
    .btn-outline-dark:hover {
        background-color: #f8f9fa;
        border-color: #667eea !important;
    }

    /* Card animation on load */
    .card {
        animation: slideUp 0.6s ease-out;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Responsive adjustments */
    @media (max-width: 576px) {
        .card-body {
            padding: 2rem !important;
        }
        
        .btn-lg {
            padding: 12px 20px !important;
        }
    }
</style>

<!-- Add Font Awesome for the eye icon (if not already included) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<!-- JavaScript for password toggle functionality -->
<script>
function togglePassword() {
    // Get the password input field
    const passwordInput = document.getElementById('password');
    // Get the toggle icon
    const toggleIcon = document.getElementById('toggleIcon');
    
    // Check if password is currently hidden
    if (passwordInput.type === 'password') {
        // Show password
        passwordInput.type = 'text';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
    } else {
        // Hide password
        passwordInput.type = 'password';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
    }
}
</script>
@endsection