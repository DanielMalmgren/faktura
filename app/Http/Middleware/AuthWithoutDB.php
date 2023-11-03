<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Aacotroneo\Saml2\Saml2Auth;

class AuthWithoutDB
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!empty(session('user'))) {
            return $next($request);
        }
    
        $saml2Auth = new Saml2Auth(Saml2Auth::loadOneLoginAuthFromIpdConfig('idp.itsam.se'));
        return $saml2Auth->login($request->fullUrl());
    }
}
