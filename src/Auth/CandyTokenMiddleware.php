<?php

namespace GetCandy\Client\Auth;

use GetCandy\Client\Facades\CandyClient;
use Closure;

class CandyTokenMiddleware
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
        if ($request->user()) {
            CandyClient::setToken($request->user()->getAuthIdentifier());
        }
        return $next($request);
    }
}
