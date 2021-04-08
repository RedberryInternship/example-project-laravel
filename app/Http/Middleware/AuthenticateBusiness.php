<?php

namespace App\Http\Middleware;

use Closure;
use App\Enums\Role as RoleEnum;

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
        $user = $request -> user(); 

        if ( !$user || $user->role->name != RoleEnum :: BUSINESS)
        {
            return redirect('/business/login');
        }

        return $next($request);
    }
}
