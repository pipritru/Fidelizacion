<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class roles extends Model
{
     use HasFactory;

    // Nombre de la tabla
    protected $table = 'roles';

    // Atributos que son asignables
    protected $fillable = [
        'code', 
        'display_name'
    ];

    // Reglas de validación
    public static $rules = [
        'code' => 'required|string|max:50|unique:roles,code',
        'display_name' => 'required|string|max:100',
    ];

    // Relación con la tabla 'users' (un rol puede tener varios usuarios)
    public function users()
    {
        return $this->hasMany(User::class, 'role_id', 'id_role');
    }
}
