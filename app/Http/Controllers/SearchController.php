<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    /**
     * Handle search requests.
     */
    public function index(Request $request): View
    {
        $query = $request->input('query');

        $posts = Post::query()
            ->with(['user', 'category'])
            ->published()
            ->when($query, function ($builder, $query) {
                $builder->where(function ($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%")
                        ->orWhere('content', 'like', "%{$query}%");
                });
            })
            ->latest()
            ->paginate(6)
            ->withQueryString();

        return view('search', [
            'posts' => $posts,
            'query' => $query,
        ]);
    }
}
