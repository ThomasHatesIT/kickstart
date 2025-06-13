<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterUserController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SellerProductController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminProductController;





// Public routes: Registration & Login (no auth required)
Route::get('/register', [RegisterUserController::class, 'index'])->name('register');
Route::post('/register', [RegisterUserController::class, 'store']);
Route::get('/login', [SessionController::class, 'index'])->name('login');
Route::post('/login', [SessionController::class, 'store']);

// Logout requires authentication
Route::post('/logout', [SessionController::class, 'destroy'])->middleware('auth')->name('logout');


Route::get('/', [HomeController::class, 'index'])->name('home');


Route::middleware('auth','role:seller')->prefix('seller/products')->name('seller.products.')->group(function () {
    // GET /seller/products
    Route::get('/', [SellerProductController::class, 'index'])->name('index');
    // GET /seller/products/create
    Route::get('/create', [SellerProductController::class, 'create'])->name('create');
    // POST /seller/products
    Route::post('/', [SellerProductController::class, 'store'])->name('store'); 
    // GET /seller/products/{product}/edit
    Route::get('/{product}/edit', [SellerProductController::class, 'edit'])->name('edit');
    // PUT /seller/products/{product}
    Route::put('/{product}', [SellerProductController::class, 'update'])->name('update');
    // You can add the destroy route here later
    // DELETE /seller/products/{product}
    Route::delete('/{product}', [SellerProductController::class, 'destroy'])->name('destroy');

});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // ... other admin routes like dashboard
Route::get('/', [AdminController::class, 'index'])->name('dashboard');
     // User Management
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::patch('/users/{user}/ban', [AdminUserController::class, 'ban'])->name('users.ban');
    Route::patch('/users/{user}/unban', [AdminUserController::class, 'unban'])->name('users.unban');

    // Seller Status (Assuming these are in AdminUserController too, or move them)
    Route::patch('/sellers/{user}/approve', [AdminUserController::class, 'approveSeller'])->name('sellers.approve');
     Route::patch('/sellers/{user}/reject', [AdminUserController::class, 'rejectSeller'])->name('sellers.reject');

Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [AdminProductController::class, 'show'])->name('products.show');



});
  


