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
        <div class="d-flex gap-2">
            <a href="{{ url()->previous() }}" class="btn btn-outline-primary btn-lg rounded-pill px-4">
                <i class="bi bi-arrow-left me-2"></i>
                Back 
            </a>
            {{-- Quick Action Buttons --}}
            @if(auth()->id() !== $user->id && !$user->hasRole('admin'))
                @if($user->status == 'approved')
                    <div class="dropdown">
                        <button class="btn btn-success dropdown-toggle rounded-pill px-4" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-gear me-2"></i>Quick Actions
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" onclick="showBanModal()"><i class="bi bi-shield-x me-2 text-danger"></i>Ban User</a></li>
                            <li><a class="dropdown-item" href="mailto:{{ $user->email }}"><i class="bi bi-envelope me-2 text-primary"></i>Send Email</a></li>
                        </ul>
                    </div>
                @endif
            @endif
        </div>
    </div>

    {{-- Include the partial for displaying session flash messages --}}
    @include('partials._alerts')

    <div class="row g-4">
        {{-- User Profile Card --}}
        <div class="col-lg-4">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                <div class="card-body text-center p-5">
                    <!-- Enhanced Profile Avatar with initials fallback -->
                    <div class="profile-avatar position-relative mb-4">
                        @if($user->profile_photo_path)
                            <img src="{{ Storage::url($user->profile_photo_path) }}" 
                                 alt="{{ $user->name }}" 
                                 class="rounded-circle mx-auto shadow-lg"
                                 style="width: 120px; height: 120px; object-fit: cover;">
                        @else
                            <div class="bg-gradient-primary rounded-circle d-flex align-items-center justify-content-center mx-auto shadow-lg" 
                                 style="width: 120px; height: 120px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <span class="text-white fw-bold" style="font-size: 48px;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}{{ strtoupper(substr(explode(' ', $user->name)[1] ?? '', 0, 1)) }}
                                </span>
                            </div>
                        @endif
                        
                        <!-- Enhanced Status Indicator with tooltip -->
                        <div class="status-indicator position-absolute bottom-0 end-0 translate-middle-x" 
                             data-bs-toggle="tooltip" 
                             title="Account Status: {{ ucfirst($user->status) }}">
                            @switch($user->status)
                                @case('approved')
                                    <div class="bg-success rounded-circle d-flex align-items-center justify-content-center shadow status-approved" 
                                         style="width: 35px; height: 35px; border: 3px solid white;">
                                        <i class="bi bi-check-lg text-white" style="font-size: 16px;"></i>
                                    </div>
                                    @break
                                @case('pending')
                                    <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center shadow status-pending" 
                                         style="width: 35px; height: 35px; border: 3px solid white;">
                                        <i class="bi bi-clock text-white" style="font-size: 14px;"></i>
                                    </div>
                                    @break
                                @case('banned')
                                    <div class="bg-danger rounded-circle d-flex align-items-center justify-content-center shadow status-banned" 
                                         style="width: 35px; height: 35px; border: 3px solid white;">
                                        <i class="bi bi-x-lg text-white" style="font-size: 16px;"></i>
                                    </div>
                                    @break
                                @case('rejected')
                                    <div class="bg-dark rounded-circle d-flex align-items-center justify-content-center shadow" 
                                         style="width: 35px; height: 35px; border: 3px solid white;">
                                        <i class="bi bi-x-circle text-white" style="font-size: 14px;"></i>
                                    </div>
                                    @break
                            @endswitch
                        </div>
                    </div>
                    
                    <h4 class="fw-bold mb-2 text-dark">{{ $user->name }}</h4>
                    
                    <!-- Enhanced Role Badge -->
                    <div class="mb-3">
                        @php
                            $roles = $user->getRoleNames();
                            $primaryRole = $roles->first() ?? 'N/A';
                            $badgeClass = match(strtolower($primaryRole)) {
                                'admin' => 'bg-danger text-white',
                                'seller' => 'bg-success text-white',
                                'customer' => 'bg-primary text-white',
                                default => 'bg-secondary text-white'
                            };
                        @endphp
                        <span class="badge {{ $badgeClass }} px-3 py-2 rounded-pill fs-6 fw-semibold">
                            <i class="bi bi-shield-check me-1"></i>
                            {{ ucfirst($primaryRole) }}
                            @if($roles->count() > 1)
                                <span class="badge bg-light text-dark ms-2">+{{ $roles->count() - 1 }}</span>
                            @endif
                        </span>
                    </div>
                    
                    <p class="text-muted mb-0 fs-6">
                        <i class="bi bi-envelope me-2"></i>
                        <span class="user-select-all">{{ $user->email }}</span>
                    </p>
                    
                    <!-- Enhanced Member Since with verification badge -->
                    <div class="mt-4 pt-3 border-top">
                        <div class="d-flex align-items-center justify-content-center gap-3">
                            <div class="text-center">
                                <small class="text-muted d-block">
                                    <i class="bi bi-calendar-check me-1"></i>
                                    Member since
                                </small>
                                <strong class="text-primary">{{ $user->created_at->format('M Y') }}</strong>
                            </div>
                            @if($user->hasVerifiedEmail())
                                <div class="text-center">
                                    <i class="bi bi-patch-check-fill text-success" style="font-size: 24px;" 
                                       data-bs-toggle="tooltip" title="Email Verified"></i>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Activity Status --}}
                    <div class="mt-3 pt-3 border-top">
                        <small class="text-muted">
                            <i class="bi bi-clock-history me-1"></i>
                            Last seen {{ $user->updated_at->diffForHumans() }}
                        </small>
                    </div>
                </div>
            </div>

           
        </div>

        {{-- User Information Card --}}
        <div class="col-lg-8">
            {{-- Navigation Tabs --}}
            <ul class="nav nav-pills nav-fill mb-4 bg-light rounded-4 p-2" id="userTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active rounded-3 fw-semibold" id="info-tab" data-bs-toggle="pill" data-bs-target="#info-tab-pane" type="button" role="tab">
                        <i class="bi bi-person-lines-fill me-2"></i>Account Info
                    </button>
                </li>
                @if($user->hasRole('seller'))
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-3 fw-semibold" id="verification-tab" data-bs-toggle="pill" data-bs-target="#verification-tab-pane" type="button" role="tab">
                            <i class="bi bi-patch-check me-2"></i>Verification
                        </button>
                    </li>
                @endif
                <li class="nav-item" role="presentation">
                    <button class="nav-link rounded-3 fw-semibold" id="activity-tab" data-bs-toggle="pill" data-bs-target="#activity-tab-pane" type="button" role="tab">
                        <i class="bi bi-clock-history me-2"></i>Activity
                    </button>
                </li>
            </ul>

            {{-- Tab Content --}}
            <div class="tab-content" id="userTabsContent">
                {{-- Account Information Tab --}}
                <div class="tab-pane fade show active" id="info-tab-pane" role="tabpanel" tabindex="0">
                    <div class="card shadow-lg border-0 rounded-4">
                        <div class="card-header bg-light border-0 py-4 px-4 rounded-top-4">
                            <h5 class="mb-0 text-dark fw-bold">
                                <i class="bi bi-person-lines-fill me-2 text-primary"></i>
                                Account Information
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            {{-- Information Grid --}}
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="form-label fw-semibold text-muted small">
                                            <i class="bi bi-person me-2"></i>FULL NAME
                                        </label>
                                        <p class="form-control-plaintext fw-semibold">{{ $user->name }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="form-label fw-semibold text-muted small">
                                            <i class="bi bi-envelope me-2"></i>EMAIL ADDRESS
                                        </label>
                                        <p class="form-control-plaintext fw-semibold user-select-all">{{ $user->email }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="form-label fw-semibold text-muted small">
                                            <i class="bi bi-telephone me-2"></i>PHONE NUMBER
                                        </label>
                                        <p class="form-control-plaintext fw-semibold">{{ $user->phone ?? 'Not provided' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="form-label fw-semibold text-muted small">
                                            <i class="bi bi-shield-check me-2"></i>USER ROLE
                                        </label>
                                        <p class="form-control-plaintext fw-semibold">
                                            {{ $user->getRoleNames()->map(fn($role) => ucfirst($role))->implode(', ') ?: 'Not Assigned' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="info-item">
                                        <label class="form-label fw-semibold text-muted small">
                                            <i class="bi bi-geo-alt me-2"></i>ADDRESS
                                        </label>
                                        <p class="form-control-plaintext fw-semibold">{{ $user->address ?? 'Not provided' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="form-label fw-semibold text-muted small">
                                            <i class="bi bi-calendar-plus me-2"></i>REGISTERED ON
                                        </label>
                                        <p class="form-control-plaintext fw-semibold">
                                            {{ $user->created_at->setTimezone('Asia/Manila')->format('F j, Y, g:i a T') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="form-label fw-semibold text-muted small">
                                            <i class="bi bi-clock-history me-2"></i>LAST UPDATED
                                        </label>
                                        <p class="form-control-plaintext fw-semibold">
                                            {{ $user->updated_at->setTimezone('Asia/Manila')->diffForHumans() }}
                                            <small class="text-muted d-block">{{ $user->updated_at->setTimezone('Asia/Manila')->format('M j, Y g:i a T') }}</small>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- Account Status Section --}}
                            <hr class="my-4">
                            <div class="row align-items-center">
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold text-muted small">
                                        <i class="bi bi-shield-check me-2"></i>ACCOUNT STATUS
                                    </label>
                                </div>
                                <div class="col-md-9">
                                    @switch($user->status)
                                        @case('approved')
                                            <span class="badge bg-success bg-gradient px-4 py-2 rounded-pill fs-6 fw-semibold">
                                                <i class="bi bi-check-circle me-2"></i>Approved & Active
                                            </span>
                                            @break
                                        @case('pending')
                                            <span class="badge bg-warning text-dark bg-gradient px-4 py-2 rounded-pill fs-6 fw-semibold">
                                                <i class="bi bi-clock me-2"></i>Pending Approval
                                            </span>
                                            @break
                                        @case('banned')
                                            <span class="badge bg-danger bg-gradient px-4 py-2 rounded-pill fs-6 fw-semibold">
                                                <i class="bi bi-x-circle me-2"></i>Banned
                                            </span>
                                            @break
                                        @case('rejected')
                                            <span class="badge bg-dark bg-gradient px-4 py-2 rounded-pill fs-6 fw-semibold">
                                                <i class="bi bi-x-circle me-2"></i>Rejected
                                            </span>
                                            @break
                                    @endswitch
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Verification Documents Tab (Only for sellers) --}}
                @if($user->hasRole('seller'))
                <div class="tab-pane fade" id="verification-tab-pane" role="tabpanel" tabindex="0">
                    <div class="card shadow-lg border-0 rounded-4">
                        <div class="card-header bg-light border-0 py-4 px-4 rounded-top-4">
                            <h5 class="mb-0 text-dark fw-bold">
                                <i class="bi bi-patch-check-fill me-2 text-primary"></i>Verification Documents
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-4">
                                {{-- Face Photo --}}
                                <div class="col-md-6">
                                    <div class="verification-section">
                                        <h6 class="fw-bold mb-3 text-primary">
                                            <i class="bi bi-person-badge me-2"></i>Face Photo
                                        </h6>
                                        @if($user->face_photo_path)
                                            <div class="document-preview">
                                                <a href="{{ Storage::url($user->face_photo_path) }}" 
                                                   class="d-block position-relative overflow-hidden rounded-3 shadow-sm border"
                                                   data-bs-toggle="modal" data-bs-target="#imageModal" 
                                                   onclick="showImage('{{ Storage::url($user->face_photo_path) }}', 'Face Photo')">
                                                    <img src="{{ Storage::url($user->face_photo_path) }}" 
                                                         alt="Face Photo" 
                                                         class="img-fluid w-100" 
                                                         style="height: 200px; object-fit: cover;">
                                                    <div class="overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-dark bg-opacity-50 opacity-0 transition-opacity">
                                                        <i class="bi bi-zoom-in text-white" style="font-size: 24px;"></i>
                                                    </div>
                                                </a>
                                            </div>
                                        @else
                                            <div class="alert alert-light border text-center py-5">
                                                <i class="bi bi-image text-muted mb-2" style="font-size: 48px;"></i>
                                                <p class="mb-0 text-muted">No face photo provided</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                {{-- ID Documents --}}
                                <div class="col-md-6">
                                    <div class="verification-section">
                                        <h6 class="fw-bold mb-3 text-primary">
                                            <i class="bi bi-file-earmark-text me-2"></i>ID Documents
                                        </h6>
                                        @forelse ($user->verificationDocuments as $index => $doc)
                                            <div class="document-item mb-3">
                                                <div class="document-preview">
                                                    <a href="{{ Storage::url($doc->document_path) }}" 
                                                       class="d-block position-relative overflow-hidden rounded-3 shadow-sm border mb-2"
                                                       data-bs-toggle="modal" data-bs-target="#imageModal" 
                                                       onclick="showImage('{{ Storage::url($doc->document_path) }}', '{{ ucfirst(str_replace('_', ' ', $doc->document_type)) }}')">
                                                        <img src="{{ Storage::url($doc->document_path) }}" 
                                                             alt="{{ $doc->document_type }}" 
                                                             class="img-fluid w-100" 
                                                             style="height: 120px; object-fit: cover;">
                                                        <div class="overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-dark bg-opacity-50 opacity-0 transition-opacity">
                                                            <i class="bi bi-zoom-in text-white" style="font-size: 20px;"></i>
                                                        </div>
                                                    </a>
                                                </div>
                                                <p class="text-center text-primary fw-semibold small mb-0">
                                                    {{ ucfirst(str_replace('_', ' ', $doc->document_type)) }}
                                                </p>
                                            </div>
                                        @empty
                                            <div class="alert alert-light border text-center py-5">
                                                <i class="bi bi-file-earmark text-muted mb-2" style="font-size: 48px;"></i>
                                                <p class="mb-0 text-muted">No ID documents uploaded</p>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Activity Tab --}}
                <div class="tab-pane fade" id="activity-tab-pane" role="tabpanel" tabindex="0">
                    <div class="card shadow-lg border-0 rounded-4">
                        <div class="card-header bg-light border-0 py-4 px-4 rounded-top-4">
                            <h5 class="mb-0 text-dark fw-bold">
                                <i class="bi bi-clock-history me-2 text-primary"></i>Recent Activity
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="timeline">
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-primary"></div>
                                    <div class="timeline-content">
                                        <h6 class="fw-bold mb-1">Account Created</h6>
                                        <p class="text-muted mb-0">{{ $user->created_at->setTimezone('Asia/Manila')->format('F j, Y \a\t g:i a T') }}</p>
                                    </div>
                                </div>
                                @if($user->hasVerifiedEmail())
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-success"></div>
                                    <div class="timeline-content">
                                        <h6 class="fw-bold mb-1">Email Verified</h6>
                                        <p class="text-muted mb-0">{{ $user->email_verified_at?->setTimezone('Asia/Manila')->format('F j, Y \a\t g:i a T') ?? 'Recently' }}</p>
                                    </div>
                                </div>
                                @endif
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-info"></div>
                                    <div class="timeline-content">
                                        <h6 class="fw-bold mb-1">Last Profile Update</h6>
                                        <p class="text-muted mb-0">{{ $user->updated_at->setTimezone('Asia/Manila')->format('F j, Y \a\t g:i a T') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===================== ENHANCED ACTION PANEL ===================== -->
            {{-- Prevent actions on the logged-in user or other admins for security --}}
            @if(auth()->id() !== $user->id && !$user->hasRole('admin'))
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden mt-4">
                <div class="card-header bg-gradient-primary text-white border-0 py-4 px-4" 
                     style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h5 class="mb-0 text-white fw-bold">
                        <i class="bi bi-ui-checks-grid me-2"></i>Account Management Actions
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
                                    <p class="mb-0 text-muted">This seller account is awaiting approval. Please review their verification documents before making a decision.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex flex-wrap gap-3 mt-3">
                            <form action="{{ route('admin.sellers.approve', $user->id) }}" method="POST" class="d-inline">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-success btn-lg rounded-pill px-4 fw-semibold"
                                        onclick="return confirm('Are you sure you want to APPROVE this seller account?');">
                                    <i class="bi bi-check-circle-fill me-2"></i>
                                    Approve Seller
                                </button>
                            </form>
                            <form action="{{ route('admin.sellers.reject', $user->id) }}" method="POST" class="d-inline">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-danger btn-lg rounded-pill px-4 fw-semibold"
                                        onclick="return confirm('Are you sure you want to REJECT this seller application? This action cannot be undone.');">
                                    <i class="bi bi-x-circle-fill me-2"></i>
                                    Reject Application
                                </button>
                            </form>
                        </div>
                    
                    {{-- 2. Banning Logic --}}
                    @elseif($user->status == 'approved')
                        <div class="alert alert-warning border-0 rounded-3 mb-4" style="background: rgba(255, 193, 7, 0.1);">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-exclamation-triangle text-warning me-3" style="font-size: 24px;"></i>
                                <div>
                                    <h6 class="mb-1 text-warning fw-bold">Account Management</h6>
                                    <p class="mb-0 text-muted">This user account is currently active. You can temporarily ban the account if needed.</p>
                                </div>
                            </div>
                        </div>
                        
                        <button type="button" class="btn btn-dark btn-lg rounded-pill px-4 fw-semibold" 
                                onclick="showBanModal()">
                            <i class="bi bi-shield-x me-2"></i>
                            Ban User Account
                        </button>

                    {{-- 3. Unbanning Logic --}}
                    @elseif($user->status == 'banned')
                        <div class="alert alert-success border-0 rounded-3 mb-4" style="background: rgba(25, 135, 84, 0.1);">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-shield-x text-success me-3" style="font-size: 24px;"></i>
                                <div>
                                    <h6 class="mb-1 text-success fw-bold">Account Restoration</h6>
                                    <p class="mb-0 text-muted">This user account is currently banned. You can restore their access to the platform.</p>
                                </div>
                            </div>
                        </div>
                        
                        <form action="{{ route('admin.users.unban', $user->id) }}" method="POST" class="d-inline">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-success btn-lg rounded-pill px-4 fw-semibold"
                                    onclick="return confirm('Are you sure you want to RESTORE access for this user?');">
                                <i class="bi bi-shield-check me-2"></i>
                                Restore Access
                            </button>
                        </form>

                    @else
                        <div class="alert alert-secondary border-0 rounded-3 mb-0" style="background: rgba(108, 117, 125, 0.1);">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-info-circle text-secondary me-3" style="font-size: 24px;"></i>
                                <div>
                                    <h6 class="mb-1 text-secondary fw-bold">No Actions Available</h6>
                                    <p class="mb-0 text-muted">No administrative actions are available for this user's current status ({{ ucfirst($user->status) }}).</p>
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

{{-- Ban User Modal --}}
<div class="modal fade" id="banUserModal" tabindex="-1" aria-labelledby="banUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-danger text-white border-0 rounded-top-4">
                <h5 class="modal-title fw-bold" id="banUserModalLabel">
                    <i class="bi bi-shield-x me-2"></i>Ban User Account
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-4">
                    <i class="bi bi-exclamation-triangle text-warning" style="font-size: 64px;"></i>
                </div>
                <h6 class="text-center fw-bold mb-3">Are you sure you want to ban this user?</h6>
                <p class="text-muted text-center">This action will:</p>
                <ul class="list-unstyled text-muted">
                    <li><i class="bi bi-x-circle me-2 text-danger"></i>Prevent the user from logging in</li>
                    <li><i class="bi bi-x-circle me-2 text-danger"></i>Revoke access to all platform features</li>
                    <li><i class="bi bi-check-circle me-2 text-success"></i>Can be reversed later if needed</li>
                </ul>
                <div class="alert alert-warning border-0 rounded-3 mt-3" style="background: rgba(255, 193, 7, 0.1);">
                    <small><strong>User:</strong> {{ $user->name }} ({{ $user->email }})</small>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg me-2"></i>Cancel
                </button>
                <form action="{{ route('admin.users.ban', $user->id) }}" method="POST" class="d-inline">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn btn-danger rounded-pill px-4 fw-semibold">
                        <i class="bi bi-shield-x me-2"></i>Yes, Ban User
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Image Modal for Verification Documents --}}
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 rounded-top-4">
                <h5 class="modal-title fw-bold" id="imageModalLabel">Document Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <img id="modalImage" src="" alt="Document" class="img-fluid w-100 rounded-bottom-4">
            </div>
        </div>
    </div>
</div>

<style>
/* Enhanced Custom Styles */
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
}

.profile-avatar {
    transition: transform 0.3s ease;
}

.profile-avatar:hover {
    transform: scale(1.05);
}

.info-item {
    padding: 1rem;
    border-radius: 0.75rem;
    transition: all 0.3s ease;
    border: 1px solid transparent;
}

.info-item:hover {
    background-color: rgba(0, 0, 0, 0.02);
    border-color: rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.info-item .form-label {
    margin-bottom: 0.5rem;
    letter-spacing: 0.5px;
}

.info-item .form-control-plaintext {
    padding: 0;
    margin-bottom: 0;
    min-height: auto;
}

.card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15) !important;
}

.btn {
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
}

.status-indicator {
    animation: pulse 2s infinite;
}

.status-approved {
    animation: pulse-success 2s infinite;
}

.status-pending {
    animation: pulse-warning 2s infinite;
}

.status-banned {
    animation: pulse-danger 2s infinite;
}

@keyframes pulse-success {
    0% { box-shadow: 0 0 0 0 rgba(25, 135, 84, 0.4); }
    70% { box-shadow: 0 0 0 10px rgba(25, 135, 84, 0); }
    100% { box-shadow: 0 0 0 0 rgba(25, 135, 84, 0); }
}

@keyframes pulse-warning {
    0% { box-shadow: 0 0 0 0 rgba(255, 193, 7, 0.4); }
    70% { box-shadow: 0 0 0 10px rgba(255, 193, 7, 0); }
    100% { box-shadow: 0 0 0 0 rgba(255, 193, 7, 0); }
}

@keyframes pulse-danger {
    0% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.4); }
    70% { box-shadow: 0 0 0 10px rgba(220, 53, 69, 0); }
    100% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); }
}

.nav-pills .nav-link {
    transition: all 0.3s ease;
}

.nav-pills .nav-link:hover {
    background-color: rgba(102, 126, 234, 0.1);
    transform: translateY(-1px);
}

.nav-pills .nav-link.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.timeline {
    position: relative;
    padding-left: 2rem;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: linear-gradient(to bottom, #667eea, #764ba2);
}

.timeline-item {
    position: relative;
    margin-bottom: 2rem;
}

.timeline-marker {
    position: absolute;
    left: -23px;
    top: 5px;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    border: 3px solid white;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.timeline-content {
    background: rgba(255, 255, 255, 0.8);
    padding: 1rem 1.5rem;
    border-radius: 0.75rem;
    border: 1px solid rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.timeline-content:hover {
    background: white;
    transform: translateX(5px);
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
}

.statistic-item {
    padding: 1rem;
    border-radius: 0.75rem;
    transition: all 0.3s ease;
}

.statistic-item:hover {
    background-color: rgba(102, 126, 234, 0.05);
    transform: translateY(-2px);
}

.verification-section {
    padding: 1rem;
    border-radius: 0.75rem;
    background: rgba(255, 255, 255, 0.5);
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.document-preview {
    position: relative;
    overflow: hidden;
    cursor: pointer;
}

.document-preview:hover .overlay {
    opacity: 1 !important;
}

.overlay {
    transition: opacity 0.3s ease;
}

.user-select-all {
    user-select: all;
    cursor: text;
}

.user-select-all:hover {
    background-color: rgba(102, 126, 234, 0.1);
    border-radius: 4px;
    padding: 2px 4px;
    margin: -2px -4px;
}

/* Enhanced Responsive Design */
@media (max-width: 768px) {
    .profile-avatar {
        margin-bottom: 2rem !important;
    }
    
    .d-flex.gap-3,
    .d-flex.flex-wrap.gap-3 {
        flex-direction: column;
    }
    
    .d-flex.gap-3 .btn,
    .d-flex.flex-wrap.gap-3 .btn {
        width: 100%;
        margin-bottom: 0.5rem;
    }
    
    .timeline {
        padding-left: 1.5rem;
    }
    
    .timeline::before {
        left: 11px;
    }
    
    .timeline-marker {
        left: -20px;
    }
    
    .nav-pills {
        flex-direction: column !important;
    }
    
    .nav-pills .nav-item {
        margin-bottom: 0.5rem;
    }
    
    .card-body {
        padding: 1.5rem !important;
    }
    
    .info-item {
        margin-bottom: 1rem;
    }
}

@media (max-width: 576px) {
    .container-fluid {
        padding: 1rem;
    }
    
    .card-header {
        padding: 1.5rem !important;
    }
    
    .modal-dialog {
        margin: 1rem;
    }
    
    .timeline-content {
        padding: 0.75rem 1rem;
    }
    
    .verification-section {
        padding: 0.75rem;
    }
}

/* Dark mode support (if implemented) */
@media (prefers-color-scheme: dark) {
    .card {
        background-color: rgba(255, 255, 255, 0.95);
    }
    
    .timeline-content {
        background: rgba(255, 255, 255, 0.9);
    }
    
    .verification-section {
        background: rgba(255, 255, 255, 0.7);
    }
}

/* Print styles */
@media print {
    .btn, .nav-pills, .modal, .card:hover {
        display: none !important;
    }
    
    .card {
        box-shadow: none !important;
        border: 1px solid #ddd !important;
    }
    
    .timeline::before {
        background: #ddd !important;
    }
}
</style>

<script>
// Enhanced JavaScript Functions
document.addEventListener('DOMContentLoaded', function () {
    // Initialize Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Initialize tab navigation with URL hash support
    const triggerTabList = document.querySelectorAll('#userTabs button');
    triggerTabList.forEach(function (triggerEl) {
        const tabTrigger = new bootstrap.Tab(triggerEl);
        
        triggerEl.addEventListener('click', function (event) {
            event.preventDefault();
            tabTrigger.show();
            
            // Update URL hash
            const target = event.target.getAttribute('data-bs-target');
            if (target) {
                history.pushState(null, null, target.replace('-pane', ''));
            }
        });
    });

    // Show tab based on URL hash
    const hash = window.location.hash;
    if (hash) {
        const targetTab = document.querySelector(`button[data-bs-target="${hash}-pane"]`);
        if (targetTab) {
            new bootstrap.Tab(targetTab).show();
        }
    }

    // Enhanced smooth animations for cards
    const cards = document.querySelectorAll('.card');
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const cardObserver = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    cards.forEach(function(card) {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        cardObserver.observe(card);
    });

    // Enhanced copy-to-clipboard functionality
    const selectableElements = document.querySelectorAll('.user-select-all');
    selectableElements.forEach(function(element) {
        element.addEventListener('click', function() {
            // Create a temporary input element
            const tempInput = document.createElement('input');
            tempInput.value = element.textContent.trim();
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand('copy');
            document.body.removeChild(tempInput);
            
            // Show toast notification (if you have toast functionality)
            showToast('Copied to clipboard!', 'success');
        });
    });
});

// Function to show ban modal
function showBanModal() {
    const banModal = new bootstrap.Modal(document.getElementById('banUserModal'));
    banModal.show();
}

// Function to show image in modal
function showImage(imageSrc, imageTitle) {
    const modalImage = document.getElementById('modalImage');
    const modalTitle = document.getElementById('imageModalLabel');
    
    modalImage.src = imageSrc;
    modalTitle.textContent = imageTitle || 'Document Preview';
}

// Function to show toast notifications (you can implement this if you have a toast system)
function showToast(message, type = 'info') {
    // Implementation depends on your toast system
    // This is a simple example using browser's built-in notification
    if ('Notification' in window && Notification.permission === 'granted') {
        new Notification(message);
    } else {
        // Fallback to console log or implement your own toast
        console.log(`${type.toUpperCase()}: ${message}`);
    }
}

// Enhanced form validation for ban action
document.addEventListener('submit', function(event) {
    const form = event.target;
    
    if (form.action && form.action.includes('/ban')) {
        event.preventDefault();
        
        const confirmMessage = 'This action will immediately ban the user and prevent them from accessing the platform. Are you absolutely sure you want to proceed?';
        
        if (confirm(confirmMessage)) {
            // Add loading state to button
            const submitButton = form.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Processing...';
            }
            
            form.submit();
        }
    }
});

// Auto-refresh status (optional - for real-time updates)
function refreshUserStatus() {
    // Only refresh if the page has been open for more than 5 minutes
    const pageLoadTime = new Date().getTime();
    const currentTime = new Date().getTime();
    const timeElapsed = currentTime - pageLoadTime;
    
    if (timeElapsed > 300000) { // 5 minutes
        // Implement AJAX call to check for status updates
        // This is optional and depends on your requirements
    }
}

// Set up auto-refresh timer (optional)
// setInterval(refreshUserStatus, 60000); // Check every minute
</script>
@endsection

@push('scripts')
{{-- Additional Scripts for Enhanced Functionality --}}
<script>
    // Enhanced tooltip initialization with custom options
    document.addEventListener('DOMContentLoaded', function () {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl, {
                delay: { show: 500, hide: 100 },
                animation: true,
                html: true
            });
        });
    });
</script>
@endpush