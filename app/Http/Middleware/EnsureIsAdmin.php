<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()?->is_admin) {
            activity()
                ->causedBy($request->user())
                ->withProperties(['url' => $request->url()])
                ->log('Unauthorized admin access attempt');

            abort(403, 'Access denied. Admin privileges required.');
        }

        return $next($request);
    }
}
