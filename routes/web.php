<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;

Route::middleware('guest')->group(function () {
    Route::view('/login', 'auth.login')->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    Route::view('/register', 'auth.register')->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');

    Route::middleware(['check_user'])->group(function () {
        Route::view('/verify-2fa', 'verify-2fa')->name('verify-2fa');
        Route::post('/verify-2fa', [AuthController::class, 'verifyTwoFactorCode'])->name('verify-2fa.post');
    });
});

Route::middleware('auth')->group(function () {
    Route::view('/home', 'home')->name('home');

    Route::post('/logout', function () {
        $user = Auth::user();

        if ($user) {
            $user->update([
                'two_factor_code' => null,
                'two_factor_expires_at' => null,
            ]);
        }

        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Logged out successfully.');
    })->name('logout');
});

Route::redirect('/', '/login');
