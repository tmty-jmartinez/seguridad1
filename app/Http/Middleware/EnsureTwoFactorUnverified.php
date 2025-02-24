<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class EnsureTwoFactorUnverified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && !auth()->user()->two_factor_code) {
            return redirect()->route('login')->withErrors('Please log in again.');
        }

        if (auth()->check() && auth()->user()->two_factor_code) {
            session(['user_id' => auth()->user()->id]);
        }

        return $next($request);
    }
}
