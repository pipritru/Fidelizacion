<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class state extends Model
{
   use HasFactory;

    // Nombre de la tabla
    protected $table = 'state';
    protected $primaryKey = 'id_state';// <-- PK real
    public $incrementing = true;       // autoincrement (opcional, por claridad)
    protected $keyType = 'int';        // tipo de PK (opcional)
    public $timestamps = true;
    protected $fillable = ['name'];


    // Definir reglas para validación, si es necesario
    public static $rules = [
        'name' => 'required|string|max:100|unique:state,name',
    ];

}
