<?php

namespace App\View\Components;

use App\Models\User;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class RecommendedAuthors extends Component
{

    public $authors;

    /**
     * Create a new component instance.
     */
    public function __construct(public $title = 'Recommended Authors', $count = 3)
    {
        /*
        SELECT users.*, 
        (SELECT 1 followers WHERE followers.follower_id = users.id AND followers.user_id = :auth) AS followings_exists 
        FROM users LIMIT :count;
        */

        $this->authors = User::query()
            ->withExists([
                'followers' => function ($query) {
                    $query->where('follower_id', Auth::id() ?? 0);
                }
            ])
            ->where('id', '<>', Auth::id() ?? 0)
            ->limit($count)
            ->get();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.recommended-authors');
    }
}
