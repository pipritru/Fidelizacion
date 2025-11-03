<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{

    protected $table = 'persons';
    protected $fillable = ['first_name', 'last_name', 'email', 'address', 'city_id', 'state_id', 'created_date', 'phone', 'birth_date'];

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function user()
    {
        return $this->hasOne(Users::class, 'person_id');
    }
}
