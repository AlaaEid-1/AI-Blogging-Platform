<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function toggle(Request $request, $postId)
    {
        $post = \App\Models\Post::findOrFail($postId);
        $favorite = $request->user()->favorites()->where('post_id', $post->id)->first();

        if ($favorite) {
            $favorite->delete();
            return back()->with('status', 'Removed from favorites.');
        } else {
            $request->user()->favorites()->create(['post_id' => $post->id]);
            
            if ($post->user_id !== $request->user()->id && $post->user) {
                $post->user->notify(new \App\Notifications\PostFavoritedNotification($post, $request->user()));
            }

            return back()->with('status', 'Added to favorites.');
        }
    }
}
