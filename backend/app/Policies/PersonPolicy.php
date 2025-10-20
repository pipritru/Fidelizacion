<?php

namespace App\Policies;

use App\Models\Users;
use App\Models\Person;

class PersonPolicy
{
    public function view(Users $user, Person $person)
    {
        return $user->person_id === $person->id || strtolower(optional($user->role)->name) === 'administrador';
    }

    public function update(Users $user, Person $person)
    {
        return $this->view($user, $person);
    }
}
