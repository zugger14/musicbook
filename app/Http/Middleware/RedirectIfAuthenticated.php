<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {   
        switch($guard){
            case 'admin':
                if(Auth::guard($guard)->check()) {
                    return redirect()->route('admin.dashboard');
                }
                break;
            
            default:
                if (Auth::guard($guard)->check()) {
                    //die('yes');
                    //dd($guard);
                    $location = Auth::guard($guard)->user()->is_artist ? 'artist/home' : 'fan/home';
                    
                    return redirect($location);
                }
                break;
        }

        return $next($request);
    }
}
