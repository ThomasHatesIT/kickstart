@extends('layouts.app')
@section('title', 'Choose Your Role')
@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body p-4 text-center">
                    <h2 class="card-title mb-3">One Last Step!</h2>
                    <p class="text-muted">
                        Welcome, <strong>{{ session('google_user.name', 'there') }}</strong>!
                        <br>
                        Please choose how you'd like to use our platform.
                    </p>

                    {{-- THIS IS THE IMPORTANT CHANGE --}}
                    <form method="POST" action="{{ route('google.process.role') }}" class="mt-4">
                        @csrf
                        <div class="d-grid gap-3">
                            <button type="submit" name="role" value="buyer" class="btn btn-primary btn-lg">
                                Register as a Buyer
                            </button>
                            <button type="submit" name="role" value="seller" class="btn btn-success btn-lg">
                                Register as a Seller
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection