<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // We will associate the product_id when we call this factory.
            'product_id' => Product::factory(), 
            
            // Use a placeholder image path as requested.
            // Ensure this image exists in your `public/images/` folder.
            'image_path' => 'images/default_product.png', 
            
            'is_primary' => false, // We will override this for the main image.
        ];
    }
}