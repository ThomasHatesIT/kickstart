<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // User Management
            'view users',
            'create users',
            'edit users',
            'delete users',
            
            // Category Management
            'view categories',
            'create categories',
            'edit categories',
            'delete categories',
            
            // Product Management
            'view products',
            'create products',
            'edit products',
            'delete products',
            'approve products',
            'reject products',
            'view own products',
            'edit own products',
            'delete own products',
            
            // Order Management
            'view all orders',
            'view own orders',
            'edit orders',
            'update order status',
            'view seller orders',
            'update seller order status',
            
            // Shopping Features
            'add to cart',
            'place orders',
            'view cart',
            'checkout',
            
            // Admin Dashboard
            'view admin dashboard',
            'view seller dashboard',
            'view reports',
            'manage system settings',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        
        // Admin Role - Full access
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        // Seller Role - Product and order management
        $sellerRole = Role::create(['name' => 'seller']);
        $sellerRole->givePermissionTo([
            'view products',
            'create products',
            'view own products',
            'edit own products',
            'delete own products',
            'view categories',
            'view seller orders',
            'update seller order status',
            'view seller dashboard',
        ]);

        // Buyer Role - Shopping features
        $buyerRole = Role::create(['name' => 'buyer']);
        $buyerRole->givePermissionTo([
            'view products',
            'view categories',
            'add to cart',
            'view cart',
            'place orders',
            'checkout',
            'view own orders',
        ]);

        $this->command->info('Roles and permissions seeded successfully!');
    }
}