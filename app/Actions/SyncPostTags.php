<?php

namespace App\Actions;

use App\Models\Post;
use Illuminate\Support\Str;

class SyncPostTags
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function handle(Post $post, string|array $tags): void
    {
        $tags = is_string($tags) ? explode(',', $tags) : $tags;
        if (empty($tags)) {
            $post->tags()->detach();
            return;
        }

        $tags_ids = [];
        foreach ($tags as $tag_name) {
            $tag_name = trim($tag_name);
            if (empty($tag_name)) {
                continue;
            }
            $tag_slug = Str::slug($tag_name);
            $tag = \App\Models\Tag::firstOrCreate(
                ['slug' => $tag_slug],
                ['name' => $tag_name]
            );
            $tags_ids[] = $tag->id;
        }
        $post->tags()->sync($tags_ids);
    }
}
