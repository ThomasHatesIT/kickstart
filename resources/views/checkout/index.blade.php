@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h1 class="mb-4 text-center">Checkout</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    @if($cartItems->isEmpty())
         <div class="alert alert-warning text-center">
            Your cart is empty. You cannot proceed to checkout.
            <a href="{{ route('home') }}" class="d-block mt-2">Continue Shopping</a>
        </div>
    @else
    {{-- THIS IS THE ONLY LINE THAT NEEDED TO BE CHANGED --}}
    <form action="{{ route('checkout.store') }}" method="POST">
        @csrf
        <div class="row">

            {{-- Shipping Information Column --}}
            <div class="col-md-7 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="bi bi-truck me-2"></i>Shipping Information</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="phone" name="phone" value="{{ old('phone', auth()->user()->phone) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Shipping Address</label>
                            <textarea class="form-control" id="address" name="address" rows="4" required>{{ old('address', auth()->user()->address) }}</textarea>
                            <div class="form-text">Please provide the full address, including house number, street, city, and postal code.</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Order Summary Column --}}
            <div class="col-md-5">
                <div class="card shadow-sm">
                    <div class="card-header bg-secondary text-white">
                        <h4 class="mb-0"><i class="bi bi-receipt-cutoff me-2"></i>Order Summary</h4>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            @foreach($cartItems as $item)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    {{ $item->product->name }} ({{ $item->size }})
                                    <small class="d-block text-muted">Quantity: {{ $item->quantity }}</small>
                                </div>
                                <span>€{{ number_format($item->product->price * $item->quantity, 2) }}</span>
                            </li>
                            @endforeach
                            <li class="list-group-item d-flex justify-content-between align-items-center fw-bold fs-5">
                                <span>Total</span>
                                <span>€{{ number_format($total, 2) }}</span>
                            </li>
                        </ul>
                    </div>
                    <div class="card-footer text-center">
                        <h5 class="mb-2">Payment Method</h5>
                        <div class="alert alert-info border-info">
                            <i class="bi bi-cash-coin me-2"></i>
                            <strong>Cash on Delivery (COD)</strong>
                        </div>
                        <p class="text-muted small">You will pay the courier upon receiving your order.</p>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg fw-bold">Place Order</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    @endif
</div>
@endsection