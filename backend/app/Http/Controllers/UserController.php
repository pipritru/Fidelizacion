<?php

namespace App\Http\Controllers;

use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *   schema="Users",
 *   type="object",
 *   required={"id","username","is_active"},
 *   @OA\Property(property="id", type="integer", example=1),
 *   @OA\Property(property="username", type="string", example="johndoe"),
 *   @OA\Property(property="is_active", type="boolean", example=true),
 *   @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-01T00:00:00Z"),
 *   @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-01T00:00:00Z")
 * )
 */
class UserController extends Controller
{
 /**
 * @OA\Post(
 *   path="/api/users/register",
 *   tags={"Users"},
 *   summary="Register a new user",
 *   description="Create a new user with personal data association",
 *   operationId="registerUser",
 *   @OA\RequestBody(
 *     required=true,
 *     @OA\JsonContent(
 *       required={"username","password","person_id"},
 *       @OA\Property(property="username", type="string", example="johndoe"),
 *       @OA\Property(property="password", type="string", example="secret123"),
 *       @OA\Property(property="person_id", type="integer", example=1)
 *     )
 *   ),
 *   @OA\Response(
 *     response=201,
 *     description="User registered",
 *     @OA\JsonContent(ref="#/components/schemas/Users")
 *   ),
 *   @OA\Response(response=422, description="Validation error")
 * )
 */
public function register(Request $request): JsonResponse
{

    $validated = $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:persons,email',
        'username' => 'required|string|unique:users,username',
        'password' => 'required|string|min:6',
    ]);

    // Crear la persona primero
    $person = \App\Models\Person::create([
        'first_name' => $validated['first_name'],
        'last_name' => $validated['last_name'],
        'email' => $validated['email'],
    ]);

        // Crear el usuario asociado a la persona
    $user = Users::create([
        'username' => $validated['username'],
        'password' => Hash::make($validated['password']),
            'person_id' => $person->id,
        'is_active' => true,
    ]);

    return response()->json($user, 201);
}

    /**
     * @OA\Post(
     *   path="/api/users/login",
     *   tags={"Users"},
     *   summary="Login a user",
     *   description="Authenticate user and return token",
     *   operationId="loginUser",
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       required={"username","password"},
     *       @OA\Property(property="username", type="string", example="johndoe"),
     *       @OA\Property(property="password", type="string", example="secret123")
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Successful login",
     *     @OA\JsonContent(
     *       @OA\Property(property="token", type="string", example="1|abcd....")
     *     )
     *   ),
     *   @OA\Response(response=401, description="Invalid credentials")
     * )
     */
    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = Users::where('username', $validated['username'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json(['token' => $token]);
    }

    /**
     * @OA\Get(
     *   path="/api/users",
     *   tags={"Users"},
     *   summary="Retrieve a list of users",
     *   operationId="getUsers",
     *   @OA\Response(
     *     response=200,
     *     description="Successful operation",
     *     @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Users"))
     *   )
     * )
     */
    public function index(): JsonResponse
    {
        return response()->json(Users::all());
    }

    /**
     * @OA\Get(
     *   path="/api/users/{id}",
     *   tags={"Users"},
     *   summary="Retrieve a specific user",
     *   operationId="getUserById",
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="Successful operation",
     *     @OA\JsonContent(ref="#/components/schemas/Users")
     *   ),
     *   @OA\Response(response=404, description="User not found")
     * )
     */
    public function show($id): JsonResponse
    {
        return response()->json(Users::findOrFail($id));
    }

    /**
     * @OA\Put(
     *   path="/api/users/{id}",
     *   tags={"Users"},
     *   summary="Update a user",
     *   operationId="updateUser",
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       @OA\Property(property="username", type="string", example="johndoe"),
     *       @OA\Property(property="password", type="string", example="newSecret123"),
     *       @OA\Property(property="is_active", type="boolean", example=true)
     *     )
     *   ),
     *   @OA\Response(response=200, description="User updated",
     *     @OA\JsonContent(ref="#/components/schemas/Users")
     *   ),
     *   @OA\Response(response=404, description="User not found")
     * )
     */
    public function update(Request $request, $id): JsonResponse
    {
        $user = Users::findOrFail($id);
        $validated = $request->validate([
            'username' => 'string|unique:users,username,' . $id,
            'password' => 'nullable|string|min:6',
            'is_active' => 'boolean',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);
        return response()->json($user);
    }

    /**
     * @OA\Delete(
     *   path="/api/users/{id}",
     *   tags={"Users"},
     *   summary="Delete a user",
     *   operationId="deleteUser",
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=204, description="User deleted"),
     *   @OA\Response(response=404, description="User not found")
     * )
     */
    public function destroy($id): JsonResponse
    {
        $user = Users::findOrFail($id);
        $user->delete();
        return response()->json(null, 204);
    }
}