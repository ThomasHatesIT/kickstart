<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\ProductImage;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->words(3, true);

        $brands = ['Nike', 'Adidas', 'Puma', 'Reebok', 'Vans', 'New Balance'];
        $materials = ['Leather', 'Suede', 'Canvas', 'Mesh', 'Synthetic'];
        $sizes = ['8', '8.5', '9', '9.5', '10', '10.5', '11', '12'];

        // --- CHANGE IS HERE ---
        // 1. Fetch all existing category IDs.
        // We use a static variable to cache the IDs so we don't query the DB on every product creation.
        static $categoryIds;
        if (!$categoryIds) {
            $categoryIds = Category::pluck('id')->all();
        }
        // --- END OF CHANGE ---

        return [
            'name' => Str::title($name),
            'slug' => Str::slug($name) . '-' . uniqid(),
            'description' => $this->faker->paragraph(3),
            'price' => $this->faker->randomFloat(2, 40, 300),
            'stock' => $this->faker->numberBetween(5, 50),
            'status' => 'approved',
            'brand' => $this->faker->randomElement($brands),
            'sizes' => $this->faker->randomElements($sizes, $this->faker->numberBetween(2, 5)),
            'color' => $this->faker->colorName(),
            'material' => $this->faker->randomElement($materials),

            // --- CHANGE IS HERE ---
            // 2. Assign a random ID from the list of existing categories.
            'category_id' => $this->faker->randomElement($categoryIds),
            // --- END OF CHANGE ---

            // We can do the same for sellers if you have a set list of seller accounts
            'seller_id' => 2, // This still creates a new seller, which is often fine.
        ];
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function ($product) {
            ProductImage::factory()->create([
                'product_id' => $product->id,
                'is_primary' => true,
            ]);
        });
    }
}