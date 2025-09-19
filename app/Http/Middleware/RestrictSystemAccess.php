<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RestrictSystemAccess
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (! $user) {
            abort(403, 'Acesso negado.');
        }

        if ($user->hasRole('master')) {
            return $next($request);
        }

        $systemPermissions = [
            'permissions-all',
            'roles-all',
            'menu-all',
        ];

        if ($user->hasAnyPermission($systemPermissions)) {
            return $next($request);
        }

        abort(403, 'Acesso negado.');
    }
}
