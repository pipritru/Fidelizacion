<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'price', 'is_active'];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
