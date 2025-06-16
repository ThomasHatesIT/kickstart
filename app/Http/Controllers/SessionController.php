<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class SessionController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $credentials['email'])->first();

        // Check user status BEFORE attempting to log them in
        if ($user) {
            if ($user->isBanned()) {
                throw ValidationException::withMessages([
                    'email' => 'This account has been suspended.',
                ]);
            }

            if ($user->isRejected()) {
                throw ValidationException::withMessages([
                    'email' => 'Your account registration was rejected. Please contact support.',
                ]);
            }

            if ($user->needsApproval()) {
                throw ValidationException::withMessages([
                    'email' => 'This account is pending admin approval. Please wait for an email notification.',
                ]);
            }
        }
        
        // Attempt to authenticate
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();
            
            if ($user->hasRole('admin')) {
                return redirect()->intended(route('admin.dashboard')); // Use intended() for smarter redirects
            }
            if ($user->hasRole('seller')) {
                return redirect()->intended(route('seller.dashboard'));
            }

            return redirect()->intended('/'); // for buyer or others
        }
        
        // If authentication fails
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function destroy()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/');
    }
}