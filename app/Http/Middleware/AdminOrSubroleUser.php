<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminOrSubroleUser
{
    public function handle($request, Closure $next)
    {
        if (Auth::guard('admin')->check() || Auth::guard('subroleuser')->check()) {
            return $next($request);
        }

        return response()->json(['message' => 'Unauthorized'], 401);
    }
}

