<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show($username)
    {
        $user = \App\Models\User::where('username', $username)->firstOrFail();
        
        $posts = $user->posts()
            ->published()
            ->with(['user', 'tags'])
            ->withCount(['favorites', 'comments'])
            ->latest()
            ->paginate(12);

        return view('users.show', compact('user', 'posts'));
    }
}
