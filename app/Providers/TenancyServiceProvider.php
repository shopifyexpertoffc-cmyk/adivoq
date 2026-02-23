<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Stancl\Tenancy\Events;
use Stancl\Tenancy\Listeners;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

class TenancyServiceProvider extends ServiceProvider
{
    public static string $tenantRouteNamePrefix = 'tenant.';

    // Events for tenant lifecycle
    public function events()
    {
        return [
            Events\CreatingTenant::class => [],
            Events\TenantCreated::class => [
                Listeners\CreateDatabase::class,
                Listeners\MigrateDatabase::class,
            ],
            Events\SavingTenant::class => [],
            Events\TenantSaved::class => [],
            Events\UpdatingTenant::class => [],
            Events\TenantUpdated::class => [],
            Events\DeletingTenant::class => [],
            Events\TenantDeleted::class => [
                Listeners\DeleteDatabase::class,
            ],
            Events\TenancyInitialized::class => [
                Listeners\BootstrapTenancy::class,
            ],
            Events\TenancyEnded::class => [
                Listeners\RevertToCentralContext::class,
            ],
            Events\BootstrappingTenancy::class => [],
            Events\TenancyBootstrapped::class => [],
            Events\RevertingToCentralContext::class => [],
            Events\RevertedToCentralContext::class => [],
            Events\CreatingDatabase::class => [],
            Events\DatabaseCreated::class => [],
            Events\MigratingDatabase::class => [],
            Events\DatabaseMigrated::class => [],
            Events\SeedingDatabase::class => [],
            Events\DatabaseSeeded::class => [],
            Events\RollingBackDatabase::class => [],
            Events\DatabaseRolledBack::class => [],
            Events\DeletingDatabase::class => [],
            Events\DatabaseDeleted::class => [],
        ];
    }

    public function register()
    {
        //
    }

    public function boot()
    {
        $this->bootEvents();
        $this->mapRoutes();
    }

    protected function bootEvents()
    {
        foreach ($this->events() as $event => $listeners) {
            foreach ($listeners as $listener) {
                Event::listen($event, $listener);
            }
        }
    }

    protected function mapRoutes()
    {
        // ✅ Central domain check - prevent tenant routes on central domains
        if ($this->app->runningInConsole()) {
            return;
        }

        $centralDomains = config('tenancy.central_domains', []);

        // ✅ Tenant routes - ONLY for tenant domains
        Route::middleware([
            'web',
            InitializeTenancyByDomain::class,
            PreventAccessFromCentralDomains::class,
        ])->group(base_path('routes/tenant.php'));
    }
}