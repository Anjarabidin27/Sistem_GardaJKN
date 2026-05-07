<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminRoleGate
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = Auth::guard('admin')->user();

        if (!$user) {
            return redirect()->route('admin.login');
        }

        // Superadmin bypasses all checks
        if ($user->role === 'superadmin') {
            return $next($request);
        }

        // Check if user's role is in the allowed list
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // Determine redirect target based on role
        $target = $this->getRedirectRoute($user->role);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Unauthorized access.'], 403);
        }

        return redirect($target)->with('error', 'unauthorized_access');
    }

    protected function getRedirectRoute($role)
    {
        switch ($role) {
            case 'petugas':
            case 'petugas_keliling':
            case 'petugas_pil':
                return '/admin/bpjs-keliling/dashboard';
            case 'pengurus':
                return '/member/informations';
            case 'admin_wilayah':
                return '/admin/dashboard';
            default:
                return '/admin/dashboard';
        }
    }
}
