<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        if ($role === 'admin') {
            if (Auth::guard('admin')->check() || ($user && $user instanceof \App\Models\AdminUser)) {
                return $next($request);
            }
            return $request->expectsJson() 
                ? response()->json(['message' => 'Unauthenticated.'], 401)
                : redirect()->route('admin.login');
        }

        if ($role === 'pengurus') {
            if (Auth::guard('member')->check() && Auth::guard('member')->user()->role === 'pengurus') {
                return $next($request);
            }
            if ($user && $user instanceof \App\Models\Member && $user->role === 'pengurus') {
                return $next($request);
            }
            return $request->expectsJson()
                ? response()->json(['message' => 'Akses khusus Pengurus.'], 403)
                : redirect()->route('login')->withErrors(['role' => 'Akses khusus Pengurus.']);
        }

        if ($role === 'anggota') {
            if (Auth::guard('member')->check() || ($user && $user instanceof \App\Models\Member)) {
                return $next($request);
            }
            return $request->expectsJson()
                ? response()->json(['message' => 'Unauthenticated.'], 401)
                : redirect()->route('login');
        }

        return $next($request);
    }
}
