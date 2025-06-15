<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display the user's shopping cart.
     */
    public function index()
    {
        $cartItems = Auth::user()->cartItems()->with('product.primaryImageModel')->get();

        $total = $cartItems->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });

        return view('cart.index', compact('cartItems', 'total'));
    }

    /**
     * Add a product to the cart.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'size' => 'required|string',
        ]);

        $product = Product::findOrFail($request->product_id);

        // Check if there is enough stock
        if ($product->stock < $request->quantity) {
            return back()->with('error', 'Not enough stock available for this product.');
        }

        // Check if the same product with the same size already exists in the cart
        $existingCartItem = Auth::user()->cartItems()
            ->where('product_id', $request->product_id)
            ->where('size', $request->size)
            ->first();

        if ($existingCartItem) {
            // Update quantity if item already exists
            $newQuantity = $existingCartItem->quantity + $request->quantity;
            if ($product->stock < $newQuantity) {
                return back()->with('error', 'Cannot add more. Not enough stock available.');
            }
            $existingCartItem->increment('quantity', $request->quantity);
        } else {
            // Create a new cart item
            Auth::user()->cartItems()->create([
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'size' => $request->size,
            ]);
        }

        return redirect()->route('c.index')->with('success', 'Product added to cart successfully!');
    }

    /**
     * Update the quantity of a cart item.
     * Enhanced to handle auto-updates smoothly
     */
    public function update(Request $request, CartItem $cartItem)
    {
        // Authorization: Ensure the item belongs to the logged-in user
        if ($cartItem->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $newQuantity = $request->quantity;
        $product = $cartItem->product;

        // Check for stock availability
        if ($product->stock < $newQuantity) {
            return back()->with('error', "Only {$product->stock} items available in stock.");
        }

        // Update the cart item
        $cartItem->update(['quantity' => $newQuantity]);

        // Calculate new totals for smooth UI updates
        $itemTotal = $cartItem->quantity * $product->price;
        $cartTotal = Auth::user()->cartItems()->with('product')->get()->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });

        // Return appropriate response based on request type
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Cart updated successfully!',
                'item_total' => number_format($itemTotal, 2),
                'cart_total' => number_format($cartTotal, 2),
                'quantity' => $cartItem->quantity
            ]);
        }

        // For regular form submissions (our case)
        $message = $newQuantity === 1 ? 
            'Cart updated successfully!' : 
            "Quantity updated to {$newQuantity} items.";
            
        return back()->with('success', $message);
    }

    /**
     * Remove an item from the cart.
     */
    public function destroy(CartItem $cartItem)
    {
        // Authorization: Ensure the item belongs to the logged-in user
        if ($cartItem->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $productName = $cartItem->product->name;
        $cartItem->delete();

        return back()->with('success', "'{$productName}' removed from cart.");
    }

    /**
     * Get cart count for navbar badge (optional helper method)
     */
    public function getCartCount()
    {
        if (!Auth::check()) {
            return 0;
        }

        return Auth::user()->cartItems()->sum('quantity');
    }
}