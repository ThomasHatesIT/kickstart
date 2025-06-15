{{-- resources/views/partials/_seller_sidebar.blade.php --}}

<div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="width: 280px;">
    
    {{-- Site Brand/Logo --}}
    <a href="" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
        <i class="bi bi-shop-window fs-4 me-2"></i>
        <span class="fs-4">KickStart Seller</span>
    </a>
    <hr>
    
    {{-- Navigation Links --}}
    {{-- 'nav-pills' gives the nice button-like active state --}}
    {{-- 'mb-auto' pushes the user dropdown at the bottom to the very bottom --}}
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            {{--
                The logic here is:
                - If the route matches, the class is 'active'.
                - If not, we explicitly set 'text-white' because the default nav-link is blue.
            --}}
            <a href="" class="nav-link {{ request()->routeIs('seller.dashboard') ? 'active' : 'text-white' }}">
                <i class="bi bi-speedometer2 me-2"></i>
                Dashboard
            </a>
        </li>
        <li>
            <a href="{{ route('seller.products.index') }}" class="nav-link {{ request()->routeIs('seller.products.*') ? 'active' : 'text-white' }}">
                <i class="bi bi-box-seam me-2"></i>
                My Products
            </a>
        </li>
        <li>
            <a href="{{ route('seller.orders.index') }}" class="nav-link {{ request()->routeIs('seller.orders.*') ? 'active' : 'text-white' }}">
                <i class="bi bi-card-list me-2"></i>
                My Orders
            </a>
        </li>
        <li>
            <a href="#" class="nav-link text-white">
                <i class="bi bi-bar-chart-line me-2"></i>
                Sales History
            </a>
        </li>
    </ul>
    
    <hr>
    
    {{-- User Profile / Logout Dropdown --}}
    <div class="dropdown">
        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
            {{-- Placeholder image, you can replace with user avatar logic later --}}
            <img src="https://via.placeholder.com/32" alt="" width="32" height="32" class="rounded-circle me-2">
            <strong>
                {{ Auth::user()->name }}
               
            </strong>
        </a>
       <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser">
    <li><a class="dropdown-item" href="#">Settings</a></li>
    <li><a class="dropdown-item" href="#">Profile</a></li>
    <li><hr class="dropdown-divider"></li>
    <li>
        <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            Sign out
        </a>
    </li>
</ul>

<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
</form>

    </div>
</div>