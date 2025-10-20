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

    // Normalize common aliases to match DB entries (e.g. 'admin' -> 'administrador')
    $lower = strtolower($role);
    if ($lower === 'admin' || $lower === 'administrator') {
        $role = 'administrador';
    }

    // Support multiple roles separated by comma and case-insensitive match
    $wanted = array_map('trim', explode(',', $role));

        // If any of the wanted tokens are numeric, allow matching by role_id
        foreach ($wanted as $w) {
            if (is_numeric($w) && (int)$user->role_id === (int)$w) {
                return $next($request);
            }
        }

        if (method_exists($user, 'role')) {
            $userRole = $user->role()->first();
            if ($userRole) {
                foreach ($wanted as $w) {
                    // numeric already checked above, so compare names case-insensitive
                    if (!is_numeric($w) && strtolower($userRole->name) === strtolower($w)) {
                        return $next($request);
                    }
                }
            }
        }

        // No match: forbidden. If app debug is enabled, return useful debug info
        if (config('app.debug')) {
            $roleName = null;
            if (method_exists($user, 'role')) {
                $r = $user->role()->first();
                $roleName = $r ? $r->name : null;
            }
            return response()->json([
                'error' => 'Forbidden',
                'user_id' => $user->id ?? null,
                'user_role_id' => $user->role_id ?? null,
                'user_role_name' => $roleName,
                'required_roles' => $wanted,
            ], 403);
        }

        return response()->json(['error' => 'Forbidden'], 403);
    }
}
