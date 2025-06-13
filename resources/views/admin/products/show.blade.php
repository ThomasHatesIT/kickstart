@extends('layouts.admin')

@section('title', 'Product Details: ' . $product->name)

@section('content')
    <!-- Header Section with Gradient Background -->
    <div class="bg-gradient-primary text-white rounded-4 p-4 mb-4 shadow-lg">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="display-6 fw-bold mb-2">Product Details</h1>
                <p class="mb-0 opacity-75">Complete information about {{ $product->name }}</p>
            </div>
            <div class="d-flex gap-2">
              
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-light btn-lg">
                    <i class="bi bi-arrow-left me-2"></i> Back to List
                </a>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Main Product Information Card -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom-0 py-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                            <i class="bi bi-box-seam text-primary fs-4"></i>
                        </div>
                        <div>
                            <h3 class="mb-1 text-dark">{{ $product->name }}</h3>
                            <p class="text-muted mb-0">Product Information & Details</p>
                        </div>
                    </div>
                </div>
                
                <div class="card-body p-4">
                    <div class="row g-4">
                        <!-- Product Name -->
                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="form-label text-uppercase fw-semibold text-muted small mb-2">
                                    <i class="bi bi-tag me-1"></i> Product Name
                                </label>
                                <p class="fs-5 fw-medium text-dark mb-0">{{ $product->name }}</p>
                            </div>
                        </div>

                        <!-- Category -->
                       <!-- Category -->
<div class="col-md-6">
    <div class="info-item">
        <label class="form-label text-uppercase fw-semibold text-muted small mb-2 d-block">
            <i class="bi bi-folder me-1"></i> Category
        </label>
        <span class="badge bg-info bg-opacity-15 text-white fs-6 px-3 py-2 rounded-pill d-inline-block">
            {{ $product->category->name ?? 'N/A' }}
        </span>
    </div>
</div>

                        <!-- Price -->
                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="form-label text-uppercase fw-semibold text-muted small mb-2">
                                    <i class="bi bi-currency-dollar me-1"></i> Price
                                </label>
                                <p class="fs-3 fw-bold text-success mb-0">₱{{ number_format($product->price, 2) }}</p>
                            </div>
                        </div>

                        <!-- Stock -->
                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="form-label text-uppercase fw-semibold text-muted small mb-2">
                                    <i class="bi bi-boxes me-1"></i> Stock Quantity
                                </label>
                                <div class="d-flex align-items-center">
                                    <span class="fs-4 fw-bold me-2 
                                        @if($product->stock > 50) text-success 
                                        @elseif($product->stock > 10) text-warning 
                                        @else text-danger @endif">
                                        {{ $product->stock }}
                                    </span>
                                    <span class="badge 
                                        @if($product->stock > 50) bg-success 
                                        @elseif($product->stock > 10) bg-warning 
                                        @else bg-danger @endif">
                                        @if($product->stock > 50) In Stock 
                                        @elseif($product->stock > 10) Low Stock 
                                        @else Very Low @endif
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="form-label text-uppercase fw-semibold text-muted small mb-2">
                                    <i class="bi bi-info-circle me-1"></i> Status
                                </label>
                                <span class="badge fs-6 px-3 py-2 rounded-pill
                                    @if($product->status === 'approved') bg-success
                                    @elseif($product->status === 'pending') bg-warning text-dark
                                    @elseif($product->status === 'rejected') bg-danger
                                    @else bg-secondary @endif">
                                    {{ ucfirst($product->status ?? 'Unknown') }}
                                </span>
                            </div>
                        </div>

                        <!-- Brand -->
                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="form-label text-uppercase fw-semibold text-muted small mb-2">
                                    <i class="bi bi-award me-1"></i> Brand
                                </label>
                                <p class="fs-5 mb-0">{{ $product->brand ?? 'Not specified' }}</p>
                            </div>
                        </div>

                        <!-- Color -->
                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="form-label text-uppercase fw-semibold text-muted small mb-2">
                                    <i class="bi bi-palette me-1"></i> Color
                                </label>
                                <p class="fs-5 mb-0">{{ $product->color ?? 'Not specified' }}</p>
                            </div>
                        </div>

                        <!-- Available Sizes -->
                        <div class="col-12">
                            <div class="info-item">
                                <label class="form-label text-uppercase fw-semibold text-muted small mb-3">
                                    <i class="bi bi-rulers me-1"></i> Available Sizes
                                </label>
                                <div>
                                    @if($product->sizes && count($product->sizes) > 0)
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach ($product->sizes as $size)
                                                <span class="badge bg-dark bg-opacity-10 text-dark fs-6 px-3 py-2 rounded-3 border">{{ $size }}</span>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="alert alert-light border d-flex align-items-center" role="alert">
                                            <i class="bi bi-info-circle me-2"></i>
                                            <span class="fst-italic">No sizes specified for this product.</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="col-12">
                            <div class="info-item">
                                <label class="form-label text-uppercase fw-semibold text-muted small mb-3">
                                    <i class="bi bi-text-paragraph me-1"></i> Description
                                </label>
                                <div class="bg-light bg-opacity-50 border rounded-3 p-4" style="white-space: pre-wrap; line-height: 1.6;">
                                    {{ $product->description }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Image Card -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom-0 py-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-success bg-opacity-10 rounded-circle p-3 me-3">
                            <i class="bi bi-image text-success fs-4"></i>
                        </div>
                        <div>
                            <h5 class="mb-1 text-dark">Product Image</h5>
                            <p class="text-muted mb-0 small">Primary product photo</p>
                        </div>
                    </div>
                </div>
                
                <div class="card-body p-4 text-center">
                    @php
                        $primaryImage = $product->images->firstWhere('is_primary', true);
                    @endphp
                    @if($primaryImage)
                        <div class="position-relative d-inline-block">
                            <img src="{{ Storage::url($primaryImage->image_path) }}" 
                                 alt="{{ $product->name }}" 
                                 class="img-fluid rounded-4 shadow-sm border"
                                 style="max-height: 400px; object-fit: cover;">
                            <div class="position-absolute top-0 end-0 m-2">
                                <span class="badge bg-primary">Primary</span>
                            </div>
                        </div>
                        <p class="text-muted mt-3 mb-3 small">{{ $product->name }} - Primary Image</p>
                    @else
                        <div class="text-center py-5">
                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                                <i class="bi bi-image text-muted" style="font-size: 2.5rem;"></i>
                            </div>
                            <h6 class="mt-3 text-muted">No Image Available</h6>
                            <p class="text-muted small mb-3">No primary image has been uploaded for this product.</p>
                        </div>
                    @endif

                    {{-- Show Approve/Reject buttons only if status is pending --}}
                    @if($product->status === 'pending')
                        <div class="d-flex flex-column gap-3 mt-4">
                            <div class="alert alert-warning border-warning d-flex align-items-center" role="alert">
                                <i class="bi bi-clock-history me-2"></i>
                                <span class="fw-medium">This product is pending approval</span>
                            </div>
                            
                            <div class="d-flex gap-2 justify-content-center">
                                <form action="" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-success shadow-sm flex-fill" onclick="return confirm('Are you sure you want to approve this product?')">
                                        <i class="bi bi-check-circle me-2"></i> Approve Product
                                    </button>
                                </form>
                                <form action="" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-danger shadow-sm flex-fill" onclick="return confirm('Are you sure you want to reject this product?')">
                                        <i class="bi bi-x-circle me-2"></i> Reject Product
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Custom Styles for Enhanced Look -->
    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .info-item {
            padding: 1rem 0;
            border-bottom: 1px solid #f8f9fa;
        }
        
        .info-item:last-child {
            border-bottom: none;
        }
        
        .card {
            transition: all 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-2px);
        }
        
        .btn {
            transition: all 0.3s ease;
        }
        
        .btn:hover {
            transform: translateY(-1px);
        }
        
        .badge {
            font-weight: 500;
        }
        
        .img-fluid:hover {
            transform: scale(1.02);
            transition: transform 0.3s ease;
        }
    </style>
@endsection