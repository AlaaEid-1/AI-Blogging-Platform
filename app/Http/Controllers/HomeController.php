<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return view('home', [
            'posts' => Post::query()
                ->published()
                ->with(['user', 'tags'])
                ->withCount(['favorites', 'comments'])
                ->latest()
                ->get()
        ]);
    }
}
