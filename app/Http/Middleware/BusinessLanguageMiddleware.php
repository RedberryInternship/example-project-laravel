<?php

namespace App\Http\Middleware;

use Closure;

class BusinessLanguageMiddleware
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
        $userSelectedLang = auth()->user()->lang();
        app()->setLocale($userSelectedLang);

        return $next($request);
    }
}
