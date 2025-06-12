<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm sticky-top">
    <div class="container">
        {{-- Brand/Logo --}}
        <a class="navbar-brand" href="{{ route('home') }}">
            <i class="fas fa-shoe-prints me-2"></i>
            KickStart
        </a>

        {{-- Mobile Toggle Button --}}
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#main-nav" aria-controls="main-nav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- Main Navigation Links --}}
        <div class="collapse navbar-collapse" id="main-nav">
            {{-- Left Aligned Links --}}
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
                </li>
                <li class="nav-item">
                    {{-- Assuming you have a route named 'products.index' for the shop page --}}
                    <a class="nav-link {{ request()->routeIs('products.index') ? 'active' : '' }}" href="{{-- route('products.index') --}}#">Shop</a>
                </li>
            </ul>

            {{-- Right Aligned Links --}}
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
                <li class="nav-item">
                    <a class="nav-link" href="{{-- route('cart.index') --}}#">
                        <i class="fas fa-shopping-cart"></i>
                        {{-- Add a badge for cart items. You can make this dynamic later. --}}
                        <span class="badge bg-primary rounded-pill ms-1">0</span>
                    </a>
                </li>

                @guest
                    {{-- Show these links only if the user is a GUEST --}}
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('register') }}" class="btn btn-primary ms-2">Register</a>
                    </li>
                @endguest

                @auth
                    {{-- Show this dropdown only if the user is LOGGED IN --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            {{-- DYNAMIC DASHBOARD LINK BASED ON ROLE --}}
                            @if(Auth::user()->hasRole('admin'))
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">Admin Dashboard</a></li>
                            @elseif(Auth::user()->hasRole('seller'))
                                <li><a class="dropdown-item" href="{{ route('seller.products.index') }}">Seller Dashboard</a></li>
                            @else
                                {{-- Default for 'Buyer' or any other user role --}}
                                <li><a class="dropdown-item" href="{{-- route('user.dashboard') --}}#">My Dashboard</a></li>
                            @endif

                            <li><a class="dropdown-item" href="{{-- route('user.orders.index') --}}#">My Orders</a></li>
                            <li><a class="dropdown-item" href="{{-- route('user.profile') --}}#">Profile Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                {{-- Secure Logout Form --}}
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>