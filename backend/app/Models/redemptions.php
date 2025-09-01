<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class redemptions extends Model
{
    use HasFactory;
    
    // Nombre de la tabla
    protected $table = 'redemptions';

    // Atributos que son asignables
    protected $fillable = [
        'id_restaurant', 
        'id_reward', 
        'id_customer', 
        'points_spent', 
        'redemption_code', 
        'status', 
        'redeemed_at'
    ];

    // Relación con la tabla 'restaurants' (un canje pertenece a un restaurante)
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'id_restaurant', 'id_restaurant');
    }

    // Relación con la tabla 'rewards_catalog' (un canje pertenece a una recompensa)
    public function reward()
    {
        return $this->belongsTo(Reward::class, 'id_reward', 'id_reward');
    }

    // Relación con la tabla 'customers' (un canje pertenece a un cliente)
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id_customer', 'id_customer');
    }

    // Reglas de validación
    public static $rules = [
        'id_restaurant' => 'required|exists:restaurants,id_restaurant',
        'id_reward' => 'required|exists:rewards_catalog,id_reward',
        'id_customer' => 'required|exists:customers,id_customer',
        'points_spent' => 'required|integer|min:0',
        'redemption_code' => 'nullable|string|max:60',
        'status' => 'required|in:completed,void',
        'redeemed_at' => 'nullable|date',
    ];
}
