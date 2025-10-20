<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Users model uses 'role_id' relation to Role; check role name if loaded
        if (method_exists($user, 'role')) {
            $userRole = $user->role()->first();
            if ($userRole && $userRole->name === $role) {
                return $next($request);
            }
        }

        return response()->json(['error' => 'Forbidden'], 403);
    }
}
