<?php

namespace App\Http\Middleware;

use App\Models\Users;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckPermission
{
    /**
     * Handle an incoming request.
     * Usage: ->middleware('permission:resource,action') e.g. permission:products,create
     */
    public function handle(Request $request, Closure $next, string $resource = null, string $action = null)
    {
        /** @var Users|null $user */
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'No autenticado'], 401);
        }

        if (!$resource || !$action) {
            return response()->json(['message' => 'Parámetros de permiso inválidos'], 403);
        }

        // Mapear acción -> columna del pivot
        $column = $this->mapActionToColumn($action);
        if (!$column) {
            return response()->json(['message' => 'Acción de permiso inválida'], 403);
        }

        // Obtener permission_id por nombre de recurso
        $permission = DB::table('permissions')->where('name', $resource)->first();
        if (!$permission) {
            return response()->json(['message' => 'Recurso de permiso no encontrado'], 403);
        }

        $permissionId = $permission->id;

        // Flag por rol
        $roleFlag = DB::table('role_permissions')
            ->where('role_id', $user->role_id)
            ->where('permission_id', $permissionId)
            ->value($column);

        // Flag por usuario (override)
        $userFlag = DB::table('user_permissions')
            ->where('user_id', $user->id)
            ->where('permission_id', $permissionId)
            ->value($column);

        $allowed = (bool)($roleFlag) || (bool)($userFlag);

        if (!$allowed) {
            return response()->json(['message' => 'No tienes permisos para esta acción'], 403);
        }

        return $next($request);
    }

    private function mapActionToColumn(string $action): ?string
    {
        $action = strtolower($action);
        return match ($action) {
            'view', 'index', 'show', 'read', 'list' => 'can_view',
            'create', 'store' => 'can_create',
            'edit', 'update', 'patch' => 'can_edit',
            'delete', 'destroy', 'remove' => 'can_delete',
            default => null,
        };
    }
}
