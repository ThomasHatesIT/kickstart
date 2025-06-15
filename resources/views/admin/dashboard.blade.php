@extends('layouts.admin') {{-- Your main admin layout --}}

@section('content')
<div class="container-fluid py-4">
    <h1 class="mb-4">Admin Dashboard</h1>

    {{-- KPI Cards --}}
    <div class="row">
        {{-- Total Revenue --}}
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card shadow-sm">
                <div class="card-body p-3">
                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Revenue</p>
                    <h5 class="font-weight-bolder">€{{ number_format($totalRevenue, 2) }}</h5>
                </div>
            </div>
        </div>
        {{-- Total Orders --}}
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card shadow-sm">
                <div class="card-body p-3">
                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Orders</p>
                    <h5 class="font-weight-bolder">{{ number_format($totalOrders) }}</h5>
                </div>
            </div>
        </div>
        {{-- New Users --}}
        <div class="col-xl-3 col-sm-6">
             <div class="card shadow-sm">
                <div class="card-body p-3">
                    <p class="text-sm mb-0 text-uppercase font-weight-bold">New Users (7d)</p>
                    <h5 class="font-weight-bolder">+{{ $newUsersCount }}</h5>
                </div>
            </div>
        </div>
    </div>

    {{-- Actionable Item Cards --}}
    <div class="row mt-4">
         {{-- Pending Sellers --}}
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
             <div class="card bg-warning text-white shadow-sm">
                <div class="card-body p-3">
                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Pending Sellers</p>
                    <h5 class="font-weight-bolder">{{ $pendingSellersCount }}</h5>
                    <a href="{{ route('admin.users.index') }}" class="stretched-link text-white">Manage Now →</a>
                </div>
            </div>
        </div>
         {{-- Pending Products --}}
        <div class="col-xl-3 col-sm-6">
            <div class="card bg-info text-white shadow-sm">
                <div class="card-body p-3">
                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Pending Products</p>
                    <h5 class="font-weight-bolder">{{ $pendingProductsCount }}</h5>
                    <a href="{{ route('admin.products.index') }}" class="stretched-link text-white">Approve Now →</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts and Recent Orders --}}
    <div class="row mt-4">
        <div class="col-lg-7 mb-lg-0 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header"><h6 class="text-capitalize">Platform Sales (Last 30 Days)</h6></div>
                <div class="card-body">
                    <canvas id="salesChart" class="chart-canvas" height="300"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card shadow-sm">
                <div class="card-header"><h6 class="mb-0">Recent Orders</h6></div>
                <div class="card-body p-3">
                     <ul class="list-group list-group-flush">
                        @forelse($recentOrders as $order)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                           <div>
                                <a href="{{ route('admin.orders.show', $order) }}">{{ $order->order_number }}</a>
                                <small class="d-block text-muted">by {{ $order->user->name }}</small>
                           </div>
                           <span class="badge bg-primary rounded-pill">{{ $order->status }}</span>
                        </li>
                        @empty
                            <li class="list-group-item">No recent orders.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Include Chart.js from a CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var ctx = document.getElementById("salesChart").getContext("2d");
        new Chart(ctx, {
            type: "bar",
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [{
                    label: "Total Sales",
                    borderColor: "#5e72e4",
                    backgroundColor: "rgba(94, 114, 228, 0.5)",
                    data: {!! json_encode($chartData) !!},
                }],
            },
            options: { responsive: true, maintainAspectRatio: false }
        });
    });
</script>
@endpush