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
        Schema::create('rewards_catalog', function (Blueprint $table) {
             $table->increments('id_reward');            // PK
            $table->unsignedInteger('id_restaurant');   // FK a restaurants
            $table->string('reward_name', 120);          // Nombre de la recompensa (Ej. "Descuento 10%", "Cena gratis")
            $table->integer('points_cost');              // Puntos necesarios para canjear la recompensa
            $table->text('description')->nullable();    // Descripción de la recompensa
            $table->boolean('active')->default(true);    // Si la recompensa está activa
            $table->integer('inventory')->nullable();   // Inventario disponible (si aplica)
            $table->timestamps();

            // llaves foraneas
            $table->foreign('id_restaurant')
            ->references('id_restaurant')
            ->on('restaurants')
            ->onDelete('cascade'); // Si se elimina el restaurante, se eliminan sus recompensas

            $table ->unique(['id_restaurant', 'reward_name']); // recompensas únicas por restaurante
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rewards_catalog');
    }
};
