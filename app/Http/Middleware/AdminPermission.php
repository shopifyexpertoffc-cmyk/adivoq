<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminPermission
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$permissions): Response
    {
        $admin = auth('admin')->user();

        if (!$admin) {
            abort(401);
        }

        // Super admin has all permissions
        if ($admin->isSuperAdmin()) {
            return $next($request);
        }

        // Check if admin has any of the required permissions
        if (!$admin->hasAnyPermission($permissions)) {
            abort(403, 'You do not have permission to access this resource.');
        }

        return $next($request);
    }
}