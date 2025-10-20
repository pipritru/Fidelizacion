<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionPoint extends Model
{
    protected $fillable = ['loyalty_point_id', 'order_id', 'points', 'type', 'transaction_date', 'description', 'created_by'];

    public function loyaltyPoint()
    {
        return $this->belongsTo(LoyaltyPoint::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
