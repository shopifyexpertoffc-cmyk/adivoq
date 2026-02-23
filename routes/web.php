<?php

use App\Http\Controllers\Tenant\AuthController as TenantAuthController;
use App\Http\Controllers\Tenant\DashboardController;

// Landing page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Tenant login routes
Route::middleware('guest:web')->group(function () {
    Route::get('/login', [TenantAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [TenantAuthController::class, 'login'])->name('login.submit');
});

Route::middleware('auth:web')->group(function () {
    Route::post('/logout', [TenantAuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // Other tenant routes here...
});