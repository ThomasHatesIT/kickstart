<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterUserController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\HomeController;




// Public routes: Registration & Login (no auth required)
Route::get('/register', [RegisterUserController::class, 'index'])->name('register');
Route::post('/register', [RegisterUserController::class, 'store']);
Route::get('/login', [SessionController::class, 'index'])->name('login');
Route::post('/login', [SessionController::class, 'store']);

// Logout requires authentication
Route::post('/logout', [SessionController::class, 'destroy'])->middleware('auth')->name('logout');


Route::get('/', [HomeController::class, 'index'])->name('home');