@extends('layouts.admin')

@section('title', 'View User Details')

@section('content')
<div class="container-fluid">
    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">User Details</h1>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i>
            Back to All Users
        </a>
    </div>

    <div class="row">
        {{-- User Profile Card --}}
        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-body text-center">
                    {{-- Placeholder for profile picture --}}
                    <i class="bi bi-person-circle text-primary" style="font-size: 80px;"></i>
                    <h5 class="my-3">{{ $user->name }}</h5>
                    <p class="text-muted mb-1">{{ ucfirst($user->getRoleNames()->first() ?? 'N/A') }}</p>
                    <p class="text-muted mb-4">{{ $user->email }}</p>
                </div>
            </div>
        </div>

        {{-- User Information Card --}}
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-3"><p class="mb-0 fw-bold">Full Name</p></div>
                        <div class="col-sm-9"><p class="text-muted mb-0">{{ $user->name }}</p></div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3"><p class="mb-0 fw-bold">Email</p></div>
                        <div class="col-sm-9"><p class="text-muted mb-0">{{ $user->email }}</p></div>
                    </div>
                       <hr>
                    <div class="row">
                        <div class="col-sm-3"><p class="mb-0 fw-bold">Role</p></div>
                        <div class="col-sm-9"><p class="text-muted mb-0">{{ $user->getRoleNames()->implode(', ') ?? 'Not provided' }}</p></div>
                    </div>

                    <hr>
                    <hr>

                    <div class="row">
                        <div class="col-sm-3"><p class="mb-0 fw-bold">Phone</p></div>
                        <div class="col-sm-9"><p class="text-muted mb-0">{{ $user->phone ?? 'Not provided' }}</p></div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-sm-3"><p class="mb-0 fw-bold">Address</p></div>
                        <div class="col-sm-9"><p class="text-muted mb-0">{{ $user->address ?? 'Not provided' }}</p></div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3"><p class="mb-0 fw-bold">Account Status</p></div>
                        <div class="col-sm-9">
                            @if ($user->status == 'approved')<span class="badge bg-success">Approved</span>@endif
                            @if ($user->status == 'pending')<span class="badge bg-warning text-dark">Pending</span>@endif
                            @if ($user->status == 'banned')<span class="badge bg-dark">Banned</span>@endif
                            @if ($user->status == 'rejected')<span class="badge bg-danger">Rejected</span>@endif
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3"><p class="mb-0 fw-bold">Registered On</p></div>
                        <div class="col-sm-9"><p class="text-muted mb-0">{{ $user->created_at->format('F j, Y, g:i a') }}</p></div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3"><p class="mb-0 fw-bold">Last Updated</p></div>
                        <div class="col-sm-9"><p class="text-muted mb-0">{{ $user->updated_at->diffForHumans() }}</p></div>
                    </div>
                </div>
            </div>

            {{-- Here you could add more cards for related information --}}
            {{-- For example, a table of the user's recent orders or products if they are a seller --}}

        </div>
    </div>
</div>
@endsection