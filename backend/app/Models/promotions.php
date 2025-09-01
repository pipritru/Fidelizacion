<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class promotions extends Model
{
    use HasFactory;
    
    // Nombre de la tabla
    protected $table = 'promotions';
    // Atributos que son asignables
    protected $fillable = [
        'id_restaurant', 
        'name', 
        'type', 
        'value', 
        'min_tier_id', 
        'min_purchase', 
        'code', 
        'starts_at', 
        'ends_at', 
        'per_user_limit', 
        'global_limit', 
        'stackable', 
        'active'
    ];

    // Relación con la tabla 'restaurants' (una promoción pertenece a un restaurante)
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'id_restaurant', 'id_restaurant');
    }

    // Relación con la tabla 'tiers' (una promoción puede estar asociada a un nivel, opcional)
    public function tier()
    {
        return $this->belongsTo(Tier::class, 'min_tier_id', 'id_tier');
    }

    // Reglas de validación
    public static $rules = [
        'id_restaurant' => 'required|exists:restaurants,id_restaurant',
        'name' => 'required|string|max:150',
        'type' => 'required|in:percent,fixed,gift,bogo',
        'value' => 'required|numeric|min:0',
        'min_tier_id' => 'nullable|exists:tiers,id_tier',
        'min_purchase' => 'nullable|numeric|min:0',
        'code' => 'nullable|string|max:40',
        'starts_at' => 'required|date',
        'ends_at' => 'nullable|date|after_or_equal:starts_at',
        'per_user_limit' => 'nullable|integer|min:1',
        'global_limit' => 'nullable|integer|min:1',
        'stackable' => 'required|boolean',
        'active' => 'required|boolean',
    ];
}
