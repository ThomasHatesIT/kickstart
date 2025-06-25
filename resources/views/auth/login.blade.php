@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <form method="POST" action="/login" class="mt-5 space-y-4 col-md-8 mx-auto">
        @csrf

        <div class="bg-white p-5 rounded shadow">
            <div class="row g-3">
                <h1>LOGIN</h1>

                <!-- Email -->
                <x-form-field>
                    <x-form-label for="email">Email</x-form-label>
                    <x-form-input type="email" name="email" id="email" value="{{ old('email') }}" required />
                    <x-form-error name="email" />
                </x-form-field>

                <!-- Password -->
                <x-form-field>
                    <x-form-label for="password">Password</x-form-label>
                    <x-form-input type="password" name="password" id="password" required/>
                    <x-form-error name="password" />
                </x-form-field>

            </div>

            <!-- Buttons -->
            <div class="d-flex justify-content-end gap-3 mt-4">
                <a 
                    href="/" 
                    class="btn btn-outline-secondary text-decoration-none"
                >
                    Cancel
                </a>
                <x-form-button>Log in</x-form-button>
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
        </div>
    </form>
@endsection