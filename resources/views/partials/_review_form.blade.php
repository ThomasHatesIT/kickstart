@auth
    {{-- A styled container for the review form --}}
    <div class="review-form-container bg-light p-4 p-md-5 rounded-3 shadow-sm">
        <div class="text-center mb-4">
            <h3 class="fw-bold">Leave a Review</h3>
            <p class="text-muted">Share your thoughts with other customers!</p>
        </div>

        <form action="" method="POST">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            
         

            {{-- Comment Section --}}
            <div class="mb-4">
                <label for="comment" class="form-label fs-5 fw-medium">Your Review (Optional)</label>
                <textarea class="form-control form-control-lg" id="comment" name="comment" rows="4" placeholder="Tell us more about the product's quality, fit, and your experience..."></textarea>
            </div>

            {{-- Submit Button --}}
            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg fw-bold">Submit Review</button>
            </div>
        </form>
    </div>

@else
    <div class="text-center bg-light p-4 rounded-3">
        <p class="mb-0 fs-5 text-muted">You must be <a href="{{ route('login') }}">logged in</a> and have purchased this product to leave a review.</p>
    </div>
@endauth

{{--
    IMPORTANT: Place this @push('styles') block in the main view file
    that includes this partial (e.g., resources/views/products/show.blade.php)
    to avoid it being pushed multiple times if the partial is used in a loop.
    If this partial is only used once on the page, leaving it here is fine.
--}}
@push('styles')
<style>
    /* Main container for the review form */
    .review-form-container {
        border: 1px solid #dee2e6;
    }

    /* Interactive Star Rating CSS */
    .star-rating {
        display: flex;
        flex-direction: row-reverse; /* This is the secret sauce! */
        justify-content: center;
        gap: 0.2rem;
    }

    /* Hide the default radio buttons */
    .star-rating input[type="radio"] {
        display: none;
    }

    /* Style the star labels */
    .star-rating .star {
        font-size: 2.5rem; /* Make stars bigger */
        color: #e0e0e0; /* Default empty star color */
        cursor: pointer;
        transition: color 0.2s ease-in-out;
    }

    /* Color the stars on hover */
    .star-rating:not(:checked) > .star:hover,
    .star-rating:not(:checked) > .star:hover ~ .star {
        color: #ffc107; /* Bootstrap's warning yellow */
    }

    /* Color the stars when a radio button is checked */
    .star-rating > input:checked ~ .star {
        color: #ffc107;
    }
    
    /* Style for the textarea to give it a better feel */
    .form-control:focus {
        border-color: var(--bs-primary);
        box-shadow: 0 0 0 0.25rem rgba(var(--bs-primary-rgb), 0.25);
    }
    
    /* A nice little transition for the submit button */
    .btn-primary {
        transition: all 0.3s ease;
    }
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
</style>
@endpush