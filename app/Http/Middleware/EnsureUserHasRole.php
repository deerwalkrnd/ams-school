<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        //first we check if the user is authenticated or not
        //if not authenticated then we redirect to the
        //login route

        if(!Auth::check()){
            return redirect()->route('login');
        }


        if(!is_null(auth()->user()))
        {

            $home = $role == "admin"? "/dashboard":"/home";
            if (!$request->user()->hasRole($role)) {
                return redirect($home);
            }
        }

        return $next($request);
    }
}
