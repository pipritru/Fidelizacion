<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class users extends Model
{
    use HasFactory;

    // Nombre de la tabla
    protected $table = 'users';

    // Atributos que son asignables
    protected $fillable = [
        'id_person', 
        'username', 
        'password_hash', 
        'status'
    ];

    // Relación con la tabla 'persons' (una persona tiene un usuario)
    public function person()
    {
        return $this->belongsTo(Person::class, 'id_person', 'id_person');
    }

    // Reglas de validación
    public static $rules = [
        'username' => 'required|string|max:80|unique:users,username,NULL,id_user',
        'password_hash' => 'required|string|min:8',
        'status' => 'required|in:active,suspended',
    ];

    // Si prefieres activar el borrado lógico, puedes descomentar la siguiente línea:
    // protected $dates = ['deleted_at']; // Borrado lógico
}
