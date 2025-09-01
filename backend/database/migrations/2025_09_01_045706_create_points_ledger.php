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
        Schema::create('points_ledger', function (Blueprint $table) {
            $table->increments('id_points_ledger');    // PK
            $table->unsignedInteger('id_restaurant');  // FK a restaurants
            $table->unsignedInteger('id_customer');    // FK a customers
            $table->unsignedInteger('id_purchase')->nullable(); // FK a purchases (si aplica)
            $table->integer('delta_points');            // Los puntos ganados (+) o consumidos (-)
            $table->string('reason', 40);               // Razón del movimiento (Ej. 'earn', 'redeem', 'expire', 'adjust')
            $table->string('note', 200)->nullable();    // Nota adicional (opcional)
            $table->timestamp('expires_at')->nullable(); // Vencimiento de los puntos (si aplica)
            $table->timestamps();

            // Llaves foráneas
            
            // fk restaurant
            $table->foreign('id_restaurant')
                ->references('id_restaurant')
                ->on('restaurants')
                ->onDelete('cascade'); // si se borra el restaurante, se borran los points_ledger asociados

            // fk customer
            $table->foreign('id_customer')
                ->references('id_customer')
                ->on('customers')
                ->onDelete('cascade');  // si se borra el customer, se borran los points_ledger asociados
            
            // fk purchase
            $table->foreign('id_purchase')
                ->references('id_purchase')
                ->on('purchases')
                ->onDelete('set null'); // si se borra la compra, se pone null en id_purchase del points_ledger
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('points_ledger');
    }
};
