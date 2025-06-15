{{-- resources/views/cart/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Shopping Cart - KickStart')

@section('content')
<div class="container my-5">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h1 class="display-6 fw-bold mb-1">Your Shopping Cart</h1>
                    <p class="text-muted mb-0">
                        <i class="bi bi-bag-check me-2"></i>
                        {{ $cartItems->count() }} {{ Str::plural('item', $cartItems->count()) }} in your cart
                    </p>
                </div>
                <div>
                    <a href="" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-left me-2"></i>Continue Shopping
                    </a>
                </div>
            </div>
        </div>
    </div>

    @include('partials._alerts')

    @if($cartItems->isEmpty())
        <!-- Empty Cart State -->
        <div class="row justify-content-center">
            <div class="col-md-6 text-center">
                <div class="card border-0 shadow-lg rounded-4">
                    <div class="card-body p-5">
                        <div class="mb-4">
                            <i class="bi bi-cart-x display-1 text-muted"></i>
                        </div>
                        <h3 class="fw-bold mb-3">Your cart is empty</h3>
                        <p class="text-muted mb-4">Looks like you haven't added any items to your cart yet. Start exploring our amazing products!</p>
                        <a href="" class="btn btn-primary btn-lg px-4">
                            <i class="bi bi-bag-plus me-2"></i>Start Shopping
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="row g-4">
            {{-- Cart Items Column --}}
            <div class="col-lg-8">
                <div class="card border-0 shadow-lg rounded-4">
                    <div class="card-header bg-white border-0 p-4">
                        <h5 class="fw-bold mb-0">
                            <i class="bi bi-basket me-2 text-primary"></i>Cart Items
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        @foreach($cartItems as $item)
                            <div class="cart-item border-bottom p-4 {{ $loop->last ? 'border-0' : '' }}">
                                <div class="row align-items-center g-3">
                                    {{-- Product Image --}}
                                    <div class="col-md-3 col-lg-2">
                                        <div class="product-image-wrapper">
                                            @php
                                                $imageModel = $item->product->primaryImageModel;
                                            @endphp
                                            
                                            @if ($imageModel->image_path === 'images/default-product.png')
                                                <img src="{{ asset($imageModel->image_path) }}" 
                                                     class="img-fluid rounded-3 product-image" 
                                                     alt="{{ $item->product->name }}">
                                            @else
                                                <img src="{{ Storage::url($imageModel->image_path) }}" 
                                                     class="img-fluid rounded-3 product-image" 
                                                     alt="{{ $item->product->name }}">
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Product Details --}}
                                    <div class="col-md-5 col-lg-6">
                                        <div class="product-details">
                                            <a href="{{ route('show', $item->product) }}" class="text-decoration-none">
                                                <h5 class="fw-bold mb-2 text-dark hover-primary">{{ $item->product->name }}</h5>
                                            </a>
                                            <div class="product-meta">
                                                <span class="badge bg-light text-dark me-2">
                                                    <i class="bi bi-tag me-1"></i>Size: {{ $item->size }}
                                                </span>
                                                <span class="text-success fw-semibold">
                                                    ₱{{ number_format($item->product->price, 2) }} each
                                                </span>
                                            </div>
                                            @if($item->product->stock < 10)
                                                <div class="mt-2">
                                                    <small class="text-warning fw-medium">
                                                        <i class="bi bi-exclamation-triangle me-1"></i>
                                                        Only {{ $item->product->stock }} left in stock
                                                    </small>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Quantity Controls --}}
                                    <div class="col-md-2 col-lg-2">
                                        <form action="{{ route('cart.update', $item->id) }}" method="POST" class="quantity-form">
                                            @csrf
                                            @method('PATCH')
                                            <div class="input-group input-group-sm">
                                                <button type="button" class="btn btn-outline-secondary qty-btn" data-action="decrease">
                                                    <i class="bi bi-dash"></i>
                                                </button>
                                                <input type="number" 
                                                       name="quantity" 
                                                       class="form-control text-center qty-input" 
                                                       value="{{ $item->quantity }}" 
                                                       min="1" 
                                                       max="{{ $item->product->stock }}"
                                                       readonly>
                                                <button type="button" class="btn btn-outline-secondary qty-btn" data-action="increase">
                                                    <i class="bi bi-plus"></i>
                                                </button>
                                            </div>
                                            <button type="submit" class="btn btn-primary btn-sm w-100 mt-2">
                                                <i class="bi bi-arrow-clockwise me-1"></i>Update
                                            </button>
                                        </form>
                                    </div>

                                    {{-- Item Total & Remove --}}
                                    <div class="col-md-2 col-lg-2">
                                        <div class="text-end">
                                            <div class="item-total mb-3">
                                                <strong class="fs-5 text-success">₱{{ number_format($item->product->price * $item->quantity, 2) }}</strong>
                                            </div>
                                            <form action="{{ route('cart.destroy', $item->id) }}" method="POST" class="remove-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Remove this item from cart?')">
                                                    <i class="bi bi-trash me-1"></i>Remove
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Order Summary Column --}}
            <div class="col-lg-4">
                <div class="position-sticky" style="top: 2rem;">
                    <div class="card border-0 shadow-lg rounded-4">
                        <div class="card-header bg-primary text-white p-4 rounded-top-4">
                            <h5 class="fw-bold mb-0">
                                <i class="bi bi-receipt me-2"></i>Order Summary
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <!-- Order Details -->
                            <div class="order-summary">
                                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                    <span class="text-muted">Items ({{ $cartItems->sum('quantity') }})</span>
                                    <span class="fw-semibold">₱{{ number_format($total, 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                    <span class="text-muted">Shipping</span>
                                    <span class="text-success fw-semibold">
                                        <i class="bi bi-truck me-1"></i>Free
                                    </span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                                    <span class="text-muted">Tax</span>
                                    <span class="fw-semibold">₱0.00</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center py-3">
                                    <span class="h5 fw-bold">Total</span>
                                    <span class="h4 fw-bold text-success">₱{{ number_format($total, 2) }}</span>
                                </div>
                            </div>

                            <!-- Checkout Button -->
                            <div class="d-grid gap-2 mt-4">
                                <a href="{{ route('checkout.index') }}" class="btn btn-success btn-lg fw-bold py-3">
                                    <i class="bi bi-credit-card me-2"></i>Proceed to Checkout
                                </a>
                                <a href="" class="btn btn-outline-primary">
                                    <i class="bi bi-arrow-left me-2"></i>Continue Shopping
                                </a>
                            </div>

                            <!-- Security Badge -->
                            <div class="text-center mt-4 pt-3 border-top">
                                <small class="text-muted">
                                    <i class="bi bi-shield-check text-success me-1"></i>
                                    Secure checkout powered by SSL encryption
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Promo Code Section -->
                    <div class="card border-0 shadow-sm rounded-4 mt-4">
                        <div class="card-body p-4">
                            <h6 class="fw-bold mb-3">
                                <i class="bi bi-tag me-2 text-primary"></i>Have a promo code?
                            </h6>
                            <form>
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Enter promo code">
                                    <button type="submit" class="btn btn-outline-primary">Apply</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
.cart-item {
    transition: all 0.3s ease;
}

.cart-item:hover {
    background-color: #f8f9fa;
}

.product-image-wrapper {
    position: relative;
    overflow: hidden;
    border-radius: 0.75rem;
    aspect-ratio: 1;
}

.product-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.product-image:hover {
    transform: scale(1.05);
}

.hover-primary:hover {
    color: var(--bs-primary) !important;
}

.quantity-form {
    max-width: 140px;
}

.qty-input {
    font-weight: 600;
    border-left: none;
    border-right: none;
}

.qty-btn {
    border-color: #dee2e6;
    width: 40px;
    padding: 0.375rem 0;
}

.qty-btn:hover {
    background-color: var(--bs-primary);
    border-color: var(--bs-primary);
    color: white;
}

.item-total {
    font-size: 1.1rem;
}

.order-summary .border-bottom {
    border-color: #e9ecef !important;
}

.remove-form button {
    transition: all 0.3s ease;
}

.remove-form button:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
}

.btn-success {
    background: linear-gradient(135deg, #198754 0%, #146c43 100%);
    border: none;
}

.btn-success:hover {
    background: linear-gradient(135deg, #146c43 0%, #0f5132 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(25, 135, 84, 0.4);
}

.position-sticky {
    position: -webkit-sticky;
    position: sticky;
}

@media (max-width: 768px) {
    .quantity-form {
        max-width: 120px;
    }
    
    .cart-item .row {
        text-align: center;
    }
    
    .cart-item .text-end {
        text-align: center !important;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Quantity control buttons
    document.querySelectorAll('.qty-btn').forEach(button => {
        button.addEventListener('click', function() {
            const action = this.dataset.action;
            const input = this.closest('.input-group').querySelector('.qty-input');
            const currentValue = parseInt(input.value);
            const max = parseInt(input.max);
            const min = parseInt(input.min);
            
            if (action === 'increase' && currentValue < max) {
                input.value = currentValue + 1;
            } else if (action === 'decrease' && currentValue > min) {
                input.value = currentValue - 1;
            }
        });
    });
    
    // Auto-submit quantity forms on input change (optional)
    document.querySelectorAll('.qty-input').forEach(input => {
        input.addEventListener('change', function() {
            // Uncomment the line below if you want auto-submit on quantity change
            // this.closest('form').submit();
        });
    });
});
</script>
@endpush