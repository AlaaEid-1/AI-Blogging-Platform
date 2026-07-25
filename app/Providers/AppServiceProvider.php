<?php

namespace App\Providers;

use App\Events\PostViewed;
use App\Listeners\IncrementPostViews;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind('APP_CONFIG', function () {
            return config('app');
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (request()->is('dashboard/*')) {
            Paginator::useTailwind();
        } else {
            Paginator::defaultView('pagination.custom-tailwind');
        }

        JsonResource::withoutWrapping();

        //Event::listen(PostViewed::class, IncrementPostViews::class);

        Gate::before(function ($user, $ability) {
            if ($user->type == 'super-admin') {
                return true;
            }
        });

        foreach (config('abilities') as $key => $value) {
            Gate::define($key, function ($user) use ($key): bool {
                foreach ($user->roles as $role) {
                    if (in_array($key, $role->abilities)) {
                        return true;
                    }
                }
                return false;
            });
        }

        View::composer('components.layout.right-sidebar', function ($view) {
            $popularAuthors = Cache::get('popular_authors');

            $isValid = $popularAuthors instanceof \Illuminate\Database\Eloquent\Collection;
            if ($isValid && $popularAuthors->isNotEmpty()) {
                if (! ($popularAuthors->first() instanceof User)) {
                    $isValid = false;
                }
            }

            if (! $isValid) {
                $popularAuthors = User::has('posts')
                    ->withCount('posts')
                    ->orderByDesc('posts_count')
                    ->take(4)
                    ->get();
                Cache::put('popular_authors', $popularAuthors, now()->addHour());
            }

            $view->with('popularAuthors', $popularAuthors);
        });
    }
}
