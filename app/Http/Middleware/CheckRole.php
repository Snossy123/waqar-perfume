<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role)
    {
        $user = $request->user('admin');
        
        if (!$user) {
            throw new AccessDeniedHttpException('Not authenticated.');
        }

        if (!$user->hasRole($role)) {
            throw new AccessDeniedHttpException('You need the ' . $role . ' role to access this page.');
        };

        return $next($request);
    }
}
