<?php

namespace App\Http\Middleware;

use Closure;
use App\User;

class CheckUserExistence
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

        if( ! auth() -> user() )
        {
            return response() -> json(
                [
                    'message' => 'User not found.',
                ],
                401
            );
        }

        return $next($request);
    }
}
