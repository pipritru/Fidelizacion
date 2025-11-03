<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\LoyaltyPoint;
use App\Models\TransactionPoint;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use OpenApi\Annotations as OA;

class RedemptionController extends Controller
{
    /**
     * @OA\Tag(
     *   name="Redemptions",
     *   description="Endpoints para canjear puntos por productos"
     * )
     *
     * @OA\Post(
     *   path="/api/redemptions",
     *   tags={"Redemptions"},
     *   summary="Canjear puntos por productos",
     *   security={{"bearerAuth":{}}},
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       required={"items"},
     *       @OA\Property(
     *         property="items",
     *         type="array",
     *         @OA\Items(
     *           @OA\Property(property="product_id", type="integer"),
     *           @OA\Property(property="quantity", type="integer")
     *         )
     *       )
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Canje exitoso",
     *     @OA\JsonContent(
     *       @OA\Property(property="success", type="boolean"),
     *       @OA\Property(property="remaining_points", type="integer")
     *     )
     *   ),
     *   @OA\Response(response=400, description="Insufficient points"),
     *   @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate(['items' => 'required|array', 'items.*.product_id' => 'required|integer|exists:products,id', 'items.*.quantity' => 'nullable|integer|min:1']);

        $user = auth()->user();
        if (!$user) return response()->json(['error' => 'Unauthorized'], 401);

        $items = $request->input('items');

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
        DB::transaction(function() use ($lp, $totalCost, $items, $user) {
            TransactionPoint::create([
                'loyalty_point_id' => $lp->id,
                'order_id' => null,
                'points' => $totalCost,
                'type' => 'redeem',
                'transaction_date' => now(),
                'description' => 'Redeemed for products: '.json_encode($items),
                'created_by' => $user->id
            ]);

            $lp->decrement('total_points', $totalCost);
        });

        return response()->json(['success' => true, 'remaining_points' => $lp->fresh()->total_points]);
    }
}
