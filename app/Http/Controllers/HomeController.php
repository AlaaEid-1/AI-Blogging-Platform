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
            ->latest()
            ->paginate(10);

        return "HOME WORKS";
    }
}
