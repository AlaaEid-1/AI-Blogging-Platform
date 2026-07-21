<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class UserMenu extends Component
{
    public $user;

    /**
     * Create a new component instance.
     */
    public function __construct(Request $request)
    {
        $this->user = Auth::user();
        // $this->user = $request->user();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.user-menu');
    }
}
