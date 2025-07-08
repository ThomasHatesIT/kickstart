<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class BrowseProductsController extends Controller
{
    /**
     * Display a paginated list of approved products.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Eager load relationships to prevent N+1 query issues.
        // We only fetch the 'primaryImage' for the index page for efficiency.
        $products = Product::where('status', 'approved')
            ->with(['category', 'primaryImage'])
            ->latest() // Order by newest first
            ->paginate(12); // Paginate results

        return view('browse-products', compact('products'));
    }

    /**
     * Display the specified product.
     * Laravel's Route Model Binding will automatically find the product by its slug.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show(Product $product)
    {
        // IMPORTANT: Ensure users can only view approved products.
        // If a user tries to access a pending/rejected product URL, they get a 404.
        if ($product->status !== 'approved') {
            abort(404);
        }

        // Load all relationships needed for the detail page.
        $product->load(['category', 'seller', 'images']);

        return view('home.show', compact('product'));
    }
}