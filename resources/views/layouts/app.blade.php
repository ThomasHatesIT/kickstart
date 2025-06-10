<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KickStart</title>

    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Use Vite to load assets -->
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>
<body>
    @include('partials._navbar')

    <main class="container py-4">
        @yield('content')
    </main>

    @include('partials._footer')

    <!-- Bootstrap JS Bundle CDN -->

</body>
</html>