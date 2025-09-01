<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tiers', function (Blueprint $table) {
           $table->increments('id_tier');             // PK
            $table->unsignedInteger('id_restaurant');  // FK a restaurantes
            $table->string('name', 80);                // Nombre del nivel (Ej. "Bronce", "Plata")
            $table->integer('min_points');             // Puntos mínimos necesarios para alcanzar este nivel
            $table->integer('priority')->default(0);   // Prioridad (para definir el orden de los niveles, 0 = Bronce)
            $table->text('benefits')->nullable();      // Descripción de los beneficios del nivel
            $table->boolean('active')->default(true);  // Si el nivel está activo o no
            $table->timestamps();

            // Llave foránea
            
            // fk restaurant
            $table ->foreign('id_restaurant')
            ->references('id_restaurant')
            ->on ('restaurants')
            ->onDelete('cascade');// si se borra el restaurante, se borran los tiers asociados

            //nombre unicos 
            $table->unique(['id_restaurant', 'name']); // Unicidad del nombre por restaurante
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tiers');
    }
};
