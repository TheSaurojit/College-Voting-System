<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StudentMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Ensure a student session is active.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!session()->has('student_id')) {
            return redirect('/login');
        }

        return $next($request);
    }
}
