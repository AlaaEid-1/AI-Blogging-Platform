<?php

use App\Http\Middleware\EnsureUserIsActive;
use App\Http\Middleware\EnsureUserType;
use App\Http\Middleware\UpdateUserLastActivityTime;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        channels: __DIR__ . '/../routes/channels.php',
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        api: __DIR__ . '/../routes/api.php',
        apiPrefix: 'app/api/'
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web([
            UpdateUserLastActivityTime::class,
        ]);
        $middleware->api([
            UpdateUserLastActivityTime::class,
        ]);

        $middleware->encryptCookies([
            'post-views',
        ]);
        $middleware->alias([
            'type' => EnsureUserType::class,
            'active' => EnsureUserIsActive::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->withBroadcasting(__DIR__ . '/../routes/channels.php')
    ->create();
