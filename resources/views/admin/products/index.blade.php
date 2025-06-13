@extends('layouts.admin')

@section('title', 'Product Management')

@section('content')
    {{-- Header with Title --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 mb-0">Product Management</h2>
    </div>

    @include('partials._alerts')

    {{-- 1. Pending Approval Table Card --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-warning-subtle">
            <h3 class="h5 mb-0 d-flex align-items-center">
                <i class="bi bi-clock-history me-2"></i>
                Pending Approval
                <span class="badge bg-warning text-dark rounded-pill ms-2">{{ $pendingProducts->count() }}</span>
            </h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Product</th>
                            <th scope="col">Seller</th>
                            <th scope="col">Price</th>
                            <th scope="col" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pendingProducts as $product)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($product->images->isNotEmpty())
                                            <img class="rounded me-3" src="{{ Storage::url($product->images->first()->image_path) }}" alt="{{ $product->name }}" style="width: 48px; height: 48px; object-fit: cover;">
                                        @else
                                             <div class="rounded me-3 bg-light d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;"><i class="bi bi-image text-muted"></i></div>
                                        @endif
                                        <div>
                                            <a href="#" class="fw-bold text-decoration-none">{{ $product->name }}</a>
                                            <div class="text-muted small">{{ $product->brand ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $product->seller->name ?? 'N/A' }}</td>
                                <td>₱{{ number_format($product->price, 2) }}</td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        {{-- Note: Update the action to your actual approval route --}}
                                        <a href="{{ route('admin.products.show', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('Approve this product?');">
                                         
                                            <button type="submit" class="btn btn-sm btn-success" data-bs-toggle="tooltip" title="Approve">
                                                <i class="bi bi-check-lg"></i> Approve
                                            </button>
                                        </form>
                                        {{-- Note: Update the action to your actual rejection route --}}
                                        <a href="{{ route('admin.products.show', $product) }}" method="POST" class="d-inline ms-1" onsubmit="return confirm('Reject this product?');">
                                          
                                            <button type="submit" class="btn btn-sm btn-danger" data-bs-toggle="tooltip" title="Reject">
                                                <i class="bi bi-x-lg"></i> Reject
                                            </button>
                                        </form>
                                       
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    <i class="bi bi-check-circle-fill fs-3 d-block mb-2"></i>
                                    No products are currently pending approval. Great job!
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    {{-- 2. All Products Table Card --}}
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="h5 mb-0 d-flex align-items-center">
                 <i class="bi bi-box-seam me-2"></i>
                All Product Listings
            </h3>
        </div>
        <div class="card-body">
            {{-- Filter Form --}}
            <form method="GET" action="{{ route('admin.products.index') }}" class="mb-3">
                <div class="row g-2 align-items-center">
                    <div class="col-md-5">
                        <input type="text" name="search" class="form-control" placeholder="Search by product or seller name..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-4">
                        <select name="status" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100">Filter</button>
                            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary w-100">Clear</a>
                        </div>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Product</th>
                            <th scope="col">Seller</th>
                            <th scope="col">Price</th>
                            <th scope="col">Stock</th>
                            <th scope="col">Status</th>
                            <th scope="col" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($product->images->isNotEmpty())
                                            <img class="rounded me-3" src="{{ Storage::url($product->images->first()->image_path) }}" alt="{{ $product->name }}" style="width: 48px; height: 48px; object-fit: cover;">
                                        @else
                                             <div class="rounded me-3 bg-light d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;"><i class="bi bi-image text-muted"></i></div>
                                        @endif
                                        <div>
                                            <div class="fw-bold">{{ $product->name }}</div>
                                            <div class="text-muted small">{{ $product->brand ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $product->seller->name ?? 'N/A' }}</td>
                                <td>₱{{ number_format($product->price, 2) }}</td>
                                <td>{{ $product->stock }}</td>
                                <td>
                                    @if($product->status == 'approved')<span class="badge bg-success-subtle text-success-emphasis rounded-pill">Approved</span>@endif
                                    @if($product->status == 'pending')<span class="badge bg-warning-subtle text-warning-emphasis rounded-pill">Pending</span>@endif
                                    @if($product->status == 'rejected')<span class="badge bg-danger-subtle text-danger-emphasis rounded-pill">Rejected</span>@endif
                                </td>
                                <td class="text-end">
                                    {{-- General Actions --}}
                                    <a href="{{ route('admin.products.show', $product) }}" class="btn btn-sm btn-outline-secondary">View</a>
                                    {{-- You could add Edit/Delete for admins here too if desired --}}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    No products match the current filters.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($products->hasPages())
        <div class="card-footer">
            {{ $products->links() }}
        </div>
        @endif
    </div>
@endsection