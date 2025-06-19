<?php

use Illuminate\Support\Facades\Route;


use App\Http\Controllers\HomeController;
use App\Http\Controllers\RegisterUserController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\BrowseProductsController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\User\OrderController as UserOrderController;
use App\Http\Controllers\Seller\SellerDashboardController as SellerDashboardController;
use App\Http\Controllers\Seller\SellerOrderController as SellerOrderController;
use App\Http\Controllers\SellerProductController as SellerProductController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminOrderController;

Route::get('/', [HomeController::class, 'index'])->name('home');

// All public product browsing routes should be together and early.
Route::get('/products', [BrowseProductsController::class, 'index'])->name('browse-products.index'); // Renamed for consistency
Route::get('/products/{product}', [BrowseProductsController::class, 'show'])->name('products.show'); // Using ID now

Route::get('/', [HomeController::class, 'index'])->name('home');

// Auth routes for guests
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterUserController::class, 'index'])->name('register');
    Route::post('/register', [RegisterUserController::class, 'store']);
    Route::get('/login', [SessionController::class, 'index'])->name('login');
    Route::post('/login', [SessionController::class, 'store']);
});

// Logout requires authentication
Route::post('/logout', [SessionController::class, 'destroy'])->middleware('auth')->name('logout');




Route::middleware('auth')->group(function () {
    // Cart Routes
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::patch('/cart/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cartItem}', [CartController::class, 'destroy'])->name('cart.destroy');

    // Checkout Routes
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');
});


// Logged-in Buyer/User Specific Routes
Route::middleware(['auth'])->prefix('user')->name('users.')->group(function () {
    // This route group now uses the aliased UserOrderController
    Route::get('/orders', [UserOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [UserOrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/download', [UserOrderController::class, 'downloadInvoice'])->name('orders.download');
    // You can add profile, favorites, etc. routes here later
});

// Seller Routes (requires seller role)
Route::middleware(['auth', 'role:seller'])->prefix('seller')->name('seller.')->group(function () {
    
    Route::get('/dashboard', [SellerDashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/sales-history', [SellerDashboardController::class, 'salesHistory'])->name('sales.history');


    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [SellerProductController::class, 'index'])->name('index');
        Route::get('/create', [SellerProductController::class, 'create'])->name('create');
        Route::post('/', [SellerProductController::class, 'store'])->name('store');
        Route::get('/{product}/edit', [SellerProductController::class, 'edit'])->name('edit');
        Route::put('/{product}', [SellerProductController::class, 'update'])->name('update');
        Route::delete('/{product}', [SellerProductController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [SellerOrderController::class, 'index'])->name('index');
        Route::get('/{order}', [SellerOrderController::class, 'show'])->name('show');
        Route::patch('/{order}', [SellerOrderController::class, 'updateStatus'])->name('update');
           Route::get('/{order}/packing-slip', [SellerOrderController::class, 'downloadPackingSlip'])->name('packing-slip');
    
    });




});

// Admin Routes (requires admin role)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
        // NEW Order Management Route
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}', [AdminOrderController::class, 'updateStatus'])->name('orders.update');

    // Admin User Management
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');
    Route::patch('/users/{user}/ban', [AdminUserController::class, 'ban'])->name('users.ban');
    Route::patch('/users/{user}/unban', [AdminUserController::class, 'unban'])->name('users.unban');
    
    // Admin Seller Approval
    Route::patch('/sellers/{user}/approve', [AdminUserController::class, 'approveSeller'])->name('sellers.approve');
    Route::patch('/sellers/{user}/reject', [AdminUserController::class, 'rejectSeller'])->name('sellers.reject');

    // Admin Product Management
    Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
    Route::get('/products/{product}', [AdminProductController::class, 'show'])->name('products.show');
    Route::patch('/products/{product}/approve', [AdminProductController::class, 'approve'])->name('products.approve');
    Route::patch('/products/{product}/reject', [AdminProductController::class, 'reject'])->name('products.reject');
    Route::delete('/products/{product}', [AdminProductController::class, 'destroy'])->name('products.destroy');
});




