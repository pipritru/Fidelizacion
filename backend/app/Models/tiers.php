<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tiers extends Model
{
    use HasFactory;

     // Nombre de la tabla
    protected $table = 'tiers';

    // Atributos que son asignables
    protected $fillable = [
        'id_restaurant', 
        'name', 
        'min_points', 
        'priority', 
        'benefits', 
        'active'
    ];

    // Relación con la tabla 'restaurants' (un nivel pertenece a un restaurante)
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'id_restaurant', 'id_restaurant');
    }

    // Reglas de validación
    public static $rules = [
        'id_restaurant' => 'required|exists:restaurants,id_restaurant',
        'name' => 'required|string|max:80|unique:tiers,name,id_restaurant',
        'min_points' => 'required|integer',
        'priority' => 'nullable|integer',
        'benefits' => 'nullable|string',
        'active' => 'required|boolean',
    ];
}
