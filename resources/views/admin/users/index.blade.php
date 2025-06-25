@extends('layouts.admin')

@section('title', 'User Management')

@section('content')
    <!-- Header Section with Gradient Background -->
    <div class="bg-gradient-primary text-white rounded-4 p-4 mb-4 shadow-lg">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="display-6 fw-bold mb-2">User Management</h1>
                <p class="mb-0 opacity-75">Manage all registered users and their permissions</p>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="text-center">
                    <div class="fs-2 fw-bold">{{ $users->total() ?? 0 }}</div>
                    <div class="small opacity-75">Total Users</div>
                </div>
                <div class="bg-white bg-opacity-15 rounded-circle p-3">
                    <i class="bi bi-people-fill fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom py-4">
            <div class="d-flex align-items-center">
                <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                    <i class="bi bi-person-lines-fill text-primary fs-4"></i>
                </div>
                <div>
                    <h4 class="mb-1 text-dark">All Registered Users</h4>
                    <p class="text-muted mb-0">View and manage user accounts, roles, and statuses</p>
                </div>
            </div>
        </div>
        
        <div class="card-body p-4">
            {{-- Include the partial for displaying session flash messages --}}
            @include('partials._alerts')

            {{-- Enhanced Filter Form --}}
            <div class="bg-light bg-opacity-50 rounded-4 p-4 mb-4">
                <form method="GET" action="{{ route('admin.users.index') }}">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="search" class="form-label fw-semibold">
                                <i class="bi bi-search me-1"></i>Search Users
                            </label>
                            <input type="text" 
                                   id="search" 
                                   name="search" 
                                   class="form-control form-control-lg" 
                                   placeholder="Search by name or email..." 
                                   value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="role" class="form-label fw-semibold">
                                <i class="bi bi-person-badge me-1"></i>Role Filter
                            </label>
                            <select name="role" id="role" class="form-select form-select-lg">
                                <option value="">All Roles</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                                        {{ ucfirst($role->name) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="status" class="form-label fw-semibold">
                                <i class="bi bi-shield-check me-1"></i>Status Filter
                            </label>
                            <select name="status" id="status" class="form-select form-select-lg">
                                <option value="">All Statuses</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                <option value="banned" {{ request('status') == 'banned' ? 'selected' : '' }}>Banned</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold text-transparent">Actions</label>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-funnel me-1"></i> Filter
                                </button>
                                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-clockwise me-1"></i> Clear
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Enhanced Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th class="border-0 py-3">
                                <i class="bi bi-hash me-1"></i>ID
                            </th>
                            <th class="border-0 py-3">
                                <i class="bi bi-person me-1"></i>User Details
                            </th>
                            <th class="border-0 py-3">
                                <i class="bi bi-person-badge me-1"></i>Role
                            </th>
                            <th class="border-0 py-3">
                                <i class="bi bi-shield-check me-1"></i>Status
                            </th>
                            <th class="border-0 py-3">
                                <i class="bi bi-calendar-event me-1"></i>Registered
                            </th>
                            <th class="border-0 py-3 text-center">
                                <i class="bi bi-gear me-1"></i>Actions
                            </th>
                        </tr>
                    </thead>
                  <!-- Paste this entire tbody block into your users/index.blade.php file -->
<tbody>
    @forelse ($users as $user)
        <tr class="border-bottom">
            <td class="py-3">
                <span class="badge bg-light text-dark fs-6 px-3 py-2">#{{ $user->id }}</span>
            </td>
            <td class="py-3">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                        <i class="bi bi-person-fill text-primary fs-5"></i>
                    </div>
                    <div>
                        <div class="fw-bold text-dark">{{ $user->name }}</div>
                        <div class="text-muted small">{{ $user->email }}</div>
                    </div>
                </div>
            </td>
            @php
                $role = $user->getRoleNames()->first();
            @endphp
            <td class="py-3">
                @if ($role === 'admin')
                    <span class="badge bg-danger text-white fs-6 px-3 py-2 rounded-pill">
                        <i class="bi bi-person-badge me-1"></i>
                        {{ ucfirst($role) }}
                    </span>
                @elseif ($role === 'seller')
                    <span class="badge bg-success text-white fs-6 px-3 py-2 rounded-pill">
                        <i class="bi bi-person-badge me-1"></i>
                        {{ ucfirst($role) }}
                    </span>
                @elseif ($role === 'buyer')
                    <span class="badge bg-primary text-white fs-6 px-3 py-2 rounded-pill">
                        <i class="bi bi-person-badge me-1"></i>
                        {{ ucfirst($role) }}
                    </span>
                @else
                    <span class="badge bg-secondary text-white fs-6 px-3 py-2 rounded-pill">
                        <i class="bi bi-person-badge me-1"></i>
                        {{ ucfirst($role ?? 'N/A') }}
                    </span>
                @endif
            </td>
            <td class="py-3">
                @if ($user->status == 'approved')
                    <span class="badge bg-success fs-6 px-3 py-2 rounded-pill"><i class="bi bi-check-circle me-1"></i>Approved</span>
                @elseif ($user->status == 'pending')
                    <span class="badge bg-warning text-dark fs-6 px-3 py-2 rounded-pill"><i class="bi bi-clock-history me-1"></i>Pending</span>
                @elseif ($user->status == 'banned')
                    <span class="badge bg-dark fs-6 px-3 py-2 rounded-pill"><i class="bi bi-shield-x me-1"></i>Banned</span>
                @elseif ($user->status == 'rejected')
                    <span class="badge bg-danger fs-6 px-3 py-2 rounded-pill"><i class="bi bi-x-circle me-1"></i>Rejected</span>
                @endif
            </td>
            <td class="py-3">
                <div class="text-dark fw-medium">{{ $user->created_at->format('M d, Y') }}</div>
                <div class="text-muted small">{{ $user->created_at->diffForHumans() }}</div>
            </td>
            <td class="py-3 text-center">
                <div class="d-flex justify-content-center gap-2 flex-wrap">
                    <!-- View Button (Always available) -->
                    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline-primary btn-sm" data-bs-toggle="tooltip" title="View User Details">
                        <i class="bi bi-eye"></i>
                    </a>

                    {{-- Do not allow actions on other Admins or the logged-in user --}}
                    @if($user->id !== auth()->id() && !$user->hasRole('admin'))
                        
                        <!-- ===================== THIS IS THE UPDATED SECTION ===================== -->
                        {{-- 1. Seller Approval Logic: Now links to the detail page for action --}}
                        @if ($user->hasRole('seller') && $user->status == 'pending')
                            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-warning btn-sm" data-bs-toggle="tooltip" title="Review to Approve/Reject">
                                <i class="bi bi-clipboard2-check"></i>
                            </a>
                        @endif
                        <!-- =================== END OF THE UPDATED SECTION ==================== -->
                        
                        {{-- 2. Banning Logic (Remains unchanged) --}}
                        @if ($user->status == 'approved')
                            <form action="{{ route('admin.users.ban', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to BAN this user?');">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-dark btn-sm" data-bs-toggle="tooltip" title="Ban User">
                                    <i class="bi bi-shield-x"></i>
                                </button>
                            </form>
                        {{-- 3. Unbanning Logic (Remains unchanged) --}}
                        @elseif ($user->status == 'banned')
                            <form action="{{ route('admin.users.unban', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to UNBAN this user?');">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-info btn-sm" data-bs-toggle="tooltip" title="Unban User">
                                    <i class="bi bi-shield-check"></i>
                                </button>
                            </form>
                        @endif

                    @endif
                </div>
            </td>
        </tr>
    @empty
        <!-- The empty state remains the same -->
        <tr>
            <td colspan="6" class="text-center py-5">
                <div class="d-flex flex-column align-items-center">
                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-people text-muted fs-1"></i>
                    </div>
                    <h5 class="text-muted mb-2">No Users Found</h5>
                    <p class="text-muted mb-0">No users match the current filter criteria.</p>
                </div>
            </td>
        </tr>
    @endforelse
</tbody>
                </table>
            </div>
        </div>
        
        <!-- Enhanced Footer with Pagination -->
    
@if($users->hasPages())
    <div class="card-footer bg-white border-top py-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div class="text-muted mb-2 mb-md-0">
                Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} results
            </div>
            <div>
                {{ $users->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endif

    </div>

    <!-- Custom Styles -->
    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .table-hover tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.03);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        .btn {
            transition: all 0.3s ease;
            border-radius: 8px;
        }
        
        .btn:hover {
            transform: translateY(-1px);
        }
        
        .badge {
            font-weight: 500;
            border-radius: 12px;
        }
        
        .card {
            transition: all 0.3s ease;
        }
        
        .form-control:focus,
        .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .table thead th {
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        
        .rounded-4 {
            border-radius: 1rem !important;
        }
        
        .text-transparent {
            color: transparent !important;
        }
    </style>
@endsection

@push('scripts')
{{-- Initialize Bootstrap tooltips for better UX --}}
<script>
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
    
    // Add loading state to form submissions
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function() {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Processing...';
            }
        });
    });
</script>
@endpush