<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class restaurants extends Model
{
      use HasFactory;

    // Nombre de la tabla
    protected $table = 'restaurants';

    // Atributos que son asignables
    protected $fillable = [
        'legal_name', 
        'trade_name', 
        'slug', 
        'tax_id', 
        'phone', 
        'email', 
        'address', 
        'id_city', 
        'timezone'
    ];

    // Relación con la tabla 'cities' (un restaurante está asociado con una ciudad)
    public function city()
    {
        return $this->belongsTo(City::class, 'id_city', 'id_city');
    }

    // Reglas de validación
    public static $rules = [
        'legal_name' => 'required|string|max:150',
        'trade_name' => 'nullable|string|max:150',
        'slug' => 'required|string|max:120|unique:restaurants,slug',
        'tax_id' => 'nullable|string|max:40',
        'phone' => 'nullable|string|max:30',
        'email' => 'nullable|string|max:150',
        'address' => 'nullable|string|max:200',
        'id_city' => 'nullable|exists:cities,id_city',
        'timezone' => 'required|string|max:64',
    ];

    // Si prefieres activar el borrado lógico, puedes descomentar la siguiente línea:
    // protected $dates = ['deleted_at']; // Borrado lógico
}
