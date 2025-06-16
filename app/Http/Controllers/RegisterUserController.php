<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Validation\Rule;

class RegisterUserController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        return view('auth.register', ['roles' => $roles]);
    }

    public function store(Request $request)
    {
        // 1. VALIDATE THE INCOMING REQUEST
        $attributes = $request->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password'              => ['required', 'confirmed', Password::min(8)],
            'role'                  => ['required', 'string', 'exists:roles,name'],
            'profile_photo'         => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'], // For general profile picture
            
            // These fields are only required if the user registers as a seller.
            'face_photo'            => ['required_if:role,seller', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'id_photo_front'        => ['required_if:role,seller', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        // 2. HANDLE FILE UPLOADS
        $userPayload = [
            'name'     => $attributes['name'],
            'email'    => $attributes['email'],
            'password' => Hash::make($attributes['password']),
            'status'   => $attributes['role'] === 'seller' ? 'pending' : 'approved',
        ];
        
        // Handle optional profile photo
        if ($request->hasFile('profile_photo')) {
            $userPayload['profile_photo_path'] = $request->file('profile_photo')->store('profile-photos', 'public');
        }

        // Handle required seller-specific photos
        if ($attributes['role'] === 'seller') {
            $userPayload['face_photo_path'] = $request->file('face_photo')->store('verification/faces', 'public');
        }

        // 3. CREATE THE USER
        $user = User::create($userPayload);

        // 4. UPLOAD VERIFICATION DOCUMENT (ID FRONT) IF SELLER
        if ($attributes['role'] === 'seller') {
            $idPhotoPath = $request->file('id_photo_front')->store('verification/documents', 'public');
            $user->verificationDocuments()->create([
                'document_path' => $idPhotoPath,
                'document_type' => 'id_front',
            ]);
        }
        
        // 5. ASSIGN THE ROLE
        $user->assignRole($attributes['role']);
        
        // 6. HANDLE CONDITIONAL LOGIN AND REDIRECT
        if ($user->hasRole('seller')) {
            return redirect('/login')->with('success', 'Registration successful! Your seller account is now pending admin approval.');
        } else {
            Auth::login($user);
            $request->session()->regenerate();
            return redirect('/')->with('success', 'Welcome! You have registered and logged in.');
        }
    }
}