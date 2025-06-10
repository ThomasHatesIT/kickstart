{{-- resources/views/partials/_seller_sidebar.blade.php --}}

<div class="list-group">
    <a href="{{ route('seller.dashboard') }}" class="list-group-item list-group-item-action {{ request()->routeIs('seller.dashboard') ? 'active' : '' }}">
        🧑‍💼 Dashboard
    </a>
    <a href="{{ route('seller.products.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('seller.products.*') ? 'active' : '' }}">
        My Products
    </a>
    <a href="{{ route('seller.orders.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('seller.orders.*') ? 'active' : '' }}">
        My Orders
    </a>
    <a href="#" class="list-group-item list-group-item-action">
        Sales History
    </a>
</div>