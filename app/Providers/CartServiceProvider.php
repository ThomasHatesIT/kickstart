<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class CartServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Use a view composer to share the cart count with the navbar partial
        // This will run every time the '_navbar.blade.php' view is rendered.
        View::composer('partials._navbar', function ($view) {
            $cartCount = 0;
            if (Auth::check()) {
                // Get the count of unique items in the cart
                $cartCount = Auth::user()->cartItems()->count();
            }
            $view->with('cartCount', $cartCount);
        });
    }
}