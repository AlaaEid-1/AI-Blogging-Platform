<?php

namespace App\Observers;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostObserver
{
    /**
     * Handle the Post "creating" event.
     */
    public function creating(Post $post): void
    {
        $post->slug = Str::slug($post->title);
        $post->user_id = Auth::id() ?? 4;
    }

    /**
     * Handle the Post "force deleted" event.
     */
    public function forceDeleted(Post $post): void
    {
        if ($post->cover_image) {
            Storage::disk('public')->delete($post->cover_image);
        }
    }
}
