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
        Schema::create('redemptions', function (Blueprint $table) {
            $table->increments('id_redemption');         // PK
            $table->unsignedInteger('id_restaurant');    
            $table->unsignedInteger('id_reward');        
            $table->unsignedInteger('id_customer');      
            $table->integer('points_spent');              // Puntos gastados en el canje
            $table->string('redemption_code', 60)->nullable(); // Código de canje 
            $table->enum('status', ['completed', 'void'])->default('completed'); // Estado del canje
            $table->timestamp('redeemed_at')->nullable(); // Fecha y hora del canje
            $table->timestamps();

            // llaves foráneas

            // fk a restaurants
            $table->foreign('id_restaurant')
                  ->references('id_restaurant')
                  ->on('restaurants')
                  ->onDelete('cascade');  // Si borran un restaurante, eliminamos los canjes asociados

            // fk a rewards_catalog     
            $table->foreign('id_reward')
                  ->references('id_reward')
                  ->on('rewards_catalog')
                  ->onDelete('cascade');  // Si borran una recompensa, eliminamos los canjes asociados

            // fk a customers
            $table->foreign('id_customer')
                  ->references('id_customer')
                  ->on('customers')
                  ->onDelete('cascade');  // Si borran un cliente, eliminamos los canjes asociados
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('redemptions');
    }
};
