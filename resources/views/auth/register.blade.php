@extends('layouts.app')

@section('title', 'Register')

@section('content')
    {{-- IMPORTANT: Add enctype="multipart/form-data" to handle file uploads --}}
    <form method="POST" action="/register" class="mt-4" enctype="multipart/form-data" x-data="{ role: '{{ old('role', '') }}' }">
        @csrf

        <div class="card shadow-sm mx-auto" style="max-width: 900px;">
            <div class="card-body p-4 p-md-5">
                <h2 class="mb-4 text-center">Create Your Account</h2>

                <div class="row g-4">
                    {{-- Full Name --}}
                    <div class="col-md-6">
                        <label for="name" class="form-label fw-bold">Full Name</label>
                        <input type="text" class="form-control" name="name" id="name" value="{{ old('name') }}" required>
                        @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>
                    
                    {{-- Email --}}
                    <div class="col-md-6">
                        <label for="email" class="form-label fw-bold">Email Address</label>
                        <input type="email" class="form-control" name="email" id="email" value="{{ old('email') }}" required>
                        @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    {{-- Password --}}
                    <div class="col-md-6">
                        <label for="password" class="form-label fw-bold">Password</label>
                        <input type="password" class="form-control" name="password" id="password" required>
                        @error('password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>
                    
                    {{-- Confirm Password --}}
                    <div class="col-md-6">
                        <label for="password_confirmation" class="form-label fw-bold">Confirm Password</label>
                        <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" required>
                    </div>

                    {{-- Role --}}
                    <div class="col-12">
                        <label for="role" class="form-label fw-bold">I want to register as a...</label>
                        <select class="form-select" name="role" id="role" x-model="role" required>
                            <option value="" disabled selected>-- Select a Role --</option>
                            @foreach ($roles->reject(fn($role) => $role->name === 'admin') as $role)
                                <option value="{{ $role->name }}" @selected(old('role') == $role->name)>{{ ucfirst($role->name) }}</option>
                            @endforeach
                        </select>
                        @error('role') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    {{-- === DYNAMIC SELLER FIELDS START === --}}
                    <div x-show="role === 'seller'" x-transition class="col-12">
                        <hr>
                        <h4 class="mb-3 text-center text-primary">Seller Verification</h4>
                        <p class="text-center text-muted small">To ensure a secure marketplace, please provide the following for verification.</p>
                        
                        <div class="row g-4 mt-2">
                            {{-- Profile Photo (Optional but good to have) --}}
                             <div class="col-md-6">
                                <label for="profile_photo" class="form-label fw-bold">Profile Photo (Optional)</label>
                                <input type="file" class="form-control" name="profile_photo" id="profile_photo" accept="image/*">
                                @error('profile_photo') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>

                            {{-- Face Photo (Required for Seller) --}}
                             <div class="col-md-6">
                                <label for="face_photo" class="form-label fw-bold">Clear Photo of Your Face</label>
                                <input type="file" class="form-control" name="face_photo" id="face_photo" accept="image/*">
                                @error('face_photo') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>

                             {{-- ID Photo (Required for Seller) --}}
                            <div class="col-12">
                                <label for="id_photo_front" class="form-label fw-bold">Photo of Government Issued ID (Front)</label>
                                <input type="file" class="form-control" name="id_photo_front" id="id_photo_front" accept="image/*">
                                @error('id_photo_front') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                     {{-- === DYNAMIC SELLER FIELDS END === --}}
                </div>

                {{-- === START: MODIFIED/NEW SECTION === --}}
                <div class="d-grid mt-4">
                    {{-- I changed the button text for better clarity --}}
                    <button type="submit" class="btn btn-primary btn-lg fw-bold">Register with Email</button>
                </div>

                <!-- Divider -->
                <div class="text-center my-4">
                    <hr class="d-inline-block" style="width: 45%;">
                    <span class="px-2 text-muted">OR</span>
                    <hr class="d-inline-block" style="width: 45%;">
                </div>

                <!-- Google Sign-In Button -->
                <div class="d-flex justify-content-center">
                    <a 
                        href="{{ route('redirect.ToGoogle') }}" 
                        class="btn btn-outline-danger d-flex align-items-center gap-2 px-4 py-2"
                        style="border-radius: 25px;"
                    >
                        <svg width="18" height="18" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        Continue with Google
                    </a>
                </div>
                {{-- === END: MODIFIED/NEW SECTION === --}}

            </div>
        </div>
    </form>
@endsection

@push('scripts')
{{-- Include Alpine.js for easy conditional logic. It's tiny and powerful. --}}
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush