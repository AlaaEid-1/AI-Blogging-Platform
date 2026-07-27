<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class HomeController extends Controller
{
 public function __invoke(Request $request)
{
    $posts = Post::query()
        ->published()
        ->with(['user', 'tags'])
        ->latest()
        ->paginate(2);

    return view('home', compact('posts'));
}
}
