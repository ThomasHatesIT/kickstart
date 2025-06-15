@extends('layouts.seller') {{-- Your seller layout --}}

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4 text-dark font-weight-bold">Seller Dashboard</h1>
        </div>
    </div>

    {{-- Enhanced KPI Cards --}}
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-lg h-100 card-hover">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-2 text-uppercase font-weight-bold text-muted">Total Revenue</p>
                                <h4 class="font-weight-bolder text-dark mb-2">€{{ number_format($totalRevenue, 2) }}</h4>
                                <p class="mb-0">
                                    <span class="text-success text-sm font-weight-bolder">
                                        <i class="bi bi-arrow-up me-1"></i>{{ $productsSoldCount }}
                                    </span>
                                    <span class="text-muted text-sm">products sold</span>
                                </p>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                                <i class="bi bi-currency-euro text-lg opacity-10"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-lg h-100 card-hover">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-2 text-uppercase font-weight-bold text-muted">Active Products</p>
                                <h4 class="font-weight-bolder text-dark mb-2">{{ $activeProductsCount }}</h4>
                                <p class="mb-0">
                                    <span class="text-success text-sm font-weight-bolder">
                                        <i class="bi bi-check-circle me-1"></i>Live
                                    </span>
                                    <span class="text-muted text-sm">on marketplace</span>
                                </p>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
                                <i class="bi bi-check-circle-fill text-lg opacity-10"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-lg h-100 card-hover">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-2 text-uppercase font-weight-bold text-muted">Pending Products</p>
                                <h4 class="font-weight-bolder text-dark mb-2">{{ $pendingProductsCount }}</h4>
                                <p class="mb-0">
                                    <span class="text-warning text-sm font-weight-bolder">
                                        <i class="bi bi-hourglass-split me-1"></i>Review
                                    </span>
                                    <span class="text-muted text-sm">in progress</span>
                                </p>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle">
                                <i class="bi bi-hourglass-split text-lg opacity-10"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-lg h-100 card-hover">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-2 text-uppercase font-weight-bold text-muted">Avg. Order Value</p>
                                <h4 class="font-weight-bolder text-dark mb-2">€{{ number_format($totalRevenue / max($productsSoldCount, 1), 2) }}</h4>
                                <p class="mb-0">
                                    <span class="text-info text-sm font-weight-bolder">
                                        <i class="bi bi-graph-up me-1"></i>Per Sale
                                    </span>
                                    <span class="text-muted text-sm">average</span>
                                </p>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-info shadow-info text-center rounded-circle">
                                <i class="bi bi-graph-up text-lg opacity-10"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Enhanced Charts and Recent Orders --}}
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-header bg-transparent border-0 pb-0 pt-4 px-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="text-capitalize mb-0 font-weight-bold">Sales Overview</h6>
                        <span class="badge bg-primary text-white px-3 py-2">Last 30 Days</span>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="chart-container" style="position: relative; height: 350px;">
                        <canvas id="salesChart" class="chart-canvas"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-header bg-transparent border-0 pb-0 pt-4 px-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 font-weight-bold">Recent Orders</h6>
                        <a href="{{ route('seller.orders.index') }}" class="text-primary text-sm font-weight-bold">View All</a>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="list-group list-group-flush">
                        @forelse($recentOrders as $order)
                        <div class="list-group-item border-0 px-0 py-3 recent-order-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm bg-gradient-primary rounded-circle me-3">
                                        <i class="bi bi-receipt text-white text-sm"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1 text-dark text-sm font-weight-bold">{{ $order->order_number }}</h6>
                                        <p class="text-xs text-muted mb-0">
                                            <i class="bi bi-person me-1"></i>{{ $order->user->name }}
                                        </p>
                                        <p class="text-xs text-muted mb-0">
                                            <i class="bi bi-clock me-1"></i>{{ $order->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <span class="text-primary text-sm font-weight-bold">
                                        €{{ number_format($order->total_amount, 2) }}
                                    </span>
                                    <br>
                                    <span class="badge bg-success text-white text-xs mt-1">Completed</span>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-4">
                            <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-2">No recent orders found.</p>
                            <a href="#" class="btn btn-primary btn-sm">Add New Product</a>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Custom Styles --}}
<style>
    .card-hover {
        transition: all 0.3s ease;
    }
    
    .card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(50, 50, 93, 0.15), 0 5px 15px rgba(0, 0, 0, 0.1) !important;
    }
    
    .icon-shape {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .bg-gradient-success {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    }
    
    .bg-gradient-warning {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }
    
    .bg-gradient-info {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }
    
    .recent-order-item {
        border-bottom: 1px solid #f1f3f4;
        transition: background-color 0.2s ease;
    }
    
    .recent-order-item:hover {
        background-color: #f8f9fa;
        border-radius: 8px;
    }
    
    .recent-order-item:last-child {
        border-bottom: none;
    }
    
    .avatar {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .chart-container {
        position: relative;
    }
    
    .chart-canvas {
        max-height: 350px;
    }
</style>
@endsection

@push('scripts')
{{-- Include Chart.js from CDN with error handling --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
<script>
    // Enhanced Chart.js implementation with error handling
    document.addEventListener("DOMContentLoaded", function() {
        // Check if Chart.js is loaded
        if (typeof Chart === 'undefined') {
            console.error('Chart.js failed to load');
            document.getElementById('salesChart').innerHTML = '<p class="text-center text-muted">Chart could not be loaded</p>';
            return;
        }
        
        // Get the canvas element
        const canvas = document.getElementById("salesChart");
        if (!canvas) {
            console.error('Canvas element not found');
            return;
        }
        
        const ctx = canvas.getContext("2d");
        
        // Prepare data with fallbacks
        const chartLabels = {!! json_encode($chartLabels ?? []) !!};
        const chartData = {!! json_encode($chartData ?? []) !!};
        
        // Validate data
        if (!Array.isArray(chartLabels) || !Array.isArray(chartData)) {
            console.error('Invalid chart data provided');
            canvas.innerHTML = '<p class="text-center text-muted">No data available</p>';
            return;
        }
        
        try {
            // Create the chart
            const salesChart = new Chart(ctx, {
                type: "line",
                data: {
                    labels: chartLabels,
                    datasets: [{
                        label: "Sales (€)",
                        tension: 0.4,
                        borderWidth: 3,
                        borderColor: "#667eea",
                        backgroundColor: "rgba(102, 126, 234, 0.1)",
                        fill: true,
                        data: chartData,
                        pointBackgroundColor: "#667eea",
                        pointBorderColor: "#fff",
                        pointBorderWidth: 2,
                        pointRadius: 6,
                        pointHoverRadius: 8,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        intersect: false,
                        mode: 'index',
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                color: '#6c757d',
                                font: {
                                    size: 12,
                                    weight: '500'
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            borderColor: '#667eea',
                            borderWidth: 1,
                            cornerRadius: 8,
                            displayColors: false,
                            callbacks: {
                                label: function(context) {
                                    return 'Sales: €' + context.parsed.y.toFixed(2);
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false,
                            },
                            ticks: {
                                color: '#6c757d',
                                font: {
                                    size: 11
                                }
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)',
                                drawBorder: false,
                            },
                            ticks: {
                                color: '#6c757d',
                                font: {
                                    size: 11
                                },
                                callback: function(value) {
                                    return '€' + value.toFixed(0);
                                }
                            }
                        }
                    },
                    elements: {
                        point: {
                            hoverBackgroundColor: "#667eea"
                        }
                    }
                },
            });
            
            // Handle responsive behavior
            window.addEventListener('resize', function() {
                if (salesChart) {
                    salesChart.resize();
                }
            });
            
        } catch (error) {
            console.error('Error creating chart:', error);
            canvas.parentElement.innerHTML = '<p class="text-center text-muted">Error loading chart</p>';
        }
    });
</script>
@endpush