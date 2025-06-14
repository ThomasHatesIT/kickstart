<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
   public function index()
    {
        // Fetch the 8 most recent, 'approved' products.
        // Eager load the 'images' relationship to prevent N+1 query problems.
        $featuredProducts = Product::where('status', 'approved')
                                   ->with(['reviews', 'category']) 
                                   ->latest() // Get the newest products
                                   ->take(8)  // Limit to 8 products for the homepage
                                   ->get();

        // Pass the data to the view with a consistent variable name
        return view('home', [
            'featuredProducts' => $featuredProducts
        ]);
    }
    public function show(Product $product){
        return view('home.show', [
            'product' => $product
        ]);
    }
}