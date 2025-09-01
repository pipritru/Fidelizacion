<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class rewards_catalog extends Model
{
    use HasFactory;
    
    // Nombre de la tabla
    protected $table = 'rewards_catalog';

     // Atributos que son asignables
    protected $fillable = [
        'id_restaurant', 
        'reward_name', 
        'points_cost', 
        'description', 
        'active', 
        'inventory'
    ];

    // Relación con la tabla 'restaurants' (una recompensa pertenece a un restaurante)
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'id_restaurant', 'id_restaurant');
    }

    // Reglas de validación
    public static $rules = [
        'id_restaurant' => 'required|exists:restaurants,id_restaurant',
        'reward_name' => 'required|string|max:120|unique:rewards_catalog,reward_name,id_restaurant',
        'points_cost' => 'required|integer|min:0',
        'description' => 'nullable|string',
        'active' => 'required|boolean',
        'inventory' => 'nullable|integer|min:0',
    ];
}
