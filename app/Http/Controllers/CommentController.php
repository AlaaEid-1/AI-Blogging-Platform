<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Notifications\PostCommentedNotification;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, $postId)
    {
        $request->validate(['content' => 'required|string|max:1000']);
        $post = Post::findOrFail($postId);

        $comment = $post->comments()->create([
            'user_id' => $request->user()->id,
            'user_name' => $request->user()->name,
            'content' => $request->content,
        ]);

        if ($post->user_id !== $request->user()->id && $post->user) {
            $post->user->notify(new PostCommentedNotification($post, $request->user(), $comment->content));
        }

        return back()->with('status', 'Comment added.');
    }

    public function destroy(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);

        if ($request->user()->id !== $comment->user_id && ! $request->user()->hasAbility('posts.manage_all')) {
            abort(403);
        }

        $comment->delete();

        return back()->with('status', 'Comment deleted.');
    }
}
