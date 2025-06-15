@extends('layouts.admin')

@section('title', 'View User Details')

@section('content')
<div class="container-fluid">
    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 mb-2 text-gray-800 fw-bold">User Details</h1>
            <p class="mb-0 text-muted fs-6">Review and manage account information and status.</p>
        </div>
        <a href="{{ url()->previous() }}" class="btn btn-outline-primary btn-lg rounded-pill px-4">
            <i class="bi bi-arrow-left me-2"></i>
            Back 
        </a>
    </div>

    {{-- Include the partial for displaying session flash messages --}}
    @include('partials._alerts')

    <div class="row g-4">
        {{-- User Profile Card --}}
        <div class="col-lg-4">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                <div class="card-body text-center p-5">
                    <!-- Enhanced Profile Avatar -->
                    <div class="profile-avatar position-relative mb-4">
                        <div class="bg-gradient-primary rounded-circle d-flex align-items-center justify-content-center mx-auto shadow-lg" 
                             style="width: 120px; height: 120px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <i class="bi bi-person-fill text-white" style="font-size: 60px;"></i>
                        </div>
                        <!-- Status Indicator -->
                        <div class="status-indicator position-absolute bottom-0 end-0 translate-middle-x">
                            @if ($user->status == 'approved')
                                <div class="bg-success rounded-circle d-flex align-items-center justify-content-center shadow" 
                                     style="width: 30px; height: 30px;">
                                    <i class="bi bi-check-lg text-white" style="font-size: 14px;"></i>
                                </div>
                            @elseif ($user->status == 'pending')
                                <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center shadow" 
                                     style="width: 30px; height: 30px;">
                                    <i class="bi bi-clock text-white" style="font-size: 14px;"></i>
                                </div>
                            @elseif ($user->status == 'banned')
                                <div class="bg-danger rounded-circle d-flex align-items-center justify-content-center shadow" 
                                     style="width: 30px; height: 30px;">
                                    <i class="bi bi-x-lg text-white" style="font-size: 14px;"></i>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <h4 class="fw-bold mb-2 text-dark">{{ $user->name }}</h4>
                    
                    <!-- Role Badge -->
                    <div class="mb-3">
                        @php
                            $role = $user->getRoleNames()->first() ?? 'N/A';
                            $badgeClass = match(strtolower($role)) {
                                'admin' => 'bg-danger',
                                'seller' => 'bg-success',
                                'customer' => 'bg-primary',
                                default => 'bg-secondary'
                            };
                        @endphp
                        <span class="badge {{ $badgeClass }} px-3 py-2 rounded-pill fs-6 fw-semibold">
                            <i class="bi bi-shield-check me-1"></i>
                            {{ ucfirst($role) }}
                        </span>
                    </div>
                    
                    <p class="text-muted mb-0 fs-6">
                        <i class="bi bi-envelope me-2"></i>
                        {{ $user->email }}
                    </p>
                    
                    <!-- Member Since -->
                    <div class="mt-4 pt-3 border-top">
                        <small class="text-muted">
                            <i class="bi bi-calendar-check me-1"></i>
                            Member since {{ $user->created_at->format('M Y') }}
                        </small>
                    </div>
                </div>
            </div>
        </div>

        {{-- User Information Card --}}
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-4 mb-4">
                <div class="card-header bg-light border-0 py-4 px-4 rounded-top-4">
                    <h5 class="mb-0 text-dark fw-bold">
                        <i class="bi bi-person-lines-fill me-2 text-primary"></i>
                        Account Information
                    </h5>
                </div>
                <div class="card-body p-4">
                    <!-- Full Name -->
                    <div class="info-row d-flex align-items-center py-3">
                        <div class="info-label" style="min-width: 140px;">
                            <i class="bi bi-person me-2 text-primary"></i>
                            <span class="fw-semibold">Full Name</span>
                        </div>
                        <div class="info-value flex-grow-1">
                            <span class="text-dark">{{ $user->name }}</span>
                        </div>
                    </div>
                    
                    <hr class="my-2 opacity-25">
                    
                    <!-- Email Address -->
                    <div class="info-row d-flex align-items-center py-3">
                        <div class="info-label" style="min-width: 140px;">
                            <i class="bi bi-envelope me-2 text-primary"></i>
                            <span class="fw-semibold">Email</span>
                        </div>
                        <div class="info-value flex-grow-1">
                            <span class="text-dark">{{ $user->email }}</span>
                        </div>
                    </div>
                    
                    <hr class="my-2 opacity-25">
                    
                    <!-- Role -->
                    <div class="info-row d-flex align-items-center py-3">
                        <div class="info-label" style="min-width: 140px;">
                            <i class="bi bi-shield-check me-2 text-primary"></i>
                            <span class="fw-semibold">Role</span>
                        </div>
                        <div class="info-value flex-grow-1">
                            <span class="text-dark">{{ $user->getRoleNames()->map(fn($role) => ucfirst($role))->implode(', ') ?: 'Not Assigned' }}</span>
                        </div>
                    </div>
                    
                    <hr class="my-2 opacity-25">
                    
                    <!-- Phone -->
                    <div class="info-row d-flex align-items-center py-3">
                        <div class="info-label" style="min-width: 140px;">
                            <i class="bi bi-telephone me-2 text-primary"></i>
                            <span class="fw-semibold">Phone</span>
                        </div>
                        <div class="info-value flex-grow-1">
                            <span class="text-dark">{{ $user->phone ?? 'Not provided' }}</span>
                        </div>
                    </div>
                    
                    <hr class="my-2 opacity-25">
                    
                    <!-- Address -->
                    <div class="info-row d-flex align-items-center py-3">
                        <div class="info-label" style="min-width: 140px;">
                            <i class="bi bi-geo-alt me-2 text-primary"></i>
                            <span class="fw-semibold">Address</span>
                        </div>
                        <div class="info-value flex-grow-1">
                            <span class="text-dark">{{ $user->address ?? 'Not provided' }}</span>
                        </div>
                    </div>
                    
                    <hr class="my-2 opacity-25">
                    
                    <!-- Account Status -->
                    <div class="info-row d-flex align-items-center py-3">
                        <div class="info-label" style="min-width: 140px;">
                            <i class="bi bi-shield-check me-2 text-primary"></i>
                            <span class="fw-semibold">Status</span>
                        </div>
                        <div class="info-value flex-grow-1">
                            @if ($user->status == 'approved')
                                <span class="badge bg-success px-3 py-2 rounded-pill fs-6">
                                    <i class="bi bi-check-circle me-1"></i>Approved
                                </span>
                            @elseif ($user->status == 'pending')
                                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fs-6">
                                    <i class="bi bi-clock me-1"></i>Pending
                                </span>
                            @elseif ($user->status == 'banned')
                                <span class="badge bg-danger px-3 py-2 rounded-pill fs-6">
                                    <i class="bi bi-x-circle me-1"></i>Banned
                                </span>
                            @elseif ($user->status == 'rejected')
                                <span class="badge bg-dark px-3 py-2 rounded-pill fs-6">
                                    <i class="bi bi-x-circle me-1"></i>Rejected
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <hr class="my-2 opacity-25">
                    
                    <!-- Registered On -->
                    <div class="info-row d-flex align-items-center py-3">
                        <div class="info-label" style="min-width: 140px;">
                            <i class="bi bi-calendar-plus me-2 text-primary"></i>
                            <span class="fw-semibold">Registered</span>
                        </div>
                        <div class="info-value flex-grow-1">
                            <span class="text-dark">{{ $user->created_at->format('F j, Y, g:i a') }}</span>
                        </div>
                    </div>
                    
                    <hr class="my-2 opacity-25">
                    
                    <!-- Last Updated -->
                    <div class="info-row d-flex align-items-center py-3">
                        <div class="info-label" style="min-width: 140px;">
                            <i class="bi bi-clock-history me-2 text-primary"></i>
                            <span class="fw-semibold">Last Updated</span>
                        </div>
                        <div class="info-value flex-grow-1">
                            <span class="text-dark">{{ $user->updated_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===================== ENHANCED ACTION PANEL ===================== -->
            {{-- Prevent actions on the logged-in user or other admins for security --}}
            @if(auth()->id() !== $user->id && !$user->hasRole('admin'))
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                <div class="card-header bg-gradient-primary text-white border-0 py-4 px-4" 
                     style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h5 class="mb-0 text-white fw-bold">
                        <i class="bi bi-ui-checks-grid me-2"></i>Account Actions
                    </h5>
                </div>
                <div class="card-body p-4">
                    {{-- 1. Seller Approval/Rejection Logic --}}
                    @if($user->hasRole('seller') && $user->status == 'pending')
                        <div class="alert alert-info border-0 rounded-3 mb-4" style="background: rgba(13, 202, 240, 0.1);">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-info-circle text-info me-3" style="font-size: 24px;"></i>
                                <div>
                                    <h6 class="mb-1 text-info fw-bold">Seller Approval Required</h6>
                                    <p class="mb-0 text-muted">This seller account is awaiting approval. Please review their details before making a decision.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex gap-3 mt-3">
                            <form action="{{ route('admin.sellers.approve', $user->id) }}" method="POST" 
                                  onsubmit="return confirm('Are you sure you want to APPROVE this seller?');">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-success btn-lg rounded-pill px-4 fw-semibold">
                                    <i class="bi bi-check-circle-fill me-2"></i>
                                    Approve Seller
                                </button>
                            </form>
                            <form action="{{ route('admin.sellers.reject', $user->id) }}" method="POST" 
                                  onsubmit="return confirm('Are you sure you want to REJECT this seller? This action is final.');">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-danger btn-lg rounded-pill px-4 fw-semibold">
                                    <i class="bi bi-x-circle-fill me-2"></i>
                                    Reject Seller
                                </button>
                            </form>
                        </div>
                    
                    {{-- 2. Banning Logic --}}
                    @elseif($user->status == 'approved')
                        <div class="alert alert-warning border-0 rounded-3 mb-4" style="background: rgba(255, 193, 7, 0.1);">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-exclamation-triangle text-warning me-3" style="font-size: 24px;"></i>
                                <div>
                                    <h6 class="mb-1 text-warning fw-bold">Ban User Account</h6>
                                    <p class="mb-0 text-muted">This user is currently active. Banning them will prevent them from logging in.</p>
                                </div>
                            </div>
                        </div>
                        
                        <form action="{{ route('admin.users.ban', $user->id) }}" method="POST" 
                              onsubmit="return confirm('Are you sure you want to BAN this user?');">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-dark btn-lg rounded-pill px-4 fw-semibold">
                                <i class="bi bi-shield-x me-2"></i>
                                Ban User
                            </button>
                        </form>

                    {{-- 3. Unbanning Logic --}}
                    @elseif($user->status == 'banned')
                        <div class="alert alert-success border-0 rounded-3 mb-4" style="background: rgba(25, 135, 84, 0.1);">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-check-circle text-success me-3" style="font-size: 24px;"></i>
                                <div>
                                    <h6 class="mb-1 text-success fw-bold">Restore User Access</h6>
                                    <p class="mb-0 text-muted">This user is currently banned. Unbanning will restore their access to the platform.</p>
                                </div>
                            </div>
                        </div>
                        
                        <form action="{{ route('admin.users.unban', $user->id) }}" method="POST" 
                              onsubmit="return confirm('Are you sure you want to UNBAN this user?');">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-success btn-lg rounded-pill px-4 fw-semibold">
                                <i class="bi bi-shield-check me-2"></i>
                                Unban User
                            </button>
                        </form>

                    @else
                        <div class="alert alert-secondary border-0 rounded-3 mb-0" style="background: rgba(108, 117, 125, 0.1);">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-info-circle text-secondary me-3" style="font-size: 24px;"></i>
                                <div>
                                    <h6 class="mb-1 text-secondary fw-bold">No Actions Available</h6>
                                    <p class="mb-0 text-muted">No actions are available for this user's current status ({{ ucfirst($user->status) }}).</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            @endif
            <!-- ===================== END OF ENHANCED ACTION PANEL ===================== -->
        </div>
    </div>
</div>

<style>
/* Custom styles for enhanced user details page */
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
}

.profile-avatar {
    transition: transform 0.3s ease;
}

.profile-avatar:hover {
    transform: scale(1.05);
}

.info-row {
    transition: background-color 0.2s ease;
}

.info-row:hover {
    background-color: rgba(0, 0, 0, 0.02);
    border-radius: 8px;
    margin: 0 -12px;
    padding-left: 12px !important;
    padding-right: 12px !important;
}

.card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
}

.btn {
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-1px);
}

.status-indicator {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(var(--bs-success-rgb), 0.4);
    }
    70% {
        box-shadow: 0 0 0 10px rgba(var(--bs-success-rgb), 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(var(--bs-success-rgb), 0);
    }
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .info-row {
        flex-direction: column;
        align-items: flex-start !important;
    }
    
    .info-label {
        min-width: auto !important;
        margin-bottom: 8px;
    }
    
    .d-flex.gap-3 {
        flex-direction: column;
    }
    
    .d-flex.gap-3 .btn {
        width: 100%;
    }
}
</style>
@endsection