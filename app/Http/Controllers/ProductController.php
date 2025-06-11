<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage; // Don't forget to create this model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
{
    // Fetch products belonging ONLY to the currently authenticated seller.
    // Order by the newest first and paginate the results.
    $products = Product::where('seller_id', Auth::id())
                        ->with('images') // Eager load images to prevent N+1 query problems
                        ->latest()
                        ->paginate(10); 

    return view('index', compact('products'));
}
    /**
     * Show the form for creating a new product.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // We need to fetch categories to display in the form's dropdown list.
        $categories = Category::orderBy('name')->get();
        
        return view('seller.products.create', compact('categories'));
    }

    /**
     * Store a newly created product in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // 1. VALIDATE THE INCOMING DATA
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'brand' => 'nullable|string|max:100',
            'color' => 'nullable|string|max:50',
            // Validate the primary image
            'primary_image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            // You can add validation for sizes if needed, e.g., 'sizes' => 'nullable|array'
        ]);

        // 2. HANDLE THE FILE UPLOAD
        $imagePath = null;
        if ($request->hasFile('primary_image')) {
            // The 'public' disk refers to 'storage/app/public'.
            // The 'products' argument is the sub-directory inside 'public'.
            // This works because you ran `php artisan storage:link`.
            $imagePath = $request->file('primary_image')->store('products', 'public');
        }

        // Use a database transaction to ensure data integrity.
        // If creating the product image fails, the product itself will not be created.
        try {
            DB::beginTransaction();

            // 3. PREPARE DATA AND CREATE THE PRODUCT
            $productData = $validated;
            $productData['seller_id'] = Auth::id(); // Assign the logged-in seller's ID
            $productData['status'] = 'pending';      // New products are always pending approval
            $productData['slug'] = Str::slug($request->name) . '-' . uniqid(); // Create a unique slug

            $product = Product::create($productData);

            // 4. CREATE THE ASSOCIATED PRODUCT IMAGE
            if ($imagePath) {
                $product->images()->create([
                    'image_path' => $imagePath,
                    'is_primary' => true,
                ]);
            }
            
            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            // Optionally, delete the uploaded file if the db transaction fails
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }
            // Log the error and return back with an error message
            // Log::error('Product creation failed: ' . $e->getMessage());
            return back()->with('error', 'There was a problem creating the product. Please try again.')->withInput();
        }

        // 5. REDIRECT WITH A SUCCESS MESSAGE
        return redirect()->route('seller.products.index')
                         ->with('success', 'Product created successfully! It is now pending admin approval.');
    }
}