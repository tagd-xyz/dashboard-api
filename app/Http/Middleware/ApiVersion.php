<?php

namespace App\Http\Middleware;

use Closure;

class ApiVersion
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next, $guard)
    {
        config(['app.api_version' => $guard]);

        return $next($request);
    }
}
