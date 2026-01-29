<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAuthenticated
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Try to authenticate via Bearer token from header or cookie
        $token = $request->bearerToken() ?? $request->cookie('auth_token');

        if ($token) {
            // Find the token in the database
            $accessToken = PersonalAccessToken::findToken($token);

            if ($accessToken) {
                // Authenticate the user
                Auth::setUser($accessToken->tokenable);
            }
        }

        // Check if user is authenticated
        if (! Auth::check()) {
            // If it's an Inertia request, redirect to login
            if ($request->header('X-Inertia')) {
                return redirect()->route('login');
            }

            // For regular requests, also redirect to login
            return redirect()->route('login');
        }

        return $next($request);
    }
}
