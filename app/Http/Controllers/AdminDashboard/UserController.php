<?php

namespace App\Http\Controllers\AdminDashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Handled by middleware: auth, type:super-admin,admin, can:update,User
        
        $users = User::with('roles')->paginate(20);
        $roles = Role::all();

        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('users.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return redirect()->route('users.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return redirect()->route('users.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return redirect()->route('users.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // Middleware already handles authorization, but let's be safe
        // Gate::authorize('update', $user);

        $request->validate([
            'status' => 'required|in:active,inactive,suspended',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
        ]);

        $user->update([
            'status' => $request->status,
        ]);

        if ($request->has('roles')) {
            $user->roles()->sync($request->roles);
        } else {
            $user->roles()->sync([]);
        }

        return redirect()->route('users.index')->with('status', 'User updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        Gate::authorize('delete', $user);
        
        $user->delete();
        
        return redirect()->route('users.index')->with('status', 'User deleted successfully!');
    }
}
