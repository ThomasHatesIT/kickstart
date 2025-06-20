<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Storage; 
class AdminProductController extends Controller
{
    

public function index(Request $request)
{
    // 1. Get all pending products (not paginated)
    $pendingProducts = Product::with('seller', 'images')
                              ->where('status', 'pending')
                              ->latest()
                              ->get();

    // 2. Build the query for all other products (paginated and filterable)
    $query = Product::with('seller', 'images')->latest();

    // Apply search filter
    if ($request->filled('search')) {
        $search = $request->input('search');
        $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhereHas('seller', function($q_seller) use ($search) {
                  $q_seller->where('name', 'like', "%{$search}%");
              });
        });
    }

    // Apply status filter
    if ($request->filled('status')) {
        $query->where('status', 'like', $request->input('status'));
    }

    // Paginate the results
    $products = $query->paginate(15)->withQueryString();

    return view('admin.products.index', [
        'pendingProducts' => $pendingProducts,
        'products' => $products
    ]);
}



        public function show(Product $product){
            $categories = Category::all();
                return view('admin.products.show', [
                    'product'=> $product,
                    'categories' => $categories,
 'sizes'      => Product::AVAILABLE_SIZES

                ]);
        }

    /**
     * Approve a pending product.
     */
    public function approve(Product $product)
    {
        $product->update(['status' => 'approved']);
        
        // TODO: Optionally send an email notification to the seller that their product was approved.

        return redirect()->back()->with('success', "Product '{$product->name}' has been approved.");
    }

    /**
     * Reject a pending product.
     */
    public function reject(Product $product)
    {
        $product->update(['status' => 'rejected']);

        // TODO: Optionally send an email notification to the seller with a reason for rejection.

        return redirect()->back()->with('warning', "Product '{$product->name}' has been rejected.");
    }


  public function destroy(Product $product)
    {
        $productName = $product->name;

        if ($product->images()->exists()) {
            foreach ($product->images as $image) {
                // Now this line will work correctly
                Storage::disk('public')->delete($image->image_path);
                $image->delete();
            }
        }

        $product->delete();

        return redirect()->route('admin.products.index')
                         ->with('success', "Product '{$productName}' has been successfully deleted.");
    }
}