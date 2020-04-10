<?php

namespace App\Http\Middleware;

use App\Traits\SessionLifetime;
use Closure;

class PinAuth
{
    use SessionLifetime;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ( !empty(session('authenticated')) && ($sessionTimestamp = intval(session('authenticated'))) ) {
            // Test for session timeout
            if (($sessionTimestamp + $this->getSessionLifetime()) < time()) {
                return redirect('/login');
            }
            // Keep session alive
            $request->session()->put('authenticated', time());
            return $next($request);
        }
        return redirect('/login');
    }
}
