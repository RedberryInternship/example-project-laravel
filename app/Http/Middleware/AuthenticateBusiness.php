<?php

namespace App\Http\Middleware;

use Closure;

class AuthenticateBusiness
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
        if ( ! $request -> user() || $request -> user() -> role_id != 3)
        {
            return redirect('/business/login');
        }

        return $next($request);
    }
}
