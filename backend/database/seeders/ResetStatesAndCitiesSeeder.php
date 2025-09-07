<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ResetStatesAndCitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Desactiva las restricciones de clave foránea
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        // Borra y reinicia el autoincremento de ambas tablas
        DB::table('state')->truncate();
        DB::table('cities')->truncate();
        // Activa las restricciones de clave foránea
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
