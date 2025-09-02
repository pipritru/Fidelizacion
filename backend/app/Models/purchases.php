<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;  // Correcto


class purchases extends Model
{
     use HasFactory;

    // Nombre de la tabla
    protected $table = 'purchases';

    // Atributos que son asignables
    protected $fillable = [
        'id_restaurant', 
        'id_customer', 
        'total_amount', 
        'currency', 
        'status', 
        'purchased_at', 
        'metadata'
    ];

    // Relación con la tabla 'restaurants' (una compra pertenece a un restaurante)
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'id_restaurant', 'id_restaurant');
    }

    // Relación con la tabla 'customers' (una compra puede tener un cliente asociado)
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id_customer', 'id_customer');
    }

    // Reglas de validación
    public static $rules = [
        'id_restaurant' => 'required|exists:restaurants,id_restaurant',
        'id_customer' => 'nullable|exists:customers,id_customer',
        'total_amount' => 'required|numeric|min:0',
        'currency' => 'required|string|max:10',
        'status' => 'required|in:paid,void,refunded',
        'purchased_at' => 'nullable|date',
        'metadata' => 'nullable|json',
    ];
}
