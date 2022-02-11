<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check() && Auth::user()->role_id == 1) {
                return redirect()->route('admin.dashboard');
            } else if (Auth::guard($guard)->check() && Auth::user()->role_id == 2) {
                return redirect()->route('pengusul.dashboard');
            } else if (Auth::guard($guard)->check() && Auth::user()->role_id == 3) {
                return redirect()->route('reviewer.dashboard');
            }
        }

        return $next($request);
    }
}
