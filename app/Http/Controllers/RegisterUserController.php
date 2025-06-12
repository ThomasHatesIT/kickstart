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
        // 1. VALIDATE THE INCOMING REQUEST
        $attributes = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'role'     => ['required', 'string', 'exists:roles,name'],
        ]);

        // 2. CREATE THE USER
        // Determine the user's initial status based on their role
        $status = $attributes['role'] === 'seller' ? 'pending' : 'approved';

        $user = User::create([
            'name'     => $attributes['name'],
            'email'    => $attributes['email'],
            'password' => Hash::make($attributes['password']),
            'status'   => $status,
        ]);

        // 3. ASSIGN THE ROLE TO THE NEW USER
        $user->assignRole($attributes['role']);

        // 4. HANDLE CONDITIONAL LOGIN AND REDIRECT
        // This is the clean, logical way to handle the flow.

        if ($user->hasRole('seller')) {
            // A seller's account is pending, so DO NOT log them in.
            // Redirect them to the login page with a success message.
            return redirect('/login')
                ->with('success', 'Registration successful! Your account is pending admin approval. You will be notified via email once it is approved.');
        } else {
            // For any other role (e.g., 'buyer'), log them in immediately.
            Auth::login($user);

            // Regenerate the session ID for security (prevents session fixation)
            $request->session()->regenerate();

            // Redirect the newly logged-in user directly to the homepage.
            return redirect('/')
                ->with('success', 'Welcome! You have been registered and successfully logged in.');
        }
    }
}