{{-- resources/views/partials/_product_card.blade.php --}}
{{-- Expects a $product variable to be passed in --}}

<div class="col">
    <div class="card h-100 shadow-sm">
        {{-- Product Image --}}
        @if($product->image_path)
            <img src="{{ Storage::url($product->image_path) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
        @else
             <img src="{{ asset('images/placeholder.png') }}" class="card-img-top" alt="No image available" style="height: 200px; object-fit: cover;">
        @endif
        
        <div class="card-body">
            <h5 class="card-title">{{ $product->name }}</h5>
            <p class="card-text text-muted">
                ${{ number_format($product->price, 2) }}
            </p>
            
            {{-- Stock Status Badge --}}
            @if($product->stock > 0)
                <span class="badge bg-success">In Stock</span>
            @else
                <span class="badge bg-danger">Out of Stock</span>
            @endif
        </div>
        
        <div class="card-footer bg-white border-top-0">
            <a href="{{ route('products.show', $product->id) }}" class="btn btn-primary w-100">
                View Details
            </a>
        </div>
    </div>
</div>