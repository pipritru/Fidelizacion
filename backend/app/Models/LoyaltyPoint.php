<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoyaltyPoint extends Model
{
    protected $fillable = ['user_id', 'total_points'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactionPoints()
    {
        return $this->hasMany(TransactionPoint::class);
    }
}
