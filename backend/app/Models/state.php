<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class state extends Model
{
   use HasFactory;

    // Nombre de la tabla
    protected $table = 'state';

    // Atributos que son asignables
    protected $fillable = [
        'name',
    ];

    // Si no quieres que Laravel maneje automáticamente las columnas created_at y updated_at, puedes desactivarlo.
    public $timestamps = true;
    
    // Definir reglas para validación, si es necesario
    public static $rules = [
        'name' => 'required|string|max:100|unique:state,name',
    ];

}
