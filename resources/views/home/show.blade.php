@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="bg-light py-3">
    <div class="container">
        {{-- Breadcrumbs for better navigation --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent p-0 m-0 small">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                <li class="breadcrumb-item"><a href="" class="text-decoration-none">Products</a></li>
                <li class="breadcrumb-item active text-muted" aria-current="page">{{ $product->name }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container my-5">
    <div class="row g-4">
        <!-- ===== Column 1: Product Image Gallery ===== -->
        <div class="col-lg-7">
            <div class="position-sticky" style="top: 2rem;">
                <div class="card product-gallery-card border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="card-body p-4 text-center bg-white">
                        <img src="{{ $product->primaryImage ? Storage::url($product->images->first()->image_path) : asset('images/default_product.png') }}"
                             alt="{{ $product->name }}" id="mainProductImage" class="img-fluid rounded-3">
                    </div>
                </div>
                @if($product->images->count() > 1)
                    <div class="d-flex justify-content-center mt-4 flex-wrap" id="thumbnail-gallery">
                        @foreach($product->images as $image)
                            <a href="#" class="thumbnail-item mx-1 mb-2 p-2 border rounded-4 bg-white shadow-sm @if($image->is_primary) active @endif" data-image-src="{{ Storage::url($image->image_path) }}">
                                <img src="{{ Storage::url($image->image_path) }}" alt="Thumbnail of {{ $product->name }}" class="img-fluid rounded-2">
                            </a>
                        @endforeach
                    </div>
                @endif

                <!-- ===== Description Section Below Image ===== -->
                <div class="card border-0 shadow-lg rounded-4 mt-4">
                    <div class="card-body p-4">
                        <h4 class="mb-3 fw-bold text-dark">
                            <i class="bi bi-info-circle me-2 text-primary"></i>About This Item
                        </h4>
                        <div class="text-muted lh-lg" style="white-space: pre-wrap;">{{ $product->description }}</div>
                    </div>
                </div>

              <!-- ===== Specifications Section Below Description ===== -->
<div class="card border-0 shadow-lg rounded-4 mt-4">
    <div class="card-body p-4">
        <h4 class="mb-4 fw-bold text-dark">
            <i class="bi bi-gear me-2 text-primary"></i>Product Details
        </h4>
        
        <div class="specifications-grid">
            <!-- Brand -->
            <div class="spec-item d-flex align-items-center justify-content-between py-3 border-bottom">
                <div class="spec-label d-flex align-items-center text-muted fw-medium">
                    <i class="bi bi-tag-fill me-3 text-primary"></i>
                    <span>Brand</span>
                </div>
                <div class="spec-value fw-semibold text-dark">
                    {{ $product->brand ?? 'N/A' }}
                </div>
            </div>

            <!-- Color -->
            <div class="spec-item d-flex align-items-center justify-content-between py-3 border-bottom">
                <div class="spec-label d-flex align-items-center text-muted fw-medium">
                    <i class="bi bi-palette-fill me-3 text-primary"></i>
                    <span>Color</span>
                </div>
                <div class="spec-value fw-semibold text-dark">
                    {{ $product->color ?? 'N/A' }}
                </div>
            </div>

            <!-- Material -->
            <div class="spec-item d-flex align-items-center justify-content-between py-3 border-bottom">
                <div class="spec-label d-flex align-items-center text-muted fw-medium">
                    <i class="bi bi-gem me-3 text-primary"></i>
                    <span>Material</span>
                </div>
                <div class="spec-value fw-semibold text-dark">
                    {{ $product->material ?? 'N/A' }}
                </div>
            </div>

            <!-- Category -->
            <div class="spec-item d-flex align-items-center justify-content-between py-3 border-bottom">
                <div class="spec-label d-flex align-items-center text-muted fw-medium">
                    <i class="bi bi-bookmark-fill me-3 text-primary"></i>
                    <span>Category</span>
                </div>
                <div class="spec-value fw-semibold text-dark">
                    {{ $product->category->name ?? 'N/A' }}
                </div>
            </div>

            <!-- Sold By -->
            <div class="spec-item d-flex align-items-center justify-content-between py-3 border-bottom">
                <div class="spec-label d-flex align-items-center text-muted fw-medium">
                    <i class="bi bi-shop me-3 text-primary"></i>
                    <span>Sold By</span>
                </div>
                <div class="spec-value fw-semibold text-dark">
                    {{ $product->seller->name ?? 'N/A' }} ({{ $product->seller->email }})
                </div>
            </div>

            <!-- Stock -->
            <div class="spec-item d-flex align-items-center justify-content-between py-3">
                <div class="spec-label d-flex align-items-center text-muted fw-medium">
                    <i class="bi bi-box-seam-fill me-3 text-primary"></i>
                    <span>Stock</span>
                </div>
                <div class="spec-value fw-semibold text-dark">
                    {{ $product->stock }} units
                </div>
            </div>
        </div>
    </div>
</div>
            </div>
        </div>

        <!-- ===== Column 2: Product Details, Info & "Buy Box" ===== -->
        <div class="col-lg-5">
            <div class="product-details-container">
                {{-- Brand and Product Name --}}
                <div class="mb-4">
                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-normal mb-3">{{ $product->brand }}</span>
                    <h1 class="display-6 fw-bold text-dark mb-0 lh-sm">{{ $product->name }}</h1>
                </div>

                {{-- Rating and Reviews --}}
                <div class="d-flex align-items-center mb-4">
                    <div class="text-warning me-3 fs-5">
                        @for ($i = 1; $i <= 5; $i++)
                            <i class="{{ $i <= round($product->average_rating) ? 'fas' : 'far' }} fa-star"></i>
                        @endfor
                    </div>
                    <a href="#reviews-section" class="text-muted text-decoration-none fw-medium">
                        <span class="me-1">{{ number_format($product->average_rating, 1) }}</span>
                        <span class="text-primary">({{ $product->reviews_count }} reviews)</span>
                    </a>
                </div>

                {{-- Price --}}
                <div class="mb-4">
                    <span class="display-4 fw-bold text-success">₱{{ number_format($product->price, 2) }}</span>
                </div>

                {{-- Short description --}}
                <div class="mb-4">
                    <p class="text-muted fs-6 lh-base">{{ Str::limit($product->description, 150) }}</p>
                </div>

                <!-- Enhanced "Buy Box" -->
                <div class="card border-0 shadow-lg rounded-4 bg-gradient" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                    <div class="card-body p-4">

                        @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- This snippet shows general success/error messages from ->with() --}}
@include('partials._alerts')

                     
@auth
    <form action="{{ route('cart.store') }}" method="POST" id="addToCartForm">
        @csrf
        <input type="hidden" name="product_id" value="{{ $product->id }}">
        
        {{-- Size Selector --}}
        @if(!empty($product->sizes))
            <div class="mb-4">
                <label class="form-label fw-bold text-dark mb-3">
                    <i class="bi bi-rulers me-2"></i>Select Size:
                    <span id="size-error" class="text-danger small ms-2 fw-medium"></span>
                </label>
                <div class="d-flex flex-wrap gap-2" id="size-group">
                    @foreach($product->sizes as $size)
                        <input type="radio" class="btn-check" name="size" id="size-{{ $loop->index }}" value="{{ $size }}" autocomplete="off">
                        <label class="btn btn-outline-dark rounded-3 px-3 py-2 fw-medium" for="size-{{ $loop->index }}">{{ $size }}</label>
                    @endforeach
                </div>
            </div>
        @else
            <input type="hidden" name="size" value="standard">
        @endif

        {{-- ======================================================= --}}
        {{-- === START: NEW QUANTITY SELECTOR BLOCK === --}}
        {{-- ======================================================= --}}
        @if($product->stock > 0)
            <div class="mb-4">
                <label for="quantity" class="form-label fw-bold text-dark mb-3">
                    <i class="bi bi-box-seam me-2"></i>Quantity:
                </label>
                <div class="input-group input-group-lg" style="max-width: 200px;">
                    <button class="btn btn-outline-secondary" type="button" id="quantity-minus">-</button>
                    <input type="number" name="quantity" id="quantity" class="form-control text-center fw-bold" 
                           value="1" min="1" max="{{ $product->stock }}" required>
                    <button class="btn btn-outline-secondary" type="button" id="quantity-plus">+</button>
                </div>
            </div>
        @endif
        {{-- ======================================================= --}}
        {{-- === END: NEW QUANTITY SELECTOR BLOCK === --}}
        {{-- ======================================================= --}}


        {{-- Stock Status & Add to Cart Button --}}
        @if($product->stock > 0)
            <div class="d-grid mb-3">
                <button type="submit" class="btn btn-primary btn-lg fw-bold py-3 rounded-3 shadow-sm">
                    <i class="bi bi-cart-plus-fill me-2"></i>Add to Cart
                </button>
            </div>
            <div class="text-center">
                @if($product->stock < 10)
                    <div class="badge bg-warning text-dark px-3 py-2 rounded-pill">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i>Only {{ $product->stock }} left in stock!
                    </div>
                @else
                    <div class="badge bg-success px-3 py-2 rounded-pill">
                        <i class="bi bi-check-circle-fill me-1"></i>In Stock
                    </div>
                @endif
            </div>
        @else
            <div class="alert alert-danger border-0 rounded-3 text-center shadow-sm" role="alert">
                <i class="bi bi-x-circle-fill me-2"></i>
                <strong>Out of Stock</strong>
            </div>
        @endif
    </form>
@endauth

@guest
    {{-- Show a clear call-to-action for users who are not logged in --}}
    <div class="d-grid">
         <a href="{{ route('login') }}" class="btn btn-primary btn-lg fw-bold py-3 rounded-3 shadow-sm">
            <i class="bi bi-box-arrow-in-right me-2"></i>Login to Purchase
        </a>
    </div>
@endguest
                    </div>
                </div>

                {{-- Key Features --}}
                <div class="mt-4">
                    <div class="row g-3 text-center">
                        <div class="col-4">
                            <div class="p-3 bg-light rounded-3">
                                <i class="bi bi-truck fs-4 text-primary mb-2 d-block"></i>
                                <small class="text-muted fw-medium">Free Shipping</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-3 bg-light rounded-3">
                                <i class="bi bi-arrow-clockwise fs-4 text-primary mb-2 d-block"></i>
                                <small class="text-muted fw-medium">Easy Returns</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-3 bg-light rounded-3">
                                <i class="bi bi-shield-check fs-4 text-primary mb-2 d-block"></i>
                                <small class="text-muted fw-medium">Warranty</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== Reviews Section ===== -->
    <div class="row mt-5 pt-5" id="reviews-section">
        <div class="col-12">
            <div class="card bg-white rounded-4 shadow-lg border-0">
                <div class="card-body p-5">
                    <h4 class="mb-4 fw-bold text-dark">
                        <i class="bi bi-star me-2 text-primary"></i>Customer Reviews 
                        <span class="badge bg-primary rounded-pill ms-2">{{ $product->reviews_count }}</span>
                    </h4>
                    @include('partials._review_form', ['product' => $product])
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .product-gallery-card #mainProductImage {
        max-height: 450px;
        object-fit: contain;
        transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }
    
    .product-gallery-card:hover #mainProductImage {
        transform: scale(1.03);
    }
    
    .thumbnail-item {
        transition: all 0.3s ease;
        cursor: pointer;
        opacity: 0.7;
        border: 2px solid transparent !important;
        width: 90px;
        height: 90px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .thumbnail-item:hover {
        opacity: 1;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15) !important;
        border-color: var(--bs-primary) !important;
    }
    
    .thumbnail-item.active {
        opacity: 1;
        border-color: var(--bs-primary) !important;
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.25);
    }
    
    .thumbnail-item img {
        width: 70px;
        height: 70px;
        object-fit: cover;
    }
    
    .nav-pills .nav-link {
        color: var(--bs-secondary);
        font-weight: 500;
        padding: 0.8rem 1.5rem;
        transition: all 0.3s ease;
        border: none;
    }
    
    .nav-pills .nav-link:hover {
        background-color: rgba(13, 110, 253, 0.1);
        color: var(--bs-primary);
        transform: translateY(-1px);
    }
    
    .nav-pills .nav-link.active {
        background-color: var(--bs-primary);
        color: white;
        box-shadow: 0 2px 8px rgba(13, 110, 253, 0.3);
    }
    
    .btn-check:checked + .btn-outline-dark {
        background-color: var(--bs-dark);
        color: white;
        border-color: var(--bs-dark);
        transform: scale(1.05);
    }
    
    .btn-outline-dark {
        transition: all 0.2s ease;
        border-width: 2px;
    }
    
    .btn-outline-dark:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }
    
    .btn-primary {
        transition: all 0.3s ease;
        background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
        border: none;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(13, 110, 253, 0.4);
        background: linear-gradient(135deg, #0b5ed7 0%, #0a58ca 100%);
    }
    
    .card {
        transition: all 0.3s ease;
    }
    
    .badge {
        font-size: 0.8em;
        font-weight: 500;
    }
    
    .display-6 {
        line-height: 1.1;
    }
    
    .bg-gradient {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%) !important;
    }
    
    .tab-content {
        border: none;
        min-height: 300px;
    }
    
    .border-bottom {
        border-color: #e9ecef !important;
    }
    
    .position-sticky {
        position: -webkit-sticky;
        position: sticky;
    }
    .specifications-grid {
    max-width: 100%;
}

.spec-item {
    transition: background-color 0.2s ease;
    margin: 0 -1rem;
    padding-left: 1rem !important;
    padding-right: 1rem !important;
    border-color: #e9ecef !important;
}

.spec-item:hover {
    background-color: #f8f9fa;
    border-radius: 0.5rem;
}

.spec-item:last-child {
    border-bottom: none !important;
}

.spec-label {
    flex: 0 0 auto;
    min-width: 140px;
}

.spec-label i {
    width: 20px;
    text-align: center;
    font-size: 1.1em;
}

.spec-value {
    flex: 1 1 auto;
    text-align: right;
    word-break: break-word;
}

@media (max-width: 576px) {
    .spec-item {
        flex-direction: column;
        align-items: flex-start !important;
        gap: 0.5rem;
    }
    
    .spec-value {
        text-align: left;
        margin-left: 3rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Image gallery functionality
    const mainImage = document.getElementById('mainProductImage');
    const gallery = document.getElementById('thumbnail-gallery');
    if (gallery) {
        gallery.addEventListener('click', function (e) {
            e.preventDefault();
            const link = e.target.closest('.thumbnail-item');
            if (!link) return;
            mainImage.src = link.dataset.imageSrc;
            gallery.querySelectorAll('.thumbnail-item').forEach(item => item.classList.remove('active'));
            link.classList.add('active');
        });
    }

    // Form validation for size selection
    const addToCartForm = document.getElementById('addToCartForm');
    const sizeError = document.getElementById('size-error');
    const hasSizes = document.querySelector('input[name="size"]');
    if (addToCartForm && hasSizes) {
        addToCartForm.addEventListener('submit', function (e) {
            const selectedSize = document.querySelector('input[name="size"]:checked');
            if (!selectedSize) {
                e.preventDefault();
                sizeError.textContent = 'Please select a size.';
            } else {
                sizeError.textContent = '';
            }
        });
    }
});


   // Wait for the document to be fully loaded
    document.addEventListener('DOMContentLoaded', function () {
        
        // Find the form, the size radio button group, and the error message span
        const addToCartForm = document.getElementById('addToCartForm');
        const sizeError = document.getElementById('size-error');
        const sizeGroup = document.getElementById('size-group');

        // This event listener triggers when the form is submitted
        addToCartForm.addEventListener('submit', function (event) {
            
            // Check if a radio button with the name 'size' is selected
            const selectedSize = document.querySelector('input[name="size"]:checked');

            // If no size is selected, and the size group exists on the page
            if (!selectedSize && sizeGroup) {
                // 1. Prevent the form from being sent to the server
                event.preventDefault(); 
                
                // 2. Display an error message
                sizeError.textContent = 'Please select a size.';
                
                // 3. Optional: Add a class to highlight the group
                sizeGroup.classList.add('border', 'border-danger', 'p-2', 'rounded-3');
            } else {
                // If a size is selected, clear any previous error message
                sizeError.textContent = '';
                if (sizeGroup) {
                    sizeGroup.classList.remove('border', 'border-danger', 'p-2', 'rounded-3');
                }
            }
        });
    });

   
    const quantityInput = document.getElementById('quantity');
    const minusBtn = document.getElementById('quantity-minus');
    const plusBtn = document.getElementById('quantity-plus');

    if (quantityInput && minusBtn && plusBtn) {
        minusBtn.addEventListener('click', function() {
            let currentValue = parseInt(quantityInput.value);
            // Ensure value doesn't go below the minimum allowed
            if (currentValue > parseInt(quantityInput.min)) {
                quantityInput.value = currentValue - 1;
            }
        });

        plusBtn.addEventListener('click', function() {
            let currentValue = parseInt(quantityInput.value);
            // Ensure value doesn't exceed the maximum allowed (stock)
            if (currentValue < parseInt(quantityInput.max)) {
                quantityInput.value = currentValue + 1;
            }
        });
    }




</script>
@endpush