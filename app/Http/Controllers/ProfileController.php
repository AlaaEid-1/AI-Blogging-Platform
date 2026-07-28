<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the public profile page for the given user.
     * Route model binding resolves {username} → User via User::getRouteKeyName().
     */
    public function show(User $user): View
    {
        $user->loadCount([
            'followers',
            'followings',
            'posts' => fn ($query) => $query->published(),
        ]);

        $isFollowing = auth()->check()
            ? auth()->user()->isFollowing($user->id)
            : false;

        $posts = $user->posts()
            ->published()
            ->with(['category', 'tags'])
            ->withCount(['favorites', 'comments'])
            ->latest('published_at')
            ->paginate(12);

        return view('users.show', compact('user', 'posts', 'isFollowing'));
    }

    /**
     * Show the profile edit form for the authenticated user.
     */
    public function edit(): View
    {
        $user = auth()->user();

        return view('users.edit', compact('user'));
    }

    /**
     * Update the authenticated user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => [
                'required',
                'string',
                'max:255',
                'regex:/^[A-Za-z0-9_]+$/',
                Rule::unique('users', 'username')->ignore($user->id),
            ],
            'bio' => ['nullable', 'string', 'max:1000'],
            'avatar' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        } else {
            unset($validated['avatar']);
        }

        $user->update($validated);

        return redirect()->route('profile.show', $user->username)
            ->with('status', 'Profile updated successfully!');
    }
}
