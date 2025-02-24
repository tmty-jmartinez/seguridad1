<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TwoFactorMiddleware
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
        $user = Auth::user();

        // Si el usuario no tiene código 2FA activo, puede pasar
        if (!$user || is_null($user->two_factor_code)) {
            return $next($request);
        }

        // Si el usuario aún tiene un código activo, redirigirlo a la verificación
        return redirect()->route('verify-2fa')->with('error', 'You need to verify your account first.');
    }
}
