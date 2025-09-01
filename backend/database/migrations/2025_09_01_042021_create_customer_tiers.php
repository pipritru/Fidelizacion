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
        Schema::create('customer_tiers', function (Blueprint $table) {
            $table->increments('id_customer_tier');  // PK
            $table->unsignedInteger('id_customer');  // FK a customers
            $table->unsignedInteger('id_restaurant'); // FK a restaurants
            $table->unsignedInteger('id_tier');      // FK a tiers
            $table->timestamp('assigned_at')->nullable(); // Fecha de asignación del nivel
            $table->timestamp('expires_at')->nullable();  // Fecha de expiración del nivel (si aplica)
            $table->timestamps();

            // Llaves foráneas
            
            // fk customer
            $table->foreign('id_customer')
                ->references('id_customer')
                ->on('customers')
                ->onDelete('cascade'); // si se borra el customer, se borran los customer_tiers asociados

            // fk tier
            $table->foreign('id_tier')
                ->references('id_tier')
                ->on('tiers')
                ->onDelete('cascade'); // si se borra el tier, se borran los customer_tiers asociados

            // fk restaurant
            $table->foreign('id_restaurant')
                ->references('id_restaurant')
                ->on('restaurants')
                ->onDelete('cascade'); // si se borra el restaurante, se borran los customer_tiers asociados
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_tiers');
    }
};
