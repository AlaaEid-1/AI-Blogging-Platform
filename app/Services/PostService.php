<?php

namespace App\Services;

use App\Actions\FileUpload;
use App\Actions\SyncPostTags;
use App\Http\Requests\PostRequest;
use App\Models\Post;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

class PostService
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected FileUpload $fileUpload, protected SyncPostTags $syncTags)
    {
        //
    }

    public function create(PostRequest $request): Post
    {
        $clean = $request->validated();

        $data = array_merge($clean, [
            'status' => 'published',
            'cover_image' => $this->fileUpload->handle(key: 'cover', path: 'covers'),
        ]);

        DB::beginTransaction();

        try {
            $post = Post::create($data);
            $this->syncTags->handle($post, $clean['tags'] ?? '');

            DB::commit();

            return $post;
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update(Post $post, PostRequest $request): Post
    {
        $clean = $request->validated();
        $data = \array_merge($clean, [
            'cover_image' => $this->fileUpload->handle(key: 'cover', path: 'covers')
        ]);

        try {
            DB::transaction(function () use ($post, $data, $clean) {
                $post->update($data);

                $this->syncTags->handle($post, $clean['tags'] ?? '');
            });

            $previous = $post->getPrevious();
            $prev_cover_image = $previous['cover_image'] ?? null;
            if ($prev_cover_image && $prev_cover_image !== $post->cover_image) {
                Storage::disk('public')->delete($previous['cover_image']); // Delete the old cover image from storage
            }

            return $post;
        } catch (Throwable $e) {
            throw $e;
        }
    }
}
