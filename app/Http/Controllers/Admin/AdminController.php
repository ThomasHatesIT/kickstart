<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        // --- KPI Card Calculations ---

        // Total revenue from all delivered orders
        $totalRevenue = Order::where('status', 'delivered')->sum('total_amount');

        // Total number of orders
        $totalOrders = Order::count();

        // New user registrations in the last 7 days
        $newUsersCount = User::where('created_at', '>=', now()->subDays(7))->count();

        // --- Actionable Items Calculations ---

        // Sellers pending approval
        $pendingSellersCount = User::where('status', 'pending')->whereHas('roles', fn($q) => $q->where('name', 'seller'))->count();
        
        // Products pending approval
        $pendingProductsCount = Product::where('status', 'pending')->count();


        // --- Chart Data (Sales in the last 30 days) ---
        $salesData = Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as total_sales')
            )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $chartLabels = $salesData->pluck('date')->map(fn($date) => date('M d', strtotime($date)));
        $chartData = $salesData->pluck('total_sales');
        

        // --- Recent Orders for the activity feed ---
        $recentOrders = Order::with('user')->latest()->take(5)->get();


        return view('admin.dashboard', compact(
            'totalRevenue',
            'totalOrders',
            'newUsersCount',
            'pendingSellersCount',
            'pendingProductsCount',
            'chartLabels',
            'chartData',
            'recentOrders'
        ));
    }
}