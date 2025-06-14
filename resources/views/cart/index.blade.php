{{-- resources/views/cart/index.blade.php --}}
@extends('layouts.app')

@section('content')

<div class="container my-5">
    <h1 class="mb-4">Your Shopping Cart</h1>

    @include('partials._alerts')

    @if($cartItems->isEmpty())
        <div class="alert alert-info" role="alert">
            Your cart is empty. <a href="" class="alert-link">Start shopping!</a>
        </div>
    @else
        <div class="row">
            {{-- Cart Items Column --}}
            <div class="col-lg-8">
                @foreach($cartItems as $item)
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row align-items-center">
                       
{{-- In resources/views/cart/index.blade.php --}}

<div class="col-md-2">
    @php
        // Call the new, non-conflicting relationship method
        $imageModel = $item->product->primaryImageModel;
    @endphp

    {{-- 
        The rest of the logic works perfectly now, because $imageModel is a guaranteed object.
    --}}
    @if ($imageModel->image_path === 'images/default-product.png')
        <img src="{{ asset($imageModel->image_path) }}" class="img-fluid" alt="{{ $item->product->name }}">
    @else
        <img src="{{ Storage::url($imageModel->image_path) }}" class="img-fluid" alt="{{ $item->product->name }}">
    @endif
</div>
                            <div class="col-md-4">
                                <h5>{{ $item->product->name }}</h5>
                                <p class="mb-0 text-muted">Size: {{ $item->size }}</p>
                                <p class="mb-0 text-muted">Price: ₱{{ number_format($item->product->price, 2) }}</p>
                            </div>
                            <div class="col-md-3">
                                {{-- Update Quantity Form --}}
                                <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-flex">
                                    @csrf
                                    @method('PATCH')
                                    <input type="number" name="quantity" class="form-control form-control-sm me-2" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock }}">
                                    <button type="submit" class="btn btn-outline-secondary btn-sm">Update</button>
                                </form>
                            </div>
                            <div class="col-md-2">
                                <strong>₱{{ number_format($item->product->price * $item->quantity, 2) }}</strong>
                            </div>
                            <div class="col-md-1 text-end">
                                {{-- Remove Item Form --}}
                                <form action="{{ route('cart.destroy', $item->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">×</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Order Summary Column --}}
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Order Summary</h5>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Subtotal
                                <span>₱{{ number_format($total, 2) }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Shipping
                                <span>Free</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center fw-bold">
                                Total
                                <span>₱{{ number_format($total, 2) }}</span>
                            </li>
                        </ul>
                        <a href="{{ route('checkout.index') }}" class="btn btn-primary w-100 mt-3">Proceed to Checkout</a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection