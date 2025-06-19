@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Browse Our Products</h1>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
        @forelse ($products as $product)
            <div class="col">
                <div class="card h-100">
                    {{-- Since you are using storage:link, asset() will generate the correct public URL --}}
                    <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}" class="card-img-top product-card-img" alt="{{ $product->name }}">
                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="{{ route('products.show', $product) }}" class="text-decoration-none text-dark">{{ $product->name }}</a>
                        </h5>
                        <p class="card-text text-muted">{{ $product->category->name }}</p>
                        <h6 class="card-subtitle mb-2 fw-bold">${{ number_format($product->price, 2) }}</h6>
                    </div>
                    <div class="card-footer bg-transparent border-top-0">
                         <a href="{{ route('products.show', $product) }}" class="btn btn-primary w-100">View Details</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    No products found. Please check back later!
                </div>
            </div>
        @endforelse
    </div>

    {{-- Pagination Links --}}
    <div class="mt-4">
        {{ $products->links() }}
    </div>
</div>
@endsection