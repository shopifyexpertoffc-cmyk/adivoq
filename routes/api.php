<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('api')->group(function () {
    // Waitlist API
    Route::post('/waitlist', [\App\Http\Controllers\Api\WaitlistController::class, 'store']);
    Route::get('/waitlist/count', [\App\Http\Controllers\Api\WaitlistController::class, 'count']);
});