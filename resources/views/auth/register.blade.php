@extends('layouts.app')

@section('title', 'Register')

@section('content')
    <form method="POST" action="/register" class="mt-4">
        @csrf

        <div class="card shadow-sm mx-auto" style="max-width: 900px;">
            <div class="card-body">
                <h2 class="mb-4">Register</h2>

                <div class="row g-3">
                    <!-- First name -->
                    <div class="col-md-6">
                        <x-form-field>
                            <x-form-label for="name">Full Name</x-form-label>
                            <x-form-input type="text" name="name" id="name" value="{{ old('name') }}" required/>
                            <x-form-error name="name" />
                        </x-form-field>
                    </div>

                  
                    <!-- Email -->
                    <div class="col-md-6">
                        <x-form-field>
                            <x-form-label for="email">Email</x-form-label>
                            <x-form-input type="email" name="email" id="email" value="{{ old('email') }}" required/>
                            <x-form-error name="email" />
                        </x-form-field>
                    </div>

                    <!-- Role -->
                    <!-- Role -->
                        <div class="mb-3">
                            <label for="role" class="form-label fw-semibold">Role</label>
                            <select class="form-select form-select-lg" name="role" id="role" required>
                                <option disabled selected>==Select Role==</option>
                            @foreach ($roles->reject(fn($role) => $role->name === 'admin') as $role)
                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                        @endforeach
                            </select>
                        </div>


                    <!-- Password -->
                    <div class="col-md-6">
                        <x-form-field>
                            <x-form-label for="password">Password</x-form-label>
                            <x-form-input type="password" name="password" id="password" required/>
                            <x-form-error name="password" />
                        </x-form-field>
                    </div>

                    <!-- Confirm Password -->
                    <div class="col-md-6">
                        <x-form-field>
                            <x-form-label for="password_confirmation">Confirm Password</x-form-label>
                            <x-form-input type="password" name="password_confirmation" id="password_confirmation" required/>
                            <x-form-error name="password_confirmation" />
                        </x-form-field>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="d-flex justify-content-end mt-4 gap-2">
                    <a href="/" class="btn btn-outline-secondary">Cancel</a>
                    <x-form-button type="submit">Register</x-form-button>
                </div>
            </div>
        </div>
    </form>
@endsection