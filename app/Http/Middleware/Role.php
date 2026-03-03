<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed ...$roles   // roles from 'role:admin,user,...'
     */
    public function handle($request, Closure $next, ...$roles)
    {
        // Not logged in → go to login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // If the user's role is NOT one of the allowed roles
        if (! in_array($user->role, $roles, true)) {
            // ❌ DO NOT redirect to 'dashboard' – that caused the loop
            // You can either:
            // abort(403);  // Forbidden
            return abort(403);
            // or: return redirect()->route('home');
        }

        // Allowed → continue
        return $next($request);
    }
}
