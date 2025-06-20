<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Review;
use App\Models\ProductImage;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * These are the fields a seller can fill in from a form.
     * We don't include 'seller_id', 'status', or 'slug' here
     * because we will set them manually in the controller for security and consistency.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'brand',
        'sizes',
        'color',
        'material',
        'category_id',

  
        'slug',
        'seller_id',
        'status',
    ];

     public const AVAILABLE_SIZES = [
        '6', '6.5', '7', '7.5', '8', '8.5', '9', '9.5', '10', '10.5', '11', '11.5', '12', '13'
    ];

    /**
     * The attributes that should be cast.
     * This automatically converts the 'sizes' JSON column from the database
     * into a PHP array and back when saving.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'sizes' => 'array',
        'price' => 'decimal:2',
    ];

    /**
     * Get the seller (a User) that owns the Product.s
     */
    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    /**
     * Get the category that the Product belongs to.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get all images for the Product.
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    /**
     * Get the primary image for the product.
     * This is a convenient accessor to get the main image without looping.
     */
    public function getPrimaryImageAttribute()
    {
        // Return the first image found, or a placeholder if no images exist.
        return $this->images()->first()->image_path ?? 'path/to/default-placeholder.jpg';
    }
    
    /**
     * Get the order items associated with the product.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Change the route model binding to use the 'slug' column instead of 'id'.
     * This allows for URLs like /products/my-cool-shoe
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
     public function getAverageRatingAttribute(): float
    {
        // The 'avg' aggregate function will return null if there are no reviews.
        // The null coalescing operator '??' defaults it to 0.
        return round($this->reviews()->avg('rating') ?? 0, 1);
    }
      public function getReviewsCountAttribute(): int
    {
        // Using `count()` is efficient here.
        return $this->reviews()->count();
    }
     public function reviews(): HasMany
{
    return $this->hasMany(Review::class);
}


public function primaryImageModel()
{
    // The logic inside stays the same. It's already robust.
    return $this->hasOne(ProductImage::class)
        ->where('is_primary', true)
        ->withDefault(function ($productImage, $product) {
            $firstImage = $product->images()->first();
            if ($firstImage) {
                $productImage->image_path = $firstImage->image_path;
            } else {
                $productImage->image_path = 'images/default-product.png';
            }
        });
}


}