<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category; // Import the model
use Illuminate\Support\Str; // Import the Str helper for slugs

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Men\'s Shoes',
            'Women\'s Shoes',
            'Kids\' Shoes',
            'Running Shoes',
            'Casual Sneakers',
            'Formal Shoes',
            'Boots',
            'Sandals',
        ];

        foreach ($categories as $categoryName) {
            Category::create([
                'name' => $categoryName,
                'slug' => Str::slug($categoryName), // Automatically create the slug
                'description' => "A collection of the finest {$categoryName} available."
            ]);
        }
    }
}