<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cities extends Model
{
      use HasFactory;

    // Nombre de la tabla
    protected $table = 'cities';

    // Atributos que son asignables
    protected $fillable = [
        'name', 
        'id_state'
    ];

    // Relación con la tabla 'state'
    public function state()
    {
        return $this->belongsTo(state::class, 'id_state', 'id_state');
    }

    // Reglas de validación (puedes ajustarlas según tus necesidades)
    public static $rules = [
        'name' => 'required|string|max:120|unique:cities,name,NULL,id_city,id_state',
        'id_state' => 'required|exists:state,id_state',
    ];
}
