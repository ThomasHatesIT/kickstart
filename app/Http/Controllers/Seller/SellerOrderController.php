<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerOrderController extends Controller
{
    /**
     * Display a listing of orders containing the seller's products.
     */
    public function index()
    {
        $sellerId = Auth::id();

        // Get orders that contain at least one product from the current seller.
        // We use a subquery with 'whereHas' on the 'items.product' relationship.
        $orders = Order::whereHas('items.product', function ($query) use ($sellerId) {
            $query->where('seller_id', $sellerId);
        })
        ->with(['user', 'items' => function ($query) use ($sellerId) {
            // Eager load only the items that belong to this seller for each order.
            $query->whereHas('product', function ($subQuery) use ($sellerId) {
                $subQuery->where('seller_id', $sellerId);
            });
        }])
        ->latest()
        ->paginate(15);

        return view('seller.orders.index', compact('orders'));
    }

    /**
     * Display the specified order details, filtering to show only the seller's items.
     */
    public function show(Order $order)
    {
        $sellerId = Auth::id();

        // Eager load the order with only the items belonging to the current seller.
        $order->load(['user', 'items' => function ($query) use ($sellerId) {
            $query->whereHas('product', function ($subQuery) use ($sellerId) {
                $subQuery->where('seller_id', $sellerId);
            })->with('product'); // Also load the product details for each item
        }]);

        // Security check: If after filtering, the order has no items for this seller, they shouldn't see it.
        if ($order->items->isEmpty()) {
            abort(404);
        }

        return view('seller.orders.show', compact('order'));
    }

    /**
     * Update the status of an order item (e.g., to 'shipped').
     * Note: We are updating the main Order status for simplicity.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,shipped,delivered',
        ]);
        
        // Security Check: You might want to add a check here to ensure
        // the seller has items in this order before allowing a status change.
        // This prevents a seller from changing the status of an order they are not part of.
        $sellerId = Auth::id();
        if (!$order->items()->whereHas('product', fn($q) => $q->where('seller_id', $sellerId))->exists()) {
            abort(403, 'Unauthorized action.');
        }

        $order->status = $request->status;

        // If the status is 'shipped', set the shipped_at timestamp.
        if ($request->status === 'shipped' && is_null($order->shipped_at)) {
            $order->shipped_at = now();
        }
        
        // Similarly for 'delivered'
        if ($request->status === 'delivered' && is_null($order->delivered_at)) {
            $order->delivered_at = now();
        }

        $order->save();

        // TODO: Send an email notification to the buyer that their order has been shipped.

        return redirect()->route('seller.orders.show', $order)->with('success', 'Order status has been updated successfully.');
    }
}