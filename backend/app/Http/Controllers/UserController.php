<?php

namespace App\Http\Controllers;

use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use OpenApi\Annotations as OA;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

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

    // Usar transacción para asegurar que ambos registros se crean juntos
    try {
        \DB::beginTransaction();

        // Crear la persona primero
        $person = \App\Models\Person::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
        ]);

        // Buscar el rol 'cliente' y validar que existe
        $clienteRole = Role::where('name', 'cliente')->first();
        if (!$clienteRole) {
            \DB::rollBack();
            return response()->json(['error' => 'No existe el rol cliente en la base de datos'], 500);
        }

        // Crear el usuario asociado a la persona con el role_id correcto
        $user = Users::create([
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'person_id' => $person->id,
            'role_id' => $clienteRole->id,
            'is_active' => true,
        ]);

        \DB::commit();
        return response()->json($user, 201);
    } catch (\Exception $e) {
        \DB::rollBack();
        return response()->json(['error' => 'Error al registrar usuario', 'details' => $e->getMessage()], 500);
    }
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
        // devolver usuarios incluyendo la relación person (contiene el email)
        $users = Users::with(['person', 'role'])->get();
        return response()->json($users);
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
        $user = Users::with(['person', 'role'])->findOrFail($id);
        return response()->json($user);
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

    /**
     * Ajustar puntos de un usuario (sumar o restar). Admin only.
     */
    public function adjustPoints(Request $request, $id): JsonResponse
    {
        $request->validate([
            'points' => 'required|integer',
            'reason' => 'nullable|string'
        ]);

        $user = Users::findOrFail($id);
        $lp = $user->loyaltyPoints()->first();
        if (!$lp) {
            $lp = \App\Models\LoyaltyPoint::create(['user_id' => $user->id, 'total_points' => 0]);
        }

        $points = (int)$request->input('points');
        // Always record adjustments as 'adjustment'
        \App\Models\TransactionPoint::create([
            'loyalty_point_id' => $lp->id,
            'order_id' => null,
            'points' => abs($points),
            'type' => 'adjustment',
            'transaction_date' => now(),
            'description' => $request->input('reason', 'Manual adjustment by admin') . ' (by user_id: ' . (auth()->id() ?? 'system') . ')'
        ]);

        if ($points >= 0) {
            $lp->increment('total_points', abs($points));
        } else {
            $lp->decrement('total_points', abs($points));
        }

        return response()->json(['success' => true, 'total_points' => $lp->fresh()->total_points]);
    }

    /**
     * Reporte simple de puntos por usuario o rango de fechas. Admin only.
     */
    public function pointsReport(Request $request): JsonResponse
    {
        $from = $request->query('from');
        $to = $request->query('to');

        $query = \App\Models\LoyaltyPoint::with('user');

        // Basic report: list users and current points
        $data = $query->get()->map(function($lp) {
            return [
                'user_id' => $lp->user_id,
                'username' => $lp->user ? $lp->user->username : null,
                'total_points' => $lp->total_points
            ];
        });

        return response()->json(['report' => $data]);
    }

    /**
     * Devolver permisos efectivos del usuario autenticado por recurso (OR de rol y usuario).
     */
    public function myPermissions(Request $request): JsonResponse
    {
        /** @var Users $user */
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'No autenticado'], 401);
        }

        $rows = DB::table('permissions as p')
            ->leftJoin('role_permissions as rp', function ($join) use ($user) {
                $join->on('rp.permission_id', '=', 'p.id')
                    ->where('rp.role_id', '=', $user->role_id);
            })
            ->leftJoin('user_permissions as up', function ($join) use ($user) {
                $join->on('up.permission_id', '=', 'p.id')
                    ->where('up.user_id', '=', $user->id);
            })
            ->select(
                'p.name',
                DB::raw('GREATEST(COALESCE(rp.can_view,0), COALESCE(up.can_view,0)) as can_view'),
                DB::raw('GREATEST(COALESCE(rp.can_create,0), COALESCE(up.can_create,0)) as can_create'),
                DB::raw('GREATEST(COALESCE(rp.can_edit,0), COALESCE(up.can_edit,0)) as can_edit'),
                DB::raw('GREATEST(COALESCE(rp.can_delete,0), COALESCE(up.can_delete,0)) as can_delete')
            )
            ->orderBy('p.name')
            ->get();

        $result = [];
        foreach ($rows as $row) {
            $result[$row->name] = [
                'can_view' => (bool)$row->can_view,
                'can_create' => (bool)$row->can_create,
                'can_edit' => (bool)$row->can_edit,
                'can_delete' => (bool)$row->can_delete,
            ];
        }

        return response()->json(['permissions' => $result]);
    }
}