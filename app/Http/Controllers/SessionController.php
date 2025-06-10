<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class SessionController extends Controller
{
   public function index(){
    return view('auth.login');
   }

   public function store(Request $request){

        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Attempt to find the user first
        $user = User::where('email', $credentials['email'])->first();

        if ($user) {
            // Check if user is rejected
            if ($user->isRejected()) {
                throw ValidationException::withMessages([
                    'email' => 'Your account has been rejected. Please contact support.',
                ]);
            }

            // Check if user needs approval (instructor with pending status)
            if ($user->needsApproval()) {
                throw ValidationException::withMessages([
                    'email' => 'Your account is pending approval. Please wait for admin approval.',
                ]);
            }
        }

        // Attempt authentication
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); // Prevent session fixation
            return redirect()->intended('/')->with('message', 'Logged in successfully!');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials. Email or password does not match our records.',
        ])->onlyInput('email');
   }

   public function destroy(){
     Auth::logout();

        return redirect('/');
   }
}