<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAdminRole
{
    public function handle(Request $request, Closure $next)
    {
        // Jika user login & role admin → lanjutkan request
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request);
        }

        // 🔹 Kalau request API (prefix /api) → balikin JSON error
        if ($request->is('api/*') || $request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin only.'
            ], 403);
        }

        // 🔹 Kalau request dari web biasa → redirect
        return redirect('/dashboard')->with(
            'error',
            'Anda tidak memiliki hak akses ke halaman ini.'
        );
    }
}
