<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BookmarkController extends Controller
{
    public function toggle(Request $request, $postId)
    {
        $post = \App\Models\Post::findOrFail($postId);
        $bookmark = $request->user()->bookmarks()->where('post_id', $post->id)->first();

        if ($bookmark) {
            $bookmark->delete();
            return back()->with('status', 'Removed from bookmarks.');
        } else {
            $request->user()->bookmarks()->create(['post_id' => $post->id]);
            return back()->with('status', 'Added to bookmarks.');
        }
    }
}
