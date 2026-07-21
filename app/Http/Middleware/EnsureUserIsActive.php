<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsActive
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if ($user->status === 'active') {
            return $next($request);
        }

        if ($user->status === 'suspended') {
            Auth::logout();
            return redirect()->route('account.suspended');
            // return redirect()->route('login')->withErrors([
            //     'account' => 'Your account has been suspended Please Contact support team.'
            // ]);
        }

        if ($user->status === 'inactive') {
            return redirect()->route('account.inactive');
            // return redirect()->route('login')->withErrors([
            //     'account' => 'Your account is inactive. Please verify your email or wait for activation.'
            // ]);
        }
        abort(403);
    }
}
