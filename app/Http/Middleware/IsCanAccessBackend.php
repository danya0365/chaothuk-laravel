<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class IsCanAccessBackend
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if ($user instanceof \App\Models\User && $user->isCanAccessBackend()) {
            return $next($request);
        }
        return redirect()->route('home')->with('error', 'คุณไม่มีสิทธิเข้าหน้า Backend');
    }
}
