<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckGuardToken
{
    public function handle(Request $request, Closure $next, ...$guards): Response
    {
        //if request has no token then return message
        if (!$request->headers->has('Authorization')) {
            return response()->json(['message' => 'Token required'], 401);
        }

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();

                // Check if the token's guard name matches the guard being used
                if ($user && $user->token()->guard_name === $guard) {
                    return $next($request);
                }
            }
        }

        return response()->json(['message' => 'Unauthorized'], 401);
    }
}
