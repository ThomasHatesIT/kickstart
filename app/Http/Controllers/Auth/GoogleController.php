<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserVerificationDocument;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     */
    public function handleGoogleCallBack()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $user = User::where('google_id', $googleUser->getId())->orWhere('email', $googleUser->getEmail())->first();

            if ($user) {
                // This is an existing user. Check their status before logging in.
                if (is_null($user->google_id)) {
                    $user->update(['google_id' => $googleUser->getId()]);
                }
                if ($user->status === 'approved') {
                    Auth::login($user);
                    return redirect()->route('home');
                }
                if ($user->status === 'pending') {
                    return redirect()->route('login')->with('error', 'Your account is pending approval. You cannot log in until it is reviewed.');
                }
                return redirect()->route('login')->with('error', 'Your account is not active. Please contact support.');
            }

            // This is a new user. Start the multi-step registration process.
            session(['google_user' => [
                'id'    => $googleUser->getId(),
                'name'  => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
            ]]);
            return redirect()->route('google.register.role');

        } catch (Exception $e) {
            Log::error('Google Login Error: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Something went wrong during Google sign-in. Please try again.');
        }
    }

    /**
     * Show the view where the user selects their role.
     */
    public function showRoleSelectionForm()
    {
        if (!session()->has('google_user')) {
            return redirect()->route('login')->with('error', 'Session expired. Please try again.');
        }
        return view('auth.google-role-selection');
    }

    /**
     * Process the user's role choice.
     */
    public function processRoleSelection(Request $request)
    {
        $request->validate(['role' => ['required', 'string', 'in:buyer,seller']]);
        if (!session()->has('google_user')) {
            return redirect()->route('login')->with('error', 'Session expired. Please try again.');
        }

        $selectedRole = $request->input('role');

        if ($selectedRole === 'buyer') {
            $googleUser = session('google_user');
            $newUser = User::create([ 'name' => $googleUser['name'], 'email' => $googleUser['email'], 'google_id' => $googleUser['id'], 'password' => Hash::make(Str::random(24)), 'status' => 'approved', ]);
            $newUser->assignRole('buyer');
            session()->forget('google_user');
            Auth::login($newUser);
            return redirect()->route('home')->with('success', 'Welcome! Your account has been created.');
        }

        if ($selectedRole === 'seller') {
            return redirect()->route('google.seller.documents.form');
        }
    }

    /**
     * [THE MISSING METHOD]
     * Show the form for the seller to upload their verification documents.
     */
    public function showSellerDocumentsForm()
    {
        if (!session()->has('google_user')) {
            return redirect()->route('login')->with('error', 'Session expired. Please try again.');
        }
        // This line simply returns the view you created earlier.
        return view('auth.google-seller-documents');
    }

    /**
     * Store the seller documents and create the final user account.
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
        
        DB::transaction(function () use ($request, $googleUser) {
            $newUser = User::create([ 'name' => $googleUser['name'], 'email' => $googleUser['email'], 'google_id' => $googleUser['id'], 'password' => Hash::make(Str::random(24)), 'status' => 'pending', ]);
            $newUser->assignRole('seller');
            
            if ($request->hasFile('profile_photo')) {
                UserVerificationDocument::create([
                    'user_id' => $newUser->id,
                    'document_path' => $request->file('profile_photo')->store('user_documents', 'public'),
                    'document_type' => 'profile_photo',
                ]);
            }
            UserVerificationDocument::create([
                'user_id' => $newUser->id,
                'document_path' => $request->file('face_photo')->store('user_documents', 'public'),
                'document_type' => 'face_photo',
            ]);
            UserVerificationDocument::create([
                'user_id' => $newUser->id,
                'document_path' => $request->file('id_photo_front')->store('user_documents', 'public'),
                'document_type' => 'id_photo_front',
            ]);
        });
        
        session()->forget('google_user');
        return redirect()->route('login')->with('status', 'Thank you! Your seller application is pending approval.');
    }
}