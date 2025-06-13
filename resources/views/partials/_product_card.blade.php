






<div class="card product-card h-100 shadow-sm border-0">
    <a href="">
        {{-- Use the primary image relationship, with a fallback --}}
        <img src="{{ Storage::url($product->images->first()->image_path) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
    </a>
    <div class="card-body d-flex flex-column">
        <h5 class="card-title">
            <a href="" class="text-decoration-none text-dark">{{ $product->name }}</a>
        </h5>
        
        <!-- Star Rating Display -->
        <div class="d-flex align-items-center mb-2">
            @if($product->reviews_count > 0)
                <div class="text-warning me-2">
                    {{-- Use a precise loop based on the rounded average rating --}}
                    @for ($i = 1; $i <= 5; $i++)
                        @if ($i <= round($product->average_rating))
                            <i class="fas fa-star"></i> <!-- Filled star -->
                        @else
                            <i class="far fa-star"></i> <!-- Empty star -->
                        @endif
                    @endfor
                </div>
                <span class="text-muted small">({{ $product->reviews_count }})</span>
            @else
                <span class="text-muted small">No reviews yet</span>
            @endif
        </div>

        <div class="mt-auto">
            <p class="card-text fs-5 fw-bold mb-2">${{ number_format($product->price, 2) }}</p>
            <a href="{{-- route('cart.add', $product->id) --}}" class="btn btn-primary w-100">Add to Cart</a>
        </div>
    </div>
</div>