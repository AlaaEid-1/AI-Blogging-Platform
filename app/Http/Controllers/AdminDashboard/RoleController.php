<?php

namespace App\Http\Controllers\AdminDashboard;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
   public function index(Request $request)
{
    $roles = Role::withCount('users')->get();

    $editRole = null; 

    if ($request->has('edit')) {
        $editRole = Role::find($request->edit);
    }

    return view('roles.index', compact('roles', 'editRole'));
}
    public function create()
    {
        return redirect()->route('roles.index');
    }

    // public function edit(Role $role)
    // {
    //     return view('roles.index', ['edit' => $role->id]);
    // }
public function update(Request $request, Role $role)
{
    $role->update([
        'name' => $request->name,
        'abilities' => $request->abilities ?? [],
    ]);

    return redirect()->route('roles.index');
}
    public function show(Role $role)
    {
        return view('roles.show', compact('role'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'abilities' => 'array'
        ]);

        Role::create([
            'name' => $request->name,
            'abilities' => $request->abilities ?? []
        ]);

        return redirect()->back();
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->back();
    }
}
