<?php

namespace App\Services;

use App\Models\Order;
use App\Models\LoyaltyPoint;
use App\Models\TransactionPoint;

class LoyaltyService
{
    protected int $firstPurchaseBonus = 100;

    public function creditPointsForOrder(Order $order): void
    {
        $user = $order->user;
        if (!$user) return;

        // Calculate points from products (product.points * quantity)
        $points = 0;
        foreach ($order->orderItems as $item) {
            $product = $item->product;
            if ($product && isset($product->points)) {
                $points += (int)$product->points * (int)$item->quantity;
            }
        }

        // Ensure loyalty record exists
        $lp = LoyaltyPoint::firstOrCreate(
            ['user_id' => $user->id],
            ['total_points' => 0]
        );

        // First purchase bonus: if user had no points before and this is first credit
        $isFirst = $lp->total_points == 0;

        $totalCredit = $points;
        if ($isFirst && $points > 0) {
            $totalCredit += $this->firstPurchaseBonus;
        }

        if ($totalCredit <= 0) return;

        // Create transaction
        TransactionPoint::create([
            'loyalty_point_id' => $lp->id,
            'order_id' => $order->id,
            'points' => $totalCredit,
            'type' => 'credit',
            'transaction_date' => now(),
            'description' => 'Points credited for order #' . $order->id
        ]);

        // Update total
        $lp->increment('total_points', $totalCredit);
    }
}
