@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card text-center shadow-lg border-success">
                <div class="card-header bg-success text-white">
                    <h2 class="mb-0">Thank You For Your Order!</h2>
                </div>
                <div class="card-body p-5">
                    <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
                    <h4 class="card-title mt-4">Your order has been placed successfully.</h4>
                    <p class="card-text fs-5">
                        Your order number is: <strong class="text-primary">#{{ $order->order_number }}</strong>
                    </p>
                    <p class="text-muted">
                        We have sent a confirmation email with your order details. You can also track the status of your order in your account dashboard.
                    </p>
                    <div class="mt-4">
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Continue Shopping
                        </a>
                        <a href="" class="btn btn-primary">
                            <i class="bi bi-receipt"></i> View My Orders
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection