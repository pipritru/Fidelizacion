<?php

namespace App\Observers;

use App\Models\Order;
use App\Services\LoyaltyService;

class OrderObserver
{
    protected LoyaltyService $loyalty;

    public function __construct()
    {
        $this->loyalty = new LoyaltyService();
    }

    public function updated(Order $order): void
    {
        if ($order->isDirty('status') && $order->status === 'completed') {
            $this->loyalty->creditPointsForOrder($order);
        }
    }
}
