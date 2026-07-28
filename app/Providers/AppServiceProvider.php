<?php

namespace App\Providers;

use App\Events\PostViewed;
use App\Listeners\IncrementPostViews;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
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

        // Event::listen(PostViewed::class, IncrementPostViews::class);

        Gate::before(function ($user, $ability) {
            if ($user->type === 'super-admin' || $user->type === 'admin') {
                return true;
            }
        });

        foreach (config('abilities') as $key => $value) {
            Gate::define($key, function ($user) use ($key): bool {
                return $user->hasAbility($key);
            });
        }

        View::composer('components.layout.right-sidebar', function ($view) {
            $popularAuthors = collect();

            $view->with('popularAuthors', $popularAuthors);
        });
    }
}
