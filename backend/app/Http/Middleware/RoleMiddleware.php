<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role)
    {
        if (! $request->user() || !in_array($request->user()->role, array_map('trim', explode(',', $role)), true) || ! $request->user()->is_active) {
            abort(403);
        }
        return $next($request);
    }
}
