<?php

namespace App\Http\Middleware;

use Closure;

class BuildHeaders
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        $response->header('x-psykhe-build', 'unknown');
        $response->header('x-psykhe-src', 'unknown');

        return $response;
    }
}
