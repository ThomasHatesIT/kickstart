<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SellerProductController extends Controller
{
    // ... (index, create, store, edit methods remain the same) ...
    // The previous code for index, create, store, and edit is correct and can be left as is.

    /**
     * Define a single source of truth for available product sizes.
     * This avoids repeating the array in multiple methods.
     * @var array
     */
    private $availableSizes = [
        '6', '6.5', '7', '7.5', '8', '8.5', '9', '9.5', '10', '10.5', '11', '11.5', '12', '13'
    ];

    /**
     * Display a listing of the seller's products.
     */
    public function index()
    {
        $products = Product::where('seller_id', Auth::id())
                            ->with('images')
                            ->latest()
                            ->paginate(10);

        return view('seller.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('seller.products.create', [
            'categories' => $categories,
            'sizes' => $this->availableSizes // Pass the sizes to the view
        ]);
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'brand' => 'nullable|string|max:100',
            'color' => 'nullable|string|max:50',
            'primary_image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'sizes' => 'nullable|array',
            'sizes.*' => ['string', Rule::in($this->availableSizes)], // Validate against our defined sizes
        ]);

        $imagePath = null;
        try {
            DB::beginTransaction();

            if ($request->hasFile('primary_image')) {
                $imagePath = $request->file('primary_image')->store('products', 'public');
            }

            $productData = $validated;
            $productData['seller_id'] = Auth::id();
            $productData['status'] = 'pending';
            $productData['slug'] = Str::slug($request->name) . '-' . uniqid();

            $product = Product::create($productData);

            if ($imagePath) {
                $product->images()->create([
                    'image_path' => $imagePath,
                    'is_primary' => true,
                ]);
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }
            Log::error('Product creation failed: ' . $e->getMessage());
            return back()->with('error', 'There was a problem creating the product. Please try again.')->withInput();
        }

        return redirect()->route('seller.products.index')
                         ->with('success', 'Product created successfully! It is now pending admin approval.');
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        // Authorize that the current user owns this product
        if ($product->seller_id !== auth()->id()) {
            abort(403, 'Unauthorized Action');
        }

        $categories = Category::orderBy('name')->get();

        return view('seller.products.edit', [
            'product' => $product,
            'categories' => $categories,
            'sizes' => $this->availableSizes // Pass all available sizes to the edit view
        ]);
    }


    /**
     * Update the specified product in storage.
     * --- MODIFIED LOGIC ---
     */
    public function update(Request $request, Product $product)
    {
        // Authorize that the current user owns this product
        if ($product->seller_id !== auth()->id()) {
            abort(403, 'Unauthorized Action');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'brand' => 'nullable|string|max:100',
            'color' => 'nullable|string|max:50',
            'primary_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'sizes' => 'nullable|array',
            'sizes.*' => ['string', Rule::in($this->availableSizes)],
            // NEW: Validate the new checkbox input
            'remove_primary_image' => 'nullable|boolean',
        ]);

        $imageChanged = false; // Flag to track if the image was changed or removed

        try {
            DB::beginTransaction();

            $oldPrimaryImage = $product->images->firstWhere('is_primary', true);

            // Case 1: A new primary image is uploaded
            if ($request->hasFile('primary_image')) {
                $imageChanged = true;
                // Delete the old primary image if it exists
                if ($oldPrimaryImage) {
                    Storage::disk('public')->delete($oldPrimaryImage->image_path);
                    $oldPrimaryImage->delete();
                }
                // Store the new one
                $imagePath = $request->file('primary_image')->store('products', 'public');
                $product->images()->create(['image_path' => $imagePath, 'is_primary' => true]);
            }
            // Case 2: The 'remove image' checkbox is checked (and there is no new image)
            elseif ($request->input('remove_primary_image') && $oldPrimaryImage) {
                 $imageChanged = true;
                 Storage::disk('public')->delete($oldPrimaryImage->image_path);
                 $oldPrimaryImage->delete();
            }

            // Prepare data for the update
            $updateData = [
                'name' => $validated['name'],
                'description' => $validated['description'],
                'price' => $validated['price'],
                'stock' => $validated['stock'],
                'category_id' => $validated['category_id'],
                'brand' => $validated['brand'],
                'color' => $validated['color'],
                'sizes' => $validated['sizes'] ?? [],
            ];

            // NEW: If the image was changed, set the status to 'pending'
            if ($imageChanged) {
                $updateData['status'] = 'pending';
            }

            // Update the product's details
            $product->update($updateData);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Product update failed for product ID {$product->id}: " . $e->getMessage());
            return back()->with('error', 'There was a problem updating the product.')->withInput();
        }

        // NEW: Provide a conditional success message
        $successMessage = $imageChanged
            ? 'Product updated successfully! Because the image was changed, it is now pending admin approval.'
            : 'Product updated successfully!';

        return redirect()->route('seller.products.index')
                         ->with('success', $successMessage);
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        // Authorize that the current user owns this product
        if ($product->seller_id !== auth()->id()) {
            abort(403, 'Unauthorized Action');
        }

        try {
            // Delete associated image files from storage first
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image->image_path);
            }

            // Delete the product from the database
            $product->delete();

        } catch (\Exception $e) {
            Log::error("Product deletion failed for product ID {$product->id}: " . $e->getMessage());
            return back()->with('error', 'There was a problem deleting the product.');
        }

        return redirect()->route('seller.products.index')
                         ->with('success', 'Product has been deleted successfully.');
    }
}