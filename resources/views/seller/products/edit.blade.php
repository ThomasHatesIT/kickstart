@extends('layouts.seller')

@section('title', 'Edit  Product')

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Edit Product </h4>
        </div>
        <div class="card-body">
            {{-- The form needs multipart/form-data for the file upload to work --}}
            <form action="{{ route('seller.products.update', $product)}} " method="POST" enctype="multipart/form-data">
                @csrf
             @method('PUT')
                <div class="row g-3">
                    <!-- Product Name -->
                    <div class="col-md-6">
                        <label for="name" class="form-label">Product Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" class="form-control @error('name') is-invalid @enderror" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

          
                   <!-- Category -->
                        <div class="col-md-6">
                            <label for="category_id" class="form-label">Category</label>
                            <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                <option value="" selected disabled>Select a Category...</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    <!-- Price -->
                    <div class="col-md-6">
                        <label for="price" class="form-label">Price (₱)</label>
                        <input type="number" name="price" id="price" value="{{ old('price', $product->price) }}" step="0.01" min="0" class="form-control @error('price') is-invalid @enderror" required>
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Stock -->
                    <div class="col-md-6">
                        <label for="stock" class="form-label">Stock Quantity</label>
                        <input type="number" name="stock" id="stock" value="{{ old('stock', $product->stock) }}" min="0" class="form-control @error('stock') is-invalid @enderror" required>
                        @error('stock')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                     
                    <!-- Brand -->
                    <div class="col-md-6">
                        <label for="brand" class="form-label">Brand (e.g., Nike, Adidas)</label>
                        <input type="text" name="brand" id="brand" value="{{ old('brand', $product->brand) }}" class="form-control @error('brand') is-invalid @enderror">
                        @error('brand')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Color -->
                    <div class="col-md-6">
                        <label for="color" class="form-label">Color</label>
                        <input type="text" name="color" id="color" value="{{ old('color', $product->color) }}" class="form-control @error('color') is-invalid @enderror">
                        @error('color')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="col-12">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" rows="4" class="form-control @error('description') is-invalid @enderror" required>{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Primary Image -->
                                                        {{-- In resources/views/seller/products/edit.blade.php --}}

                                            {{-- ... inside your form ... --}}

                                            <!-- START: Display Current Image and Add Remove Option -->
                                            <div class="col-12 mb-3">
                                                <label class="form-label">Current Primary Image</label>
                                                <div>
                                                    @php
                                                        $primaryImage = $product->images->firstWhere('is_primary', true);
                                                    @endphp

                                                    @if($primaryImage)
                                                        <img src="{{ Storage::url($primaryImage->image_path) }}" 
                                                            alt="{{ $product->name }}" 
                                                            class="img-thumbnail" 
                                                            style="max-width: 200px; max-height: 200px;">
                                                        
                                                        {{-- NEW: Add the remove checkbox --}}
                                                        <div class="form-check text-danger mt-2">
                                                            <input class="form-check-input" type="checkbox" name="remove_primary_image" id="remove_primary_image" value="1">
                                                            <label class="form-check-label" for="remove_primary_image">
                                                                <strong>Remove this image</strong>
                                                            </label>
                                                        </div>
                                                        
                                                    @else
                                                        <span class="text-muted">No primary image uploaded.</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <!-- END: Display Current Image -->

                                            <!-- Your existing file input -->
                                            <div class="col-12">
                                                <label for="primary_image" class="form-label">Upload New Primary Image (Optional)</label>
                                                <input type="file" name="primary_image" id="primary_image" class="form-control @error('primary_image') is-invalid @enderror">
                                                <div class="form-text">Leave blank to keep the current image. PNG, JPG, WEBP up to 2MB.</div>
                                                @error('primary_image')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                </div>

                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-primary px-4">
                        Edit

                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection