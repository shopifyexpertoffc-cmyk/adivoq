<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InitializeTenancyByPath
{
    public function handle(Request $request, Closure $next): Response
    {
        $tenantId = $request->route('tenant');

        if (! $tenantId) {
            return redirect('/');
        }

        tenancy()->initialize($tenantId);

        return $next($request);
    }
}