<div class="card product-card h-100 shadow-lg border-0 rounded-3 overflow-hidden position-relative">
    <!-- Product Image Container -->
    <div class="image-container position-relative overflow-hidden">
        <a href="" class="d-block">
            {{-- Use the primary image relationship, with a fallback --}}
            <img src="{{ Storage::url($product->images->first()->image_path) }}" 
                 class="card-img-top product-image" 
                 alt="{{ $product->name }}" 
                 style="height: 250px; object-fit: cover; transition: transform 0.3s ease;">
        </a>
        <!-- Hover overlay -->
        <div class="image-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center opacity-0" 
             style="background: rgba(0,0,0,0.4); transition: opacity 0.3s ease;">
            <span class="text-white fw-bold">View Details</span>
        </div>
    </div>
    
    <div class="card-body d-flex flex-column p-4">
        <!-- Product Title -->
        <h5 class="card-title mb-3 lh-base">
            <a href="" class="text-decoration-none text-dark fw-semibold product-title-link">
                {{ $product->name }}
            </a>
        </h5>
        
        <!-- Star Rating Display -->
        <div class="rating-section mb-3">
            @if($product->reviews_count > 0)
                <div class="d-flex align-items-center">
                    <div class="stars me-2" style="color: #ffc107; font-size: 1rem;">
                        {{-- Use a precise loop based on the rounded average rating --}}
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= round($product->average_rating))
                                <i class="fas fa-star"></i>
                            @else
                                <i class="far fa-star"></i>
                            @endif
                        @endfor
                    </div>
                    <span class="rating-number fw-medium me-2" style="color: #6c757d; font-size: 0.9rem;">
                        {{ number_format($product->average_rating, 1) }}
                    </span>
                    <span class="review-count" style="color: #6c757d; font-size: 0.85rem;">
                        ({{ $product->reviews_count }} {{ $product->reviews_count === 1 ? 'review' : 'reviews' }})
                    </span>
                </div>
            @else
                <div class="no-reviews d-flex align-items-center" style="color: #6c757d; font-size: 0.9rem;">
                    <div class="stars me-2" style="color: #dee2e6;">
                        @for ($i = 1; $i <= 5; $i++)
                            <i class="far fa-star"></i>
                        @endfor
                    </div>
                    <span>No reviews yet</span>
                </div>
            @endif
        </div>

        <!-- Price and Add to Cart Section -->
        <div class="mt-auto">
            <div class="price-section mb-3">
                <p class="price mb-0 fw-bold" style="font-size: 1.5rem; color: #198754;">
                   ₱{{ number_format($product->price, 2) }}
                </p>
            </div>
            
          <!-- Using an <a> tag styled as a button -->
<a href="{{ route('show', $product) }}" 
   role="button"
   class="btn btn-primary w-100 py-2 fw-semibold rounded-2 add-to-cart-btn" 
   style="background: linear-gradient(135deg, #007bff 0%, #0056b3 100%); border: none; transition: all 0.3s ease;">
    <i class="fas fa-cart-plus me-2"></i>
    Add to Cart
</a>
        </div>
    </div>
</div>

<style>
/* Enhanced Product Card Styles */
.product-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    background: #fff;
}

.product-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.15) !important;
}

.product-card:hover .product-image {
    transform: scale(1.05);
}

.product-card:hover .image-overlay {
    opacity: 1 !important;
}

.product-title-link {
    transition: color 0.3s ease;
}

.product-title-link:hover {
    color: #007bff !important;
}

.add-to-cart-btn:hover {
    background: linear-gradient(135deg, #0056b3 0%, #004085 100%) !important;
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(0,123,255,0.3);
}

.add-to-cart-btn:active {
    transform: translateY(0);
}

/* Rating stars styling */
.stars i {
    margin-right: 2px;
}

/* Responsive adjustments */
@media (max-width: 576px) {
    .card-body {
        padding: 1rem !important;
    }
    
    .price {
        font-size: 1.25rem !important;
    }
}
</style>