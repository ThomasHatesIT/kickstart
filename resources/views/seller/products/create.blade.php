@extends('layouts.seller')

@section('title', 'Add New Product')

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Create a New Product Listing</h4>
        </div>
        <div class="card-body">
            {{-- The form needs multipart/form-data for the file upload to work --}}
            <form action="{{ route('seller.products.store')}} " method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-3">
                    <!-- Product Name -->
                    <div class="col-md-6">
                        <label for="name" class="form-label">Product Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required>
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
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                        <input type="number" name="price" id="price" value="{{ old('price') }}" step="0.01" min="0" class="form-control @error('price') is-invalid @enderror" required>
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Stock -->
                    <div class="col-md-6">
                        <label for="stock" class="form-label">Stock Quantity</label>
                        <input type="number" name="stock" id="stock" value="{{ old('stock') }}" min="0" class="form-control @error('stock') is-invalid @enderror" required>
                        @error('stock')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                     
                    <!-- Brand -->
                    <div class="col-md-6">
                        <label for="brand" class="form-label">Brand (e.g., Nike, Adidas)</label>
                        <input type="text" name="brand" id="brand" value="{{ old('brand') }}" class="form-control @error('brand') is-invalid @enderror">
                        @error('brand')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Color -->
                    <div class="col-md-6">
                        <label for="color" class="form-label">Color</label>
                        <input type="text" name="color" id="color" value="{{ old('color') }}" class="form-control @error('color') is-invalid @enderror">
                        @error('color')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="col-12">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" rows="4" class="form-control @error('description') is-invalid @enderror" required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Primary Image -->
                    <div class="col-12">
                        <label for="primary_image" class="form-label">Primary Product Image</label>
                        <input type="file" name="primary_image" id="primary_image" class="form-control @error('primary_image') is-invalid @enderror" required>
                        <div class="form-text">PNG, JPG, WEBP up to 2MB.</div>
                         @error('primary_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-primary px-4">
                        Submit for Approval
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection