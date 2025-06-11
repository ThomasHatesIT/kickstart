<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Seller Dashboard') - {{ config('app.name', 'KickStart') }}</title>

    <!-- Scripts (Using Tailwind CDN for immediate styling) -->
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- This would be your Vite assets in a real setup --}}
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
</head>
<body class="font-sans antialiased bg-gray-100">

    <div class="flex h-screen bg-gray-200">
        <!-- Sidebar -->
        {{-- The seller-specific sidebar navigation will be included here --}}
        @include('partials._seller_sidebar')

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Bar -->
            <header class="flex justify-between items-center p-4 bg-white border-b-2 border-gray-200">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-800">@yield('title', 'Seller Dashboard')</h1>
                </div>
                <div class="text-right">
                     <p class="text-gray-700">
                        Welcome,
                        {{-- This will show the logged-in seller's name once auth is set up --}}
                        {{-- Auth::user()->name --}}
                        Seller User
                    </p>
                    {{-- This will be a form to log out --}}
                    {{-- <a href="{{ route('logout') }}" class="text-sm text-blue-500 hover:underline">Logout</a> --}}
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                 {{-- Session-based alerts for feedback (e.g., "Product created successfully!") --}}
                @include('partials._alerts')

                @yield('content')
            </main>
        </div>
    </div>

</body>
</html>