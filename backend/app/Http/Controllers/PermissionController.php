<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;


/**
 * @OA\Schema(
 *     schema="Permission",
 *     type="object",
 *     required={"id", "name"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="edit_articles"),
 *     @OA\Property(property="description", type="string", example="Permission to edit articles"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-01T00:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-01T00:00:00Z")
 * )
 */

class PermissionController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/permissions",
     *     tags={"Permissions"},
     *     summary="Retrieve a list of permissions",
     *     description="Get all permissions",
     *     operationId="getPermissions",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Permission")
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $permissions = Permission::all();
        return response()->json($permissions);
    }

    /**
     * @OA\Post(
     *     path="/api/permissions",
     *     tags={"Permissions"},
     *     summary="Create a new permission",
     *     description="Add a new permission",
     *     operationId="createPermission",
     *     @OA\RequestBody(
     *         description="Permission details",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Permission")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Permission created",
     *         @OA\JsonContent(ref="#/components/schemas/Permission")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
            'description' => 'nullable|string',
        ]);

        $permission = Permission::create($validated);
        return response()->json($permission, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/permissions/{id}",
     *     tags={"Permissions"},
     *     summary="Retrieve a specific permission",
     *     description="Get permission by ID",
     *     operationId="getPermissionById",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Permission ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Permission")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Permission not found"
     *     )
     * )
     */
    public function show($id): JsonResponse
    {
        $permission = Permission::findOrFail($id);
        return response()->json($permission);
    }

    /**
     * @OA\Put(
     *     path="/api/permissions/{id}",
     *     tags={"Permissions"},
     *     summary="Update a permission",
     *     description="Update permission details",
     *     operationId="updatePermission",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Permission ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         description="Updated permission details",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Permission")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Permission updated",
     *         @OA\JsonContent(ref="#/components/schemas/Permission")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Permission not found"
     *     )
     * )
     */
    public function update(Request $request, $id): JsonResponse
    {
        $permission = Permission::findOrFail($id);
        $validated = $request->validate([
            'name' => 'string|max:255|unique:permissions,name,' . $id,
            'description' => 'nullable|string',
        ]);

        $permission->update($validated);
        return response()->json($permission);
    }

    /**
     * @OA\Delete(
     *     path="/api/permissions/{id}",
     *     tags={"Permissions"},
     *     summary="Delete a permission",
     *     description="Remove a permission by ID",
     *     operationId="deletePermission",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Permission ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Permission deleted"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Permission not found"
     *     )
     * )
     */
    public function destroy($id): JsonResponse
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();
        return response()->json(null, 204);
    }
}