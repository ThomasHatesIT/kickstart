@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    {{-- Header Section --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Order Management</h1>
            <p class="text-muted mb-0">Manage and track all customer orders</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary btn-sm" onclick="window.print()">
                <i class="fas fa-print me-1"></i>Print
            </button>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Orders</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $orders->total() ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Revenue (Monthly)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">€0.00</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-euro-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Pending Orders</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Completed Today</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Enhanced Search Section --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white border-bottom-0">
            <h6 class="m-0 font-weight-bold text-primary">Search & Filter Orders</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.orders.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label">Search Orders</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input class="form-control" type="search" name="search" id="search" 
                               placeholder="Order # or Customer Name..." 
                               value="{{ request('search') }}">
                    </div>
                </div>
                
                <div class="col-md-2">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" name="status" id="status">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="date_from" class="form-label">From Date</label>
                    <input type="date" class="form-control" name="date_from" id="date_from" 
                           value="{{ request('date_from') }}">
                </div>

                <div class="col-md-2">
                    <label for="date_to" class="form-label">To Date</label>
                    <input type="date" class="form-control" name="date_to" id="date_to" 
                           value="{{ request('date_to') }}">
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <div class="btn-group w-100" role="group">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search me-1"></i>Search
                        </button>
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>Clear
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Enhanced Orders Table --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white border-bottom-0">
            <h6 class="m-0 font-weight-bold text-primary">Orders List</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0">Order #</th>
                            <th class="border-0">Customer</th>
                            <th class="border-0">Date</th>
                            <th class="border-0">Items</th>
                            <th class="border-0">Total</th>
                            <th class="border-0">Status</th>
                            <th class="border-0">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                        <tr class="order-row">
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-light rounded-circle d-flex align-items-center justify-content-center me-2">
                                        <i class="fas fa-receipt text-muted"></i>
                                    </div>
                                    <div>
                                        <strong class="text-dark">#{{ $order->order_number }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $order->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-2">
                                        <span class="text-white font-weight-bold">
                                            {{ strtoupper(substr($order->user->name, 0, 1)) }}
                                        </span>
                                    </div>
                                    <div>
                                        <strong>{{ $order->user->name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $order->user->email ?? 'N/A' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <strong>{{ $order->created_at->format('M d, Y') }}</strong>
                                <br>
                                <small class="text-muted">{{ $order->created_at->format('g:i A') }}</small>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark rounded-pill">
                                    {{ $order->items_count ?? 0 }} items
                                </span>
                            </td>
                            <td>
                                <strong class="text-success">€{{ number_format($order->total_amount, 2) }}</strong>
                            </td>
                            <td>
                                @php
                                    $statusColors = [
                                        'pending' => 'warning',
                                        'processing' => 'info',
                                        'shipped' => 'primary',
                                        'delivered' => 'success',
                                        'cancelled' => 'danger'
                                    ];
                                    $statusColor = $statusColors[$order->status] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $statusColor }} rounded-pill">
                                    <i class="fas fa-circle me-1" style="font-size: 0.5rem;"></i>
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order) }}" 
                                   class="btn btn-sm btn-outline-primary" 
                                   data-bs-toggle="tooltip" title="View Details">
                                    <i class="fas fa-eye me-1"></i>View Details
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="empty-state">
                                    <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">No orders found</h5>
                                    <p class="text-muted">Try adjusting your search criteria or check back later.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{-- Enhanced Pagination --}}
            @if($orders->hasPages())
            <div class="card-footer bg-white border-top-0">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <small class="text-muted">
                            Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} 
                            of {{ $orders->total() }} results
                        </small>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-end">
                            {{ $orders->withQueryString()->links() }}
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Custom CSS --}}
<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.avatar-sm {
    width: 2.5rem;
    height: 2.5rem;
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 0, 0, 0.025);
}

.order-row {
    transition: all 0.2s ease-in-out;
}

.order-row:hover {
    transform: translateY(-1px);
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.empty-state {
    padding: 2rem;
}

.card-header {
    font-weight: 600;
}

.btn-group .btn {
    border-right: 1px solid #dee2e6;
}

.btn-group .btn:last-child {
    border-right: none;
}

@media (max-width: 768px) {
    .btn-group {
        flex-direction: column;
    }
    
    .btn-group .btn {
        border-right: none;
        border-bottom: 1px solid #dee2e6;
    }
    
    .btn-group .btn:last-child {
        border-bottom: none;
    }
}
</style>

{{-- Custom JavaScript --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Auto-submit form on select change
    const statusSelect = document.getElementById('status');
    if (statusSelect) {
        statusSelect.addEventListener('change', function() {
            this.form.submit();
        });
    }
});
</script>
@endsection