<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSiswa
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!$request->user()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 401);
        }

        // Check if role is siswa
        if ($request->user()->role !== 'siswa') {
            return response()->json([
                'success' => false,
                'message' => 'Access denied. Role must be siswa.',
            ], 403);
        }

        // Check if user has relation to siswa
        if (!$request->user()->siswa) {
            return response()->json([
                'success' => false,
                'message' => 'User does not have a linked Siswa profile.',
            ], 403);
        }

        return $next($request);
    }
}
