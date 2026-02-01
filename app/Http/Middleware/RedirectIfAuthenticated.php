<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    
    public function handle(Request $request, Closure $next, ...$guards): Response
    {
        if (Auth::check()) {
            return match (Auth::user()->role) {
                'admin' => redirect('/admin/dashboard'),
                'guru'  => redirect('/guru/dashboard'),
                'siswa' => redirect('/siswa/dashboard'),
                default => redirect('/'),
            };
        }

        return $next($request);
    }
}
