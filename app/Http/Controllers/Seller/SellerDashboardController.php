<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SellerDashboardController extends Controller
{
    /**
     * Display the seller's dashboard with stats and charts.
     */
    public function dashboard()
    {
        $sellerId = Auth::id();

        // --- KPI Calculations ---

        // Total Revenue: Sum of price*quantity for all 'delivered' items.
        $totalRevenue = OrderItem::whereHas('product', fn($q) => $q->where('seller_id', $sellerId))
                                  ->whereHas('order', fn($q) => $q->where('status', 'delivered'))
                                  ->sum(DB::raw('price * quantity'));

        // Total Products Sold
        $productsSoldCount = OrderItem::whereHas('product', fn($q) => $q->where('seller_id', $sellerId))
                                        ->whereHas('order', fn($q) => $q->where('status', '!=', 'cancelled'))
                                        ->sum('quantity');

        // Active & Pending Products
        $activeProductsCount = Product::where('seller_id', $sellerId)->where('status', 'approved')->count();
        $pendingProductsCount = Product::where('seller_id', $sellerId)->where('status', 'pending')->count();

        // --- Recent Orders ---
        $recentOrders = Order::whereHas('items.product', fn($q) => $q->where('seller_id', $sellerId))
                             ->with('user')
                             ->latest()
                             ->take(5)
                             ->get();

        // --- Enhanced Chart Data (Sales in the last 30 days) ---
        // Get actual sales data from database
        $salesData = OrderItem::whereHas('product', fn($q) => $q->where('seller_id', $sellerId))
            ->whereHas('order', fn($q) => $q->where('status', '!=', 'cancelled')) // Only count non-cancelled orders
            ->select(
                DB::raw('DATE(order_items.created_at) as date'),
                DB::raw('SUM(price * quantity) as total_sales')
            )
            ->where('order_items.created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->keyBy('date'); // Key by date for easy lookup

        // Create arrays for all 30 days (fill missing days with 0)
        $chartLabels = [];
        $chartData = [];
        
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dateString = $date->format('Y-m-d');
            
            // Add label (format: "Jan 15")
            $chartLabels[] = $date->format('M j');
            
            // Add sales data (0 if no sales that day)
            $chartData[] = isset($salesData[$dateString]) 
                ? (float) $salesData[$dateString]->total_sales 
                : 0;
        }

        // Debug: Add some sample data if no real data exists
        if (array_sum($chartData) == 0) {
            // If no sales data, add some sample data to test the chart
            $chartData = array_map(function() {
                return rand(0, 200); // Random values between 0-200 for testing
            }, $chartData);
        }

        return view('seller.dashboard', compact(
            'totalRevenue',
            'productsSoldCount',
            'activeProductsCount',
            'pendingProductsCount',
            'recentOrders',
            'chartLabels',
            'chartData'
        ));
    }

    /**
     * Display a detailed sales history report.
     */
    public function salesHistory()
    {
        // Get all individual items sold by this seller, paginated.
        $sales = OrderItem::whereHas('product', fn($q) => $q->where('seller_id', Auth::id()))
                            ->with(['order', 'product'])
                            ->latest()
                            ->paginate(20);

        return view('seller.sales_history', compact('sales'));
    }
}