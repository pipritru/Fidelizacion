<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="City",
 *     type="object",
 *     required={"id", "name", "state_id"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Bogotá"),
 *     @OA\Property(property="state_id", type="integer", example=1)
 * )
 */

class CityController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/cities",
     *     tags={"Cities"},
     *     summary="Retrieve a list of cities",
     *     description="Get all cities with optional state filter",
     *     operationId="getCities",
     *     @OA\Parameter(
     *         name="state_id",
     *         in="query",
     *         description="Filter by state ID",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/City")
     *         )
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $query = City::query();
        if ($request->has('state_id')) {
            $query->where('state_id', $request->input('state_id'));
        }
        $cities = $query->get();
        return response()->json($cities);
    }

    /**
     * @OA\Post(
     *     path="/api/cities",
     *     tags={"Cities"},
     *     summary="Create a new city",
     *     description="Add a new city",
     *     operationId="createCity",
     *     @OA\RequestBody(
     *         description="City details",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/City")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="City created",
     *         @OA\JsonContent(ref="#/components/schemas/City")
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
            'state_id' => 'required|exists:states,id',
        ]);

        $city = City::create($validated);
        return response()->json($city, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/cities/{id}",
     *     tags={"Cities"},
     *     summary="Retrieve a specific city",
     *     description="Get city by ID",
     *     operationId="getCityById",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="City ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/City")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="City not found"
     *     )
     * )
     */
    public function show($id): JsonResponse
    {
        $city = City::findOrFail($id);
        return response()->json($city);
    }

    /**
     * @OA\Put(
     *     path="/api/cities/{id}",
     *     tags={"Cities"},
     *     summary="Update a city",
     *     description="Update city details",
     *     operationId="updateCity",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="City ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         description="Updated city details",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/City")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="City updated",
     *         @OA\JsonContent(ref="#/components/schemas/City")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="City not found"
     *     )
     * )
     */
    public function update(Request $request, $id): JsonResponse
    {
        $city = City::findOrFail($id);
        $validated = $request->validate([
            'name' => 'string|max:255',
            'state_id' => 'exists:states,id',
        ]);

        $city->update($validated);
        return response()->json($city);
    }

    /**
     * @OA\Delete(
     *     path="/api/cities/{id}",
     *     tags={"Cities"},
     *     summary="Delete a city",
     *     description="Remove a city by ID",
     *     operationId="deleteCity",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="City ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="City deleted"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="City not found"
     *     )
     * )
     */
    public function destroy($id): JsonResponse
    {
        $city = City::findOrFail($id);
        $city->delete();
        return response()->json(null, 204);
    }
}