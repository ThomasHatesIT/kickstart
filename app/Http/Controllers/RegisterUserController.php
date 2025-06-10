<?php 

namespace App\Http\Controllers; 

use Spatie\Permission\Models\Permission; 
use Spatie\Permission\Models\Role; 

use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Hash; 
use Illuminate\Validation\Rules\Password; 
use Illuminate\Support\Facades\Auth; 
use App\Models\User; 

class RegisterUserController extends Controller 
{ 
    public function index(){
        $roles = Role::all(); 
        return view('auth.register', [  
            'roles' => $roles 
        ]); 
    } 

    public function store(Request $request) 
    { 
        $attributes = $request->validate([ 
            'name' => ['required', 'string', 'max:255'], 
           
            'email'      => ['required', 'email', 'unique:users,email'], 
            'password'   => ['required', Password::default(), 'confirmed'], 
            'role'       => ['required', 'string', 'exists:roles,name'], 
        ]); 

        // Determine status based on role
        $status = $attributes['role'] === 'Instructor' ? 'pending' : 'approved';

        // Create the user 
        $user = User::create([ 
            'first_name' => $attributes['first_name'], 
          
            'email'      => $attributes['email'], 
            'password'   => Hash::make($attributes['password']),
            'status'     => $status,
        ]); 

        // Assign role using Spatie
        $user->assignRole($attributes['role']); 

        // Handle different scenarios based on role
        if ($attributes['role'] === 'seller') {
            // Don't log in instructor users automatically
            return redirect('/login')->with('message', 'Registration successful! Your account is pending approval. You will be notified once an admin approves your account.');
        } else {
            // Log in other users normally (students, etc.)
            Auth::login($user); 
            return redirect('/')->with('message', 'User registered and logged in successfully!'); 
        }
    } 
}