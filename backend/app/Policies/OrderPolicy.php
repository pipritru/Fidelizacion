<?php

namespace App\Policies;

use App\Models\Users;
use App\Models\Order;

class OrderPolicy
{
    public function view(Users $user, Order $order)
    {
        return $user->id === $order->user_id || strtolower(optional($user->role)->name) === 'administrador';
    }

    public function update(Users $user, Order $order)
    {
        // Allow admin or owner for update (business may restrict status changes)
        return $this->view($user, $order);
    }
}
