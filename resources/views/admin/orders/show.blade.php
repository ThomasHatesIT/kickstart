@extends('layouts.admin') {{-- Admin layout --}}

@section('content')
<div class="container-fluid py-4">
    {{-- Header Section --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-2 text-dark font-weight-bold">Order Management (Admin)</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-primary">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}" class="text-primary">Orders</a></li>
                            <li class="breadcrumb-item active">{{ $order->order_number }}</li>
                        </ol>
                    </nav>
                </div>
                <div class="d-flex gap-2">
                 
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i>Back to Orders
                    </a>
                </div>
            </div>
        </div>
    </div>

    @include('partials._alerts')

    {{-- Order Summary Card --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-2">
                            <h4 class="mb-1 font-weight-bold text-dark">{{ $order->order_number }}</h4>
                            <p class="text-muted mb-0">
                                <i class="bi bi-calendar me-1"></i>
                                {{ $order->created_at->format('M d, Y \a\t g:i A') }}
                            </p>
                        </div>
                        <div class="col-md-2">
                            <div class="d-flex align-items-center">
                                <div class="status-indicator status-{{ $order->status }} me-2"></div>
                                <div>
                                    <p class="text-muted mb-0 text-sm">Status</p>
                                    <span class="badge bg-{{ $order->status === 'delivered' ? 'success' : ($order->status === 'shipped' ? 'info' : ($order->status === 'confirmed' ? 'warning' : 'secondary')) }} text-white px-3 py-2">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <p class="text-muted mb-0 text-sm">Total Amount</p>
                            <h5 class="mb-0 font-weight-bold text-primary">₱{{ number_format($order->total_amount, 2) }}</h5>
                        </div>
                        <div class="col-md-2">
                            <p class="text-muted mb-0 text-sm">Items Count</p>
                            <h5 class="mb-0 font-weight-bold">{{ $order->items->sum('quantity') }} items</h5>
                        </div>
                        <div class="col-md-2">
                            <p class="text-muted mb-0 text-sm">Sellers Involved</p>
                            <h5 class="mb-0 font-weight-bold text-info">{{ $order->items->groupBy('product.seller_id')->count() }} sellers</h5>
                        </div>
                        <div class="col-md-2">
                            <p class="text-muted mb-0 text-sm">Payment Method</p>
                            <span class="badge bg-dark text-white">{{ $order->payment_method ?? 'COD' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- Order Items Section --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-header bg-transparent border-0 pb-0 pt-4 px-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 font-weight-bold">
                            <i class="bi bi-box-seam me-2 text-primary"></i>
                            Order Items & Seller Information
                        </h5>
                        <span class="badge bg-light text-dark px-3 py-2">{{ $order->items->count() }} Products</span>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 text-uppercase text-xs font-weight-bold">Product & Seller</th>
                                    <th class="border-0 text-uppercase text-xs font-weight-bold text-center">Size</th>
                                    <th class="border-0 text-uppercase text-xs font-weight-bold text-center">Qty</th>
                                    <th class="border-0 text-uppercase text-xs font-weight-bold text-center">Price</th>
                                    <th class="border-0 text-uppercase text-xs font-weight-bold text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr class="order-item-row">
                                    <td class="border-0 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="product-image me-3">
                                                @if($item->product->images->first())
                                                    <img src="{{ asset('storage/' . $item->product->images->first()->image_path) }}" 
                                                         alt="{{ $item->product->name }}" 
                                                         class="rounded" 
                                                         width="50" height="50"
                                                         style="object-fit: cover;">
                                                @else
                                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                         style="width: 50px; height: 50px;">
                                                        <i class="bi bi-image text-muted"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <h6 class="mb-1 font-weight-bold">{{ $item->product->name }}</h6>
                                                <div class="seller-info">
                                                    <div class="d-flex align-items-center mb-1">
                                                        <i class="bi bi-shop text-primary me-1" style="font-size: 12px;"></i>
                                                        <span class="text-primary text-sm font-weight-bold">{{ $item->product->seller->business_name ?? $item->product->seller->name }}</span>
                                                    </div>
                                                    <div class="d-flex align-items-center">
                                                        <i class="bi bi-envelope text-muted me-1" style="font-size: 11px;"></i>
                                                        <a href="mailto:{{ $item->product->seller->email }}" class="text-muted text-xs">{{ $item->product->seller->email }}</a>
                                                    </div>
                                                </div>
                                                <p class="text-muted text-sm mb-0">SKU: {{ $item->product->id }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="border-0 py-3 text-center">
                                        <span class="badge bg-secondary text-white">{{ $item->size }}</span>
                                    </td>
                                    <td class="border-0 py-3 text-center">
                                        <span class="font-weight-bold">{{ $item->quantity }}</span>
                                    </td>
                                    <td class="border-0 py-3 text-center">
                                        <span class="text-muted">₱{{ number_format($item->price, 2) }}</span>
                                    </td>
                                    <td class="border-0 py-3 text-end">
                                        <span class="font-weight-bold text-primary">₱{{ number_format($item->price * $item->quantity, 2) }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <td colspan="4" class="border-0 py-3 text-end font-weight-bold">
                                        Order Total:
                                    </td>
                                    <td class="border-0 py-3 text-end">
                                        <span class="font-weight-bold text-primary h5">
                                            ₱{{ number_format($order->total_amount, 2) }}
                                        </span>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Sidebar --}}
        <div class="col-lg-4">
            {{-- Customer Details --}}
            <div class="card border-0 shadow-lg mb-4">
                <div class="card-header bg-transparent border-0 pb-0 pt-4 px-4">
                    <h5 class="mb-0 font-weight-bold">
                        <i class="bi bi-person me-2 text-primary"></i>
                        Customer Information
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="customer-info">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar avatar-lg bg-gradient-primary rounded-circle me-3">
                                <span class="text-white font-weight-bold">
                                    {{ strtoupper(substr($order->user->name, 0, 2)) }}
                                </span>
                            </div>
                            <div>
                                <h6 class="mb-1 font-weight-bold">{{ $order->user->name }}</h6>
                                <p class="text-muted text-sm mb-1">
                                    <i class="bi bi-envelope me-1"></i>
                                    <a href="mailto:{{ $order->user->email }}">{{ $order->user->email }}</a>
                                </p>
                                <p class="text-muted text-sm mb-0">
                                    <i class="bi bi-telephone me-1"></i>
                                    {{ $order->user->phone ?? 'No phone provided' }}
                                </p>
                            </div>
                        </div>
                        
                        <div class="shipping-address">
                            <h6 class="font-weight-bold mb-2">
                                <i class="bi bi-geo-alt me-1 text-primary"></i>
                                Shipping Address
                            </h6>
                            <div class="bg-light p-3 rounded">
                                <address class="mb-0 text-sm">
                                    {!! nl2br(e($order->shipping_address)) !!}
                                </address>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sellers Summary --}}
            <div class="card border-0 shadow-lg mb-4">
                <div class="card-header bg-transparent border-0 pb-0 pt-4 px-4">
                    <h5 class="mb-0 font-weight-bold">
                        <i class="bi bi-shop me-2 text-primary"></i>
                        Sellers in this Order
                    </h5>
                </div>
                <div class="card-body p-4">
                    @php
                        $sellerGroups = $order->items->groupBy('product.seller_id');
                    @endphp
                    
                    @foreach($sellerGroups as $sellerId => $items)
                        @php
                            $seller = $items->first()->product->seller;
                            $sellerTotal = $items->sum(function($item) { return $item->price * $item->quantity; });
                            $sellerItemCount = $items->sum('quantity');
                        @endphp
                        
                        <div class="seller-summary-item p-3 border rounded mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <div class="avatar bg-gradient-info rounded-circle me-3">
                                    <span class="text-white text-sm font-weight-bold">
                                        {{ strtoupper(substr($seller->name, 0, 2)) }}
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 font-weight-bold">{{ $seller->business_name ?? $seller->name }}</h6>
                                    <p class="text-muted text-sm mb-0">
                                        <a href="mailto:{{ $seller->email }}">{{ $seller->email }}</a>
                                    </p>
                                </div>
                            </div>
                            
                            <div class="row text-center">
                                <div class="col-6">
                                    <p class="text-muted text-xs mb-0">Items</p>
                                    <span class="font-weight-bold">{{ $sellerItemCount }}</span>
                                </div>
                                <div class="col-6">
                                    <p class="text-muted text-xs mb-0">Amount</p>
                                    <span class="font-weight-bold text-primary">₱{{ number_format($sellerTotal, 2) }}</span>
                                </div>
                            </div>
                            
                            @if($seller->phone)
                            <div class="mt-2 pt-2 border-top">
                                <p class="text-muted text-xs mb-0">
                                    <i class="bi bi-telephone me-1"></i>
                                    {{ $seller->phone }}
                                </p>
                            </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Admin Order Actions --}}
            <div class="card border-0 shadow-lg mb-4">
                <div class="card-header bg-transparent border-0 pb-0 pt-4 px-4">
                    <h5 class="mb-0 font-weight-bold">
                        <i class="bi bi-gear me-2 text-primary"></i>
                        Admin Actions
                    </h5>
                </div>
           <div class="card-body p-4">
    <form action="{{ route('seller.orders.update', $order) }}" method="POST" id="statusUpdateForm">
        @csrf
        @method('PATCH')

        <div class="mb-4">
            <label for="status" class="form-label font-weight-bold">Current Status</label>
            <select name="status" id="status" class="form-select form-select-lg" disabled>
                <option value="pending" @selected($order->status === 'pending')>
                    📋 Pending
                </option>
                <option value="confirmed" @selected($order->status === 'confirmed')>
                    ✅ Confirmed
                </option>
                <option value="shipped" @selected($order->status === 'shipped')>
                    🚚 Shipped
                </option>
                <option value="delivered" @selected($order->status === 'delivered')>
                    📦 Delivered
                </option>
            </select>
        </div>
    </form>
</div>

            </div>

            {{-- Order Timeline --}}
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-transparent border-0 pb-0 pt-4 px-4">
                    <h5 class="mb-0 font-weight-bold">
                        <i class="bi bi-clock-history me-2 text-primary"></i>
                        Order Timeline
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="timeline">
                        {{-- Order Placed --}}
                        <div class="timeline-item active">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content ms-3">
                                <h6 class="mb-1">Order Placed</h6>
                                <p class="text-muted text-sm mb-0">{{ $order->created_at->format('M d, Y \a\t g:i A') }}</p>
                            </div>
                        </div>

                        {{-- Order Confirmed --}}
                        @if($order->status !== 'pending')
                        <div class="timeline-item active">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content ms-3">
                                <h6 class="mb-1">Order Confirmed</h6>
                                <p class="text-muted text-sm mb-0">Status updated to confirmed</p>
                            </div>
                        </div>
                        @endif

                        {{-- Order Shipped --}}
                        @if(in_array($order->status, ['shipped', 'delivered']))
                        <div class="timeline-item active">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content ms-3">
                                <h6 class="mb-1">Order Shipped</h6>
                                <p class="text-muted text-sm mb-0">
                                    Package dispatched
                                    @if($order->tracking_number)
                                        <br><small class="text-info">Tracking: {{ $order->tracking_number }}</small>
                                    @endif
                                </p>
                            </div>
                        </div>
                        @endif

                        {{-- Order Delivered --}}
                        @if($order->status === 'delivered')
                        <div class="timeline-item active">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content ms-3">
                                <h6 class="mb-1">Order Delivered</h6>
                                <p class="text-muted text-sm mb-0">Successfully delivered</p>
                            </div>
                        </div>
                        @endif

                        {{-- Order Cancelled --}}
                        @if($order->status === 'cancelled')
                        <div class="timeline-item">
                            <div class="timeline-marker bg-danger"></div>
                            <div class="timeline-content ms-3">
                                <h6 class="mb-1">Order Cancelled</h6>
                                <p class="text-muted text-sm mb-0">Order has been cancelled</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Custom Styles --}}
<style>
    .status-indicator {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        display: inline-block;
    }
    
    .status-pending { background-color: #6c757d; }
    .status-confirmed { background-color: #ffc107; }
    .status-shipped { background-color: #17a2b8; }
    .status-delivered { background-color: #28a745; }
    .status-cancelled { background-color: #dc3545; }
    
    .order-item-row:hover {
        background-color: #f8f9fa;
    }
    
    .avatar {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .avatar-lg {
        width: 60px;
        height: 60px;
    }
    
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .bg-gradient-info {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }
    
    .seller-info {
        background: #f8f9fa;
        padding: 8px;
        border-radius: 6px;
        margin: 5px 0;
    }
    
    .seller-summary-item {
        background: #fafbfc;
        transition: all 0.2s ease;
    }
    
    .seller-summary-item:hover {
        background: #f1f3f4;
        transform: translateY(-1px);
    }
    
    .timeline {
        position: relative;
        padding-left: 20px;
    }
    
    .timeline::before {
        content: '';
        position: absolute;
        left: 8px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e9ecef;
    }
    
    .timeline-item {
        position: relative;
        margin-bottom: 20px;
    }
    
    .timeline-item:last-child {
        margin-bottom: 0;
    }
    
    .timeline-marker {
        position: absolute;
        left: -16px;
        top: 5px;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        border: 3px solid #fff;
        box-shadow: 0 0 0 3px #e9ecef;
    }
    
    .timeline-item.active .timeline-marker {
        box-shadow: 0 0 0 3px #28a745;
    }
    
    .timeline-content h6 {
        font-size: 14px;
    }
    
    .product-image img {
        transition: transform 0.2s ease;
    }
    
    .product-image img:hover {
        transform: scale(1.1);
    }
    
    .table th {
        font-size: 11px;
        letter-spacing: 0.5px;
    }
</style>

{{-- JavaScript for enhanced interactions --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Status update confirmation
    const statusForm = document.getElementById('statusUpdateForm');
    if (statusForm) {
        statusForm.addEventListener('submit', function(e) {
            const statusSelect = document.getElementById('status');
            const selectedStatus = statusSelect.value;
            const currentStatus = '{{ $order->status }}';
            
            if (selectedStatus !== currentStatus) {
                if (!confirm(`Are you sure you want to update the order status to "${selectedStatus.toUpperCase()}"?`)) {
                    e.preventDefault();
                }
            }
        });
    }
    
    // Copy tracking number functionality
    const trackingInput = document.getElementById('tracking_number');
    if (trackingInput && trackingInput.value) {
        trackingInput.addEventListener('click', function() {
            this.select();
        });
    }
});

// Cancel order function
function cancelOrder() {
    if (confirm('Are you sure you want to cancel this order? This action cannot be undone.')) {
        const form = document.getElementById('statusUpdateForm');
        const statusSelect = document.getElementById('status');
        statusSelect.value = 'cancelled';
        form.submit();
    }
}
</script>
@endsection