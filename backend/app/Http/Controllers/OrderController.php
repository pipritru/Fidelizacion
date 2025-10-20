<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Order",
 *     type="object",
 *     required={"id", "user_id", "total_amount", "order_date", "status"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="user_id", type="integer", example=1),
 *     @OA\Property(property="total_amount", type="number", format="float", example=159.99),
 *     @OA\Property(property="order_date", type="string", format="date", example="2024-01-15"),
 *     @OA\Property(property="status", type="string", example="pending"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-01T00:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-01T00:00:00Z")
 * )
 */
class OrderController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/orders",
     *     tags={"Orders"},
     *     summary="Retrieve a list of orders",
     *     description="Get all orders",
     *     operationId="getOrders",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Order")
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $orders = Order::with(['user', 'orderItems'])->get();
        return response()->json($orders);
    }

    /**
     * @OA\Post(
     *     path="/api/orders",
     *     tags={"Orders"},
     *     summary="Create a new order",
     *     description="Add a new order",
     *     operationId="createOrder",
    *     @OA\RequestBody(
    *         description="Order details. You can include `items` to create order items in the same request.",
    *         required=true,
    *         @OA\JsonContent(
    *             required={"user_id", "total_amount", "order_date", "status"},
    *             @OA\Property(property="user_id", type="integer", example=1),
    *             @OA\Property(property="total_amount", type="number", format="float", example=159.99),
    *             @OA\Property(property="order_date", type="string", format="date", example="2024-01-15"),
    *             @OA\Property(property="status", type="string", example="pending"),
    *             @OA\Property(
    *                 property="items",
    *                 type="array",
    *                 @OA\Items(
    *                     type="object",
    *                     required={"product_id","quantity","subtotal"},
    *                     @OA\Property(property="product_id", type="integer", example=5),
    *                     @OA\Property(property="quantity", type="integer", example=1),
    *                     @OA\Property(property="subtotal", type="number", format="float", example=159.99)
    *                 )
    *             )
    *         )
    *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Order created",
     *         @OA\JsonContent(ref="#/components/schemas/Order")
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
            'user_id' => 'required|exists:users,id',
            'total_amount' => 'required|numeric|min:0',
            'order_date' => 'required|date',
            'status' => 'required|in:pending,processing,completed,cancelled',
            'items' => 'sometimes|array',
            'items.*.product_id' => 'required_with:items|exists:products,id',
            'items.*.quantity' => 'required_with:items|integer|min:1',
            'items.*.subtotal' => 'required_with:items|numeric|min:0',
        ]);

        // Si vienen items, verificamos que la suma de subtotales coincida con total_amount
        if (isset($validated['items']) && is_array($validated['items'])) {
            $sum = 0;
            foreach ($validated['items'] as $it) {
                $sum += (float) $it['subtotal'];
            }
            // Comparación con tolerancia para floats
            if (abs($sum - (float)$validated['total_amount']) > 0.01) {
                return response()->json(['error' => 'El total_amount no coincide con la suma de los subtotales de items'], 422);
            }
        }

        // Crear orden y sus items en una transacción
        try {
            \DB::beginTransaction();

            $orderData = [
                'user_id' => $validated['user_id'],
                'total_amount' => $validated['total_amount'],
                'order_date' => $validated['order_date'],
                'status' => $validated['status'],
            ];

            $order = Order::create($orderData);

            if (!empty($validated['items']) && is_array($validated['items'])) {
                $itemsToCreate = [];
                foreach ($validated['items'] as $it) {
                    $itemsToCreate[] = [
                        'product_id' => $it['product_id'],
                        'quantity' => $it['quantity'],
                        'subtotal' => $it['subtotal'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                $order->orderItems()->createMany($itemsToCreate);
            }

            \DB::commit();
            $order->load(['orderItems.product', 'user']);
            return response()->json($order, 201);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(['error' => 'Error creando la orden', 'details' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/orders/{id}",
     *     tags={"Orders"},
     *     summary="Retrieve a specific order",
     *     description="Get order by ID",
     *     operationId="getOrderById",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Order ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Order")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Order not found"
     *     )
     * )
     */
    public function show($id): JsonResponse
    {
        $order = Order::with(['user', 'orderItems.product', 'transactionPoints'])->findOrFail($id);
        return response()->json($order);
    }

    /**
     * @OA\Put(
     *     path="/api/orders/{id}",
     *     tags={"Orders"},
     *     summary="Update an order",
     *     description="Update order details",
     *     operationId="updateOrder",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Order ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         description="Updated order details",
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="total_amount", type="number", format="float", example=159.99),
     *             @OA\Property(property="order_date", type="string", format="date", example="2024-01-15"),
     *             @OA\Property(property="status", type="string", example="pending")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Order updated",
     *         @OA\JsonContent(ref="#/components/schemas/Order")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Order not found"
     *     )
     * )
     */
    public function update(Request $request, $id): JsonResponse
    {
        $order = Order::findOrFail($id);
        
        $validated = $request->validate([
            'user_id' => 'exists:users,id',
            'total_amount' => 'numeric|min:0',
            'order_date' => 'date',
            'status' => 'in:pending,processing,completed,cancelled',
        ]);

        $order->update($validated);
        return response()->json($order);
    }

    /**
     * @OA\Delete(
     *     path="/api/orders/{id}",
     *     tags={"Orders"},
     *     summary="Delete an order",
     *     description="Remove an order by ID",
     *     operationId="deleteOrder",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Order ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Order deleted"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Order not found"
     *     )
     * )
     */
    public function destroy($id): JsonResponse
    {
        $order = Order::findOrFail($id);
        $order->delete();
        return response()->json(null, 204);
    }
}