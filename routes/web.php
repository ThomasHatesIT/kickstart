<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterUserController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SellerProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminProductController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/


// ========================================================================
// 1. ALL STATIC AND SPECIFIC ROUTES FIRST
// ========================================================================

// Homepage
Route::get('/', [HomeController::class, 'index'])->name('home');

// Authentication routes
Route::get('/register', [RegisterUserController::class, 'index'])->name('register');
Route::post('/register', [RegisterUserController::class, 'store']);
Route::get('/login', [SessionController::class, 'index'])->name('login');
Route::post('/login', [SessionController::class, 'store']);
Route::post('/logout', [SessionController::class, 'destroy'])->middleware('auth')->name('logout');


// ========================================================================
// 2. ALL GROUPED AND PREFIXED ROUTES
// ========================================================================

// Cart routes (auth required)
Route::middleware(['auth'])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::patch('/cart/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cartItem}', [CartController::class, 'destroy'])->name('cart.destroy');
});

// Seller routes (auth & role required)
Route::middleware(['auth', 'role:seller'])->prefix('seller/products')->name('seller.products.')->group(function () {
    Route::get('/', [SellerProductController::class, 'index'])->name('index');
    Route::get('/create', [SellerProductController::class, 'create'])->name('create');
    Route::post('/', [SellerProductController::class, 'store'])->name('store'); 
    Route::get('/{product}/edit', [SellerProductController::class, 'edit'])->name('edit');
    Route::put('/{product}', [SellerProductController::class, 'update'])->name('update');
    Route::delete('/{product}', [SellerProductController::class, 'destroy'])->name('destroy');
});

// Admin routes (auth & role required)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    // User Management
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');
    Route::patch('/users/{user}/ban', [AdminUserController::class, 'ban'])->name('users.ban');
    Route::patch('/users/{user}/unban', [AdminUserController::class, 'unban'])->name('users.unban');
    // Seller Status
    Route::patch('/sellers/{user}/approve', [AdminUserController::class, 'approveSeller'])->name('sellers.approve');
    Route::patch('/sellers/{user}/reject', [AdminUserController::class, 'rejectSeller'])->name('sellers.reject');
    // Product Management
    Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
    Route::get('/products/{product}', [AdminProductController::class, 'show'])->name('products.show');
    Route::patch('/products/{product}/approve', [AdminProductController::class, 'approve'])->name('products.approve');
    Route::patch('/products/{product}/reject', [AdminProductController::class, 'reject'])->name('products.reject');
});


// ========================================================================
// 3. CATCH-ALL DYNAMIC ROUTES LAST
// ========================================================================

// This must be one of the LAST public routes defined.
Route::get('/{product}', [HomeController::class, 'show'])->name('show');