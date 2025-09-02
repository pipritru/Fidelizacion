<?php

namespace App\Http\Controllers;

use App\Models\persons;
use App\Models\cities;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use OpenApi\Annotations as OA;


/**
 * @OA\Schema(
 *     schema="Person",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="first_name", type="string", example="John"),
 *     @OA\Property(property="last_name", type="string", example="Doe"),
 *     @OA\Property(property="national_id", type="string", example="123456789"),
 *     @OA\Property(property="email", type="string", example="john.doe@example.com"),
 *     @OA\Property(property="phone", type="string", example="+1234567890"),
 *     @OA\Property(property="address", type="string", example="123 Main St"),
 *     @OA\Property(property="id_city", type="integer", example=1),
 *     @OA\Property(
 *         property="city",
 *         type="object",
 *         ref="#/components/schemas/City"
 *     ),
 *     @OA\Property(property="birthdate", type="string", format="date", example="1990-01-01"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 *
 * @OA\Schema(
 *     schema="PersonRequest",
 *     type="object",
 *     required={"first_name"},
 *     @OA\Property(property="first_name", type="string", example="John"),
 *     @OA\Property(property="last_name", type="string", example="Doe"),
 *     @OA\Property(property="national_id", type="string", example="123456789"),
 *     @OA\Property(property="email", type="string", example="john.doe@example.com"),
 *     @OA\Property(property="phone", type="string", example="+1234567890"),
 *     @OA\Property(property="address", type="string", example="123 Main St"),
 *     @OA\Property(property="id_city", type="integer", example=1),
 *     @OA\Property(property="birthdate", type="string", format="date", example="1990-01-01")
 * )
 */
class PersonsController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/persons",
     *     summary="Retrieve a list of persons",
     *     tags={"Persons"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Person")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $persons = persons::with('city')->get();
        return response()->json($persons);
    }

    /**
     * @OA\Post(
     *     path="/api/persons",
     *     summary="Create a new person",
     *     tags={"Persons"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/PersonRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Person created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Person")
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
            $validatedData = $request->validate(persons::$rules);
            $person = persons::create($validatedData);
            $person->load('city');
            return response()->json($person, 201);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/persons/{id}",
     *     summary="Retrieve a specific person",
     *     tags={"Persons"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Person")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Person not found"
     *     )
     * )
     */
    public function show($id)
    {
        $person = persons::with('city')->find($id);
        if (!$person) {
            return response()->json(['message' => 'Person not found'], 404);
        }
        return response()->json($person);
    }

    /**
     * @OA\Put(
     *     path="/api/persons/{id}",
     *     summary="Update a specific person",
     *     tags={"Persons"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/PersonRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Person updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Person")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Person not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $person = persons::find($id);
        if (!$person) {
            return response()->json(['message' => 'Person not found'], 404);
        }

        // Adjust rules for update: allow same national_id and email for the same person
        $rules = persons::$rules;
        $rules['national_id'] = 'nullable|string|max:40|unique:persons,national_id,' . $id;
        $rules['email'] = 'nullable|string|max:150|unique:persons,email,' . $id;

        try {
            $validatedData = $request->validate($rules);
            $person->update($validatedData);
            $person->load('city');
            return response()->json($person);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/persons/{id}",
     *     summary="Delete a specific person",
     *     tags={"Persons"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Person deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Person not found"
     *     )
     * )
     */
    public function destroy($id)
    {
        $person = persons::find($id);
        if (!$person) {
            return response()->json(['message' => 'Person not found'], 404);
        }
        $person->delete();
        return response()->json(null, 204);
    }
}

/**
 * @OA\Schema(
 *     schema="Person",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="first_name", type="string", example="John"),
 *     @OA\Property(property="last_name", type="string", example="Doe"),
 *     @OA\Property(property="national_id", type="string", example="123456789"),
 *     @OA\Property(property="email", type="string", example="john.doe@example.com"),
 *     @OA\Property(property="phone", type="string", example="+1234567890"),
 *     @OA\Property(property="address", type="string", example="123 Main St"),
 *     @OA\Property(property="id_city", type="integer", example=1),
 *     @OA\Property(
 *         property="city",
 *         type="object",
 *         ref="#/components/schemas/City"
 *     ),
 *     @OA\Property(property="birthdate", type="string", format="date", example="1990-01-01"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 *
 * @OA\Schema(
 *     schema="PersonRequest",
 *     type="object",
 *     required={"first_name"},
 *     @OA\Property(property="first_name", type="string", example="John"),
 *     @OA\Property(property="last_name", type="string", example="Doe"),
 *     @OA\Property(property="national_id", type="string", example="123456789"),
 *     @OA\Property(property="email", type="string", example="john.doe@example.com"),
 *     @OA\Property(property="phone", type="string", example="+1234567890"),
 *     @OA\Property(property="address", type="string", example="123 Main St"),
 *     @OA\Property(property="id_city", type="integer", example=1),
 *     @OA\Property(property="birthdate", type="string", format="date", example="1990-01-01")
 * )
 */