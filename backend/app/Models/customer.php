<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class customer extends Model
{
    use HasFactory;

    // Nombre de la tabla
    protected $table = 'customers';

    // Atributos que son asignables
    protected $fillable = [
        'id_restaurant', 
        'id_user'
    ];

    // Relación con la tabla 'restaurants' (un cliente pertenece a un restaurante)
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'id_restaurant', 'id_restaurant');
    }

    // Relación con la tabla 'users' (un cliente está asociado a un usuario)
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
