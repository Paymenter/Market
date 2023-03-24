<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(!auth()->user())
            return redirect()->route('home')->with('error', 'You are not authorized to access this page');
        elseif(auth()->user()->is_admin == 1){
            if(auth()->user()->role == 'resourcemod' && !$request->routeIs('admin.resources.*'))
                return redirect()->route('admin.resources.index');
            else
                return $next($request);
        } else return redirect()->route('home')->with('error', 'You are not authorized to access this page');
    }
}
