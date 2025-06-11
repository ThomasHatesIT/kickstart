{{-- resources/views/layouts/seller.blade.php --}}
<!DOCTYPE html>
{{-- Add h-100 class to the HTML tag --}}
<html lang="en" class="h-100"> 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Seller Dashboard') - KickStart</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    {{-- We no longer need the extra style block, Bootstrap classes handle it --}}
</head>
{{-- Add d-flex and h-100 classes to the BODY tag --}}
<body class="d-flex h-100">

    {{-- This sidebar will now automatically stretch to the full height of the body --}}
    @include('partials._seller_sidebar')

    <!-- Main Content Area -->
    {{-- The 'flex-grow-1' class ensures this area takes up all remaining horizontal space --}}
    <main class="container-fluid p-4 flex-grow-1">
        <h1 class="h3 mb-4">@yield('title')</h1>
        
        @include('partials._alerts')
        
        @yield('content')
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>