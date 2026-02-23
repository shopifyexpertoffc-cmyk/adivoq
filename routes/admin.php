<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TenantController;
use App\Http\Controllers\Admin\WaitlistController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\BlogController as AdminBlogController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

// Admin Auth Routes (Guest)
Route::middleware('web')->prefix('admin')->name('admin.')->group(function () {
    
    // Guest Routes
    Route::middleware('guest:admin')->group(function () {
        Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [AuthController::class, 'login'])->name('login.submit');
        Route::get('forgot-password', [AuthController::class, 'showForgotForm'])->name('password.request');
        Route::post('forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
        Route::get('reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
        Route::post('reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
    });

    // Authenticated Admin Routes
    Route::middleware('admin')->group(function () {
        // Logout
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        
        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
        Route::get('dashboard/stats', [DashboardController::class, 'stats'])->name('dashboard.stats');
        
        // Tenants Management
        Route::resource('tenants', TenantController::class);
        Route::post('tenants/{tenant}/suspend', [TenantController::class, 'suspend'])->name('tenants.suspend');
        Route::post('tenants/{tenant}/activate', [TenantController::class, 'activate'])->name('tenants.activate');
        Route::post('tenants/{tenant}/impersonate', [TenantController::class, 'impersonate'])->name('tenants.impersonate');
        
        // Waitlist Management
        Route::resource('waitlist', WaitlistController::class)->only(['index', 'show', 'destroy']);
        Route::post('waitlist/{waitlist}/invite', [WaitlistController::class, 'invite'])->name('waitlist.invite');
        Route::post('waitlist/export', [WaitlistController::class, 'export'])->name('waitlist.export');
        
        // Contact Enquiries
        Route::resource('contacts', ContactController::class)->only(['index', 'show', 'destroy']);
        Route::post('contacts/{contact}/mark-read', [ContactController::class, 'markRead'])->name('contacts.markRead');
        Route::post('contacts/{contact}/reply', [ContactController::class, 'reply'])->name('contacts.reply');
        
        // Plans Management
        Route::resource('plans', PlanController::class);
        Route::post('plans/{plan}/toggle-status', [PlanController::class, 'toggleStatus'])->name('plans.toggleStatus');
        
        // Activity Logs
        Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
        Route::get('activity-logs/{activityLog}', [ActivityLogController::class, 'show'])->name('activity-logs.show');
        
        // Settings
        Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('settings', [SettingController::class, 'update'])->name('settings.update');
        Route::get('settings/{group}', [SettingController::class, 'group'])->name('settings.group');
        
        // Blog management
Route::resource('blog-posts', AdminBlogController::class);

        // Admin Users Management (Super Admin Only)
        Route::middleware('admin:super_admin')->group(function () {
            Route::resource('admins', AdminController::class);
            Route::post('admins/{admin}/toggle-status', [AdminController::class, 'toggleStatus'])->name('admins.toggleStatus');
        });
        
        // Profile
        Route::get('profile', [AuthController::class, 'profile'])->name('profile');
        Route::put('profile', [AuthController::class, 'updateProfile'])->name('profile.update');
        Route::put('profile/password', [AuthController::class, 'updatePassword'])->name('profile.password');
    });
});