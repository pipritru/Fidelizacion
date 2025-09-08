<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

class PersonController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/persons",
     *     tags={"Persons"},
     *     summary="Retrieve a list of persons",
     *     description="Get all persons",
     *     operationId="getPersons",
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
    public function index(): JsonResponse
    {
        $persons = Person::all();
        return response()->json($persons);
    }

    /**
     * @OA\Post(
     *     path="/api/persons",
     *     tags={"Persons"},
     *     summary="Create a new person",
     *     description="Add a new person",
     *     operationId="createPerson",
     *     @OA\RequestBody(
     *         description="Person details",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Person")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Person created",
     *         @OA\JsonContent(ref="#/components/schemas/Person")
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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:persons,email',
            'address' => 'nullable|string',
            'city_id' => 'nullable|exists:cities,id',
            'state_id' => 'nullable|exists:states,id',
        ]);

        $person = Person::create($validated);
        return response()->json($person, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/persons/{id}",
     *     tags={"Persons"},
     *     summary="Retrieve a specific person",
     *     description="Get person by ID",
     *     operationId="getPersonById",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Person ID",
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
    public function show($id): JsonResponse
    {
        $person = Person::findOrFail($id);
        return response()->json($person);
    }

    /**
     * @OA\Put(
     *     path="/api/persons/{id}",
     *     tags={"Persons"},
     *     summary="Update a person",
     *     description="Update person details",
     *     operationId="updatePerson",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Person ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         description="Updated person details",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Person")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Person updated",
     *         @OA\JsonContent(ref="#/components/schemas/Person")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Person not found"
     *     )
     * )
     */
    public function update(Request $request, $id): JsonResponse
    {
        $person = Person::findOrFail($id);
        $validated = $request->validate([
            'first_name' => 'string|max:255',
            'last_name' => 'string|max:255',
            'email' => 'email|unique:persons,email,' . $id,
            'address' => 'nullable|string',
            'city_id' => 'nullable|exists:cities,id',
            'state_id' => 'nullable|exists:states,id',
        ]);

        $person->update($validated);
        return response()->json($person);
    }

    /**
     * @OA\Delete(
     *     path="/api/persons/{id}",
     *     tags={"Persons"},
     *     summary="Delete a person",
     *     description="Remove a person by ID",
     *     operationId="deletePerson",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Person ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Person deleted"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Person not found"
     *     )
     * )
     */
    public function destroy($id): JsonResponse
    {
        $person = Person::findOrFail($id);
        $person->delete();
        return response()->json(null, 204);
    }
}