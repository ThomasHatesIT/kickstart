<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    /**
     * Display a listing of all orders.
     */
   public function index()
{
    // Use a basic query builder for searching.
    $query = Order::query()->with('user')->latest();

    if (request('search')) {
        $query->where('order_number', 'like', '%' . request('search') . '%')
              ->orWhereHas('user', function($q) {
                  $q->where('name', 'like', '%' . request('search') . '%');
              });
    }


    if (request('status')) {
        $query->where('status', request('status'));
    }

    if (request('date_from')) {
        $query->whereDate('created_at', '>=', request('date_from'));
    }

    if (request('date_to')) {
        $query->whereDate('created_at', '<=', request('date_to'));
    }
    // END OF DATE FILTERING SECTION

    $orders = $query->paginate(5);

    return view('admin.orders.index', compact('orders'));
}
    /**
     * Display the specified order.
     */
    public function show(Order $order, OrderItem $orderitems)
    {
        // Eager load all related data for the detail view.
        $order->load('user', 'items.product.seller');
    
        return view('admin.orders.show', compact('order', $orderitems));
    }

    /**
     * Update the status of the specified order.
     * This is the "manual override" for admins.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,shipped,delivered,cancelled,refunded'
        ]);

        $order->status = $request->status;
        
        // You could add logic to automatically set timestamps here as well
        if ($request->status === 'shipped' && is_null($order->shipped_at)) {
            $order->shipped_at = now();
        }

        $order->save();

        return redirect()->route('admin.orders.show', $order)->with('success', 'Order status updated successfully.');
    }
}