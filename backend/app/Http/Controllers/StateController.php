<?php

namespace App\Http\Controllers;

use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Schema(
 *     schema="State",
 *     type="object",
 *     required={"id", "name"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Cundinamarca")
 * )
 */

class StateController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/states",
     *     tags={"States"},
     *     summary="Retrieve a list of states",
     *     description="Get all states",
     *     operationId="getStates",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/State")
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $states = State::all();
        return response()->json($states);
    }

    /**
     * @OA\Post(
     *     path="/api/states",
     *     tags={"States"},
     *     summary="Create a new state",
     *     description="Add a new state",
     *     operationId="createState",
     *     @OA\RequestBody(
     *         description="State details",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/State")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="State created",
     *         @OA\JsonContent(ref="#/components/schemas/State")
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
            'name' => 'required|string|max:255',
        ]);

        $state = State::create($validated);
        return response()->json($state, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/states/{id}",
     *     tags={"States"},
     *     summary="Retrieve a specific state",
     *     description="Get state by ID",
     *     operationId="getStateById",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="State ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/State")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="State not found"
     *     )
     * )
     */
    public function show($id): JsonResponse
    {
        $state = State::findOrFail($id);
        return response()->json($state);
    }

    /**
     * @OA\Put(
     *     path="/api/states/{id}",
     *     tags={"States"},
     *     summary="Update a state",
     *     description="Update state details",
     *     operationId="updateState",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="State ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         description="Updated state details",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/State")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="State updated",
     *         @OA\JsonContent(ref="#/components/schemas/State")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="State not found"
     *     )
     * )
     */
    public function update(Request $request, $id): JsonResponse
    {
        $state = State::findOrFail($id);
        $validated = $request->validate([
            'name' => 'string|max:255',
        ]);

        $state->update($validated);
        return response()->json($state);
    }

    /**
     * @OA\Delete(
     *     path="/api/states/{id}",
     *     tags={"States"},
     *     summary="Delete a state",
     *     description="Remove a state by ID",
     *     operationId="deleteState",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="State ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="State deleted"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="State not found"
     *     )
     * )
     */
    public function destroy($id): JsonResponse
    {
        $state = State::findOrFail($id);
        $state->delete();
        return response()->json(null, 204);
    }
}