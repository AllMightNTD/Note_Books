<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if ($role == 'admin' && auth('api')->user()->role != 1) {
            return response()->json(['error' => 'You do not have permission to access.'], 403);
        } elseif ($role == 'user' && auth('api')->user()->role != 0) {
            return response()->json(['error' => 'You do not have permission to access.'], 403);
        } elseif ($role == 'manager' && auth('api')->user()->role != 2) {
            return response()->json(['error' => 'You do not have permission to access.'], 403);
        }

        return $next($request);
    }
}
