@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <form method="POST" action="/login" class="mt-5 space-y-4 col-md-8 mx-auto">
        @csrf

        <div class="bg-white p-5 rounded shadow">
            <div class="row g-3">

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
        </div>
    </form>
@endsection