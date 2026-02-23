use Illuminate\Support\Facades\View;

public function boot()
{
    // This will run for all tenant views (adjust the namespace/path as needed)
    View::composer('tenant.*', function ($view) {
        $tenantId = tenant('id');

        // Share tenantId with all tenant views
        $view->with('tenantId', $tenantId);
    });
        Route::macro('tenantRoute', function ($name, $parameters = [], $absolute = true) {
        if (!isset($parameters['tenant'])) {
            $parameters['tenant'] = tenant('id');
        }
        return route($name, $parameters, $absolute);
    });
}