<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserType
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$types): Response
    {
        $user = $request->user();
        if (! in_array($user->type, $types)) {
            abort(403);
        }

        $response = $next($request);

        //$response->headers->set('x-custom', 'ssss');

        return $response;
    }
}
