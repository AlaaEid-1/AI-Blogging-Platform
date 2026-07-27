<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UpdateUserLastActivityTime
{
    /**
     * How often (in seconds) to write last_activity_at to the database.
     * Within this window the session timestamp is used instead, avoiding
     * a synchronous DB write on every single request.
     */
    private const UPDATE_INTERVAL = 60;

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && $request->hasSession()) {
            $lastUpdated = $request->session()->get('last_activity_updated_at', 0);

            if ((time() - $lastUpdated) >= self::UPDATE_INTERVAL) {
                $request->user()->forceFill([
                    'last_activity_at' => now(),
                ])->save();

                $request->session()->put('last_activity_updated_at', time());
            }
        }

        return $next($request);
    }
}
