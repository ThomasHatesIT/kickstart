{{-- resources/views/layouts/seller.blade.php --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Seller Dashboard') - KickStart</title>
    
    {{-- Link to Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Link to Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    {{-- You can add your custom CSS file here --}}
    {{-- <link rel="stylesheet" href="{{ asset('css/seller.css') }}"> --}}
</head>
<body>
    {{-- MAIN FLEX CONTAINER --}}
    {{--
        HERE IS THE FIX: Added `min-vh-100`
        This makes the container take up at least 100% of the viewport height.
    --}}
    <div class="d-flex min-vh-100">
        
        {{-- 1. THE SIDEBAR --}}
        {{-- It will now stretch to fill the full height of its parent --}}
        @include('partials._seller_sidebar')

        {{-- 2. THE MAIN CONTENT AREA --}}
        {{-- This will also stretch to the full height --}}
        <main class="flex-grow-1 p-4" style="background-color: #f8f9fa;">
                    {{-- Success Alert --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Error Alert --}}
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

            @yield('content')
        </main>
        
    </div>

    {{-- Link to Bootstrap JS (and Popper.js) for dropdowns, etc. --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>