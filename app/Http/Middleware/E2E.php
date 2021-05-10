<?php

namespace App\Http\Middleware;

use Closure;
use App\Library\Entities\Helper;
use Illuminate\Http\Response;

class E2E
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        abort_if(!Helper::isDev(), Response::HTTP_FORBIDDEN);
        
        return $next($request);
    }
}
