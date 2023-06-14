<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Support\Facades\Auth;

class IsSupervisor
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
        if (Auth::user() &&  Auth::user()->isCanAccessSupervisor()) {
            return $next($request);
        }
        return redirect()->route('home')->with('error', 'คุณไม่มีสิทธิเข้าหน้า Supervisor');
    }
}
