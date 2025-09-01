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
        Schema::create('point_balances', function (Blueprint $table) {
            $table->unsignedInteger('id_restaurant');  // FK a restaurants
            $table->unsignedInteger('id_customer');    // FK a customers
            $table->integer('total_points')->default(0); // Total de puntos acumulados
            $table->timestamps();

            // Llaves foráneas
            
            // fk restaurant
            $table->foreign('id_restaurant')
                ->references('id_restaurant')
                ->on('restaurants')
                ->onDelete('cascade'); // si se borra el restaurante, se borran los point_balances asociados
           
                // fk customer
            $table->foreign('id_customer')
                ->references('id_customer')
                ->on('customers')
                ->onDelete('cascade'); // si se borra el customer, se borran los point_balances asociados

            $table->primary(['id_restaurant', 'id_customer']); // llave primaraia compuesta
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('point_balances');
    }
};
