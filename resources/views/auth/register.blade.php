@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center py-5" style="background-color: #ffffff;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="card border-0 shadow-lg" style="border-radius: 20px; background-color: #ffffff; border: 1px solid #e9ecef !important;">
                    <div class="card-body p-5">
                        <!-- Header -->
                        <div class="text-center mb-5">
                            <h2 class="fw-bold text-dark mb-2">Create Your Account</h2>
                            <p class="text-muted">Join our community today</p>
                        </div>

                        {{-- IMPORTANT: Add enctype="multipart/form-data" to handle file uploads --}}
                        <form method="POST" action="/register" enctype="multipart/form-data" x-data="{ role: '{{ old('role', '') }}' }">
                            @csrf

                            <div class="row g-4">
                                {{-- Full Name --}}
                                <div class="col-md-6">
                                    <label for="name" class="form-label fw-semibold text-dark mb-2">Full Name</label>
                                    <input 
                                        type="text" 
                                        class="form-control form-control-lg rounded-pill border-0 shadow-sm" 
                                        style="background-color: #f8f9fa; padding: 15px 20px;"
                                        name="name" 
                                        id="name" 
                                        value="{{ old('name') }}" 
                                        placeholder="Enter your full name"
                                        required
                                    >
                                    @error('name') <div class="text-danger small mt-2 ms-3">{{ $message }}</div> @enderror
                                </div>
                                
                                {{-- Email --}}
                                <div class="col-md-6">
                                    <label for="email" class="form-label fw-semibold text-dark mb-2">Email Address</label>
                                    <input 
                                        type="email" 
                                        class="form-control form-control-lg rounded-pill border-0 shadow-sm" 
                                        style="background-color: #f8f9fa; padding: 15px 20px;"
                                        name="email" 
                                        id="email" 
                                        value="{{ old('email') }}" 
                                        placeholder="Enter your email"
                                        required
                                    >
                                    @error('email') <div class="text-danger small mt-2 ms-3">{{ $message }}</div> @enderror
                                </div>

                                {{-- Password --}}
                                <div class="col-md-6">
                                    <label for="password" class="form-label fw-semibold text-dark mb-2">Password</label>
                                    <div class="position-relative">
                                        <input 
                                            type="password" 
                                            class="form-control form-control-lg rounded-pill border-0 shadow-sm" 
                                            style="background-color: #f8f9fa; padding: 15px 20px; padding-right: 50px;"
                                            name="password" 
                                            id="password" 
                                            placeholder="Create a password"
                                            required
                                        >
                                        <button type="button" class="btn position-absolute end-0 top-50 translate-middle-y me-3" style="border: none; background: none;" onclick="togglePassword('password', 'toggleIcon1')">
                                            <i class="fas fa-eye text-muted" id="toggleIcon1"></i>
                                        </button>
                                    </div>
                                    @error('password') <div class="text-danger small mt-2 ms-3">{{ $message }}</div> @enderror
                                </div>
                                
                                {{-- Confirm Password --}}
                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label fw-semibold text-dark mb-2">Confirm Password</label>
                                    <div class="position-relative">
                                        <input 
                                            type="password" 
                                            class="form-control form-control-lg rounded-pill border-0 shadow-sm" 
                                            style="background-color: #f8f9fa; padding: 15px 20px; padding-right: 50px;"
                                            name="password_confirmation" 
                                            id="password_confirmation" 
                                            placeholder="Confirm your password"
                                            required
                                        >
                                        <button type="button" class="btn position-absolute end-0 top-50 translate-middle-y me-3" style="border: none; background: none;" onclick="togglePassword('password_confirmation', 'toggleIcon2')">
                                            <i class="fas fa-eye text-muted" id="toggleIcon2"></i>
                                        </button>
                                    </div>
                                </div>

                                {{-- Role --}}
                                <div class="col-12">
                                    <label for="role" class="form-label fw-semibold text-dark mb-2">I want to register as a...</label>
                                    <select 
                                        class="form-select form-select-lg rounded-pill border-0 shadow-sm" 
                                        style="background-color: #f8f9fa; padding: 15px 20px;"
                                        name="role" 
                                        id="role" 
                                        x-model="role" 
                                        required
                                    >
                                        <option value="" disabled selected>-- Select a Role --</option>
                                        @foreach ($roles->reject(fn($role) => $role->name === 'admin') as $role)
                                            <option value="{{ $role->name }}" @selected(old('role') == $role->name)>{{ ucfirst($role->name) }}</option>
                                        @endforeach
                                    </select>
                                    @error('role') <div class="text-danger small mt-2 ms-3">{{ $message }}</div> @enderror
                                </div>

                                {{-- === DYNAMIC SELLER FIELDS START === --}}
                                <div x-show="role === 'seller'" x-transition class="col-12">
                                    <div class="card mt-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 15px; border: none;">
                                        <div class="card-body p-4">
                                            <h4 class="mb-3 text-center text-white">
                                                <i class="fas fa-shield-alt me-2"></i>Seller Verification
                                            </h4>
                                            <p class="text-center text-white small opacity-90 mb-4">
                                                To ensure a secure marketplace, please provide the following for verification.
                                            </p>
                                            
                                            <div class="row g-4">
                                                {{-- Profile Photo (Optional but good to have) --}}
                                                <div class="col-md-6">
                                                    <label for="profile_photo" class="form-label fw-semibold text-white mb-2">
                                                        Profile Photo <span class="small opacity-75">(Optional)</span>
                                                    </label>
                                                    <input 
                                                        type="file" 
                                                        class="form-control form-control-lg rounded-pill border-0" 
                                                        style="background-color: rgba(255,255,255,0.9); padding: 12px 20px;"
                                                        name="profile_photo" 
                                                        id="profile_photo" 
                                                        accept="image/*"
                                                    >
                                                    @error('profile_photo') <div class="text-warning small mt-2 ms-3">{{ $message }}</div> @enderror
                                                </div>

                                                {{-- Face Photo (Required for Seller) --}}
                                                <div class="col-md-6">
                                                    <label for="face_photo" class="form-label fw-semibold text-white mb-2">
                                                        Clear Photo of Your Face <span class="text-warning">*</span>
                                                    </label>
                                                    <input 
                                                        type="file" 
                                                        class="form-control form-control-lg rounded-pill border-0" 
                                                        style="background-color: rgba(255,255,255,0.9); padding: 12px 20px;"
                                                        name="face_photo" 
                                                        id="face_photo" 
                                                        accept="image/*"
                                                    >
                                                    @error('face_photo') <div class="text-warning small mt-2 ms-3">{{ $message }}</div> @enderror
                                                </div>

                                                {{-- ID Photo (Required for Seller) --}}
                                                <div class="col-12">
                                                    <label for="id_photo_front" class="form-label fw-semibold text-white mb-2">
                                                        Photo of Government Issued ID (Front) <span class="text-warning">*</span>
                                                    </label>
                                                    <input 
                                                        type="file" 
                                                        class="form-control form-control-lg rounded-pill border-0" 
                                                        style="background-color: rgba(255,255,255,0.9); padding: 12px 20px;"
                                                        name="id_photo_front" 
                                                        id="id_photo_front" 
                                                        accept="image/*"
                                                    >
                                                    @error('id_photo_front') <div class="text-warning small mt-2 ms-3">{{ $message }}</div> @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- === DYNAMIC SELLER FIELDS END === --}}
                            </div>

                            {{-- Register Button --}}
                            <div class="d-grid gap-2 mt-5">
                                <button 
                                    type="submit" 
                                    class="btn btn-primary btn-lg rounded-pill py-3 fw-semibold" 
                                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; transition: all 0.3s ease;"
                                >
                                    Create Account
                                </button>
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

                            <!-- Sign In Link -->
                            <div class="text-center mt-4">
                                <p class="text-muted small mb-0">
                                    Already have an account? 
                                    <a href="/login" class="text-primary text-decoration-none fw-semibold">Sign in here</a>
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
    .form-control:focus,
    .form-select:focus {
        border-color: transparent;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        background-color: #fff !important;
    }

    /* Animation for form fields */
    .form-control,
    .form-select {
        transition: all 0.3s ease;
    }

    /* File input styling */
    .form-control[type="file"] {
        padding: 12px 20px;
    }

    .form-control[type="file"]::-webkit-file-upload-button {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 20px;
        margin-right: 15px;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .form-control[type="file"]::-webkit-file-upload-button:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
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

    /* Seller verification card special styling */
    .card-body .card {
        animation: fadeIn 0.5s ease-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: scale(0.95);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .card-body {
            padding: 2rem !important;
        }
        
        .btn-lg {
            padding: 12px 20px !important;
        }

        .form-control-lg,
        .form-select-lg {
            padding: 12px 16px !important;
        }
    }

    @media (max-width: 576px) {
        .card-body {
            padding: 1.5rem !important;
        }
    }
</style>

<!-- Add Font Awesome for the eye icon (if not already included) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<!-- JavaScript for password toggle functionality -->
<script>
function togglePassword(inputId, iconId) {
    // Get the password input field
    const passwordInput = document.getElementById(inputId);
    // Get the toggle icon
    const toggleIcon = document.getElementById(iconId);
    
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

@push('scripts')
{{-- Include Alpine.js for easy conditional logic. It's tiny and powerful. --}}
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush