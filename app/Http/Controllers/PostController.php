<?php

namespace App\Http\Controllers;

use App\Enums\PostStatus;
use App\Events\PostViewed;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{

    public function index()
    {
        $posts = Post::query()
            ->published()
            ->with(['user', 'tags'])
            ->withCount(['favorites', 'comments'])
            ->latest()
            ->get();

        return view('posts.index', [
            'posts' => $posts,
        ]);
    }

    public function show(string $slug)
    {
        $post = Post::query()
            ->published()
            ->withCount('favorites', 'comments')
            ->with(['comments' => fn($q) => $q->latest()])
            ->slug($slug)
            ->firstOrFail();

        //event('posts.viewed', $post);
        broadcast(new PostViewed($post))->toOthers();

        return view('posts.show', [
            'post' => $post,
        ]);
    }
}
