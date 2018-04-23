<?php

namespace App\Http\Middleware;

use Closure;
use Auth;


class Fan
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
        if(Auth::guard('web')->check() && !Auth::guard()->user()->is_artist) {

            return $next($request);
        } 
            return redirect('home/artist');


        //return redirect()->route('landing');
    }
}
