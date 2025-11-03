<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['user_id', 'total_amount', 'order_date', 'status'];

    public function user()
    {
        return $this->belongsTo(Users::class, 'user_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function transactionPoints()
    {
        return $this->hasMany(TransactionPoint::class);
    }
}
