<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = ['name', 'description'];

    public function users()
    {
        return $this->hasMany(Users::class, 'permission_id');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions');
    }
}
