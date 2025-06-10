{{-- resources/views/partials/_admin_sidebar.blade.php --}}

<div class="list-group">
    <a href="{{ route('admin.dashboard') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        👑 Dashboard
    </a>
    <a href="{{ route('admin.users.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
        Manage Users
    </a>
    <a href="{{ route('admin.sellers.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.sellers.*') ? 'active' : '' }}">
        Manage Sellers
    </a>
    <a href="{{ route('admin.products.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
        Manage Products
    </a>
    <a href="{{ route('admin.orders.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
        Manage Orders
    </a>
</div>