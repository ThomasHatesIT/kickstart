@extends('layouts.app')
@section('title', 'Seller Verification')
@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form method="POST" action="{{ route('google.seller.documents.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="card shadow-sm">
                    <div class="card-body p-4 p-md-5">
                        <h2 class="card-title mb-2 text-center">Seller Verification</h2>
                        <p class="text-center text-muted mb-4">
                            Welcome, <strong>{{ session('google_user.name') }}</strong>! Please upload the required documents.
                        </p>

                        <div class="row g-4">
                            <div class="col-md-6">
                                <label for="profile_photo" class="form-label fw-bold">Profile Photo (Optional)</label>
                                <input type="file" class="form-control @error('profile_photo') is-invalid @enderror" name="profile_photo" id="profile_photo" accept="image/*">
                                @error('profile_photo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="face_photo" class="form-label fw-bold">Clear Photo of Your Face <span class="text-danger">*</span></label>
                                <input type="file" class="form-control @error('face_photo') is-invalid @enderror" name="face_photo" id="face_photo" accept="image/*" required>
                                @error('face_photo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="id_photo_front" class="form-label fw-bold">Photo of Government ID (Front) <span class="text-danger">*</span></label>
                                <input type="file" class="form-control @error('id_photo_front') is-invalid @enderror" name="id_photo_front" id="id_photo_front" accept="image/*" required>
                                @error('id_photo_front')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary btn-lg fw-bold">Submit for Approval</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection