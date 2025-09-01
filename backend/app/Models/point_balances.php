<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class point_balances extends Model
{
    use HasFactory;

    // Nombre de la tabla
    protected $table = 'point_balances';

    // Atributos que son asignables
    protected $fillable = [
        'id_restaurant', 
        'id_customer', 
        'total_points'
    ];

    // Relación con la tabla 'restaurants' (un balance de puntos pertenece a un restaurante)
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'id_restaurant', 'id_restaurant');
    }

    // Relación con la tabla 'customers' (un balance de puntos pertenece a un cliente)
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id_customer', 'id_customer');
    }

    // Reglas de validación
    public static $rules = [
        'id_restaurant' => 'required|exists:restaurants,id_restaurant',
        'id_customer' => 'required|exists:customers,id_customer',
        'total_points' => 'required|integer|min:0',
    ];
}
