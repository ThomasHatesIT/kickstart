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
  // In app/Http/Controllers/CartController.php

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

     // CORRECT VERSION
return redirect()->route('cart.index')->with('success', 'Product added to cart successfully!');
    }

    /**
     * Update the quantity of a cart item.
     */
    public function update(Request $request, CartItem $cartItem)
    {
        // Authorization: Ensure the item belongs to the logged-in user
        if ($cartItem->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate(['quantity' => 'required|integer|min:1']);

        // Check for stock
        if ($cartItem->product->stock < $request->quantity) {
            return back()->with('error', 'Not enough stock available.');
        }
        
        $cartItem->update(['quantity' => $request->quantity]);

        return back()->with('success', 'Cart updated successfully.');
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

        $cartItem->delete();

        return back()->with('success', 'Product removed from cart.');
    }
}