<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Users\UserController;
use Illuminate\Support\Facades\Route;

// guest
Route::middleware('guest')->controller(AuthController::class)->group(function () {
    Route::post('/register', 'register')->name('register');
    Route::post('/login', 'login')->name('login');
});

// auth
Route::middleware('auth:sanctum')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout')
        ->middleware('guest');

    // Auth user
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
});

Route::fallback(function () {
    abort(404);
});
