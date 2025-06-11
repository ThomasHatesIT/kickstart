@extends('layouts.app')

@section('title', 'Welcome to KickStart')

@section('content')




<!-- Hero Section -->
<div class="container-fluid px-0">
    <div class="bg-dark text-white text-center py-5" style="background: url('https://images.unsplash.com/photo-1542291026-7eec264c27ff?q=80&w=2070&auto=format&fit=crop') no-repeat center center; background-size: cover; min-height: 60vh; display: flex; align-items: center; justify-content: center;">
        <div class="bg-dark bg-opacity-50 p-5 rounded">
            <h1 class="display-4 fw-bold">Find Your Next Perfect Pair</h1>
            <p class="lead my-3">High-quality shoes from the best sellers, curated for you.</p>
            <a href="" class="btn btn-primary btn-lg mt-3">Shop Now <i class="fas fa-arrow-right ms-2"></i></a>
        </div>
    </div>
</div>

<!-- Featured Products Section -->
<div class="container my-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold">New Arrivals</h2>
        <p class="text-muted">Check out the latest and greatest in our collection.</p>
    </div>

    <div class="row g-4">
        
            <div class="col-md-6 col-lg-3">
                {{-- Here we include the reusable product card partial --}}
                @include('partials._product_card')
            </div>
      
            <div class="col">
                <p class="text-center">No featured products available at the moment.</p>
            </div>

    </div>
</div>

<!-- Features Section -->
<div class="container my-5 py-5 bg-light rounded">
    <div class="row text-center">
        <div class="col-md-4">
            <div class="p-4">
                <i class="fas fa-truck-fast fa-3x text-primary mb-3"></i>
                <h4 class="fw-bold">Fast Shipping</h4>
                <p class="text-muted">Get your orders delivered to your doorstep in no time.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-4">
                <i class="fas fa-shield-alt fa-3x text-primary mb-3"></i>
                <h4 class="fw-bold">Secure Payments</h4>
                <p class="text-muted">Your transactions are safe with our COD system.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-4">
                <i class="fas fa-headset fa-3x text-primary mb-3"></i>
                <h4 class="fw-bold">24/7 Support</h4>
                <p class="text-muted">Our team is here to help you around the clock.</p>
            </div>
        </div>
    </div>
</div>

@endsection