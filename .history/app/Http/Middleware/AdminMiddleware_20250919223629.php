<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // pastikan user sudah login dan role = admin
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            // kalau bukan admin, bisa redirect atau abort
            return redirect('/')->with('error', 'Access denied. Admins only.');
            // atau bisa pakai: abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
