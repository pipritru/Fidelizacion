<?php

namespace App\Http\Controllers;

use App\Models\cities;
use App\Models\state;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use OpenApi\Annotations as OA;


/**
 * @OA\Schema(
 *     schema="City",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="New York"),
 *     @OA\Property(property="id_state", type="integer", example=1),
 *     @OA\Property(
 *         property="state",
 *         type="object",
 *         ref="#/components/schemas/State"
 *     ),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 *
 * @OA\Schema(
 *     schema="CityRequest",
 *     type="object",
 *     required={"name", "id_state"},
 *     @OA\Property(property="name", type="string", example="New York"),
 *     @OA\Property(property="id_state", type="integer", example=1)
 * )
 */
class CitiesController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/cities",
     *     summary="Retrieve a list of cities",
     *     tags={"Cities"},
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
    public function index()
    {
        $cities = cities::with('state')->get();
        return response()->json($cities);
    }

    /**
     * @OA\Post(
     *     path="/api/cities",
     *     summary="Create a new city",
     *     tags={"Cities"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CityRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="City created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/City")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function store(Request $request)
    {
        try {
            $rules = cities::$rules;
            $rules['name'] = 'required|string|max:120|unique:cities,name,NULL,id_city,id_state,' . $request->id_state;
            $validatedData = $request->validate($rules);
            $city = cities::create($validatedData);
            $city->load('state');
            return response()->json($city, 201);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/cities/{id}",
     *     summary="Retrieve a specific city",
     *     tags={"Cities"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
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
    public function show($id)
    {
        $city = cities::with('state')->find($id);
        if (!$city) {
            return response()->json(['message' => 'City not found'], 404);
        }
        return response()->json($city);
    }

    /**
     * @OA\Put(
     *     path="/api/cities/{id}",
     *     summary="Update a specific city",
     *     tags={"Cities"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CityRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="City updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/City")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="City not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $city = cities::find($id);
        if (!$city) {
            return response()->json(['message' => 'City not found'], 404);
        }

        // Adjust rules for update: allow same name for the same city and state
        $rules = cities::$rules;
        $rules['name'] = 'required|string|max:120|unique:cities,name,' . $id . ',id,id_state,' . $request->id_state;

        try {
            $validatedData = $request->validate($rules);
            $city->update($validatedData);
            $city->load('state');
            return response()->json($city);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/cities/{id}",
     *     summary="Delete a specific city",
     *     tags={"Cities"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="City deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="City not found"
     *     )
     * )
     */
    public function destroy($id)
    {
        $city = cities::find($id);
        if (!$city) {
            return response()->json(['message' => 'City not found'], 404);
        }
        $city->delete();
        return response()->json(null, 204);
    }

    /**
     * @OA\Schema(
     *     schema="City",
     *     type="object",
     *     @OA\Property(property="id", type="integer", example=1),
     *     @OA\Property(property="name", type="string", example="New York"),
     *     @OA\Property(property="id_state", type="integer", example=1),
     *     @OA\Property(
     *         property="state",
     *         type="object",
     *         ref="#/components/schemas/State"
     *     ),
     *     @OA\Property(property="created_at", type="string", format="date-time"),
     *     @OA\Property(property="updated_at", type="string", format="date-time")
     * )
     *
     * @OA\Schema(
     *     schema="CityRequest",
     *     type="object",
     *     required={"name", "id_state"},
     *     @OA\Property(property="name", type="string", example="New York"),
     *     @OA\Property(property="id_state", type="integer", example=1)
     * )
     */
}