<?php

namespace App\Http\Controllers\Auth;


use Illuminate\Support\Facades\Auth; // Make sure this is at the top
use Illuminate\Support\Facades\Hash; // Add this
use Illuminate\Support\Str;          // Add this
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

use Illuminate\Http\Request;

class GoogleController extends Controller
{

    public function redirectToGoogle(Request $request){
     return Socialite::driver('google')->redirect();

   }

 public function handleGoogleCallBack()
{
    try {
        $googleUser = Socialite::driver('google')->user();
        $user = User::updateOrCreate(
            [
                'google_id' => $googleUser->getId(),
            ],
            [
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),

                'password' => Hash::make(Str::random(24)),
            ]
        );

        // Log the user in
        Auth::login($user);

        // Redirect to your intended page after login
        return redirect()->route('home')->with('success', 'Successfully logged in with Google!');

    } catch (\Exception $e) {

        \Log::error('Google Login Error: ' . $e->getMessage());
        return redirect()->route('login')->with('error', 'Something went wrong during Google sign-in. Please try again.');
    }
}

}
