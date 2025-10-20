<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Users;
use App\Models\LoyaltyPoint;
use App\Models\TransactionPoint;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use OpenApi\Annotations as OA;

class PointsController extends Controller
{
    /**
     * @OA\Tag(
     *   name="Points",
     *   description="Endpoints para consultar, ajustar y canjear puntos"
     * )
     */

    /**
     * @OA\Get(
     *   path="/api/users/{id}/points",
     *   tags={"Points"},
     *   summary="Obtener balance y transacciones de puntos de un usuario",
     *   security={{"bearerAuth":{}}},
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="Balance",
     *     @OA\JsonContent(
     *       @OA\Property(property="user_id", type="integer"),
     *       @OA\Property(property="total_points", type="integer"),
     *       @OA\Property(property="transactions", type="array", @OA\Items(
     *         @OA\Property(property="id", type="integer"),
     *         @OA\Property(property="order_id", type="integer", nullable=true),
     *         @OA\Property(property="points", type="integer"),
     *         @OA\Property(property="type", type="string"),
     *         @OA\Property(property="description", type="string", nullable=true),
     *         @OA\Property(property="transaction_date", type="string", format="date-time")
     *       ))
     *     )
     *   ),
     *   @OA\Response(response=401, description="Unauthorized"),
     *   @OA\Response(response=403, description="Forbidden")
     * )
     */
    // GET /api/users/{id}/points
    public function show($id): JsonResponse
    {
        $user = Users::with('loyaltyPoints.transactionPoints')->findOrFail($id);

        // Authorization: owner or admin
        $auth = auth()->user();
        if (!$auth) return response()->json(['error' => 'Unauthorized'], 401);
        if ($auth->id !== $user->id && strtolower(optional($auth->role)->name) !== 'administrador') {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $lp = $user->loyaltyPoints;
        if (!$lp) {
            return response()->json(['user_id' => $user->id, 'total_points' => 0, 'transactions' => []]);
        }

        $transactions = $lp->transactionPoints()->orderByDesc('transaction_date')->get();

        return response()->json([
            'user_id' => $user->id,
            'total_points' => $lp->total_points,
            'transactions' => $transactions,
        ]);
    }

    // POST /api/users/{id}/points/adjust (admin only)
    public function adjust(Request $request, $id): JsonResponse
    {
        $request->validate(['points' => 'required|integer', 'reason' => 'nullable|string']);

        $auth = auth()->user();
        if (!$auth) return response()->json(['error' => 'Unauthorized'], 401);
        if (strtolower(optional($auth->role)->name) !== 'administrador') {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $user = Users::findOrFail($id);

        DB::transaction(function() use ($user, $request) {
            $points = (int)$request->input('points');
            $lp = LoyaltyPoint::firstOrCreate(['user_id' => $user->id], ['total_points' => 0]);

            TransactionPoint::create([
                'loyalty_point_id' => $lp->id,
                'order_id' => null,
                'points' => abs($points),
                'type' => 'adjustment',
                'transaction_date' => now(),
                'description' => $request->input('reason', 'Manual adjustment by admin'),
                'created_by' => auth()->id()
            ]);

            if ($points >= 0) {
                $lp->increment('total_points', $points);
            } else {
                $lp->decrement('total_points', abs($points));
            }
        });

        return response()->json(['success' => true]);
    }

    /**
     * @OA\Post(
     *   path="/api/users/{id}/points/adjust",
     *   tags={"Points"},
     *   summary="Ajustar puntos de un usuario (admin)",
     *   security={{"bearerAuth":{}}},
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\RequestBody(required=true, @OA\JsonContent(
     *       @OA\Property(property="points", type="integer"),
     *       @OA\Property(property="reason", type="string")
     *   )),
     *   @OA\Response(response=200, description="Ajuste aplicado"),
     *   @OA\Response(response=403, description="Forbidden")
     * )
     */

    // POST /api/redeem
    public function redeem(Request $request): JsonResponse
    {
        $request->validate(['items' => 'required|array']);
        $user = auth()->user();
        if (!$user) return response()->json(['error' => 'Unauthorized'], 401);

        $items = $request->input('items'); // [{product_id, quantity}]

        // Calculate cost in points
        $totalCost = 0;
        $products = Product::whereIn('id', array_column($items, 'product_id'))->get()->keyBy('id');
        foreach ($items as $it) {
            $p = $products[$it['product_id']] ?? null;
            if (!$p) return response()->json(['error' => 'Product not found: '.$it['product_id']], 404);
            $qty = max(1, (int)($it['quantity'] ?? 1));
            $cost = (int)($p->points_cost ?? $p->points ?? 0);
            $totalCost += $cost * $qty;
        }

        $lp = LoyaltyPoint::firstOrCreate(['user_id' => $user->id], ['total_points' => 0]);

        if ($lp->total_points < $totalCost) {
            return response()->json(['error' => 'Insufficient points', 'total_points' => $lp->total_points, 'required' => $totalCost], 400);
        }

        // Redeem: create a transaction and decrement total
        DB::transaction(function() use ($lp, $totalCost, $items) {
            TransactionPoint::create([
                'loyalty_point_id' => $lp->id,
                'order_id' => null,
                'points' => $totalCost,
                'type' => 'redeem',
                'transaction_date' => now(),
                'description' => 'Redeemed for products: '.json_encode($items),
                'created_by' => auth()->id()
            ]);

            $lp->decrement('total_points', $totalCost);
        });

        return response()->json(['success' => true, 'remaining_points' => $lp->fresh()->total_points]);
    }

    /**
     * @OA\Post(
     *   path="/api/redeem",
     *   tags={"Points"},
     *   summary="Canjear puntos por productos",
     *   security={{"bearerAuth":{}}},
     *   @OA\RequestBody(required=true, @OA\JsonContent(
     *       @OA\Property(property="items", type="array", @OA\Items(
     *           @OA\Property(property="product_id", type="integer"),
     *           @OA\Property(property="quantity", type="integer")
     *       ))
     *   )),
     *   @OA\Response(response=200, description="Canje exitoso"),
     *   @OA\Response(response=400, description="Insufficient points")
     * )
     */
}
