<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Client
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
        if (!Auth::user()) {
            return $next($request);
        }
        $tipo = $request->user() ? $request->user()->tipo : Auth::user()->tipo;
        if ($tipo == 'C') {
            return $next($request);
        }
        return back();
    }
}
