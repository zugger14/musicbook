<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class Artist
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
        if(Auth::guard('web')->check() && Auth::guard('web')->user()->is_artist) {

            return $next($request);

        } else {

            return redirect('fan/home');
            
        }

    }
}
