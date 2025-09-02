<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class persons extends Model
{
    /// Nombre de la tabla
    protected $table = 'persons';

    // Atributos que son asignables
    protected $fillable = [
        'first_name', 
        'last_name', 
        'national_id', 
        'email', 
        'phone', 
        'address', 
        'id_city', 
        'birthdate'
    ];

    // Relación con la tabla 'cities' (opcional)
    public function city()
    {
        return $this->belongsTo(City::class, 'id_city', 'id_city');
    }

    // Reglas de validación
    public static $rules = [
        'first_name' => 'required|string|max:80',
        'last_name' => 'nullable|string|max:80',
        'national_id' => 'nullable|string|max:40|unique:persons,national_id,NULL,id_person',
        'email' => 'nullable|string|max:150|unique:persons,email,NULL,id_person',
        'phone' => 'nullable|string|max:30',
        'address' => 'nullable|string|max:200',
        'id_city' => 'nullable|exists:cities,id_city',
        'birthdate' => 'nullable|date',
    ];
    // Si prefieres activar el borrado lógico, puedes descomentar la siguiente línea:
    // protected $dates = ['deleted_at']; // Borrado lógico
}
