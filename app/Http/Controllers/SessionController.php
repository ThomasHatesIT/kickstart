<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Traits\HasRoles;
use App\Models\User;

class SessionController extends Controller
{
   public function index(){
    return view('auth.login');
   }

  public function store(Request $request)
{
    $credentials = $request->validate([
        'email'    => ['required', 'email'],
        'password' => ['required'],
    ]);

    $user = User::where('email', $credentials['email'])->first();

    if ($user) {
        if ($user->isRejected()) {
            throw ValidationException::withMessages([
                'email' => 'Your account has been rejected. Please contact support.',
            ]);
        }

        if ($user->needsApproval()) {
            throw ValidationException::withMessages([
                'email' => 'Your account is pending approval. Please wait for admin approval.',
            ]);
        }
    }

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        $user = Auth::user();
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('seller')) {
            return redirect()->route('seller.products.index');
        } else {
            return redirect('/'); // for buyer or others
        }
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