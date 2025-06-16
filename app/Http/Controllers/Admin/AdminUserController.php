<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
class AdminUserController extends Controller
{
    /**
     * Display a listing of all users.
     */
    public function index(Request $request)
    {
        // Start building the query
        $query = User::with('roles')->latest();

        // Apply search filter if provided
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('email', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Apply role filter if provided
        if ($request->filled('role')) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('name', $request->input('role'));
            });
        }

        // Apply status filter if provided
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Paginate the results, and importantly, append the query string to pagination links
        $users = $query->paginate(10)->withQueryString();

        // Get all roles to populate the filter dropdown
        $roles = Role::all();

        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * Approve a pending seller account.
     */
    public function approveSeller(User $user)
    {
        // You could add extra checks here if needed
        $user->update(['status' => 'approved']);

        // TODO: Optionally send an email notification to the seller
        // Mail::to($user->email)->send(new SellerAccountApproved($user));

        return redirect()->route('admin.users.index')
                         ->with('success', "Seller '{$user->name}' has been approved.");
    }

    /**
     * Reject a pending seller account.
     */
    public function rejectSeller(User $user)
    {
        $user->update(['status' => 'rejected']);

        // TODO: Optionally send an email notification to the seller
        // Mail::to($user->email)->send(new SellerAccountRejected($user));

        return redirect()->route('admin.users.index')
                         ->with('warning', "Seller '{$user->name}' has been rejected.");
    }

     public function ban(User $user)
    {
        // Prevent an admin from banning themselves
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot ban yourself.');
        }

        $user->update(['status' => 'banned']);

        return redirect()->route('admin.users.index')->with('warning', "User '{$user->name}' has been banned.");
    }

    /**
     * Unban a user.
     */
    public function unban(User $user)
    {
        // When unbanning, set their status back to 'approved'
        $user->update(['status' => 'approved']);

        return redirect()->route('admin.users.index')->with('success', "User '{$user->name}' has been unbanned.");
    }
    public function show (User $user){

        return view('admin.users.show',[
            'user' => $user
        ]);
    }

}