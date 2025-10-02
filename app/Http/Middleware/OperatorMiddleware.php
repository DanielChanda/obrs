<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OperatorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth()->check()) {
            return redirect()->route('login');
        }

        if (Auth()->user()->role !== 'operator') {
            abort(403, 'Unauthorized access. Operator privileges required.');
        }
        return $next($request);
    }
}
