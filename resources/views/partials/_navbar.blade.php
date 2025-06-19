{{-- resources/views/partials/_navbar.blade.php --}}
<nav class="navbar navbar-expand-lg navbar-light bg-white">
    <div class="container">
        <!-- Brand/Logo -->
        <a class="navbar-brand" href="{{ route('home') }}">
            <i class="fas fa-shopping-bag me-2"></i>KickStart
        </a>

        <!-- Mobile Toggle Button -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar Links -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Left Side Links -->
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                        <i class="fas fa-home me-1"></i>Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{route('browse-products.index')}}">
                        <i class="fas fa-th-large me-1"></i>Products
                    </a>
                </li>
              
              
            </ul>

            <!-- Right Side Links -->
            <ul class="navbar-nav">
                <!-- Search Form (Optional) -->
                <li class="nav-item me-3">
                    <form class="d-flex" action="#" method="GET">
                        <div class="input-group">
                            <input class="form-control form-control-sm" type="search" placeholder="Search products..." aria-label="Search" name="search" style="min-width: 200px;">
                            <button class="btn btn-outline-primary btn-sm" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </li>
                    @auth
                <!-- Cart -->
                <li class="nav-item">
                    <a class="nav-link position-relative {{ request()->routeIs('cart.index') ? 'active' : '' }}" href="{{ route('cart.index') }}">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6em;">
                     {{ Auth::check() ? Auth::user()->cartItems->count() : 0 }}


                        </span>
                        Cart
                    </a>
                </li>
                @endauth

                @guest
                    <!-- Login/Register for guests -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') ?? '#' }}">
                            <i class="fas fa-sign-in-alt me-1"></i>Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') ?? '#' }}">
                            <i class="fas fa-user-plus me-1"></i>Register
                        </a>
                    </li>
                @else
                    <!-- User Dropdown for authenticated users -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle me-1"></i>{{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profile</a></li>
                            <li><a class="dropdown-item" href="{{ route('users.orders.index') }}"><i class="fas fa-shopping-bag me-2"></i>My Orders</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-heart me-2"></i>Wishlist</a></li>
                            @if(Auth::user()->role === 'seller')
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-store me-2"></i>Seller Dashboard</a></li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('logout') ?? '#' }}" 
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') ?? '#' }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>