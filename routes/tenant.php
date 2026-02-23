<?php

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

use App\Http\Controllers\Tenant\AuthController;
use App\Http\Controllers\Tenant\DashboardController;
use App\Http\Controllers\Tenant\BrandController;
use App\Http\Controllers\Tenant\CampaignController;
use App\Http\Controllers\Tenant\InvoiceController;
use App\Http\Controllers\Tenant\PaymentController;
use App\Http\Controllers\Tenant\ReportController;
use App\Http\Controllers\Tenant\SettingController;
use App\Http\Controllers\Tenant\TeamController;
use App\Http\Controllers\Tenant\ProfileController;

/*
|--------------------------------------------------------------------------
| Tenant Routes (Subdomain-based tenancy)
|--------------------------------------------------------------------------
|
| These routes are loaded only when tenancy is initialized by domain.
| The middleware ensures these routes are NOT accessible on central domains.
|
*/

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {

    // Guest routes (tenant login, password reset)
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'showLoginForm'])->name('tenant.login');
        Route::post('/login', [AuthController::class, 'login'])->name('tenant.login.submit');
        Route::get('/forgot-password', [AuthController::class, 'showForgotForm'])->name('tenant.password.request');
        Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('tenant.password.email');
        Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('tenant.password.reset');
        Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('tenant.password.update');
    });

    // Authenticated tenant routes
    Route::middleware('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('tenant.logout');

        Route::get('/', [DashboardController::class, 'index'])->name('tenant.dashboard');
        Route::get('/dashboard', [DashboardController::class, 'index']);

        Route::resource('brands', BrandController::class);

        Route::resource('campaigns', CampaignController::class);
        Route::post('campaigns/{campaign}/milestones', [CampaignController::class, 'addMilestone'])->name('campaigns.milestones.store');
        Route::put('campaigns/{campaign}/milestones/{milestone}', [CampaignController::class, 'updateMilestone'])->name('campaigns.milestones.update');
        Route::delete('campaigns/{campaign}/milestones/{milestone}', [CampaignController::class, 'deleteMilestone'])->name('campaigns.milestones.destroy');

        Route::resource('invoices', InvoiceController::class);
        Route::get('invoices/{invoice}/pdf', [InvoiceController::class, 'downloadPdf'])->name('invoices.pdf');
        Route::post('invoices/{invoice}/send', [InvoiceController::class, 'send'])->name('invoices.send');
        Route::post('invoices/{invoice}/send-whatsapp', [InvoiceController::class, 'sendWhatsApp'])->name('invoices.send-whatsapp');
        Route::post('invoices/{invoice}/mark-paid', [InvoiceController::class, 'markPaid'])->name('invoices.mark-paid');
        Route::post('invoices/{invoice}/record-payment', [InvoiceController::class, 'recordPayment'])->name('invoices.record-payment');

        Route::resource('payments', PaymentController::class)->only(['index', 'show', 'destroy']);

        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/revenue', [ReportController::class, 'revenue'])->name('reports.revenue');
        Route::get('reports/tax', [ReportController::class, 'tax'])->name('reports.tax');
        Route::get('reports/clients', [ReportController::class, 'clients'])->name('reports.clients');
        Route::get('reports/export', [ReportController::class, 'export'])->name('reports.export');

        Route::resource('team', TeamController::class)->except(['show']);

        Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('settings', [SettingController::class, 'update'])->name('settings.update');

        Route::get('profile', [ProfileController::class, 'index'])->name('profile.index');
        Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    });

    // Debug route to confirm tenant
    Route::get('/whoami', function () {
        return 'Tenant: ' . (tenant('id') ?? 'none');
    })->name('tenant.whoami');
});