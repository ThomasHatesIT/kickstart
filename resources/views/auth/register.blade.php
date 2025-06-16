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

                <div class="d-grid mt-4">
                    <button type="submit" class="btn btn-primary btn-lg fw-bold">Register</button>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
{{-- Include Alpine.js for easy conditional logic. It's tiny and powerful. --}}
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush