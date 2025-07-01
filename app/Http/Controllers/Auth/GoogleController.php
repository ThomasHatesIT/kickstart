<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

   // In app/Http/Controllers/Auth/GoogleController.php

public function handleGoogleCallBack()
{
    try {
        $googleUser = Socialite::driver('google')->user();

        // Find an existing user by their Google ID, or by their email address.
        $user = User::where('google_id', $googleUser->getId())
                    ->orWhere('email', $googleUser->getEmail())
                    ->first();

        // === SCENARIO 1: An existing user is trying to log in ===
        if ($user) {

            // First, ensure their google_id is set if they were matched by email
            if (is_null($user->google_id)) {
                $user->update(['google_id' => $googleUser->getId()]);
            }

            // ==========================================================
            //   THE CRITICAL STATUS CHECK - THIS IS THE FIX
            // ==========================================================
            if ($user->status === 'approved') {
                Auth::login($user);
                return redirect()->route('home');
            }
            
            if ($user->status === 'pending') {
                // User's account is not yet approved. Redirect with a specific message.
                return redirect()->route('login')->with('error', 'Your account is pending approval. You cannot log in until it has been reviewed by an administrator.');
            }

            // For any other status like 'banned', 'rejected', etc.
            return redirect()->route('login')->with('error', 'Your account is not active. Please contact support.');
            // ==========================================================

        }

        // === SCENARIO 2: A completely new user is registering ===
        // The user was not found, so this is a new registration.
        // The existing logic for this is correct and will handle the multi-step registration.
        session([
            'google_user' => [
                'id'    => $googleUser->getId(),
                'name'  => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
            ]
        ]);
        
        return redirect()->route('google.register.role');

    } catch (Exception $e) {
        Log::error('Google Login Error: ' . $e->getMessage());
        return redirect()->route('login')->with('error', 'Something went wrong during Google sign-in. Please try again.');
    }
}

    /**
     * Shows the page where the user selects "Buyer" or "Seller"
     */
    public function showRoleSelectionForm()
    {
        if (!session()->has('google_user')) {
            return redirect()->route('login')->with('error', 'Session expired. Please try again.');
        }
        return view('auth.google-role-selection');
    }

    /**
     * Processes the role choice. Buyers are created instantly.
     * Sellers are redirected to the document upload form.
     */
    public function processRoleSelection(Request $request)
    {
        $request->validate(['role' => ['required', 'string', 'in:buyer,seller']]);
        if (!session()->has('google_user')) {
            return redirect()->route('login')->with('error', 'Session expired. Please try again.');
        }

        $selectedRole = $request->input('role');

        // If 'buyer', create account and log in immediately
        if ($selectedRole === 'buyer') {
            $googleUser = session('google_user');
            $newUser = User::create([
                'name'      => $googleUser['name'],
                'email'     => $googleUser['email'],
                'google_id' => $googleUser['id'],
                'password'  => Hash::make(Str::random(24)),
                'status'    => 'approved',
            ]);
            $newUser->assignRole('buyer');
            session()->forget('google_user');
            Auth::login($newUser);
            return redirect()->route('home')->with('success', 'Welcome! Your account has been created.');
        }

        // If 'seller', redirect to the document upload form
        if ($selectedRole === 'seller') {
            return redirect()->route('google.seller.documents.form');
        }
    }

    /**
     * Shows the document upload form for sellers.
     */
    public function showSellerDocumentsForm()
    {
        if (!session()->has('google_user')) {
            return redirect()->route('login')->with('error', 'Session expired. Please try again.');
        }
        return view('auth.google-seller-documents');
    }

    /**
     * Stores seller documents & creates the user with 'pending' status.
     */
    public function storeSellerFromGoogle(Request $request)
    {
        $request->validate([
            'profile_photo'   => ['nullable', 'image', 'max:2048'],
            'face_photo'      => ['required', 'image', 'max:2048'],
            'id_photo_front'  => ['required', 'image', 'max:2048'],
        ]);
        
        if (!session()->has('google_user')) {
            return redirect()->route('login')->with('error', 'Session expired. Please try again.');
        }

        $googleUser = session('google_user');
        
        // Store uploaded files and get their paths
        $profilePath = $request->file('profile_photo')?->store('profiles', 'public');
        $facePath = $request->file('face_photo')->store('verifications', 'public');
        $idPath = $request->file('id_photo_front')->store('verifications', 'public');

        // Create the user with all data
        $newUser = User::create([
            'name'                  => $googleUser['name'],
            'email'                 => $googleUser['email'],
            'google_id'             => $googleUser['id'],
            'password'              => Hash::make(Str::random(24)),
            'status'                => 'pending',
            'profile_photo_path'    => $profilePath,
            'face_photo_path'       => $facePath,
            'id_photo_front_path'   => $idPath,
        ]);
        
        $newUser->assignRole('seller');
        session()->forget('google_user');

        return redirect()->route('login')->with('status', 'Thank you! Your seller application is pending approval.');
    }
}