<?php

use App\Http\Controllers\Central\WorkspaceController;
use Illuminate\Support\Facades\Route;

// Landing page on the central/main domain
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Workspace finder on the central/main domain
Route::middleware('guest')->group(function () {
    Route::get('/login', [WorkspaceController::class, 'showForm'])->name('login');
    Route::get('/workspace', [WorkspaceController::class, 'showForm'])->name('central.workspace');
    Route::post('/workspace', [WorkspaceController::class, 'redirect'])->name('central.tenant.redirect');
});

// Central admin routes
require __DIR__ . '/admin.php';
