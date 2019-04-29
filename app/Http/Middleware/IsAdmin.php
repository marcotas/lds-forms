<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

class IsAdmin
{
    public function handle($request, Closure $next)
    {
        if (!user()->isAn('admin', 'superadmin')) {
            return $request->ajax()
                ? response(null, Response::HTTP_NOT_FOUND)
                : redirect(Response::HTTP_NOT_FOUND);
        }

        return $next($request);
    }
}
