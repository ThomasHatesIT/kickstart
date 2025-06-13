@extends('layouts.app')

@section('title', 'Welcome to KickStart')

@section('content')
<!-- Hero Carousel Section -->
<div id="heroCarousel" class="carousel slide mb-5" data-bs-ride="carousel" data-bs-interval="5000">
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
    </div>
  <div class="carousel-inner">
        <!-- Slide 1 -->
        <div class="carousel-item active">
            <div class="hero-slide d-flex align-items-center justify-content-center text-white position-relative" 
                 style="background: url('{{ asset('images/carousel/shoes1.jpg') }}') center/cover no-repeat; min-height: 500px;">
                <div class="hero-overlay position-absolute top-0 start-0 w-100 h-100" 
                     style="background: rgba(0,0,0,0.5);"></div>
                <div class="container text-center position-relative z-index-1">
                    <h1 class="display-4 fw-bold mb-3 animate__animated animate__fadeInUp">
                        Find Your Next Perfect Pair
                    </h1>
                    <p class="lead mb-4 animate__animated animate__fadeInUp animate__delay-1s">
                        High-quality shoes from the best sellers, curated for you.
                    </p>
                    <a href="" 
                       class="btn btn-light btn-lg px-5 py-3 animate__animated animate__fadeInUp animate__delay-2s">
                        Shop Now
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Slide 2 -->
        <div class="carousel-item">
            <div class="hero-slide d-flex align-items-center justify-content-center text-white position-relative" 
                 style="background: url('{{ asset('images/carousel/shoes6.jpg') }}') center/cover no-repeat; min-height: 500px;">
                <div class="hero-overlay position-absolute top-0 start-0 w-100 h-100" 
                     style="background: rgba(0,0,0,0.5);"></div>
                <div class="container text-center position-relative z-index-1">
                    <h1 class="display-4 fw-bold mb-3">
                        Step Into Style
                    </h1>
                    <p class="lead mb-4">
                        Discover premium footwear that combines comfort with fashion.
                    </p>
                    <a href="" 
                       class="btn btn-warning btn-lg px-5 py-3">
                        Explore Collection
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Slide 3 -->
        <div class="carousel-item">
            <div class="hero-slide d-flex align-items-center justify-content-center text-white position-relative" 
                 style="background: url('{{ asset('images/carousel/shoes5.jpg') }}') center/cover no-repeat; min-height: 500px;">
                <div class="hero-overlay position-absolute top-0 start-0 w-100 h-100" 
                     style="background: rgba(0,0,0,0.5);"></div>
                <div class="container text-center position-relative z-index-1">
                    <h1 class="display-4 fw-bold mb-3">
                        Quality You Can Trust
                    </h1>
                    <p class="lead mb-4">
                        Every pair is carefully selected for durability and style.
                    </p>
                    <a href="" 
                       class="btn btn-success btn-lg px-5 py-3">
                        Shop Quality
                    </a>
                </div>
            </div>
        </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>

<!-- Featured Products Section -->
<div class="container my-5">
    <div class="row">
        <div class="col-12 text-center mb-5">
            <h2 class="display-5 fw-bold mb-3">New Arrivals</h2>
            <p class="lead text-muted">Check out the latest and greatest in our collection.</p>
        </div>
    </div>
    
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
        {{-- The loop is now perfect. It passes each product to the partial. --}}
        @forelse($featuredProducts as $product)
            <div class="col">
                {{-- This partial now contains all the display logic, including stars --}}
                @include('partials._product_card', ['product' => $product])
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="bi bi-box-open fa-4x text-muted mb-3"></i>
                    </div>
                    <h4 class="text-muted">No Featured Products Available</h4>
                    <p class="text-muted mb-4">We're working hard to bring you amazing products. Check back soon!</p>
                    <a href="" class="btn btn-primary">
                        Browse All Products
                    </a>
                </div>
            </div>
        @endforelse
    </div>
    
    @if($featuredProducts->count() > 0)
        <div class="row mt-5">
            <div class="col-12 text-center">
                <a href="" class="btn btn-outline-primary btn-lg">
                    View All Products
                </a>
            </div>
        </div>
    @endif
</div>

<!-- Features Section -->
<div class="bg-light py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="text-center p-4 h-100">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-shipping-fast fa-3x text-primary"></i>
                    </div>
                    <h4 class="mb-3">Fast Shipping</h4>
                    <p class="text-muted">Get your orders delivered to your doorstep in no time with our express delivery service.</p>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="text-center p-4 h-100">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-shield-alt fa-3x text-success"></i>
                    </div>
                    <h4 class="mb-3">Secure Payments</h4>
                    <p class="text-muted">Your transactions are safe with our secure COD system and trusted payment gateways.</p>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="text-center p-4 h-100">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-headset fa-3x text-info"></i>
                    </div>
                    <h4 class="mb-3">24/7 Support</h4>
                    <p class="text-muted">Our dedicated team is here to help you around the clock with any questions or concerns.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Newsletter Section -->
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="bg-primary text-white rounded-3 p-5 text-center">
                <h3 class="mb-3">Stay Updated</h3>
                <p class="mb-4">Subscribe to our newsletter and be the first to know about new arrivals and exclusive offers.</p>
                <form class="row g-2 justify-content-center">
                    <div class="col-md-6">
                        <input type="email" class="form-control" placeholder="Enter your email">
                    </div>
                    <div class="col-md-auto">
                        <button type="submit" class="btn btn-light">Subscribe</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<style>
    .hero-slide {
        position: relative;
    }
    
    .feature-icon {
        transition: transform 0.3s ease;
    }
    
    .feature-icon:hover {
        transform: translateY(-5px);
    }
    
    .carousel-item {
        transition: transform 0.6s ease-in-out;
    }
    
    .product-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
</style>
@endpush

@push('scripts')
<script>
    // Initialize carousel with custom settings
    document.addEventListener('DOMContentLoaded', function() {
        var carousel = new bootstrap.Carousel(document.getElementById('heroCarousel'), {
            interval: 5000,
            wrap: true,
            pause: 'hover'
        });
    });
</script>
@endpush