<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class points_ledger extends Model
{
    use HasFactory;
    
    // Nombre de la tabla
    protected $table = 'points_ledger';

     // Atributos que son asignables
    protected $fillable = [
        'id_restaurant', 
        'id_customer', 
        'id_purchase', 
        'delta_points', 
        'reason', 
        'note', 
        'expires_at'
    ];

    // Relación con la tabla 'restaurants' (un registro de puntos pertenece a un restaurante)
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'id_restaurant', 'id_restaurant');
    }

    // Relación con la tabla 'customers' (un registro de puntos pertenece a un cliente)
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id_customer', 'id_customer');
    }

    // Relación con la tabla 'purchases' (un registro de puntos puede estar relacionado con una compra)
    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'id_purchase', 'id_purchase');
    }

    // Reglas de validación
    public static $rules = [
        'id_restaurant' => 'required|exists:restaurants,id_restaurant',
        'id_customer' => 'required|exists:customers,id_customer',
        'id_purchase' => 'nullable|exists:purchases,id_purchase',
        'delta_points' => 'required|integer',
        'reason' => 'required|string|max:40',
        'note' => 'nullable|string|max:200',
        'expires_at' => 'nullable|date',
    ];
}
