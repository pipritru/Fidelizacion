<?php


namespace App\Http\Controllers;

use App\Models\state;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use OpenApi\Annotations as OA;


/**
 * @OA\Schema(
 *     schema="State",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Active"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 *
 * @OA\Schema(
 *     schema="StateRequest",
 *     type="object",
 *     required={"name"},
 *     @OA\Property(property="name", type="string", example="Active")
 * )
 */
class StateController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/states",
     *     summary="Retrieve a list of states",
     *     tags={"States"},
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
    public function index()
    {
        $states = state::all();
        return response()->json($states);
    }

    /**
     * @OA\Post(
     *     path="/api/states",
     *     summary="Create a new state",
     *     tags={"States"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StateRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="State created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/State")
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
            $validatedData = $request->validate(state::$rules);
            $state = state::create($validatedData);
            return response()->json($state, 201);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/states/{id}",
     *     summary="Retrieve a specific state",
     *     tags={"States"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
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
    public function show($id)
    {
        $state = state::find($id);
        if (!$state) {
            return response()->json(['message' => 'State not found'], 404);
        }
        return response()->json($state);
    }

    /**
     * @OA\Put(
     *     path="/api/states/{id}",
     *     summary="Update a specific state",
     *     tags={"States"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StateRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="State updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/State")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="State not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $state = state::find($id);
        if (!$state) {
            return response()->json(['message' => 'State not found'], 404);
        }

        // Adjust rules for update: remove unique if updating the same name
        $rules = state::$rules;
    $rules['name'] = 'required|string|max:100|unique:state,name,' . $id . ',id_state';

        try {
            $validatedData = $request->validate($rules);
            $state->update($validatedData);
            return response()->json($state);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/states/{id}",
     *     summary="Delete a specific state",
     *     tags={"States"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="State deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="State not found"
     *     )
     * )
     */
    public function destroy($id)
    {
        $state = state::find($id);
        if (!$state) {
            return response()->json(['message' => 'State not found'], 404);
        }
        $state->delete();
        return response()->json(null, 204);
    }
}

/**
 * @OA\Schema(
 *     schema="State",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Active"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 *
 * @OA\Schema(
 *     schema="StateRequest",
 *     type="object",
 *     required={"name"},
 *     @OA\Property(property="name", type="string", example="Active")
 * )
 */