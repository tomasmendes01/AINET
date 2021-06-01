<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class notSoftDeleted
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
        $user = $request->user() ?? Auth::user();
        if ($user->deleted_at != null) {
            Auth::logout();
            Session::flush();
            Session::regenerate();
            return redirect()->route('login')->with(['error' => 'Your account has been disabled!']);
        } else {
            return $next($request);
        }
    }
}
