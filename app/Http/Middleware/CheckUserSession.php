<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class CheckUserSession
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
        if (!Session::has('user_id')) {
            Log::info('Middleware CheckUserSession: user_id not found in session.');
            return redirect()->route('login')->withErrors('You must log in to access this page.');
        }

        Log::info('Middleware CheckUserSession: user_id found in session.', ['user_id' => Session::get('user_id')]);

        return $next($request);
    }
}
