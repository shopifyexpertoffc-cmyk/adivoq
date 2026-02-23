<?php

use App\Http\Controllers\Central\WorkspaceController;
use Illuminate\Support\Facades\Route;

$centralRoutes = static function (): void {
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
};

$centralDomains = config('tenancy.central_domains', []);

if (! empty($centralDomains)) {
    foreach ($centralDomains as $domain) {
        Route::domain($domain)->group($centralRoutes);
    }
} else {
    Route::group([], $centralRoutes);
}
