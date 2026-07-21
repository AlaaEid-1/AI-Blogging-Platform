<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class OwnerScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->type === 'super-admin' || $user->hasAbility('posts.manage_all')) {
                return; // Skip adding global scope for super-admins and editors
            }

            if (Route::is('dashboard.*')) {
                $builder->where('user_id', $user->id);
            }
        }
    }
}
