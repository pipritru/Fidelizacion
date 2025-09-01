<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class permissions extends Model
{
       use HasFactory;

    // Nombre de la tabla
    protected $table = 'permissions';

    // Atributos que son asignables
    protected $fillable = [
        'code', 
        'description'
    ];

    // Reglas de validación
    public static $rules = [
        'code' => 'required|string|max:80|unique:permissions,code',
        'description' => 'nullable|string|max:200',
    ];

    // Relación con la tabla 'roles' (un permiso puede estar asociado a muchos roles)
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permissions', 'permission_id', 'role_id');
    }
}
