<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class promotion_applications extends Model
{
    use HasFactory;
    
    // Nombre de la tabla
    protected $table = 'promotion_applications';
     // Atributos que son asignables
    protected $fillable = [
        'id_promotion', 
        'id_customer', 
        'id_purchase', 
        'applied_value', 
        'applied_at'
    ];

    // Relación con la tabla 'promotions' (una aplicación de promoción pertenece a una promoción)
    public function promotion()
    {
        return $this->belongsTo(Promotion::class, 'id_promotion', 'id_promotion');
    }

    // Relación con la tabla 'customers' (una aplicación de promoción pertenece a un cliente)
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id_customer', 'id_customer');
    }

    // Relación con la tabla 'purchases' (una aplicación de promoción pertenece a una compra)
    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'id_purchase', 'id_purchase');
    }

    // Reglas de validación
    public static $rules = [
        'id_promotion' => 'required|exists:promotions,id_promotion',
        'id_customer' => 'required|exists:customers,id_customer',
        'id_purchase' => 'required|exists:purchases,id_purchase',
        'applied_value' => 'required|numeric|min:0',
        'applied_at' => 'nullable|date',
    ];
}
