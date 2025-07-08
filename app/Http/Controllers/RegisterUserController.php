<?php

namespace App\Http\Controllers;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;
use Illuminate\Auth\Events\Registered;

class RegisterUserController extends Controller
{
    /**
     * Display the registration view.
     * It fetches the available roles and passes them to the form.
     */
    public function index()
    {
        // Fetch all roles except 'admin' to display in the dropdown.
        $roles = Role::where('name', '!=', 'admin')->get();

        // Return the view and provide it with the $roles variable.
        return view('auth.register', ['roles' => $roles]);
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request)
    {
        // 1. Validate the incoming form data.
        $attributes = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'email'          => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password'       => ['required', 'confirmed', Password::min(8)],
            'role'           => ['required', 'string', 'exists:roles,name'],
            'face_photo'     => ['required_if:role,seller', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'id_photo_front' => ['required_if:role,seller', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        // 2. Use a database transaction to ensure all operations succeed or none do.
        DB::transaction(function () use ($attributes, $request) {
            
            // 3. Create the user in the 'users' table.
            $user = User::create([
                'name'     => $attributes['name'],
                'email'    => $attributes['email'],
                'password' => Hash::make($attributes['password']),
                'status'   => $attributes['role'] === 'seller' ? 'pending' : 'approved',
            ]);

            // 4. If the role is 'seller', store their verification documents.
            if ($attributes['role'] === 'seller') {
                if ($request->hasFile('face_photo')) {
                    $path = $request->file('face_photo')->store('user_documents', 'public');
                    $user->verificationDocuments()->create(['document_path' => $path, 'document_type' => 'face_photo']);
                }
                if ($request->hasFile('id_photo_front')) {
                    $path = $request->file('id_photo_front')->store('user_documents', 'public');
                    $user->verificationDocuments()->create(['document_path' => $path, 'document_type' => 'id_front']);
                }
            }
            
            // 5. THIS IS THE KEY PART: Assign the selected role to the new user.
            // This action creates the record in the 'model_has_roles' table.
            $user->assignRole($attributes['role']);
            
            // 6. Trigger the 'Registered' event. Laravel's listener will automatically
            // send the verification email because your User model implements MustVerifyEmail.
            event(new Registered($user));
        });

        // 7. Redirect the user to the login page with a success message.
        return redirect()->route('login')->with('status', 'Registration successful! A verification link has been sent to your email address.');
    }
}