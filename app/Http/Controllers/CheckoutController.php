<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    /**
     * Display the checkout page.
     * Gathers cart items and total to show the order summary.
     */
    public function index()
    {
        $cartItems = Auth::user()->cartItems()->with('product')->get();
        
        // If the cart is empty, redirect them back to the cart page with a warning.
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('warning', 'Your cart is empty. Please add products before checking out.');
        }

        $total = $cartItems->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });

        return view('checkout.index', compact('cartItems', 'total'));
    }

    /**
     * Store a newly created order in storage.
     * This is the main logic for placing an order.
     */
    public function store(Request $request)
    {
        // 1. Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
        ]);
        
        $user = Auth::user();
        $cartItems = $user->cartItems()->with('product')->get();

        // 2. Double-check if the cart is empty
        if ($cartItems->isEmpty()) {
            return redirect()->route('home')->with('error', 'Your cart is empty.');
        }

        // 3. Start a database transaction.
        // This ensures that if any step fails (e.g., stock update),
        // the entire order process is rolled back.
        $order = DB::transaction(function () use ($request, $user, $cartItems) {
            
            // 4. Check stock for all items BEFORE creating the order
            foreach ($cartItems as $item) {
                if ($item->product->stock < $item->quantity) {
                    // If stock is insufficient, throw an exception to roll back the transaction
                    throw new \Exception('Sorry, the product "' . $item->product->name . '" does not have enough stock.');
                }
            }

            // 5. Calculate the total amount from the server-side to ensure price integrity
            $totalAmount = $cartItems->sum(function ($item) {
                return $item->quantity * $item->product->price;
            });

            // 6. Create the Order
            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => 'KS-' . strtoupper(Str::random(8)), // Generate a unique order number
                'total_amount' => $totalAmount,
                'status' => 'pending', // Initial status
                'shipping_address' => $request->address,
                'payment_method' => 'COD', // Cash on Delivery
            ]);

            // 7. Create Order Items and Decrement Product Stock
            foreach ($cartItems as $item) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price, // Price at the time of purchase
                    'size' => $item->size,
                ]);

                // Decrement the stock of the product
                $item->product->decrement('stock', $item->quantity);
            }

            // 8. Clear the user's shopping cart
            $user->cartItems()->delete();
            
            // Update the user's profile with the new address/phone if they don't have one
            if(empty($user->address) || empty($user->phone)){
                $user->update([
                    'address' => $user->address ?? $request->address,
                    'phone' => $user->phone ?? $request->phone,
                ]);
            }

            // 9. Return the created order from the transaction closure
            return $order;

        }, 3); // The '3' is the number of times to re-attempt the transaction on deadlock

        // TODO: Send an order confirmation email to the user here.

        // 10. Redirect to the success page with the newly created order
        return redirect()->route('checkout.success', $order)->with('success', 'Order placed successfully!');
    }

    /**
     * Show the order success page.
     */
    public function success(Order $order)
    {
        // Use Route-Model Binding to automatically fetch the order.
        // Add authorization to ensure the user can only see their own success page.
        if ($order->user_id !== Auth::id()) {
            abort(403, 'You are not authorized to view this page.');
        }

        return view('checkout.success', compact('order'));
    }
}