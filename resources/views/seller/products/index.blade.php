{{-- resources/views/seller/products/index.blade.php --}}

@extends('layouts.seller')

@section('title', 'My Products')

@section('content')
    {{-- Header with Title and "Add New" Button --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 mb-0">Your Product Listings</h2>
        {{-- This route will take the seller to the create form --}}
        <a href="{{  route('seller.products.create') }} " class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Add New Product
        </a>
    </div>

    {{-- Products Table Card --}}
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Product</th>
                            <th scope="col">Price</th>
                            <th scope="col">Stock</th>
                            <th scope="col">Status</th>
                            <th scope="col" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr>
                                {{-- Product Name and Image --}}
                                <td>
                                    <div class="d-flex align-items-center">
                                        {{-- Use Storage::url() since you used storage:link --}}
                                        @if($product->images->isNotEmpty())
                                            <img class="rounded me-3" src="{{ Storage::url($product->images->first()->image_path) }}" alt="{{ $product->name }}" style="width: 48px; height: 48px; object-fit: cover;">
                                        @else
                                             <div class="rounded me-3 bg-light d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                                <i class="bi bi-image text-muted"></i>
                                             </div>
                                        @endif
                                        <div>
                                            <div class="fw-bold">{{ $product->name }}</div>
                                            <div class="text-muted small">{{ $product->brand ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                                
                                {{-- Price --}}
                                <td>₱{{ number_format($product->price, 2) }}</td>
                                
                                {{-- Stock --}}
                                <td>{{ $product->stock }}</td>

                                {{-- Status Badge --}}
                                <td>
                                    @if($product->status == 'approved')
                                        <span class="badge bg-success-subtle text-success-emphasis rounded-pill">
                                            Approved
                                        </span>
                                    @elseif($product->status == 'pending')
                                        <span class="badge bg-warning-subtle text-warning-emphasis rounded-pill">
                                            Pending
                                        </span>
                                    @else
                                         <span class="badge bg-danger-subtle text-danger-emphasis rounded-pill">
                                            Rejected
                                        </span>
                                    @endif
                                </td>

                                {{-- Action Buttons --}}
                                <td class="text-end">
                                    <a href="{{ route('seller.products.edit', $product) }}" class="btn btn-sm btn-outline-secondary me-2">Edit</a>
                                    
                                    <form action="{{ route('seller.products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    You have not added any products yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination Links --}}
        @if ($products->hasPages())
        <div class="card-footer">
            {{ $products->links() }}
        </div>
        @endif
    </div>
@endsection