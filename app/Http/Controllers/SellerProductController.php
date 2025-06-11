<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;  
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage; // Don't forget to create this model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SellerProductController extends Controller
{
    public function index()
{
    // Fetch products belonging ONLY to the currently authenticated seller.
    // Order by the newest first and paginate the results.
    $products = Product::where('seller_id', Auth::id())
                        ->with('images') // Eager load images to prevent N+1 query problems
                        ->latest()
                        ->paginate(10); 

    return view('seller.products.index', compact('products'));
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
             Log::error('Product creation failed: ' . $e->getMessage());
            return back()->with('error', 'There was a problem creating the product. Please try again.')->withInput();
        }

        // 5. REDIRECT WITH A SUCCESS MESSAGE
        return redirect()->route('seller.products.index')
                         ->with('success', 'Product created successfully! It is now pending admin approval.');
    }



    public function edit(Product $product) 
{



    if ($product->seller_id !== auth()->id()) {
        abort(403, 'Unauthorized Action');
    }

    $categories = Category::orderBy('name')->get();

    return view('seller.products.edit', [
        'product' => $product,
        'categories' => $categories
    ]);
}



public function update(Request $request, Product $product)
{
    // 1. AUTHORIZE
    if ($product->seller_id !== auth()->id()) {
        abort(403, 'Unauthorized Action');
    }

    // 2. VALIDATE THE DATA
    // We need to ensure a user doesn't try to remove AND upload an image.
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        // ... other fields ...
        'brand' => 'nullable|string|max:100',
        'color' => 'nullable|string|max:50',
        'remove_primary_image' => 'nullable|boolean', // Validate our new checkbox
        'primary_image' => [ // Use an array for complex rules
            'nullable',
            'image',
            'mimes:jpeg,png,jpg,webp',
            'max:2048',
            // IMPORTANT: Prohibit uploading if the "remove" box is checked
            'prohibited_if:remove_primary_image,true',
        ],
    ], [
        // Custom error message for a better user experience
        'primary_image.prohibited_if' => 'You cannot upload a new image and remove the current one at the same time.'
    ]);

    try {
        DB::beginTransaction();

        // 3. HANDLE IMAGE REMOVAL (if checkbox was ticked)
        if ($request->boolean('remove_primary_image')) {
            $primaryImage = $product->images->firstWhere('is_primary', true);
            if ($primaryImage) {
                // Delete the file from storage
                Storage::disk('public')->delete($primaryImage->image_path);
                // Delete the record from the database
                $primaryImage->delete();
            }
        } 
        // 4. HANDLE NEW IMAGE UPLOAD (if a new file was provided)
        // This 'else if' is safe because our validation prevents both from being true
        else if ($request->hasFile('primary_image')) { 
            // This is the same logic as before: delete the old and store the new
            $oldPrimaryImage = $product->images->firstWhere('is_primary', true);
            if ($oldPrimaryImage) {
                Storage::disk('public')->delete($oldPrimaryImage->image_path);
                $oldPrimaryImage->delete();
            }

            $imagePath = $request->file('primary_image')->store('products', 'public');
            $product->images()->create([
                'image_path' => $imagePath,
                'is_primary' => true,
            ]);
        }

        // 5. UPDATE THE PRODUCT'S OTHER DETAILS
        $product->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            // ... other fields ...
            'brand' => $validated['brand'],
            'color' => $validated['color'],
        ]);

        DB::commit();

    } catch (\Exception $e) {
        // ... (your existing catch block is fine) ...
    }

    return redirect()->route('seller.products.index')
                     ->with('success', 'Product updated successfully!');
}
 public function destroy(Product $product)
    {
        // 1. AUTHORIZE THE ACTION
        // Ensure the logged-in user is the owner of the product.
        if ($product->seller_id !== auth()->id()) {
            abort(403, 'Unauthorized Action');
        }

        try {
            // 2. DELETE ASSOCIATED IMAGE FILES FROM STORAGE
            // It's important to delete files before the database record.
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image->image_path);
            }

            // 3. DELETE THE PRODUCT FROM THE DATABASE
            // This will also automatically delete the related 'product_images' records
            // if you have set up cascading deletes in your migration (see tip below).
            $product->delete();

        } catch (\Exception $e) {
            Log::error('Product deletion failed: ' . $e->getMessage());
            return back()->with('error', 'There was a problem deleting the product.');
        }

        // 4. REDIRECT WITH A SUCCESS MESSAGE
        return redirect()->route('seller.products.index')
                         ->with('success', 'Product has been deleted successfully.');
    }
}